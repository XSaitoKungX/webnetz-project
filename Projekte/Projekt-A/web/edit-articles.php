<?php
/**
 * edit-articles.php
 *
 * Diese Datei ermöglicht die Verwaltung von Artikeln durch Administratoren.
 * Administratoren können alle Artikel anzeigen und bearbeiten.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  ArticleManagementSystem
 * @last-modified 2023-09-15
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php'; // Wir verwenden jetzt PDO statt mysqli
require_once '../app/functions.php';

$pageTitle = "Artikel verwalten";

sessionManager(true);

// Überprüfen, ob Benutzer angemeldet ist, sonst zur Anmeldeseite weitergeleitet
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob der angemeldete Benutzer Administrator ist
$userQuery = "SELECT is_admin FROM users WHERE id = ?";
$userStatement = $pdo->prepare($userQuery);
$userStatement->execute([$_SESSION['user_id']]);
$userResult = $userStatement->fetch(PDO::FETCH_ASSOC);

// Überprüfen, ob der Benutzer Administratorrechte hat
if (!$userResult || !$userResult['is_admin']) {
    header("Location: /profile.php?id=" . $_SESSION['user_id']);
    exit();
}

// Artikel-Daten aus der Datenbank abrufen
$articleQuery = "SELECT * FROM articles";
$articleStatement = $pdo->query($articleQuery);
$allArticles = $articleStatement->fetchAll(PDO::FETCH_ASSOC);

include '../template/edit-articles.html.php';
