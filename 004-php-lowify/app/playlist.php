<?php

require_once './inc/reusable.inc.php';

$db = null;
$songsHtml = "";


if (!isset($_GET["id"])) {
    header("Location: playlists.php");
    exit;
}

    $playlistId = (int)$_GET["id"];

try {
    $db = connectDatabase();

    // Infos de ma playlist
    $playlistData = $db->executeQuery(<<<SQL
        SELECT * 
        FROM playlist 
        WHERE id = {$playlistId}
    SQL);

    if (!$playlistData) {
        header("Location: playlists.php");
        exit;
    }
    $playlist = $playlistData[0];

    // Chansons de la playlist
    $songs = $db->executeQuery(<<<SQL
        SELECT s.*, a.name as artist_name, alb.cover
        FROM x_playlist_song x
        JOIN song s ON x.song_id = s.id
        JOIN album alb ON s.album_id = alb.id
        JOIN artist a ON s.artist_id = a.id
        WHERE x.playlist_id = $plId
        ORDER BY x.added_at DESC
    SQL);

} catch (PDOException $e) { die("Erreur BDD"); }

$count = 1;
foreach ($songs as $song) {
    $dur = formatDurationMmSs($song['duration']);
    $songsHtml .= <<<HTML
        <div class="track-row fade-in">
            <div class="track-left">
                <span class="track-num">$count</span>
                <img src="{$song['cover']}" alt="cover" style="width:40px; height:40px; border-radius:4px; margin: 0 15px;">
                <div class="track-info">
                    <span class="title">{$song['name']}</span>
                    <span class="track-sub">{$song['artist_name']} • {$song['note']} <i class="ri-star-fill star-shine"></i></span>
                </div>
            </div>
            <div class="track-right">
                <span class="duration" style="margin-right: 20px;">$dur</span>
                <a href="remove_from_playlist.php?song_id={$song['id']}&playlist_id={$plId}" class="like-btn" style="color: #ff4d4d;" title="Retirer">
                    <i class="ri-close-circle-line"></i>
                </a>
            </div>
        </div>
    HTML;
    $count++;
}

$totalDur = formatDurationHhMmSs($playlist['total_duration']);

$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
HTML;

$html = <<<HTML
    <nav class="glass-nav">
        <a href="./playlists.php" class="logo"><i class="ri-arrow-left-line"></i></a>
    </nav>

    <main class="container" style="padding-top: 100px;">
        <div class="playlist-header-detail fade-in">
            <div class="playlist-cover-placeholder" style="width: 200px; height: 200px; margin: 0 auto 30px auto; border-radius: 20px; font-size: 5rem;">
                <i class="ri-disc-line"></i>
            </div>
            <h1 class="playlist-title-large">{$playlist['name']}</h1>
            <p style="color: var(--text-secondary);">
                {$playlist['total_songs']} titres • Durée : <span class="gold-text">$totalDur</span>
            </p>
            
            <div style="margin-top: 30px;">
                <button class="btn-gold"><i class="ri-play-fill"></i> Lecture</button>
                <a href="delete_playlist.php?id=$plId" class="btn-glass" style="margin-left: 10px; color: #ff4d4d; border-color: rgba(255,0,0,0.3);" onclick="return confirm('Supprimer définitivement ?');">
                    <i class="ri-delete-bin-line"></i> Supprimer
                </a>
            </div>
        </div>

        <div class="tracks-list">
            {$songsHtml}
        </div>
    </main>
HTML;

echo (new HTMLPage(title: "{$playlist['name']} | Lowify"))
    ->addContent($html)
    ->addHead($htmlHead)
    ->addStylesheet("./others/global.css")
    ->addStylesheet("./others/album.css")
    ->addStylesheet("./others/playlists.css")
    ->addScript("./others/global.js")
    ->render();
