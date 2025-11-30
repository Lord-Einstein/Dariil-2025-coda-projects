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
    
        <a href="./artist.php?id={$id}" class="artist-card">
            <div class="artist-img">
                <img src="$cover" alt="Artist">
            </div>
            <h3>$name</h3>
        </a>
    HTML;

}




$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
    <main class="container">
        <div class="page-header animate-on-scroll">
            <h1>Nos <span class="gold-text">Artistes</span></h1>
            <div class="filters">
                <button class="filter-btn active">Tous</button>
                <button class="filter-btn">Pop</button>
                <button class="filter-btn">Rap</button>
            </div>
        </div>
        
        <div class="artists-grid animate-on-scroll">
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