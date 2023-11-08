<?php
/**
 * Dateiname: create-user.php
 *
 * Diese Datei ermöglicht das Löschen eines Artikels.
 *
 * Benutzer müssen angemeldet sein und die Berechtigung haben, Artikel zu löschen.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    ArticleManagementSystem
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

// Erforderliche Konfigurationen und Funktionen einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Seitentitel festlegen
$pageTitle = "Artikel löschen";

// Überprüfen, ob der Benutzer angemeldet ist
sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob eine Artikel-ID übergeben wurde
if (!isset($_GET['id'])) {
    header("Location: /article.php");
    exit();
}

// Artikel-ID aus der URL abrufen
$articleId = $_GET['id'];

// Überprüfen, ob das Löschen-Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_article'])) {
    // Zuerst die Tags des Artikels löschen
    $deleteTagsSql = "DELETE FROM articles_tags WHERE article_id = :articleId";
    $deleteTagsStmt = $pdo->prepare($deleteTagsSql);
    $deleteTagsStmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
    $deleteTagsStmt->execute();

    // Dann den Artikel löschen
    $deleteArticleSql = "DELETE FROM articles WHERE id = :articleId";
    $deleteArticleStmt = $pdo->prepare($deleteArticleSql);
    $deleteArticleStmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);

    if ($deleteArticleStmt->execute()) {
        // Artikel erfolgreich gelöscht
        $successMessage = "Artikel erfolgreich gelöscht!";

        // Verzeichnis des gelöschten Artikels löschen
        $uploadDir = "../uploads/$articleId";
        if (is_dir($uploadDir)) {
            // Das Verzeichnis rekursiv löschen
            rrmdir($uploadDir);
        }
    } else {
        // Fehler beim Löschen des Artikels
        $errorMessage = "Fehler beim Löschen des Artikels: " . $deleteArticleStmt->errorInfo()[2];
    }
}

// Benutzerinformation abrufen
$sql = "SELECT * FROM users WHERE id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Benutzer nicht gefunden
    $_SESSION['message'] = "Benutzer nicht gefunden!";
    header("Location: ../error/404.html.php");
    exit();
}

include '../template/delete-article.html.php';
