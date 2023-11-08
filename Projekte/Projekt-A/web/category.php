<?php
/**
 * Dateiname: category.php
 *
 * Beschreibung: Behandelt die Anzeige von Artikeln in einer bestimmten Kategorie.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    Kategorie
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

require_once '../app/mysql.php';
require_once '../app/functions.php';
require_once '../config/config.php';

// Anzahl der Artikel pro Seite für die Pagination festlegen
$categoryPerPage = 3;

// Kategorie-ID aus der URL abrufen oder auf die Standardkategorie setzen
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Aktuelle Seite aus der URL abrufen oder auf die erste Seite setzen
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// ====================== [1. Database Query - Gesamtanzahl der Artikel ermitteln] ====================== //

// SQL-Abfrage, um die Gesamtanzahl der Artikel in dieser Kategorie für die Pagination zu ermitteln
$sqlCount = "SELECT COUNT(*) as total FROM articles WHERE category_id = :categoryId";
$statementCount = $pdo->prepare($sqlCount);
$statementCount->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
$statementCount->execute();
$totalCategories = $statementCount->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalCategories / $categoryPerPage);

// URL-Parameter für die Pagination erstellen
$urlParameter = modifyUrlParameter($totalPages);

// ====================== [2. Database Query - Artikel abrufen] ====================== //

// Den Startindex für die Abfrage basierend auf der aktuellen Seite berechnen
$startIndex = ($currentPage - 1) * $categoryPerPage;

// SQL-Abfrage, um Artikel in dieser Kategorie zu abzurufen (mit Pagination)
$sql = "SELECT * FROM articles WHERE category_id = :categoryId LIMIT :startIndex, :categoryPerPage";
$statement = $pdo->prepare($sql);
$statement->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
$statement->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
$statement->bindParam(':categoryPerPage', $categoryPerPage, PDO::PARAM_INT);
$statement->execute();
$articles = $statement->fetchAll(PDO::FETCH_ASSOC);

// ====================== [3. Database Query - Kategoriename abrufen] ====================== //

// SQL-Abfrage, um den Namen der aktuellen Kategorie abzurufen
$sql = "SELECT title FROM categories WHERE id = :categoryId";
$statement = $pdo->prepare($sql);
$statement->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
$statement->execute();
$pageTitle = $statement->fetch(PDO::FETCH_COLUMN);

// Das gefundene Ergebnis in das Template einfügen
include '../template/category.html.php';
