<?php

require_once './inc/reusable.inc.php';

$db = null;

$comeWithSongId = isset($_GET['song_id']) ? (int)$_GET['song_id'] : null;
//pour gérer le cas où on veut créer une playlist pendant le process d'ajout d'une chanson dans cette même playlist(i'll be crazy :p).

if (isset($_POST["name"]) && !empty($_POST["name"])) {
    try {
        $db = connectDatabase();
        $name = $_POST["name"];

        // phase d'insert dans ma db
        $db->executeQuery(<<<SQL
            INSERT INTO playlist (name, duration, nb_song)
            VALUES (:name, 0, 0);
        SQL,
            [
                "name" => $name
            ]
        );

        //on récupère l'id de la playlist qu'on vient de créer
        $lastIdData = $db->executeQuery(<<<SQL
            SELECT id FROM playlist ORDER BY id DESC LIMIT 1;
        SQL);
        $newPlaylistId = $lastIdData[0]["id"];

        //si on avait une chanson en attente, on l'ajoute
        if (!empty($comeWithSongId)) {
            $songId = $comeWithSongId;

            //liaison
            $db->executeQuery(<<<SQL
                INSERT INTO x_playlist_song (playlist_id, song_id) VALUES ($newPlaylistId, $songId);
            SQL);

            //mettre à jour compteurs de playlist
            $songData = $db->executeQuery(<<<SQL
                SELECT duration FROM song WHERE id = $songId;
            SQL);
            $duration = $songData[0]["duration"] ?? 0;

            $db->executeQuery(<<<SQL
                UPDATE playlist SET nb_song = 1, duration = $duration
                WHERE id = $newPlaylistId;
            SQL);
            //c'est normalement le prmier enregistrement dans cette playlist donc je peux mettre les valeurs en dur

            //redirection vers la new playlist pour voir le résultat
            header("Location: playlist.php?id=$newPlaylistId");
            exit;
        }

        //Sinon la redirection classique
        header("Location: ./playlists.php");
        exit;

    } catch (PDOException $e) {
        header("Location: ./error.php?message=Oups! Pb de création de votre playlist.");
        exit;
    }
}

$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
HTML;

//mettre un lien de retour dynamique pour retourner avec l'id ou sur les playlists selon le cas
$backLink = $comeWithSongId ? "add_to_playlist.php?id=$comeWithSongId" : "./playlists.php";
//contenus et affichages dynamiques selon qu'on souhaite faire ajout et création en même temps ou pas
$btnText = $comeWithSongId ? "Créer & Ajouter" : "Créer";
$infoText = $comeWithSongId ? "La chanson sera ajoutée automatiquement à cette nouvelle playlist." : "Créez une collection pour vos titres.";

$html = <<<HTML
    <nav class="glass-nav">
        <a href="$backLink" class="logo"><i class="ri-arrow-left-line"></i></a>
    </nav>

    <main class="container">
        <div class="form-container fade-in">
            <h1 style="margin-bottom: 30px;">Nouvelle <span class="gold-text">Playlist</span></h1>
            
            <p style="color:var(--text-secondary); margin-bottom: 30px; font-size: 0.9rem;">
                $infoText
            </p>
            
            
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Nom de la playlist.</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="Ex: Soirée, Sport, Chill..." required autofocus>
                </div>
                
                <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;">
                    $btnText <i class="ri-check-line"></i>
                </button>
            </form>
        </div>
    </main>
HTML;

echo (new HTMLPage(title: "Créer ma playlist | Lowify & Darill"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/playlists.css")
->addScript("./others/global.js")
->render();
