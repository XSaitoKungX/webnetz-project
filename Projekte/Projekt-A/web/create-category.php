<?php
/**
 * Dateiname: create-category.php
 *
 * Beschreibung: Behandelt die Anzeige und Verarbeitung des Formulars zum Erstellen einer neuen Kategorie.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    ArticleManagementSystem
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

/**
 * Die Seitentitel für die Erstellungsseite einer Kategorie.
 */
$pageTitle = "Create - Category";

sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Formularverarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error_message = processCategoryForm($pdo);
}

$categories = getCategories($pdo);

include '../template/create-category.html.php';
