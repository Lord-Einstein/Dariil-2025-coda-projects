<?php

require_once './inc/reusable.inc.php';

$db = null;

$artists = [];
$artistsHtml = "";

$albums = [];
$albumsHtml = "";

$songs = [];
$songsHtml = "";

$query = "";

$emptyStateHtml = <<<HTML
    <div class="empty-state-container fade-in">
        <svg class="loader-loupe" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="40" r="30" class="loupe-glass" />
            <line x1="65" y1="65" x2="90" y2="90" class="loupe-handle" />
        </svg>
        <p>Aucun résultat trouvé...</p>
    </div>
HTML;

$db = connectDatabase();

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
            generateQuerryError($e);
        }

        if($artists) {
            foreach ($artists as $artist) {
                $id = $artist["id"];
                $name = $artist["name"];
                $cover = $artist["cover"];

                $artistsHtml .= <<<HTML
                    <a href="artist.php?id={$id}" class="result-card artist-style fade-in">
                        <div class="img-wrapper">
                            <img src="$cover" alt="$name">
                        </div>
                        <div class="info">
                            <h3>$name</h3>
                            <span class="tag">Artiste</span>
                        </div>
                    </a>
                HTML;
            }
        }else{
            $artistsHtml = $emptyStateHtml;
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
            generateQuerryError($e);
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
                    <a href="album.php?id={$id}" class="result-card album-style fade-in">
                        <div class="img-wrapper">
                            <img src="$cover" alt="Image de couverture album $name">
                            <div class="overlay-icon"><i class="ri-album-line"></i></div>
                        </div>
                        <div class="info">
                            <h3>$name</h3>
                            <p>$artistName • $month-$year</p>
                        </div>
                    </a>
                HTML;
            }
        }else{
            $albumsHtml = $emptyStateHtml;
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
            generateQuerryError($e);
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

                //j'implémente le liked...
                $isLiked = $song['is_liked'] == 1;
                $heartIcon = $isLiked ? 'ri-heart-fill' : 'ri-heart-line';
                $activeClass = $isLiked ? 'liked' : '';

                $songsHtml .= <<<HTML
                    <div class="search-track-row fade-in" id="{$id}">
                        <div class="track-left">
                            <img src="$cover" alt="cover">
                            <div class="track-meta">
                                <a href="album.php?id=$albumId#$id" class="track-title">$name</a>
                                <span class="track-artist">$artistName | Album : $albumName</span>
                            </div>
                        </div>

                        <div class="track-right">
                            <a href="add_to_playlist.php?id={$id}" class="like-btn" title="Ajouter à une playlist">
                                <i class="ri-add-box-line"></i>
                            </a>
                             <a href="./like_song.php?id={$id}" class="like-btn $activeClass">
                                <i class="$heartIcon"></i>
                             </a>
                             <span class="duration">$note ⭐</span>
                             <span class="duration">$formatDuration</span>
                        </div>
                    </div>
                HTML;
            }
        }else{
            $songsHtml = $emptyStateHtml;
        }


    } else {
        $artistsHtml = $emptyStateHtml;

        $albumsHtml = $emptyStateHtml;

        $songsHtml = $emptyStateHtml;
    }

}else{
    header("Location: ./error.php?message=\"Votre chanson préférée n'est par ici!\" !");
}




$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Résultats de recherche Lowify">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
HTML;

$html = <<<HTML
        
    <nav class="navbar">
        <a href="./index.php" class="logo">L<span>&</span>D</a>
        
         <ul class="nav-links">
            <li><a href="./index.php">Accueil</a></li>
            <li><a href="./artists.php">Artistes</a></li>
            <li><a href="./playlists.php">Playlists</a></li>
            <li><a href="./search.php?query=&submit=" class="active"><i class="ri-search-line"></i></a></li>
        </ul>
    </nav>

    <main class="container search-page">
        
        <header class="search-header fade-in">
            <h1>Résultats pour <span class="gold-text">"$query"</span></h1>
            <form action="" method="GET" class="mini-search-form">
                <div class="input-group">
                    <i class="ri-search-line"></i>
                    <input type="text" name="query" value="$query" placeholder="Rechercher autre chose...">
                </div>
                <button type="submit" name="submit" class="hidden-submit">Go</button>
            </form>
        </header>
    
       <div class="search-results-wrapper">
            
            <section class="result-section">
                <div class="section-title">
                    <h2><i class="ri-mic-line"></i> Artistes</h2>
                    <div class="line-separator"></div>
                </div>
                <div class="results-grid artists-grid">
                    {$artistsHtml}
                </div>
            </section>

            <section class="result-section">
                <div class="section-title">
                    <h2><i class="ri-disc-line"></i> Albums</h2>
                    <div class="line-separator"></div>
                </div>
                <div class="results-grid albums-grid">
                    {$albumsHtml}
                </div>
            </section>

            <section class="result-section">
                <div class="section-title">
                    <h2><i class="ri-music-2-line"></i> Chansons</h2>
                    <div class="line-separator"></div>
                </div>
                <div class="results-list">
                    {$songsHtml}
                </div>
            </section>
            
       </div>
    </main>

HTML;


echo (new HTMLPage(title: "Recherche : $query "))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/search.css")
->addScript("./others/global.js")
->render();
