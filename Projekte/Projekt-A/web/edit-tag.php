<?php
/**
 * edit-tag.php
 *
 * Diese Datei ermöglicht die Bearbeitung eines vorhandenen Tags.
 *
 * PHP-Version: 7.4
 *
 * @category Backend
 * @package  TagManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

$pageTitle = "Tag Bearbeiten";
sessionManager(true);

// Überprüfen, ob Benutzer angemeldet ist, sonst zur Anmeldeseite weitergeleitet
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob eine Tag-ID in der URL vorhanden ist, sonst zur Tag-Seite weitergeleitet
if (!isset($_GET['tag'])) {
    header("Location: /tags.php");
    exit();
}

// Tag-ID aus der URL holen
$tag = $_GET['tag'];

// Verarbeiten des Formulars, wenn Daten gesendet wurden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten abrufen und validieren
    $newTag = trim($_POST['tag']);

    if (empty($newTag)) {
        $error_message = "Bitte geben Sie einen Tag ein.";
    } else {
        // SQL-Abfrage ausführen, um den Tag in der Datenbank zu aktualisieren
        $sql = "UPDATE tags SET tag=? WHERE id=?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$newTag, $tag]);

        if ($statement->rowCount() > 0) {
            header('Location: /tags.php?tag=' . urlencode($newTag));
            exit();
        } else {
            $error_message = "Fehler beim Aktualisieren des Tags.";
        }
    }
}

// Überprüfen, ob der Tag in der Datenbank vorhanden ist
$sql = "SELECT * FROM tags WHERE tag = :tag";
$statement = $pdo->prepare($sql);
$statement->execute(['tag' => $tag]);
$tag = $statement->fetch(PDO::FETCH_ASSOC);

if (!$tag) {
    // Tag-Datensatz nicht gefunden, umleiten zur Tag-Seite
    header('Location: /tags.php');
    exit();
}

include '../template/edit-tag.html.php';
