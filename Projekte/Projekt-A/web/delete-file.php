<?php
/**
 * Datei löschen - Seite
 *
 * Diese Seite ermöglicht das Löschen von Dateien, die zu Artikeln gehören. Sie überprüft die Benutzerauthentifizierung,
 * die Dateiexistenz und führt das Löschen durch.
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

$pageTitle = "Delete - Option";

sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

if (isset($_GET['filename']) && isset($_GET['id'])) {
    $articleId = (int)$_GET['id'];
    $fileName = $_GET['filename'];

    $fileDirectory = "../uploads/" . $articleId . "/";
    $fileDelete = $fileDirectory . $fileName;

    if (file_exists($fileDelete)) {
        // Lösche das Originalbild und alle skalierten Bilder (unverändert gelassen)
        unlink($fileDelete);

        // Lösche skalierte Bilder, die den Dateinamen des Originalbilds als Präfix haben
        $pattern = $fileDirectory . str_replace('.', '_*', $fileName);
        $matchingFiles = glob($pattern);

        foreach ($matchingFiles as $matchingFile) {
            unlink($matchingFile);
        }

        $serverMessage = 'Datei "' . $fileName . '" wurde gelöscht.';
    } else {
        $serverMessage = "Datei nicht gefunden!";
    }

    $_SESSION['serverMessage'] = $serverMessage;
    header('Location: edit-article.php?id=' . $articleId);
} else {
    $serverMessage = "Ups! Ein Fehler ist aufgetreten. Versuche es erneut!";
    $_SESSION['serverMessage'] = $serverMessage;
    header('Location: index.php');
}
