<?php
/**
 * edit-tags.php
 *
 * Diese Datei ermöglicht die Bearbeitung von Tags.
 *
 * PHP-Version: 7.4
 *
 * @category Backend
 * @package  TagManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

// Seitentitel festlegen
$pageTitle = "Edit - Tags";

// Sitzungsverwaltung
sessionManager(true);

// Prüfen, ob der Benutzer angemeldet ist, sonst zur Anmeldeseite weiterleiten
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Nutzerdaten aus der Datenbank abrufen
$sql = "SELECT * FROM tags";
$stmtTags = $pdo->query($sql);
$allTags = $stmtTags->fetchAll(PDO::FETCH_ASSOC);

include '../template/edit-tags.html.php';
