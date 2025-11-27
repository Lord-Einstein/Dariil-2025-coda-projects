<?php

const VALIDATORS_VALUE = 5;
$validators_score = 0;

$html_validators = "";
$html_validators_score = "";

$validators = [
    "Comporte au moins 8 caractères." => false,
    "Contient au moins une lettre majuscule." => false,
    "Contient au moins une lettre minuscule." => false,
    "Contient au moins un chiffre." => false,
    "Contient au moins un symbole." => false
];



function ValidatePassword(array &$validators, string $password, int &$validators_score): void {

    if(strlen($password) >= 8) {
        $validators["Comporte au moins 8 caractères."] = true;
    }

    preg_match_all('/([A-Z])|([a-z])|(\d)|(\W)/', $password, $matches);
    if(!empty($matches[0])) {
        return;
    } else {
        if(!empty($matches[1])) {
            $validators["Contient au moins une lettre majuscule."] = true;
            $validators_score += VALIDATORS_VALUE;
        }
        if(!empty($matches[2])) {
            $validators["Contient au moins une lettre minuscule."] = true;
            $validators_score += VALIDATORS_VALUE;
        }
        if(!empty($matches[3])) {
            $validators["Contient au moins un chiffre."] = true;
            $validators_score += VALIDATORS_VALUE;
        }
        if(!empty($matches[4])) {
            $validators["Contient au moins un symbole."] = true;
            $validators_score += VALIDATORS_VALUE;
        }
    }
}

function EditValidators(array $validators) : string {
    $all_validators = "";
    $class = "RED";
    foreach($validators as $description => $state) {
        if($state) { $class = "GREEN";}
        $all_validators .= "<p class=\"{$class}\">{$description}</p>";
    }

    return $all_validators;
}

function EditScore(int $validators_score) : string {
    return (string)($validators_score*VALIDATORS_VALUE);
}

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    
    <h1 class="legend">Password Validator.</h1>
    <main>
        <form action="./validator.php" method="post">
            <fieldset class="first_field">
                <label for="password">Password.</label>
                <input type="text" name="password" id="password" placeholder="**********" required>
            </fieldset>

            <button type="submit">Validate.</button>
        </form>

        <div class="valid_blocs">
            <p class="note_lign"><span class="note">20</span>/100</p>
            <p>Comporte au moins 8 caractères.</p>
            <p>Contient au moins une lettre majuscule.</p>
            <p>Contient au moins une lettre minuscule.</p>
            <p>Contient au moins un chiffre.</p>
            <p>Contient au moins un symbole.</p>
        </div>

    </main>
    <a href="./index.php">GENERATOR.</a>
</body>
</html>

HTML;

echo $html;