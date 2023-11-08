<?php
/**
 * delete-user.php
 *
 * Diese Datei ermöglicht das Löschen eines Benutzers aus der Datenbank.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  UserManagementSystem
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Seitentitel festlegen
$pageTitle = "Delete - User";

// Session-Verwaltung
sessionManager(true);

// Benutzer-Authentifizierung
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob eine Benutzer-ID im Query-String vorhanden ist
if (!isset($_GET['id'])) {
    header("Location: /profile.php");
    exit();
}

// Benutzer-ID aus dem Query-String abrufen
$userId = $_GET['id'];

// Vorbereitung der SQL-Abfrage zum Löschen des Benutzers mit PDO
$sql = "DELETE FROM users WHERE id = :userId";
$statement = $pdo->prepare($sql);
$statement->bindParam(':userId', $userId, PDO::PARAM_INT);

if ($statement->execute()) {
    // Benutzer erfolgreich gelöscht
    $success_message = "Benutzer erfolgreich gelöscht!";
} else {
    // Fehler beim Löschen des Benutzers
    $error_message = "Fehler beim Löschen des Benutzers: " . implode(" ", $statement->errorInfo());
}

include '../template/delete-user.html.php';
