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

$albums = [];
$albumHtml = "";



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
        return "+ " . floor($formatted) . $suffix;
    } else {
        return "+ " . number_format($formatted, 1) . $suffix;
    }
}
function formatDuration(int $seconds): string
{
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;

    //faut aussi mettre mes secondes sur deux caractères... merci str_pad ;)
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
        echo "ERREUR DE CONNEXION BDD" . $e->getMessage();
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
        echo "ERREUR DE QUERY" . $e->getMessage();
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
            <div class="banner-bg" style="background-image: url('$cover');"></div>
            <div class="banner-gradient"></div>
            
            <div class="artist-content container">
                <div class="verified-badge fade-in">
                    <i class="ri-checkbox-circle-fill"></i> Artiste Vérifié
                </div>
                <h1 class="fade-in">{$name}</h1>
                <p class="listeners fade-in">{$format_number} auditeurs mensuels</p>
                <p class="bio fade-in">{$biography}</p>
                
                <div class="actions fade-in">
                    <button class="btn-gold"><i class="ri-play-fill"></i> Lecture</button>
                    <button class="btn-glass"><i class="ri-heart-line"></i> Suivre</button>
                </div>
            </div>
        </section>

        HTML;

    } else {
        header("Location: ./error.php?message=\"Cet artiste n'existe pas\" !");
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
        echo "ERREUR DE QUERY" . $e->getMessage();
    }

    $rang = 1;
    foreach($songs as $song){
        $name = $song["name"];
        $duration = $song["duration"];
        $note = $song["note"];
        $cover = $song["cover"];

        $duration_formatted = formatDuration($duration);

        $songHtml .= <<<HTML
            <div class="track-row fade-in">
                <span class="track-num">{$rang}</span>
                <div class="track-img-wrapper">
                    <img src="$cover" alt="cover">
                    <div class="play-overlay"><i class="ri-play-fill"></i></div>
                </div>
                <div class="track-info">
                    <span class="title">{$name}</span>
                    <span class="rating">
                        <i class="ri-star-fill star-shine-large"></i> {$note}
                    </span>
                </div>
                <div class="track-actions">
                    <button class="like-btn"><i class="ri-heart-add-line"></i></button>
                    <span class="duration">{$duration_formatted}</span>
                </div>
            </div>

        HTML;

        $rang++;
    }

//Albums

    try {
        $albums = $db -> executeQuery( <<<SQL
            SELECT album.id, album.name, album.cover, YEAR(album.release_date) AS year
            FROM album
            INNER JOIN artist
            ON artist.id = album.artist_id
            WHERE artist.id = {$id}
            ORDER BY album.release_date DESC;
        SQL);
    } catch (PDOException $e) {
        echo "ERREUR DE QUERY" . $e->getMessage();
    }

    foreach($albums as $album){
        $albumId = $album["id"];
        $name = $album["name"];
        $cover = $album["cover"];
        $year = $album["year"];

        $duration_formatted = formatDuration($duration);

        $albumHtml .= <<<HTML
            <a href="./album.php?id={$albumId}" class="card-mini fade-in">
                <div class="mini-img">
                    <img src="{$cover}" alt="Album">
                </div>
                <div class="mini-info">
                    <span class="name">{$name}</span>
                    <span class="year">{$year}</span>
                </div>
            </a>
        HTML;
    }



} else {
    header("Location: ./error.php?message=\"Cet artiste n'existe pas\" !");
}


$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ecoutez {$artist["name"]} sur Lowify.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
HTML;

$html = <<<HTML
    <nav class="glass-nav">
        <a href="./index.php" class="logo">L<span>&</span>D</a>
    </nav>

    <main class="artist-page-wrapper">
           {$artistHtml}
      
       <div class="container content-split">
           <section class="top-tracks">
                <div class="section-title">
                    <h2>Populaires</h2>
                    <div class="line-accent"></div>
                </div>
                <div class="track-list">
                   {$songHtml}
                </div>
           </section>     
           
           <section class="discography">
                <div class="section-title">
                    <h2>Discographie</h2>
                    <div class="line-accent"></div>
                </div>
                <div class="grid-mini">
                     {$albumHtml}
                </div>
           </section>
       </div>
    </main>
HTML;


echo (new HTMLPage(title: "Artiste | {$artist["name"]}"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/artist.css")
->addScript("./others/global.js")
->render();