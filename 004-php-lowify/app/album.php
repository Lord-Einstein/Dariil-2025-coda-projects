<?php

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";

$db = null;

$albums = [];
$albumHtml = "";









$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
    <main class="container">
           {$artistHtml}
      
       <div class="container content-split">
           <section class="top-tracks">
                <h2>Populaires</h2>
                <div class="track-list">
                   {$songHtml}
                </div>
           </section>     
           
           <section class="discography">
                <h2>Albums</h2>
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