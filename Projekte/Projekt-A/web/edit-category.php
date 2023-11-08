<?php

/**
 * edit-category.php
 *
 * Diese Datei ermöglicht die Bearbeitung einer vorhandenen Kategorie.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  CategoryManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

$pageTitle = "Kategorie Bearbeiten";
sessionManager(true);

// Überprüfen, ob Benutzer angemeldet ist, sonst zur Anmeldeseite weitergeleitet
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob eine Kategorie-ID in der URL vorhanden ist, sonst zur Kategorieseite weitergeleitet
if (!isset($_GET['id'])) {
    header("Location: /categories.php");
    exit();
}

// Kategorie-ID aus der URL holen
$categoryId = $_GET['id'];

// Verarbeiten des Formulars, wenn Daten gesendet wurden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und mit der Datenbank überprüfen

    // Formulardaten abrufen und validieren
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $last_change_date = date('Y-m-d H:i:s'); // Aktuelles Änderungsdatum
    $last_editor_id = $_SESSION['user_id'];

    if (empty($title) || empty($description)) {
        $error_message = "Bitte geben Sie Daten in alle Felder ein.";
    } else {
        // SQL-Abfrage ausführen, um die Kategorie in der Datenbank zu aktualisieren
        $sql = "UPDATE categories SET title=?, description=?, last_change_date=?, last_editor_id=? WHERE id=?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$title, $description, $last_change_date, $last_editor_id, $categoryId]);

        if ($statement->rowCount() > 0) {
            header('Location: /categories.php?id=' . $categoryId);
            exit();
        } else {
            $error_message = "Fehler beim Aktualisieren der Kategorie.";
        }
    }
}

// Überprüfen, ob die Kategorie in der Datenbank vorhanden ist
$sql = "SELECT * FROM categories WHERE id = :category_id";
$statement = $pdo->prepare($sql);
$statement->execute(['category_id' => $categoryId]);
$category = $statement->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    // Kategorie-Datensatz nicht gefunden, umleiten zur Kategorieseite
    header('Location: /categories.php');
    exit();
}

$sql = "SELECT * FROM users";
$result = $pdo->query($sql);
$users = $result->fetchAll(PDO::FETCH_ASSOC);

include '../template/edit-category.html.php';
