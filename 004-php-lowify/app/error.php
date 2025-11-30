<?php

require_once './inc/page.inc.php';

$message = [];

if(isset($_GET["message"])) {
    $message = $_GET["message"];
} else {
    $message = "Oups ! Il semblerait que cette piste soit rayée.";
}


$htmlHead = <<<HTML
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page introuvable - Lowify & Darill.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
HTML;

$catSvg = <<<SVG
<svg class="cat-jump-svg" viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg">
    <ellipse cx="100" cy="90" rx="40" ry="5" class="cat-shadow" />
    
    <g class="cat-body">
        <path d="M160,80 C160,80 180,50 150,40 C130,33 110,40 90,50 C70,60 50,55 40,70 C35,78 30,85 50,85 L140,85 C150,85 160,80 160,80 Z" fill="currentColor" />
        <circle cx="45" cy="65" r="12" fill="currentColor" />
        <path d="M35,60 L30,45 L45,55 Z" fill="currentColor" />
        <path d="M55,60 L60,45 L45,55 Z" fill="currentColor" />
        <path d="M160,75 Q180,70 180,50 Q180,30 160,40" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" class="cat-tail"/>
    </g>
</svg>
SVG;

$html = <<<HTML
    <main class="error-container">
        <div class="ambient-particles"></div>
        
        <div class="glass-box fade-in">
            <div class="cat-wrapper">
                $catSvg
            </div>
            
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Fausses Pistes Musicales</h2>
            <p class="error-msg">$message</p>
            
            <div class="action-area">
                <a href="./index.php" class="btn-gold">
                    <i class="ri-arrow-left-line"></i> Retour à l'accueil
                </a>
            </div>
        </div>
   </main>
HTML;


echo (new HTMLPage(title: "Page Introuvable | Lowify"))
->addContent($html)
->addHead($htmlHead)
->addStylesheet("./others/global.css")
->addStylesheet("./others/error.css")
->addScript("./others/global.js")
->render();