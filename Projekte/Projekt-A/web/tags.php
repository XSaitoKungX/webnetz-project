<?php
/**
 * tags.php
 *
 * Diese Datei zeigt alle Tags an.
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
$pageTitle = "Tags";

// Tags aus der Datenbank abrufen
$sql = "SELECT * FROM tags";
$stmtTags = $pdo->query($sql);
$allTags = $stmtTags->fetchAll(PDO::FETCH_ASSOC);

include '../template/tags.html.php';
