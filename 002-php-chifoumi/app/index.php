<?php

$choixJoueur = "";
$choixRandom = "";
$result = "";

$winsJoueur = 0;

$stats = [
    "winsJoueur" => 0,
    "winsRandom" => 0,
    "drawParts" => 0,
    "playedParts" => 0,
    "serie" => 0,
    "bestSerie" => 0
];


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
function StatsFiller(string $result, array &$stats) : void {

    foreach ($stats as $key => $value) {
        $stats[$key] = (int) ($_GET[$key] ?? 0);
    }

    $stats["playedParts"]++;

    switch($result) {
        case "DUEL NUL":
            $stats["drawParts"]++;
            $stats["serie"] = 0;
        break;

        case "VICTOIRE ROYALE":
            $stats["winsJoueur"]++;
            $stats["serie"]++;
        break;

        case "DEFAITE ROYALE":
            $stats["winsRandom"]++;
            $stats["serie"] = 0;
        break;

        default:
        return;

    }

    if($stats["bestSerie"] < $stats["serie"]) {
        $stats["bestSerie"] = $stats["serie"];
    }

}



if(isset($_GET["choixJoueur"]) && !empty($_GET["choixJoueur"])){

    $choixJoueur = $_GET["choixJoueur"];
    $choixRandom = RandomChoice();

    $result = Delibarate($choixJoueur, $choixRandom);
    StatsFiller($result, $stats);

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
                <a href="./index.php?choixJoueur=Pierre&winsJoueur={$stats["winsJoueur"]}&winsRandom={$stats["winsRandom"]}&drawParts={$stats["drawParts"]}&playedParts={$stats["playedParts"]}&serie={$stats["serie"]}&bestSerie={$stats["bestSerie"]}">Pierre</a>
                
                <a href="./index.php?choixJoueur=Feuille&winsJoueur={$stats['winsJoueur']}
                    &winsRandom={$stats['winsRandom']}
                    &drawParts={$stats['drawParts']}
                    &playedParts={$stats['playedParts']}
                    &serie={$stats['serie']}
                    &bestSerie={$stats['bestSerie']}
                ">Feuille</a>
                
                <a href="./index.php?choixJoueur=Ciseaux&winsJoueur={$stats['winsJoueur']}
                    &winsRandom={$stats['winsRandom']}
                    &drawParts={$stats['drawParts']}
                    &playedParts={$stats['playedParts']}
                    &serie={$stats['serie']}
                    &bestSerie={$stats['bestSerie']}
                ">Ciseaux</a>
                
                <a href="./index.php?choixJoueur=Lezard&winsJoueur={$stats['winsJoueur']}
                    &winsRandom={$stats['winsRandom']}
                    &drawParts={$stats['drawParts']}
                    &playedParts={$stats['playedParts']}
                    &serie={$stats['serie']}
                    &bestSerie={$stats['bestSerie']}
                ">Lezard</a>
                
                <a href="./index.php?choixJoueur=Spock&winsJoueur={$stats['winsJoueur']}
                    &winsRandom={$stats['winsRandom']}
                    &drawParts={$stats['drawParts']}
                    &playedParts={$stats['playedParts']}
                    &serie={$stats['serie']}
                    &bestSerie={$stats['bestSerie']}
                ">Spock</a>

            </div>
            <a href="./index.php">
                Reset Games.
            </a>
        </section>

    </main>
    
     <section class="stats">
        <div class="box">
            <p>VICTOIRE</p>
            <p class="data">{$stats["winsJoueur"]}</p>
        </div>
        <div class="box">
            <p>DEFAITE</p>
            <p class="data">{$stats["winsRandom"]}</p>
        </div>
        <div class="box">
            <p>Matchs nuls</p>
            <p class="data">{$stats["drawParts"]}</p>
        </div>
        <div class="box">
            <p>Parties totales</p>
            <p class="data">{$stats["playedParts"]}</p>
        </div>
        <div class="box">
            <p>série</p>
            <p class="data">{$stats["serie"]}</p>
        </div>
        <div class="box">
            <p>Meilleur score</p>
            <p class="data">{$stats["bestSerie"]}</p>
        </div>
    </section>


</body>
</html>

HTML;



echo $html;