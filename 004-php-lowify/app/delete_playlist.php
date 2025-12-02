<?php
require_once './inc/reusable.inc.php';


if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        $db = connectDatabase();

        //supprimer les liens de cette playlist puis ensuite elle même...
        //normalement le CASCADE en SQL le fait mais je n'ai pas trouvé le "ON DELETE CASCADE" dans le script de création...
        $db->executeQuery(<<<SQL
            DELETE FROM x_playlist_song WHERE playlist_id = $id;
        SQL);
        $db->executeQuery(<<<SQL
            DELETE FROM playlist WHERE id = $id;
        SQL);

        header("Location: playlists.php");
        exit;

    } catch (PDOException $e) {
        header("Location: error.php?message=Votre piste suppression de la playlist est cassée ! ");
        exit;
    }
}
header("Location: playlists.php");
