<?php
/**
 * navigation.php
 *
 * Diese Datei ist für die Erstellung der Navigationsleiste und die Kategorienabfrage verantwortlich.
 *
 * PHP-Version 7.4
 *
 * @category Frontend
 * @package  Navigation
 * @author   Nattawat Pongsuwan
 * @last-modified 2023-09-15
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once '../config/config.php';
require_once('../app/mysql.php'); // Stelle eine PDO-Verbindung her

// Prüfe, ob der Benutzer ein Administrator ist
$isAdmin = false;
if (isLoggedIn()) {
    $userId = $_SESSION['user_id'];
    $isAdmin = isUserAdmin($userId, $pdo); // Übergebe die PDO-Verbindung zur Datenbank
}

$sql = "SELECT id, title FROM categories";
$statement = $pdo->query($sql);

/**
 * Das Ergebnis der SQL-Abfrage für die Navigationsleiste.
 *
 * @var array $navbarCategories Ein Array, das die Kategorien für die Navigationsleiste enthält.
 */
$navbarCategories = $statement->fetchAll(PDO::FETCH_ASSOC);

/**
 * Suchergebnisse, wenn das Suchformular abgesendet wurde.
 *
 * @var PDOStatement|false $searchStatement Das vorbereitete Statement für die Suche.
 */
$searchStatement = false;
if (isset($_POST["submit"])) {
    $search = $_POST["search"];
    $searchQuery = "SELECT * FROM articles WHERE title = :search";
    $searchStatement = $pdo->prepare($searchQuery);
    $searchStatement->bindParam(':search', $search, PDO::PARAM_STR);
    $searchStatement->execute();
}

// Die Navigationsleiste rendern
include('../template/_navigation.html.php');
