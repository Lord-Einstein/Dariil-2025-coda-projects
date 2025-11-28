<?php

require_once './inc/page.inc.php';

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";


$message = [];

if(isset($_GET["message"])) {
    $message = $_GET["message"];
} else {
    $message = "Retournez à l'accueil, votre playlist vous attend.";
}


$htmlHead = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;

$html = <<<HTML
   <main class="error-container">
        <div class="glass-box">
            <h1 class="gold-text">404</h1>
            <p>$message</p>
            <a href="./index.php" class="btn-gold">Retourner à l'accueil</a>
        </div>
   </main>
HTML;


echo (new HTMLPage(title: "Lowify & Darill | Out page"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/error.css")
->addScript("./others/global.js")
->render();