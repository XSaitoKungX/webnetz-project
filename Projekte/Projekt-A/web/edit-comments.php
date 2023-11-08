<?php
/**
 * edit-comments.php
 *
 * Diese Datei ermöglicht die Verwaltung von Kommentaren.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  CommentManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

$pageTitle = "Kommentarverwaltung";

sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob der angemeldete Benutzer Administrator ist
$sqlAdminCheck = "SELECT is_admin FROM users WHERE id = :user_id";
$paramsAdminCheck = ['user_id' => $_SESSION['user_id']];
$resultAdminCheck = $pdo->prepare($sqlAdminCheck);
$resultAdminCheck->execute($paramsAdminCheck);

// Überprüfen, ob der Benutzer ein Administrator ist
$isAdmin = $resultAdminCheck->fetchColumn();
if ($isAdmin != 1) {
    header("Location: /profile.php?id=" . $_SESSION['user_id']);
    exit();
}

// Kommentardaten aus der Datenbank abrufen
$sql = "SELECT * FROM comments";
$result = $pdo->query($sql);
$allComments = $result->fetchAll(PDO::FETCH_ASSOC);

// Die HTML-Vorlage für die Kommentarverwaltung anzeigen
include '../template/edit-comments.html.php';
