<?php

require_once './inc/page.inc.php';

$html_head = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Retrouvez vos meilleures musiques sur Lowify & Darill.">
HTML;


$html = <<<HTML

    

HTML;


echo (new HTMLPage(title: "Lowify & Darill | Artistes"))
->addContent($html)
->addHead($html_head)
->addStylesheet("./others/global.css")
->addStylesheet("./others/artists.css")
->render();