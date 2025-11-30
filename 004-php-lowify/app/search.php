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

$albums = [];
$albumsHtml = "";

$songs = [];
$songsHtml = "";

$query = "";

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

try{
    $db = new DatabaseManager(
        dsn: "mysql:host=$host; dbname=$dbname; charset=utf8mb4",
        username : $username,
        password: $password
    );

}catch (PDOException $e){
    echo "ERREUR DE CONNEXION BDD" . $e->getMessage();
}

if(isset($_GET["submit"])){

    $query = $_GET["query"] ?? null;
    $likeSearch = "%" . $query . "%";

    if($query) {

        //artists

        try {
            $artists = $db -> executeQuery(<<<SQL
                SELECT *
                FROM artist
                WHERE
                    MATCH(name) AGAINST(:query IN NATURAL LANGUAGE MODE)
                   OR name LIKE :likeSearch;
            SQL,

            [
                "query" => $query,
                "likeSearch" => $likeSearch,
            ]

            );
        }catch(PDOException $e){
            echo "ERREUR DE QUERY" . $e->getMessage();
        }

        if($artists) {
            foreach ($artists as $artist) {
                $id = $artist["id"];
                $name = $artist["name"];
                $cover = $artist["cover"];

                $artistsHtml .= <<<HTML
                     <div class="card-horizontal glass-effect">
                        <a href="artist.php?id={$id}">
                            <img src="$cover" alt="Image de couverture de $name">
                        </a>
                        <div class="info">
                            <h2>$name</h2>
                            <span class="tag">Artiste</span>
                        </div>
                    </div>
                HTML;
            }
        }else{
            $artistsHtml = <<<HTML
                <p>Aucun résultat sur "Artists"...</p>
            HTML;
        }

        //albums

        try {
            $albums = $db -> executeQuery(<<<SQL
                SELECT album.*, YEAR(album.release_date) AS year, MONTHNAME(album.release_date) AS month, artist.name AS artist_name
                FROM album
                JOIN artist
                ON artist.id = album.artist_id
                WHERE
                    MATCH(album.name) AGAINST(:query IN NATURAL LANGUAGE MODE)
                   OR album.name LIKE :likeSearch;
                SQL,

                [
                    "query" => $query,
                    "likeSearch" => $likeSearch,
                ]

            );
        }catch(PDOException $e){
            echo "ERREUR DE QUERY" . $e->getMessage();
        }

        if($albums) {
            foreach ($albums as $album) {
                $id = $album["id"];
                $name = $album["name"];
                $cover = $album["cover"];
                $year = $album["year"];
                $month = $album["month"];
                $artistName = $album["artist_name"];

                $albumsHtml .= <<<HTML
                     <div class="card-horizontal glass-effect">
                        <a href="album.php?id={$id}">
                            <img src="$cover" alt="Image de couverture album $name de $artistName">
                        </a>
                        <div class="info">
                            <h2>$name</h2>
                            <span class="tag">Album de $artistName</span>
                            <span class="tag">$month - $year</span>
                        </div>
                    </div>
                HTML;
            }
        }else{
            $albumsHtml = <<<HTML
                <p>Aucun résultat sur "Albums"...</p>
            HTML;
        }


//songs

        try {
            $songs = $db -> executeQuery(<<<SQL
                SELECT song.*, artist.name AS artist_name, album.name AS album_name, artist.cover
                    FROM song
                    JOIN album
                    ON song.album_id = album.id
                    JOIN artist
                    ON song.artist_id = artist.id
                    WHERE
                        MATCH(song.name) AGAINST(:query IN NATURAL LANGUAGE MODE)
                       OR song.name LIKE :likeSearch;
                SQL,

                [
                    "query" => $query,
                    "likeSearch" => $likeSearch,
                ]

            );
        }catch(PDOException $e){
            echo "ERREUR DE QUERY" . $e->getMessage();
        }

        if($songs) {
            foreach ($songs as $song) {
                $id = $song["id"];
                $albumId = $song["album_id"];
                $name = $song["name"];
                $cover = $song["cover"];
                $duration = $song["duration"];
                $note = $song["note"];
                $artistName = $song["artist_name"];
                $albumName = $song["album_name"];

                $formatDuration = formatDuration($duration);

                $songsHtml .= <<<HTML
                     <div class="card-horizontal glass-effect">
                        <a href="album.php?id=$albumId#$id">
                            <img src="$cover" alt="Image de couverture de $artistName">
                        </a>
                        <div class="info">
                            <h2>$name</h2>
                            <span class="tag">Chanson de $artistName | Album : $albumName</span>
                            <span class="tag">$formatDuration</span>
                            <span class="tag">$note ⭐</span>
                        </div>
                    </div>
                HTML;
            }
        }else{
            $songsHtml = <<<HTML
                <p>Aucun résultat sur "Chansons"...</p>
            HTML;
        }


    } else {
        $artistsHtml = <<<HTML
            <p>Aucun résultat sur "Artists"...</p>
        HTML;

        $albumsHtml = <<<HTML
                <p>Aucun résultat sur "Albums"...</p>
        HTML;

        $songsHtml = <<<HTML
                <p>Aucun résultat sur "Chansons"...</p>
        HTML;
    }

}else{
    header("Location: ./error.php?message=\"Votre chanson préférée n'est par ici!\" !");
}












$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
<main>
   <div class="search-results">
        <section class="top-result">
            <h3>Résultats sur "Artistes"...</h3>
            {$artistsHtml}
        </section>
        <section class="top-result">
            <h3>Résultats sur "Albums"...</h3>
            {$albumsHtml}
        </section>
        <section class="top-result">
            <h3>Résultats sur "Chansons"...</h3>
            {$songsHtml}
        </section>
   </div>
</main>
        

HTML;


echo (new HTMLPage(title: "Rechercher "))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/search.css")
->addScript("./others/global.js")
->render();