<?php

$choixJoueur = "";
$choixRandom = "";
$result = "";

$winsJoueur = 0;
$winsRandom = 0;
$drawParts = 0;
$playedParts = 0;
$bestSerie = 0;


function RandomChoice () : string {
    $choiceArray = ["Pierre", "Feuille", "Ciseaux", "Lezard", "Spock"];
    return $choiceArray[array_rand($choiceArray)];
}
function Delibarate(string $choixJoueur, string $choixRandom) : string {

    $winCases = [
        "Pierre" => ["Ciseaux", "Lezard"],
        "Feuille" => ["Pierre", "Spock"],
        "Ciseaux" => ["Feuille", "Lezard"],
        "Lezard" => ["Feuille", "Spock"],
        "Spock" => ["Pierre", "Ciseaux"]
    ];

    if($choixJoueur === $choixRandom) {
        return "DUEL NUL";
    }
    else if(in_array($choixRandom, $winCases[$choixJoueur])) {
        return "VICTOIRE ROYALE";
    }
    else {
        return "DEFAITE ROYALE";
    }
}


if(isset($_GET["choixJoueur"]) && !empty($_GET["choixJoueur"])){

    $choixJoueur = $_GET["choixJoueur"];
    $choixRandom = RandomChoice();

    $result = Delibarate($choixJoueur, $choixRandom);

} else {
    $choixJoueur = "- - -";
    $choixRandom = "- - -";
    $result = "DO YOUR ROYAL CHOICE.";
}


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
                <p>$choixJoueur</p>
            </div> <div class="players">
                <h2>PHP</h2>
                <p>$choixRandom</p>
            </div>
        </section>

        <p class="winner">$result</p>

        <section class="game-table">
            <div class="games">
                <a href="./index.php?choixJoueur=Pierre">Pierre</a>
                <a href="./index.php?choixJoueur=Feuille">Feuille</a>
                <a href="./index.php?choixJoueur=Ciseaux">Ciseaux</a>
                <a href="./index.php?choixJoueur=Lezard">Lezard</a>
                <a href="./index.php?choixJoueur=Spock">Spock</a>
            </div>
            <a href="./index.php">
                Reset Games.
            </a>
        </section>

    </main>
</body>
</html>

HTML;



echo $html;