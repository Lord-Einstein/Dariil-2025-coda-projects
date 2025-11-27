<?php

const VALIDATORS_VALUE = 20;

$validators_score = 0;

$password = "";
$html_validators = "";
$html_validators_score = "";

$validators = [
    "Comporte au moins 8 caractères." => false,
    "Contient au moins une lettre majuscule." => false,
    "Contient au moins une lettre minuscule." => false,
    "Contient au moins un chiffre." => false,
    "Contient au moins un symbole." => false
];


$password = $_GET["password"] ?? null;


function ValidatePassword(array &$validators, string $password, int &$validators_score): void {

    $validators_score = 0;

    $matches = [];
    preg_match_all('/([A-Z])|([a-z])|(\d)|([^\w\s])/', $password, $matches);

    $validators["Comporte au moins 8 caractères."] = (strlen($password) >= 8);


    $validators["Contient au moins une lettre majuscule."] = (count(array_filter($matches[1])) > 0) ? true : false;
    $validators["Contient au moins une lettre minuscule."] = (count(array_filter($matches[2])) > 0) ? true : false;
    $validators["Contient au moins un chiffre."] = (count(array_filter($matches[3])) > 0) ? true : false;
    $validators["Contient au moins un symbole."] = (count(array_filter($matches[4])) > 0) ? true : false;

    foreach($validators as $state) {
        if($state === true) {
            $validators_score += VALIDATORS_VALUE;
        }
    }
}

function EditValidators(array $validators) : string {
    $all_validators = "";

    foreach($validators as $description => $state) {
        $class = ($state===true) ? "GREEN" : "RED";
        $all_validators .= "<p class=\"$class\">$description</p>\n";
    }
    return $all_validators;
}

function EditScore(int $validators_score) : string {
    return (string)($validators_score);
}


if(isset($_POST["submit"])) {
    $password = $_POST["password"] ?? null;

    if ($password === "") {
        $html_validators = EditValidators($validators);
        $html_validators_score = EditScore(0);
    } else {
        validatePassword($validators, $password, $validators_score);
        $html_validators = EditValidators($validators);
        $html_validators_score = EditScore($validators_score);
    }

} else {
//    $html_validators = EditValidators($validators);
        $html_validators_score = EditScore($validators_score);
}


$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator</title>
    <link rel="stylesheet" href="./style2.css">
</head>
<body>
    
    <h1 class="legend">Password Validator.</h1>
    <main>
        <form action="./validator.php" method="post">
            <fieldset class="first_field">
                <label for="password"></label>
                <input type="text" name="password" id="password" value="{$password}" placeholder="**********" required>
            </fieldset>

            <button type="submit" name="submit">Validate.</button>
        </form>

        <div class="valid_blocs">
            <p class="note_lign"><span class="note">{$html_validators_score}</span>/100</p>
            {$html_validators}
        </div>

    </main>
    <a href="./index.php">GENERATOR.</a>
</body>
</html>

HTML;

echo $html;