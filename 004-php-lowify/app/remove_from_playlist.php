<?php
require_once './inc/reusable.inc.php';

$db = null;

//recup des id envoyés depuis mon playlist.php
if (isset($_GET['song_id']) && isset($_GET['playlist_id'])) {
    $sId = (int)$_GET['song_id'];
    $pId = (int)$_GET['playlist_id'];

    try {
        $db = connectDatabase();

        //supprimer le lien avec la playlist dans x_playlist_song
        $db->executeQuery(<<<SQL
            DELETE FROM x_playlist_song
            WHERE playlist_id = $pId AND song_id = $sId
            LIMIT 1;
            --Pour bien ne supprimer qu'une seule fois en cas de doublons de la chanson dans la playlist
        SQL);

        //récupérer la durée totale et ensuite actualiser les données dans la playlist
        $songData = $db->executeQuery(<<<SQL
            SELECT duration FROM song WHERE id = $sId;
        SQL);
        $duration = $songData[0]["duration"];

        $db->executeQuery(<<<SQL
            UPDATE playlist
            SET nb_song = GREATEST(0, nb_song - 1),
            duration = GREATEST(0, duration - $duration);
            WHERE id = $pId;
            --Les greatest pour pas avoir de valeurs négatives par sécurité...
        SQL);

        header("Location: playlist.php?id=$pId");//revenir à la playlist
        exit;

    } catch (PDOException $e) {
        header("Location: error.php?message=Oups! Petit bug de suppression.");
    }
}
header("Location: playlists.php");
