<?php


require_once './inc/page.inc.php';
require_once './inc/database.inc.php';

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";

$db = null;

$trends = [];
$trendsHtml = "";

$outers = [];
$outersHtml = "";

$albums = [];
$albumsHtml = "";

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

//Top artists

try{
    $trends = $db -> executeQuery(<<<SQL
        SELECT *
        FROM artist
        ORDER BY artist.monthly_listeners DESC
        LIMIT 5;
    SQL);
}catch(PDOException $e){
    echo "ERREUR DE QUERY" . $e->getMessage();
}

foreach($trends as $trend){

    $id = $trend["id"];
    $name = $trend["name"];
    $cover = $trend["cover"];
    $listeners = $trend["monthly_listeners"];

    $listenersFormatted = formatCompact($listeners);

    $trendsHtml .= <<<HTML
         <article class="card glass-card">
                <div class="card-image">
                    <a href="artist.php?id={$id}">
                        <img src="$cover" alt="Artist Cover">
                    </a>
                </div>
                <div class="card-info">
                    <h3>$name</h3>
                    <p>$listenersFormatted Followers</p>
                </div>
        </article>
    HTML;

}

//top albums on release_date

try{
    $outers = $db -> executeQuery(<<<SQL
        SELECT *, YEAR(album.release_date) AS year, MONTHNAME(album.release_date) AS month
        FROM album
        ORDER BY album.release_date DESC
        LIMIT 5;
    SQL);
}catch (PDOException $e){
    echo "ERREUR DE QUERY" . $e->getMessage();
}

foreach($outers as $outer){
    $id = $outer["id"];
    $cover = $outer["cover"];
    $name = $outer["name"];
    $year = $outer["year"];
    $month = $outer["month"];

    $outersHtml .= <<<HTML
         <article class="card glass-card">
            <div class="card-image">
                <a href="album.php?id={$id}">
                    <img src="$cover" alt="Album">
                    <div class="play-hover">▶</div>
                </a>
            </div>
            <div class="card-info">
                <h3>$name</h3>
                <p>$month $year</p>
            </div>
        </article>
    HTML;

}

//top albums on AVG(notes)

try{
    $albums = $db -> executeQuery(<<<SQL
        SELECT album.id, album.name, album.cover, ROUND(AVG(song.note), 2) AS moyenne
        FROM album
        INNER JOIN song
        ON album.id = song.album_id
        GROUP BY album.id, album.name
        ORDER BY moyenne DESC
        LIMIT 5;
    SQL);
}catch (PDOException $e){
    echo "ERREUR DE QUERY" . $e->getMessage();
}

foreach($albums as $album){
    $id = $album["id"];
    $name = $album["name"];
    $cover = $album["cover"];
    $moyenne = $album["moyenne"];

    $albumsHtml .= <<<HTML
         <article class="card glass-card">
            <div class="card-image">
                <a href="album.php?id={$id}">
                    <img src="$cover" alt="Album">
                    <div class="play-hover">▶</div>
                </a>
            </div>
            <div class="card-info">
                <h3>$name</h3>
                <p>$moyenne ⭐</p>
            </div>
        </article>
    HTML;

}




$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
    <nav class="navbar">
        <a href="./index.php" class="logo">L<span>&</span>D</a>
        <button class="mobile-toggle">☰</button>
        <ul class="nav-links">
            <li><a href="./index.php" class="active">Accueil</a></li>
            <li><a href="./artists.php">Artistes</a></li>
            <li><a href="./search.php">Recherche</a></li>
        </ul>
    </nav>

    <header class="hero-section">
        <div class="hero-content animate-on-scroll">
            <h1>Feel the <span class="gold-text">Vibe</span>.</h1>
            <p>Découvrez les pépites musicales du moment.</p>
           <form action="./search.php" method="post">
                <div class="search-wrapper glass-panel">
                    <input type="text" name="query" id="query" placeholder="Rechercher un artiste, un titre...">
                    <button class="btn-gold" type="submit" name="submit">Go</button>
                </div>
           </form>
        </div>
    </header>
    
     <main class="container">
        <section class="trends animate-on-scroll">
            <div class="section-header">
                <h2>Top Artistes Tendances...</h2>
                <a href="./artists.php" class="view-all">Voir tout</a>
            </div>
            <div class="card-grid">
                {$trendsHtml}
            </div>
            <div class="section-header">
                <h2>Top Recent Outers...</h2>
            </div>
            <div class="card-grid">
               {$outersHtml}
            </div>
            <div class="section-header">
                <h2>Top Best Albums...</h2>
            </div>
            <div class="card-grid">
               {$albumsHtml}
            </div>
        </section>
    </main>
        

HTML;


echo (new HTMLPage(title: "Acceuil"))
    ->addContent($html)
    ->addHead($htmlHead)
    ->addStylesheet("./others/global.css")
    ->addStylesheet("./others/index.css")
    ->addScript("./others/global.js")
    ->render();