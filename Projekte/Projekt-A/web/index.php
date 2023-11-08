<?php
/**
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  Startseite
 * @last-modified 2023-09-22
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

/**
 * Die Titelseite der Webseite.
 *
 * Diese Seite zeigt eine Liste von Artikeln und ermöglicht die Paginierung.
 *
 * @var string $pageTitle Der Titel der Seite.
 * @var int $articlesPerPage Die Anzahl der Artikel pro Seite.
 * @var int $currentPage Die aktuelle Seite, die angezeigt wird.
 * @var PDO $pdo Die PDO-Datenbankverbindung.
 */

// Überprüfen, ob die Seite AMP ist
$host = $_SERVER['HTTP_HOST'];
$isAmpPage = str_contains($host, 'amp.');

// Setze den Titel basierend auf AMP oder nicht
if ($isAmpPage) {
    $pageTitle = "AMP - Azubi-Blog";
} else {
    $pageTitle = "Azubi-Blog";
}

$articlesPerPage = 3;

// Aktuelle Seite ermitteln
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

/**
 * Ermittelt die Gesamtanzahl der Artikel für die Pagination.
 *
 * @var string $sqlCount Die SQL-Abfrage, um die Gesamtanzahl der Artikel zu ermitteln.
 * @var int $totalArticles Die Gesamtanzahl der Artikel.
 * @var int $totalPages Die Gesamtanzahl der Seiten.
 */
$sqlCount = "SELECT COUNT(*) as total FROM articles";
$result = $pdo->query($sqlCount);
$totalArticles = $result->fetchColumn();
$totalPages = ceil($totalArticles / $articlesPerPage);

/**
 * Überprüft die eingegebene Seitenzahl und leitet bei Ungültigkeit auf die Fehlerseite um.
 *
 * @var int $urlParameter Der modifizierte URL-Parameter für die Pagination.
 */
$urlParameter = modifyUrlParameter($totalPages);

// Artikel für die aktuelle Seite ermitteln
$startIndex = ($currentPage - 1) * $articlesPerPage;

/**
 * Ermittelt Artikelinformationen für die aktuelle Seite.
 *
 * @var string $sql Die SQL-Abfrage, um Artikelinformationen abzurufen.
 * @var PDOStatement $statement Das vorbereitete PDO-Statement.
 * @var array $articles Ein Array mit Artikelinformationen.
 */
// Ermittelt Artikelinformationen für die aktuelle Seite.
$templateExtension = 'html.php'; // Standardvorlage

// Wenn IS_AMP wahr ist, verwende die AMP-Vorlage
if (IS_AMP) {
    $templateExtension = 'amp.php';
}

$sql = "SELECT articles.*, users.username FROM articles LEFT JOIN users ON users.id=articles.last_editor_id LIMIT :startIndex, :articlesPerPage";
$statement = $pdo->prepare($sql);
$statement->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
$statement->bindParam(':articlesPerPage', $articlesPerPage, PDO::PARAM_INT);
$statement->execute();
$articles = $statement->fetchAll(PDO::FETCH_ASSOC);

// Überprüfung, ob Artikel vorhanden sind; andernfalls auf die Fehlerseite umleiten
if (!$articles) {
    header('Location: /error/404.php');
    exit();
}

// Die Seite rendern
require("../template/index.$templateExtension");
