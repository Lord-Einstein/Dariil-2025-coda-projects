<?php

$options = "";
$placeholder = "";

$default_selected = 10;

$maj_letters = 0;
$min_letters = 0;
$numbers = 0;
$symbols = 0;


$passwordGenerated = "";

$selected_size = $default_selected;
$maj_array = range('A', 'Z');
$min_array = range('a', 'z');
$num_array = range(0, 9);
$symbols_array = str_split(" !\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~ ");



function generateSelectOptions (string &$placeholder, int &$default_selected = 10, int $min_size = 8, int $max_size = 45) : string {
    $options = "";
    if($default_selected < $min_size){$default_selected = $min_size;}
    for($i = $min_size; $i <= $max_size; $i++) {
        if($i === $default_selected) {
            $option = "<option value=\"{$i}\" selected>$i</option>";
        } else {
            $option = "<option value=\"{$i}\">$i</option>";
        }
        $options .= $option;
    }
    $placeholder = str_repeat("*", $default_selected);
    return $options;
}

function recupDatas() : void {

    if(isset($_POST["submit"])){
        $maj_letters = (int) $_POST["maj_array"] ?? 0;
        $min_letters = (int) $_POST["maj_array"] ?? 0;
        $numbers = (int) $_POST["maj_array"] ?? 0;
        $min_array = (int) $_POST["maj_array"] ?? 0;

        $selected_size = (int) $_POST["size"] ?? 0;
    } else {
        return;
    }


    $_POST = array();

}

$options = generateSelectOptions($placeholder);

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
    <main>
        <form action="./" method="post">
            <fieldset>
                <legend>Password Generator.</legend>

                <label for="password">Password.</label>
                <input type="text" name="password" id="password" value="" placeholder="$placeholder" readonly>
            </fieldset>

            <button type="submit">Genarate.</button>
            
             <fieldset>
                <label for="size">Generated text size.</label>
                <select name="size" id="size" aria-label="The generated default text size.">
                    {$options}
                </select>
            </fieldset>
        
            <div class="conditions">
                <fieldset>
                    <label for="maj_letters">Inclure les lettres majuscules.</label>
                    <input type="checkbox" name="maj_letters" id="maj_letters" value="1" checked>
                </fieldset>
                <fieldset>
                    <label for="min_letters">Inclure les lettres minuscules.</label>
                    <input type="checkbox" name="min_letters" id="min_letters" value="1" checked>
                </fieldset>
                <fieldset>
                    <label for="numbers">Inclure les chiffres de 0 à 9.</label>
                    <input type="checkbox" name="numbers" id="numbers" value="1" checked>
                </fieldset>
                <fieldset>
                    <label for="symbols">Inclure les symboles (%>#...).</label>
                    <input type="checkbox" name="symbols" id="symbols" value="1" checked>
                </fieldset>
            </div>
        

HTML;









$html .= <<<HTML
        </form>
    </main>
</body>
</html>

HTML;

echo $html;