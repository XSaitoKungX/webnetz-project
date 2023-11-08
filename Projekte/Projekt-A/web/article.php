<?php
/**
 * Dateiname: article.php
 *
 * Beschreibung: Behandelt die Anzeige und Verarbeitung von Artikeln.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    Article
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';
require_once 'classes/SocialSharing.php';

$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Abfrage, um den Artikel aus der Datenbank abzurufen
$query = "SELECT articles.*, GROUP_CONCAT(tags.tag SEPARATOR ' ') AS tags
          FROM articles
          LEFT JOIN articles_tags ON articles.id = articles_tags.article_id
          LEFT JOIN tags ON articles_tags.tag = tags.tag
          WHERE articles.id = :articleId
          GROUP BY articles.id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if ($article) {
    // Hier Editor-Namen abrufen und hinzufügen
    $editorName = '';

    $editorSql = "SELECT username FROM users WHERE id = :editorId";
    $editorStatement = $pdo->prepare($editorSql);
    $editorStatement->bindParam(':editorId', $article['last_editor_id'], PDO::PARAM_INT);
    $editorStatement->execute();
    $editorRow = $editorStatement->fetch(PDO::FETCH_ASSOC);

    if ($editorRow) {
        $editorName = htmlspecialchars($editorRow['username']);
    }

    // Setze den Editor-Namen im Artikel-Array
    $article['username'] = $editorName;

    $pageTitle = $article['title'];
    $metaDescription = $article['content'];

    // Erstelle ein SocialSharing-Objekt mit dem Artikel als Parameter
    $social = new SocialSharing($article);

    $shareLinks = $social->getShareLinks('Schau dir diesen tollen Artikel an:');

    if (isset($_GET['mime'])) {
        $mime = $_GET['mime'];
        $validMimeTypes = ['txt', 'json', 'xml'];

        if (in_array($mime, $validMimeTypes)) {
            header('Content-Type: ' . $mimeTypes[$mime]);
            include "../template/mimeTypes/article.$mime.php";
            exit();
        }
    } else {
        $uploadDir = defineUploadDir($articleId);

        if (is_dir($uploadDir)) {
            $filesDir = scandir($uploadDir);
            $files = array_diff($filesDir, ['.', '..']);
            $fileCategorized = categorizeFiles($files);
            $imageFiles = $fileCategorized['images'];
            $fileFiles = $fileCategorized['files'];
        } else {
            $imageFiles = [];
            $fileFiles = [];
        }

        // Kommentarfunktion integrieren
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $comment_text = $_POST["message"];

            if (!empty($name) && !empty($email) && !empty($comment_text) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Füge den Kommentar in die Datenbank ein
                $insertQuery = "INSERT INTO comments (article_id, name, email, comment_text) VALUES (:articleId, :name, :email, :comment_text)";
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
                $insertStmt->bindParam(':name', $name, PDO::PARAM_STR);
                $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
                $insertStmt->bindParam(':comment_text', $comment_text, PDO::PARAM_STR);
                $insertStmt->execute();

                // Erfolgsmeldung
                $success_message = "Ihr Kommentar wurde erfolgreich übermittelt. Ein Admin wird sich darum kümmern, ob Ihr Kommentar zur Veröffentlichung freigegeben wird oder nicht.";
            } else {
                // Fehlermeldung
                $error = true;
                $error_message = "Ungültige Eingaben. Bitte füllen Sie alle Felder korrekt aus.";
            }
        }

        // Kommentare abrufen
        $commentsQuery = "SELECT * FROM comments WHERE article_id = :articleId AND is_public = 1 ORDER BY comment_date DESC";
        $commentsStmt = $pdo->prepare($commentsQuery);
        $commentsStmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $commentsStmt->execute();
        $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

        require('captcha.php');
        include '../template/article.html.php';
    }
} else {
    header("Location: /error/404.php");
    exit();
}
