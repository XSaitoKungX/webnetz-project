<?php
/**
 * edit-users.php
 *
 * Diese Datei ermöglicht die Bearbeitung von Benutzerprofilen durch Administratoren.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  ArticleManagementSystem
 * @author   Nattapat
 * @last-modified 2023-09-18
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

// Seitentitel festlegen
$pageTitle = "Edit - Users";

// Sitzungsverwaltung
sessionManager(true);

// Prüfen, ob der Benutzer angemeldet ist, sonst zur Anmeldeseite weiterleiten
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob der angemeldete Benutzer Administrator ist
$sqlAdminCheck = "SELECT is_admin FROM users WHERE id = ?";
$stmtAdminCheck = $pdo->prepare($sqlAdminCheck);
$stmtAdminCheck->execute([$_SESSION['user_id']]);
$resultAdminCheck = $stmtAdminCheck->fetchColumn();

if ($resultAdminCheck !== 1) {
    header("Location: /profile.php?id=" . $_SESSION['user_id']);
    exit();
}

// Nutzerdaten aus der Datenbank abrufen
$sql = "SELECT * FROM users";
$stmtUsers = $pdo->query($sql);
$allUsers = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

include '../template/edit-users.html.php';
