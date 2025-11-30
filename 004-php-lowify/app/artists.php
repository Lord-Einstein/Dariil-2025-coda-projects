<?php

require_once './inc/page.inc.php';
require_once './inc/database.inc.php';

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";

$db = null;

$artists = [];
$artistsHtml = "";




//Database connection
try {
    $db = new DatabaseManager(
        dsn: "mysql:host=$host; dbname=$dbname; charset=utf8mb4",
        username : $username,
        password: $password
    );
} catch (PDOException $e) {
    echo "ERREUR DE CONNEXION BDD" . $e->getMessage();
}

// Now i run the query
try{
    $artists = $db -> executeQuery(
        <<<SQL
            SELECT id, name, cover
            FROM artist;
        SQL
    );
} catch (PDOException $e) {
    echo "ERREUR DE QUERY" . $e->getMessage();
}

//And i generate the html
foreach ($artists as $artist) {
    $id = $artist["id"];
    $name = $artist["name"];
    $cover = $artist["cover"];

    $artistsHtml .= <<<HTML
    
        <a href="./artist.php?id={$id}" class="artist-card fade-in">
            <div class="artist-img-wrapper">
                <img src="{$cover}" alt="{$name}" loading="lazy">
                <div class="overlay-glow"></div>
            </div>
            <div class="artist-info">
                <h3>{$name}</h3>
                <span class="view-profile">Voir le profil <i class="ri-arrow-right-line"></i></span>
            </div>
        </a>
    HTML;

}




$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
HTML;

$html = <<<HTML
    <nav class="navbar">
        <a href="./index.php" class="logo">L<span>&</span>D</a>
        <button class="mobile-toggle" aria-label="Toggle menu">
            <i class="ri-menu-3-line"></i>
        </button>
        <ul class="nav-links">
            <li><a href="./index.php">Accueil</a></li>
            <li><a href="./artists.php" class="active">Artistes</a></li>
        </ul>
    </nav>

    <main class="container page-container">
        <div class="page-header">
            <h1 class="fade-in">Nos <span class="gold-text">Artistes</span></h1>
            <p class="subtitle fade-in">Les talents qui façonnent le son de demain.</p>
            
            <div class="filters fade-in">
                <button class="filter-btn active">Tous</button>
                <button class="filter-btn">Pop</button>
                <button class="filter-btn">Rap</button>
                <button class="filter-btn">Electro</button>
            </div>
        </div>
        
        <div class="artists-grid">
            {$artistsHtml}
        </div>
    </main>
HTML;




echo (new HTMLPage(title: "Lowify & Darill | Artistes"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/artists.css")
->addScript("./others/global.js")
->render();