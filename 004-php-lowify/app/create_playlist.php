<?php

require_once './inc/reusable.inc.php';

$db = null;


if (isset($_POST["name"]) && !empty($_POST["name"])) {
    try {
        $db = connectDatabase();
        $name = $_POST["name"];

        // phase d'insert dans ma db
        $db->executeQuery(<<<SQL
            INSERT INTO playlist (name, duration, nb_song) 
            VALUES (:name, 0, 0)
        SQL,
            [
                "name" => $name
            ]
        );

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

$html = <<<HTML
    <nav class="glass-nav">
        <a href="./playlists.php" class="logo"><i class="ri-arrow-left-line"></i></a>
    </nav>

    <main class="container">
        <div class="form-container fade-in">
            <h1 style="margin-bottom: 30px;">Nouvelle <span class="gold-text">Playlist</span></h1>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Nom de la playlist.</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="Ex: Soirée, Sport, Chill..." required autofocus>
                </div>
                
                <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;">
                    Créer <i class="ri-check-line"></i>
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