<?php
/**
 * search.php
 *
 * Diese Datei ermöglicht die Suche nach Artikeln und deren Anzeige in Suchergebnissen.
 *
 * PHP-Version 7.4
 *
 * @category Frontend
 * @package  Suche
 * @author   Nattawat Pongsuwan
 * @last-modified 2023-09-18
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

$pageTitle = "Search";

$searchPerPage = 3;

// Überprüfen, ob das Formular abgesendet wurde
if (isset($_GET['search'])) {
    $searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] .  '%' : '';
    $searchCategory = isset($_GET['searchCategory']) ? (int)$_GET['searchCategory'] : 0;

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $startIndex = ($currentPage - 1) * $searchPerPage;

    if (empty($searchTerm) && $searchCategory == 0) {
        // Szenario 1: Kein Suchbegriff, keine Kategorie ausgewählt
        $sql = "SELECT * FROM articles LIMIT :startIndex, :searchPerPage";
    } elseif (!empty($searchTerm) && $searchCategory == 0) {
        // Szenario 2: Suchbegriff angegeben, keine Kategorie ausgewählt
        $sql = "SELECT * FROM articles WHERE title LIKE :searchTerm OR description LIKE :searchTerm OR content LIKE :searchTerm LIMIT :startIndex, :searchPerPage";
    } elseif (empty($searchTerm) && $searchCategory > 0) {
        // Szenario 3: Kein Suchbegriff, Kategorie ausgewählt
        $sql = "SELECT * FROM articles WHERE category_id = :searchCategory LIMIT :startIndex, :searchPerPage";
    } elseif (!empty($searchTerm) && $searchCategory > 0) {
        // Szenario 4: Suchbegriff angegeben, spezifische Kategorie ausgewählt
        $sql = "SELECT * FROM articles WHERE (title LIKE :searchTerm OR description LIKE :searchTerm OR content LIKE :searchTerm) AND category_id = :searchCategory LIMIT :startIndex, :searchPerPage";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':searchCategory', $searchCategory, PDO::PARAM_INT);
    $stmt->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
    $stmt->bindParam(':searchPerPage', $searchPerPage, PDO::PARAM_INT);

    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gesamtanzahl der Seiten für die Paginierung berechnen
    $totalPages = ceil(count($articles) / $searchPerPage);

    // URL-Parameteranpassung
    $urlParameter = modifyUrlParameter($totalPages);
}

// Bestimmen der Gesamtanzahl der Kategorien (für die Paginierung)
if ($searchCategory > 0) {
    $sql = "SELECT COUNT(*) as total FROM articles WHERE category_id = :searchCategory";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':searchCategory', $searchCategory, PDO::PARAM_INT);
} else {
    $sql = "SELECT COUNT(*) as total FROM articles";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$totalPages = ceil($result['total'] / $searchPerPage);

$urlParameter = modifyUrlParameter($totalPages);

// Inkludieren der Vorlage zur Anzeige der Suchergebnisse
include '../template/search.html.php';
