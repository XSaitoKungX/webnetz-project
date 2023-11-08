<?php
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

/**
 * Delete Category Page
 *
 * This page allows authorized users to delete a category.
 * It checks for user authentication and handles category deletion.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    CategoryManagementSystem
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

$pageTitle = "Delete - Category";

sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: /categories.php");
    exit();
}

// Kategorie-ID abrufen
$categoryId = $_GET['id'];

// SQL-Abfrage ausführen, um die Kategorie zu löschen (Verwendung von PDO)
$sql = "DELETE FROM categories WHERE id = :categoryId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Kategorie erfolgreich gelöscht
    $success_message = "Kategorie erfolgreich gelöscht";
} else {
    // Fehler beim Löschen der Kategorie
    $error_message = "Fehler beim Löschen der Kategorie: " . $stmt->errorInfo()[2];
}

include '../template/delete-category.html.php';
