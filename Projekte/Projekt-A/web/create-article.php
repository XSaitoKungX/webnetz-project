<?php
/**
 * Dateiname: create-article.php
 *
 * Beschreibung: Behandelt die Anzeige und Verarbeitung des Formulars zum Erstellen eines neuen Artikels.
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

$pageTitle = "Neuer Artikel erstellen";

sessionManager(true);

// Weiterleitung, wenn der Benutzer nicht angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Formularverarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und mit der Datenbank überprüfen
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $date = date('Y-m-d');
    $last_change_date = date('Y-m-d H:i:s', time());
    $last_editor_id = $_SESSION['user_id'];
    $category_id = intval($_POST['category_id']);
    $tags = $_POST['tags'] ?? [];

    $isValid = true;
    $error_message = "";

    if (empty($title) || empty($description) || empty($content)) {
        $isValid = false;
        $error_message = "Bitte geben Sie Daten in alle Felder ein.";
    }

    if ($isValid) {
        try {
            $pdo->beginTransaction();

            // SQL-Abfrage ausführen, um den Artikel in der Datenbank zu erstellen
            $sql = "INSERT INTO articles (title, description, content, date, last_change_date, last_editor_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $statement = $pdo->prepare($sql);
            $statement->execute([$title, $description, $content, $date, $last_change_date, $last_editor_id, $category_id]);

            $newArticleId = $pdo->lastInsertId();

            // Ordner für das Bild erstellen
            $uploadDir = "../uploads/$newArticleId";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Erstelle den Ordner rekursiv mit Schreibbeschreibung
            }

            // Überprüfe, ob Dateien hochgeladen wurden
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = array('gif', 'jpg', 'jpeg', 'png', 'pdf', 'txt');
                $uploadedExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($uploadedExtension, $allowedExtensions)) {
                    $originalFileName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
                    $originalFileExtension = $uploadedExtension;

                    // Dateiname für das Originalbild generieren
                    $originalImageName = "{$originalFileName}.{$originalFileExtension}";
                    $originalImagePath = "$uploadDir/$originalImageName";

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
                        $pdo->rollBack();
                        $isValid = false;
                        $error_message = "Fehler beim Hochladen des Bilds.";
                    }
                } else {
                    $pdo->rollBack();
                    $isValid = false;
                    $error_message = "Ungültige Dateiendung. Erlaubt sind: gif, jpg, jpeg, png, pdf, txt.";
                }
            }

            // Tags in die articles_tags-Tabelle einfügen
            foreach ($tags as $tag) {
                $sql = "INSERT INTO articles_tags (article_id, tag) VALUES (?, ?)";
                $statement = $pdo->prepare($sql);
                $statement->execute([$newArticleId, $tag]);
            }

            if ($isValid) {
                $pdo->commit();
                // Wenn die Datenbank- und Dateiüberprüfungen erfolgreich waren, weiterleiten
                header('Location: /article.php?id=' . $newArticleId);
                exit();
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $isValid = false;
            $error_message = "Fehler beim Erstellen des Artikels: " . $e->getMessage();
        }
    }
}

$categories = getCategories($pdo);
$articles = getArticles($pdo);
$getTags = getTags($pdo);

include '../template/create-article.html.php';
