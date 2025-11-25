<?php

$choixJoueur = "";
$choixRandom = "";
$result = "";

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHIFOUMI - Version Royale.</title>
    <link rel="stylesheet" href="./style.css">

</head>
<body>
    <main>
        <h1>CHIFOUMI - ROYAL</h1>

        <section class="game-screen">
            <div class="players">
                <h2>VOUS</h2>
                <p>Pierre</p>
            </div> <div class="players">
                <h2>PHP</h2>
                <p>Ciseaux</p>
            </div>
        </section>

        <p class="winner">PHP is Winner</p>

        <section class="game-table">
            <div class="games">
                <a href="./index.php?choixJoueur=Pierre">Pierre</a>
                <a href="./index.php?choixJoueur=Feuille">Feuille</a>
                <a href="./index.php?choixJoueur=Ciseaux">Ciseaux</a>
            </div>
            <a href="">
                Reset Games
            </a>
        </section>

    </main>
</body>
</html>

HTML;

function RandomChoice () : string {
    $choiceArray = ["Pierre", "Feuille", "Ciseaux"];
    return array_rand($choiceArray);
}
function Delibarate(string $choixJoueur, string $choixRandom) : string {
    if($choixJoueur === $choixRandom) {
        return "MATCH NUL";
    }
    if(
        ($choixJoueur === "Feuille" && $choixRandom === "Pierre") ||
        ($choixJoueur === "Pierre" && $choixRandom === "Ciseaux") ||
        ($choixJoueur === "Ciseaux" && $choixRandom === "Feuille")
    ) {

    }
}

if(isset($_GET["choixJoueur"]) && !empty($_GET["choixJoueur"])){

    $choixJoueur = $_GET["choixJoueur"];
    $choixRandom = RandomChoice();



} else {
    header("Location: ./index.php");
//    exit();
}

echo $html;