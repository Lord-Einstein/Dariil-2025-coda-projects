<?php

require_once './inc/reusable.inc.php';

$db = null;

$songs = [];
$db = connectDatabase();


if (isset($_GET['id']) && !empty($_GET['id'])) {

    $songId = (int)$_GET['id']; //juste pour m'assurer que j'ai pas mon id en type mixed...

    try {
        $songs = $db->executeQuery(<<<SQL
            SELECT id, is_liked
            FROM song
            WHERE id = $songId;
        SQL);
    } catch (PDOException $e) {
        header("Location: ./error.php?message=Cette piste est introuvable.");
        exit;
    }

    //si ma bdd me retourne un résultat
    if ($songs) {
        $song = $songs[0];

        $currentLike = $song['is_liked']; //l'état actuel de mon champ is_liked
        $newLike = ($currentLike == 1) ? 0 : 1; //j'inverse en ternaire

        try {
            $db->executeQuery(<<<SQL
                UPDATE song
                SET is_liked = $newLike
                WHERE id = $songId;
            SQL);
        } catch (PDOException $e) {
            header("Location: ./error.php?message=Votre like s'est fait la malle!.");
            exit;
        }

        $ancre = "{$songId}";

        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']); //conformément à la consigne
            exit;
        } else {
            header('Location: ./index.php');//au cas où...
        }
        exit;

    } else {
        header("Location: ./error.php?message=Cette chanson n'existe pas.");
        exit;
    }

} else {
    header("Location: ./error.php?message=Identifiant de chanson manquant.");
    exit;
}