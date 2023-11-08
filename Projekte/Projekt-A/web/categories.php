<?php
/**
 * Dateiname: categories.php
 *
 * Beschreibung: Behandelt die Anzeige und Verwaltung von Kategorien.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    Kategorieverwaltung
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Seitentitel festlegen
$pageTitle = "Kategorien";
$categoriesPerPage = 3;

// ====================== [1. Database Query - Anzahl der Kategorien ermitteln] ====================== //

// SQL-Abfrage, um die Gesamtanzahl der Kategorien für die Paginierung zu ermitteln
$sqlCount = "SELECT COUNT(*) as total FROM categories";
$statementCount = $pdo->prepare($sqlCount);
$statementCount->execute();
$totalCategories = $statementCount->fetchColumn();
$totalPages = ceil($totalCategories / $categoriesPerPage);

// Aktuelle Seite aus der URL abrufen oder auf die erste Seite setzen
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// URL-Parameter für die Pagination erstellen
$urlParameter = modifyUrlParameter($totalPages);

// ====================== [2. Database Query - Kategorien abrufen] ====================== //

// Den Startindex für die Abfrage basierend auf der aktuellen Seite berechnen
$startIndex = ($currentPage - 1) * $categoriesPerPage;

// SQL-Abfrage, um Kategorien mit dem letzten Bearbeiter abzurufen
$sql = "SELECT categories.*, users.username FROM categories LEFT JOIN users ON users.id=categories.last_editor_id LIMIT :start, :perPage";
$statement = $pdo->prepare($sql);
$statement->bindParam(":start", $startIndex, PDO::PARAM_INT);
$statement->bindParam(":perPage", $categoriesPerPage, PDO::PARAM_INT);
$statement->execute();

// Kategorien aus der Datenbank abrufen
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// Wenn keine Kategorien gefunden wurden, auf die 404-Seite umleiten
if (empty($categories)) {
    header('Location: /error/404.php');
    exit();
}

// Die gefundenen Kategorien in das Template einfügen
include '../template/categories.html.php';
