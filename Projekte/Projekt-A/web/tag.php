<?php
/**
 * tag.php
 *
 * Diese Datei zeigt Artikel für einen bestimmten Tag an.
 *
 * PHP-Version: 7.4
 *
 * @category Backend
 * @package  TagManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

// Überprüfen, ob ein Tag in der URL vorhanden ist
if (!isset($_GET['tag'])) {
    header("Location: /tags.php");
    exit();
}

// Tag aus der URL abrufen und vor SQL-Injection schützen
$tag = htmlspecialchars($_GET['tag']);

// Artikel für den bestimmten Tag abrufen
$sql = "SELECT articles.* FROM articles
        JOIN articles_tags ON articles.id = articles_tags.article_id
        JOIN tags ON articles_tags.tag = tags.tag
        WHERE tags.tag = ?";
$stmtArticles = $pdo->prepare($sql);
$stmtArticles->execute([$tag]);
$taggedArticles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Artikel für Tag: $tag";

include '../template/tag.html.php';
