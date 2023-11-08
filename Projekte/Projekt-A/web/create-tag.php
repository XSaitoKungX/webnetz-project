<?php
/**
 * Dateiname: create-tag.php
 *
 * Beschreibung: Behandelt die Anzeige und Verarbeitung des Formulars zum Erstellen eines neuen Tags.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    ArticleManagementSystem
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

$pageTitle = "Neuen Tag erstellen";

sessionManager(true);

// Weiterleitung, wenn der Benutzer nicht angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Formularverarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und mit der Datenbank überprüfen
    $tagName = $_POST['tag'];

    // Überprüfen, ob der Tagname nicht leer ist
    if (!empty($tagName)) {
        try {
            // SQL-Abfrage ausführen, um den Tag in der Datenbank zu erstellen
            $sql = "INSERT INTO tags (tag) VALUES (?)";
            $statement = $pdo->prepare($sql);
            $statement->execute([$tagName]);

            $success_message = "Tag erfolgreich erstellt";

            // Weiterleitung nach erfolgreichem Erstellen des Tags
            header('Location: /edit-tags.php');
            exit();
        } catch (PDOException $e) {
            $error_message = "Fehler beim Erstellen des Tags: " . $e->getMessage();
        }
    } else {
        $error_message = "Bitte geben Sie einen Tag ein.";
    }
}

include '../template/create-tag.html.php'; // Hier sollte Ihre HTML-Vorlage für das Formular sein
