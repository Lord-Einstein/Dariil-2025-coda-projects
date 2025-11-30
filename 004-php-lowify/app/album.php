<?php

require_once './inc/page.inc.php';
require_once './inc/database.inc.php';

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";

$db = null;

$albums = [];
$albumHtml = "";

$songs = [];
$songsHtml = "";



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

    try{
        $db = new DatabaseManager(
            dsn: "mysql:host=$host; dbname=$dbname; charset=utf8mb4",
            username : $username,
            password: $password
        );

    }catch (PDOException $e){
        echo "ERREUR DE CONNEXION BDD" . $e->getMessage();
    }

    try{
        $albums = $db -> executequery( <<<SQL
        SELECT
            artist.name AS artist_name, artist.cover AS artist_cover,
            album.*, YEAR(album.release_date) AS release_year, MONTHNAME(album.release_date) AS release_month,
            COUNT(song.id) AS total_songs,
            SUM(song.duration) AS total_duration
        FROM album
         JOIN artist ON artist.id = album.artist_id
         LEFT JOIN song ON song.album_id = album.id
        WHERE album.id = {$id}
        GROUP BY album.id;
        SQL);
    }catch (PDOException $e){
        echo "ERREUR DE QUERY" . $e->getMessage();
    }

    if($albums){
        $album = $albums[0];
        $artistName = $album["artist_name"];
        $artistId = $album["artist_id"];
        $artistCover = $album["artist_cover"];
        $albumName = $album["name"];
        $cover = $album["cover"];
//        $releaseDate = $album["release_date"];
        $releaseYear = $album["release_year"];
        $releaseMonth = $album["release_month"];
        $totalSong = $album["total_songs"];

        $totalDuration = $album["total_duration"];
        $durationFormat = formatDuration($totalDuration);

        $albumHtml = <<<HTML
            <aside class="album-sidebar animate-on-scroll">
                <div class="album-art-wrapper">
                    <img src="$cover" alt="Cover" class="album-cover">
                </div>
                <h1>$albumName</h1>
                <div class="album-meta">
                    <img src="$artistCover" class="artist-mini" alt=""> 
                    <a href="artist.php?id={$artistId}"> $artistName </a> •  $releaseMonth - $releaseYear • $totalSong titres • $durationFormat
                </div>
                <div class="album-actions">
                    <button class="btn-gold play-all">▶ Lire</button>
                    <button class="btn-glass icon-only">♥</button>
                </div>
            </aside>
        HTML;

    } else {
        header("Location: ./error.php?message=\"Cet album n'existe pas\" !");
    }

    try{
        $songs = $db -> executeQuery( <<<SQL
            SELECT *
            FROM song
            WHERE song.album_id = {$id}
            ORDER BY song.id DESC;
        SQL);
    }catch (PDOException $e){
        echo "ERREUR DE QUERY" . $e->getMessage();
    }

    $trackNum = 1;
    foreach($songs as $song){
        $songID = $song["id"];
        $songName = $song["name"];
        $duration = $song["duration"];
        $notes = $song["note"];

        $durationFormat = formatDuration($duration);

        $songsHtml .= <<<HTML
            <div class="track-row" id="$songID">
                <span class="track-num">$trackNum</span>
                <div class="track-info">
                    <span class="title">$songName</span>
                    <span class="artist-sub">$notes ⭐</span>
                </div>
                <span class="duration">$durationFormat</span>
            </div>
        HTML;

        $trackNum++;
    }

} else {
    header("Location: ./error.php?message=\"Cet album n'existe pas\" !");
}






$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
    <main class="container album-layout">
         {$albumHtml}
         <section class="album-tracks animate-on-scroll">
            <div class="table-header">
                <span>Titres</span>
                <span class="align-right">Durée</span>
            </div>
            {$songsHtml}
        </section>     
    </main>
HTML;


echo (new HTMLPage(title: "{$album["name"]} de {$album["artist_name"]}"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/album.css")
->addScript("./others/global.js")
->render();