<?php

require_once './inc/page.inc.php';
require_once './inc/database.inc.php';

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";

$db = null;

$artist = [];
$artistHtml = "";

$songs = [];
$songHtml = "";



function formatCompact(int $number): string {
    if ($number >= 1_000_000_000) {
        $formatted = $number / 1_000_000_000;
        $suffix = ' B';
    } elseif ($number >= 1_000_000) {
        $formatted = $number / 1_000_000;
        $suffix = ' M';
    } elseif ($number >= 1_000) {
        $formatted = $number / 1_000;
        $suffix = ' k';
    } else {
        return (string)$number;
    }

    //Si ma décimale est pas propre, je remets une virgule
    if ($formatted == floor($formatted)) {
        return "+" . floor($formatted) . $suffix;
    } else {
        return "+" . number_format($formatted, 1) . $suffix;
    }
}
function formatDuration(int $seconds): string
{
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;

    //faut aussi mettre mes secondes sur deux car... merci str_pad ;)
    $formattedSeconds = str_pad($remainingSeconds, 2, "0", STR_PAD_LEFT);
    return $minutes . " m : " . $formattedSeconds . " s";
}


if(isset($_GET["id"])){
    $id = $_GET["id"];

//Database connection
    try {
        $db = new DatabaseManager(
            dsn: "mysql:host=$host; dbname=$dbname; charset=utf8mb4",
            username : $username,
            password: $password
        );
    } catch (PDOException $e) {
        //...
    }

// Now I run the query
    try{
        $artist = $db -> executeQuery(
            <<<SQL
            SELECT *
            FROM artist
            WHERE id = {$id} ;
        SQL
    );
    } catch (PDOException $e) {
        //...
    }

//And I generate the html
    if($artist) {
        $artist = $artist[0];
        $name = $artist["name"];
        $biography = $artist["biography"];
        $cover = $artist["cover"];
        $monthly_listeners = $artist["monthly_listeners"];

        $format_number = formatCompact($monthly_listeners);

        $artistHtml .= <<<HTML
        <section class="artist-banner">
            <div class="banner-overlay"></div>
            <img src="$cover" alt="Banner" class="bg-img">
             <div class="artist-header-content container">
                <span class="verified"><i class="fas fa-check-circle"></i> Artiste Vérifié</span>
                <h1>$name</h1>
                <p>{$format_number} auditeurs mensuels</p> 
                <p>{$biography}</p>
                <div class="actions">
                    <button class="btn-gold">Lecture</button>
                    <button class="btn-glass">Suivre</button>
                </div>
            </div>
        </section>
        HTML;

    } else {
        header("Location: ./error.php");
        exit();
    }

//the top song of artist
    try {
        $songs = $db -> executeQuery( <<<SQL
            SELECT song.*, album.cover
            FROM artist
            INNER JOIN song
            ON artist.id = song.artist_id
            INNER JOIN album
            ON song.album_id = album.id
            WHERE artist.id = {$id}
            ORDER BY song.note DESC
            LIMIT 5;
        SQL);
    } catch (PDOException $e) {
        //...
    }

    $rang = 1;
    foreach($songs as $song){
        $name = $song["name"];
        $duration = $song["duration"];
        $note = $song["note"];
        $cover = $song["cover"];

        $duration_formatted = formatDuration($duration);

        $songHtml .= <<<HTML
            <div class="track-row">
                    <span class="track-num">$rang</span>
                    <img src="$cover" alt="cover">
                    <div class="track-info">
                        <span class="title">$name</span>
                        <span class="views">⭐ $note</span>
                    </div>
                    <span class="duration">$duration_formatted</span>
            </div>
        HTML;

        $rang++;
    }



} else {
    header("Location: ./error.php");
    exit();
}


$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
    <main class="container">
           {$artistHtml}
           
       <section class="top-tracks">
            <h2>Populaires</h2>
            <div class="track-list">
               {$songHtml}
            </div>
       </section>
           
    </main>
HTML;


echo (new HTMLPage(title: "Lowify & Darill | Artiste"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/artist.css")
->addScript("./others/global.js")
->render();