<?php
/**
 * edit-article.php
 *
 * Diese Datei ermöglicht das Bearbeiten von Artikeln und das Hochladen von Bildern/Dateien.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  ArticleManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-22
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Seitentitel festlegen
$pageTitle = "Artikel bearbeiten";

// Sitzungsverwaltung
sessionManager(true);

// Überprüfen, ob Benutzer angemeldet ist, sonst zur Anmeldeseite weitergeleitet
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfe, ob eine Artikel-ID in der URL vorhanden ist, sonst zur Artikelseite weitergeleitet
if (!isset($_GET['id'])) {
    header("Location: /article.php");
    exit();
}

// Artikel-ID aus der URL holen
$articleId = $_GET['id'];

// Überprüfung und Erstellung des Upload-Ordners
$uploadDir = defineUploadDir($articleId);

// Falls das Verzeichnis nicht existiert, es erstellen
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Erstelle den Ordner rekursiv mit Schreibberechtigung
}

// Verarbeiten des Formulars, wenn Daten gesendet wurden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $tags = $_POST['tags'] ?? [];

    $isValid = true;
    $error_message = "";
    $success_message = "";

    // Überprüfe. ob alle Felder ausgefüllt sind
    if (empty($title) || empty($description) || empty($content)) {
        $isValid = false;
        $error_message = "Bitte geben Sie Daten in alle Felder ein.";
    }

    if ($isValid) {
        // Aktualisierung der Artikel-Daten in der Datenbank
        $last_change_date = date('Y-m-d H:i:s');
        $last_editor_id = $_SESSION['user_id'];

        // Verwenden von PDO Prepared Statements
        $updateSql = "UPDATE articles SET title=?, description=?, content=?, last_change_date=?, last_editor_id=? WHERE id=?";
        $updateStatement = $pdo->prepare($updateSql);
        $updateStatement->execute([$title, $description, $content, $last_change_date, $last_editor_id, $articleId]);

        if ($updateStatement->rowCount() > 0) {
            // Lösche bestehende Tags des Artikels
            $deleteTagsSql = "DELETE FROM articles_tags WHERE article_id = ?";
            $deleteTagsStatement = $pdo->prepare($deleteTagsSql);
            $deleteTagsStatement->execute([$articleId]);

            // Füge die neuen Tags hinzu
            foreach ($tags as $tag) {
                $insertTagSql = "INSERT INTO articles_tags (article_id, tag) VALUES (?, ?)";
                $insertTagStatement = $pdo->prepare($insertTagSql);
                $insertTagStatement->execute([$articleId, $tag]);
            }

            // Verarbeiten des hochgeladenen Bilds
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = array('gif', 'jpg', 'jpeg', 'webp', 'png', 'pdf', 'txt');
                $uploadedExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($uploadedExtension, $allowedExtensions)) {
                    // Dateiname für das Originalbild generieren
                    $originalFileName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
                    $originalFileExtension = $uploadedExtension;

                    // Dateiname für das Originalbild generieren
                    $originalImageName = "{$originalFileName}.{$originalFileExtension}";
                    $originalImagePath = "$uploadDir/$originalImageName";

                    // Datei verschieben und hochladen
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $originalImagePath)) {
                        // Rufe die Funktion zur Skalierung und Speicherung der Bilder auf
                        scaleAndSaveImages($originalImagePath, $uploadDir, $originalFileName, $originalFileExtension);

                        // Überarbeite die HTML-Ausgabe für das Artikelbild mit srcset-Attribut
                        $articleImage = generateSrcSet($uploadDir, $originalImageName);

                        // Setze das Artikelbild in verschiedenen Größen
                        $imageSizes = ['192x192', '320x240', '512x512', '640x480', '1024x578', '1024x768', '1024x578']; // Je nach Seitenanforderungen
                        $imageSources = [];

                        foreach ($imageSizes as $size) {
                            $imageSources[] = "$uploadDir/{$originalFileName}_{$size}.{$originalFileExtension} {$size}w";
                        }

                        $imageSrcset = implode(', ', $imageSources);

                        // Füge das Artikelbild in verschiedenen Größen in die HTML-Ausgabe ein
                        echo "<img src='{$articleImage}' srcset='{$imageSrcset}' alt='Beschreibung des Bildes'>";
                    } else {
                        $isValid = false;
                        $error_message = "Fehler beim Hochladen der Datei.";
                    }
                } else {
                    $isValid = false;
                    $error_message = "Ungültige Dateiendung. Erlaubt sind: gif, jpg, jpeg, webp, png, pdf, txt.";
                }
            }
        } else {
            $isValid = false;
            $error_message = "Fehler beim Aktualisieren des Artikels.";
        }
    }

    if ($isValid) {
        header('Location: /edit-article.php?id=' . $articleId);
        exit();
    }
}

if (file_exists($uploadDir)) {
    $filesDir = scandir($uploadDir);

    // Entferne die Einträge für '.' und '..' aus dem Array
    unset($filesDir[0], $filesDir[1]);

    $categorizedFiles = categorizeFiles($filesDir);

    $imageFiles = $categorizedFiles['images'];
    $fileFiles = $categorizedFiles['files'];
}

// Verwenden von PDO Prepared Statements
$articleQuery = "SELECT * FROM articles WHERE id = ?";
$articleStatement = $pdo->prepare($articleQuery);
$articleStatement->execute([$articleId]);
$article = $articleStatement->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: /article.php');
    exit();
}

$userQuery = "SELECT * FROM users";
$userStatement = $pdo->query($userQuery);
$users = $userStatement->fetchAll(PDO::FETCH_ASSOC);

$getTags = getTags($pdo);
$articleTags = getTagsForArticleId($pdo, $articleId);

include '../template/edit-article.html.php';
