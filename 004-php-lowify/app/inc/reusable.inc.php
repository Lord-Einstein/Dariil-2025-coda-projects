<?php

require_once './inc/page.inc.php';
require_once './inc/database.inc.php';

$host = "mysql";
$dbname = "lowify";
$username = "lowify";
$password = "lowifypassword";


function formatCompact(int $number): string {
    if ($number >= 1_000_000_000) {
        $formatted = $number / 1_000_000_000;
        $suffix = ' B';
    } elseif ($number >= 1_000_000) {
        $formatted = $number / 1_000_000;
        $suffix = ' M';
    } elseif ($number >= 1_000) {
        $formatted = $number / 1_000;
        $suffix = ' k';
    } else {
        return (string)$number;
    }

    //Si ma décimale est pas propre, je remets une virgule
    if ($formatted == floor($formatted)) {
        return "+ " . floor($formatted) . $suffix;
    } else {
        return "+ " . number_format($formatted, 1) . $suffix;
    }
}
function formatDuration(int $seconds): string
{
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;

    //faut aussi mettre mes secondes sur deux caractères... merci str_pad ;)
    $formattedSeconds = str_pad($remainingSeconds, 2, "0", STR_PAD_LEFT);
    return $minutes . " m : " . $formattedSeconds . " s";
}
function formatDurationHMS(int $seconds): string {
    return gmdate("H:i:s", $seconds);
}

function connectDatabase(string $host="mysql", string $dbname="lowify", string $username="lowify", string $password="lowifypassword"): ?DatabaseManager {
    try {
        $db = new DatabaseManager(
            dsn: "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            username: $username,
            password: $password
        );
        return $db;

    } catch (PDOException $e) {
        echo "ERREUR DE CONNEXION BDD : " . $e->getMessage();
        return null;
    }
}

function generateQuerryError(PDOException $e) : void {
    echo "ERREUR DE QUERY" . $e->getMessage();
}