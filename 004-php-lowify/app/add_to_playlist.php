<?php

require_once './inc/reusable.inc.php';

$db = null;


if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$songId = (int)$_GET['id'];

try {
    $db = connectDatabase();

    //je check que la méthode requête est bien POST avant de check le isset
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playlist_id'])) {
        $playlistId = (int)$_POST['playlist_id'];

        //C'est optionnel mais je veux bien éviter d'avoir la même musique dans la même playlist
        $check = $db->executeQuery(<<<SQL
            SELECT id FROM x_playlist_song
            WHERE playlist_id = $playlistId AND song_id = $songId;
        SQL);

        if(empty($check)) {
            //enregistrer la liaison dans x_playlist_song
            $db->executeQuery(<<<SQL
                INSERT INTO x_playlist_song (playlist_id, song_id)
                VALUES ($playlistId, $songId);
            SQL);

            //prendre la durée de la chanson avec son id puis recalculer la durée totale de la playlist...
            $songData = $db->executeQuery(<<<SQL
                SELECT duration
                FROM song WHERE id = $songId;
            SQL);
            $duration = $songData[0]["duration"];

            //mise à jour de " playlist"
            $db->executeQuery(<<<SQL
                UPDATE playlist
                SET nb_song = nb_song + 1, duration = duration + $duration
                WHERE id = $playlistId;
            SQL);
        }

        //revenir à la dernière page sur la pile
        header("Location: playlist.php?id=$playlistId");
        exit;
    }

    //infos de chanson pour l'affichage
    $song = $db->executeQuery(<<<SQL
        SELECT name FROM song WHERE id = $songId
    SQL)[0];//comme cà je mets directement dans song le 1 premier sous tableau retourné par la query

    //sélectionner toutes les playlists
    $playlists = $db->executeQuery(<<<SQL
        SELECT *
        FROM playlist
        ORDER BY id DESC;
    SQL);

} catch (PDOException $e) {
    generateQuerryError($e);
    exit;
}

//le contenu dans playlistsHtml sous forme de cartes pour l'injecter ensuite
$playlistsHtml = "";
foreach ($playlists as $playlist) {
    $playlistsHtml .= <<<HTML
        <form method="POST" action="">
            <input type="hidden" name="playlist_id" value="{$playlist["id"]}">
            <div class="select-card" onclick="this.parentNode.submit()">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div style="width:40px; height:40px; background:#222; border-radius:4px; display:flex; align-items:center; justify-content:center;">
                        <i class="ri-music-2-line" style="color:var(--gold-main);"></i>
                    </div>
                    <div>
                        <div style="font-weight:600; color:white;">{$playlist["name"]}</div>
                        <div style="font-size:0.8rem; color:#888;">{$playlist["nb_song"]} titres</div>
                    </div>
                </div>
                <i class="ri-add-circle-line" style="font-size:1.5rem; color:var(--gold-main);"></i>
            </div>
        </form>
    HTML;
}

$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
HTML;

$html = <<<HTML
    <nav class="glass-nav">
        <a href="javascript:history.back()" class="logo"><i class="ri-close-line"></i></a>
    </nav>

    <main class="container">
        <div class="form-container fade-in" style="max-width: 500px;">
            <i class="ri-add-to-queue-line" style="font-size: 3rem; color: var(--gold-main); margin-bottom: 20px;"></i>
            <h2>Ajouter <span class="gold-text">"{$song["name"]}"</span> à...</h2>
            
            <div class="select-playlist-grid">
                {$playlistsHtml}
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                 <a href="create_playlist.php?song_id={$songId}" class="btn-glass" style="width:100%; justify-content:center;">
                    <i class="ri-add-line"></i> Nouvelle Playlist
                </a>
            </div>
        </div>
    </main>
HTML;

echo (new HTMLPage(title: "Ajouter à la playlist | Lowify & Darill"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/playlists.css")
->addScript("./others/global.js")
->render();
