<?php

require_once './inc/reusable.inc.php';

$db = null;
$playlistsHtml = "";


$db = connectDatabase();

// récupérer les playlists
try {
    $playlists = $db->executeQuery(<<<SQL
        SELECT *
        FROM playlist
        ORDER BY id DESC;
    SQL);
} catch (PDOException $e) {
    $playlists = [];
}

foreach ($playlists as $playlist) {
    $id = $playlist["id"];
    $name = $playlist["name"];
    $nb_song = $playlist["nb_song"];
    $formatDuration = formatDurationHMS($playlist["duration"]);

    $playlistsHtml .= <<<HTML
        <div class="playlist-card fade-in">
            <a href="delete_playlist.php?id={$id}" class="delete-playlist-btn" onclick="return confirm('Supprimer cette playlist ?');">
                <i class="ri-delete-bin-line"></i>
            </a>
            <a href="playlist.php?id={$id}" style="text-decoration: none; height: 100%; display: flex; flex-direction: column;">
                <div class="playlist-cover-placeholder">
                    <i class="ri-music-2-line"></i>
                </div>
                <div class="playlist-info">
                    <h2>{$name}</h2>
                    <div class="playlist-meta">
                        <span><i class="ri-music-line"></i> {$nb_song} titres</span>
                        <span><i class="ri-time-line"></i> {$formatDuration}</span>
                    </div>
                </div>
            </a>
        </div>
    HTML;
}

$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
HTML;

$html = <<<HTML
    <nav class="navbar">
        <a href="./index.php" class="logo">L<span>&</span>D</a>
        <button class="mobile-toggle" aria-label="Toggle menu">
            <i class="ri-menu-3-line"></i>
        </button>
        <ul class="nav-links">
            <li><a href="./index.php">Accueil</a></li>
            <li><a href="./artists.php">Artistes</a></li>
            <li><a href="./playlists.php" class="active">Playlists</a></li>
        </ul>
    </nav>

    <main class="container" style="padding-top: 120px;">
        <div class="section-header">
            <h2>Mes <span class="gold-text">Playlists</span></h2>
        </div>
        
        <div class="playlists-grid">
            <a href="./create_playlist.php" class="create-card fade-in">
                <i class="ri-add-circle-line create-icon"></i>
                <span class="create-text">Créer une Playlist</span>
            </a>
            
            {$playlistsHtml}
        </div>
    </main>
HTML;

echo (new HTMLPage(title: "Mes Playlists | Lowify"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/playlists.css")
->addScript("./others/global.js")
->render();
