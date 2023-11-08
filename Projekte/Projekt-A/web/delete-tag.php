<?php
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

/**
 * Delete Tag Page
 *
 * This page allows authorized users to delete a tag.
 * It checks for user authentication and handles tag deletion.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    TagManagementSystem
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

$pageTitle = "Tag löschen";

sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

if (!isset($_GET['tag'])) {
    header("Location: /tags.php");
    exit();
}

// Tag abrufen
$tag = $_GET['tag'];

// SQL-Abfrage ausführen, um den Tag zu löschen (Verwendung von PDO)
$sql = "DELETE FROM tags WHERE tag = :tag";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':tag', $tag, PDO::PARAM_STR);

if ($stmt->execute()) {
    // Tag erfolgreich gelöscht
    $success_message = "Tag erfolgreich gelöscht";
} else {
    // Fehler beim Löschen des Tags
    $error_message = "Fehler beim Löschen des Tags: " . $stmt->errorInfo()[2];
}

include '../template/delete-tag.html.php';
?>
