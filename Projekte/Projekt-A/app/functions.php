<?php
require_once 'mysql.php';
session_start();

// Definiere die $sessionCaptcha-Variable
$sessionCaptcha = $_SESSION['sessionCaptcha'] ?? '';

// ================================== [SQL-Abfragen] ================================== //

// Funktion zur Ausführung von SQL-Abfragen mit Prepared Statements
function executeStatement($pdo, $sql, $bindValues)
{
    try {
        $stmt = $pdo->prepare($sql);

        if ($stmt === false) {
            die("Vorbereitung der SQL-Anweisung fehlgeschlagen.");
        }

        $stmt->execute($bindValues);

        return $stmt;
    } catch (PDOException $e) {
        die("Fehler beim Ausführen der SQL-Anweisung: " . $e->getMessage());
    }
}

function cleanInput($input): string
{
    // Entfernen von Leerzeichen am Anfang und Ende
    $input = trim($input);

    // Entfernen von Backslashes
    $input = stripslashes($input);

    // HTML- und PHP-Tags entfernen
    $input = htmlspecialchars($input);

    return $input;
}

// ================================== [Register] ================================== //

/**
 * Registriert einen Benutzer in der Datenbank.
 *
 * @param PDO $pdo Die PDO-Datenbankverbindung
 *
 * @return array Ein assoziatives Array mit dem Registrierungsstatus und Fehlermeldung
 */
function registerUser(PDO $pdo): array
{
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Überprüfen, ob E-Mail oder Benutzername bereits existieren
    $stmt = executeStatement($pdo, "SELECT * FROM users WHERE email = ? OR username = ?", [$email, $username]);

    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        if ($existingUser['email'] === $email) {
            return ['status' => false, 'errorMessage' => 'Diese E-Mail-Adresse existiert bereits!'];
        } elseif ($existingUser['username'] === $username) {
            return ['status' => false, 'errorMessage' => 'Dieser Benutzername existiert bereits!'];
        }
    }

    // Füge den neuen Benutzer hinzu
    $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";
    $bindValues = [$first_name, $last_name, $email, $username, $password];

    try {
        executeStatement($pdo, $sql, $bindValues);

        $_SESSION['active'] = '';
        $_SESSION['logged_in'] = true;
        return ['status' => true, 'errorMessage' => ''];
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Ein Fehler ist aufgetreten! Registrierung fehlgeschlagen!';
        return ['status' => false, 'errorMessage' => 'Ein Fehler ist aufgetreten! Registrierung fehlgeschlagen!'];
    }
}

/**
 * Erstellt einen neuen Benutzer in der Datenbank.
 *
 * @param PDO $pdo Die PDO-Datenbankverbindung
 * @param string $firstname Vorname des Benutzers
 * @param string $lastname Nachname des Benutzers
 * @param string $email E-Mail-Adresse des Benutzers
 * @param string $username Benutzername des Benutzers
 * @param string $password Passwort des Benutzers
 * @param int $active Gibt an, ob der Benutzer aktiv ist oder nicht
 * @param int $is_admin Gibt an, ob der Benutzer ein Administrator ist
 *
 * @return array Ein assoziatives Array mit dem Erstellungsstatus
 */
function createNewUser(PDO $pdo, string $firstname, string $lastname, string $email, string $username, string $password, int $active, int $is_admin): array
{
    if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password)) {
        return ['status' => false, 'message' => 'Bitte füllen Sie alle erforderlichen Felder aus!'];
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, username, password, active, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $bindValues = [$firstname, $lastname, $email, $username, $hashed_password, $active, $is_admin];

    try {
        executeStatement($pdo, $sql, $bindValues);
        return ['status' => true, 'message' => 'Benutzer erfolgreich erstellt.'];
    } catch (PDOException $e) {
        return ['status' => false, 'message' => 'Fehler beim Erstellen des Benutzers: ' . $e->getMessage()];
    }
}

// ================================== [Login / Logout] ================================== //

// Funktion, um den Benutzer einzuloggen, unabhängig von der Art der Anmeldung (Email oder Benutzername)
function loginUserWithCredentials($identifier, $password, $pdo, $isEmail = true): array
{
    $column = $isEmail ? 'email' : 'username';
    $stmt = executeStatement($pdo, "SELECT * FROM users WHERE $column = ? LIMIT 1", [$identifier]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        return ['status' => false, 'errorMessage' => 'Ungültige Anmeldeinformationen!'];
    }

    $_SESSION['email'] = $user['email'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['active'] = $user['active'] ?? '';
    $_SESSION['logged_in'] = true;

    return ['status' => true, 'errorMessage' => ''];
}

function isLoggedIn(): bool
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function sessionManager($required_login = false): void
{
    // Überprüfen, ob der Benutzer NICHT angemeldet ist
    if ($required_login && !isLoggedIn()) {
        // Benutzer ist nicht angemeldet, umleiten zur Anmeldeseite
        $_SESSION['message'] = "Sie müssen sich anmelden, bevor Sie fortfahren können!";
        header("Location: /login.php"); // Oder eine andere geeignete Anmeldeseite
        exit();
    }
}

// Haupt-Login-Funktion für E-Mail-Anmeldung
function loginWithEmail($email, $password, $pdo): array
{
    return loginUserWithCredentials($email, $password, $pdo, true);
}

// Haupt-Login-Funktion für Benutzername-Anmeldung
function loginWithUsername($username, $password, $pdo): array
{
    return loginUserWithCredentials($username, $password, $pdo, false);
}

function logout(): void
{
    /* Yeah! Log out process, unsets and destroys session variables */
    session_unset();
    session_destroy();
}

// ================================== [isActive] ================================== //

function isUserAdmin($user_id, $pdo): bool
{
    // SQL-Abfrage, um den is_admin-Wert des Benutzers abzurufen
    $sql = "SELECT is_admin FROM users WHERE id = ?";
    $stmt = executeStatement($pdo, $sql, [$user_id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['is_admin'] == 1) {
        $_SESSION['is_admin'] = true;
        return true;
    }

    return false; // Benutzer nicht gefunden oder kein Administrator
}

// ================================== [Get Pages] ================================== //

function modifyUrlParameter($totalPages): string
{
    // Get the current query string
    $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

    // Parse the query string into an associative array
    parse_str($queryString, $queryParams);

    // Ensure 'page' parameter is not negative and within the valid range
    if (isset($queryParams['page'])) {
        $page = max(1, min($totalPages, (int)$queryParams['page']));
        $queryParams['page'] = $page;
    }

    // Rebuild the query string
    return http_build_query($queryParams);
}

// Funktion zur Überprüfung und Bereinigung der Seitenzahl
function sanitizePageNumber($pageNumber, $totalPages) {
    $pageNumber = intval($pageNumber); // Konvertiere zu einer Ganzzahl (Integer)
    return max(0, min($pageNumber, $totalPages)); // Stelle sicher, dass die Seitenzahl im gültigen Bereich liegt
}

// ================================== [getCategories & getArticles & getTags] ================================== //

/**
 * Diese Funktion ruft alle vorhandenen Kategorien aus der Datenbank ab.
 *
 * @param PDO $pdo Die PDO-Datenbankverbindung.
 * @return array Ein Array von Kategoriedaten.
 */
function getCategories(PDO $pdo): array
{
    $stmt = executeStatement($pdo, "SELECT * FROM categories", []);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArticles($pdo) {
    $stmt = executeStatement($pdo, "SELECT * FROM articles", []);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTags($pdo) {
    $sql = "SELECT tag FROM tags";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getTagsForArticleId($pdo, $articleId) {
    $sql = "SELECT tag FROM articles_tags WHERE article_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$articleId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// ================================== [create-category.php] ================================== //

/**
 * Diese Funktion verarbeitet das Kategorieformular und erstellt eine neue Kategorie in der Datenbank.
 *
 * @param PDO $pdo Die PDO-Datenbankverbindung.
 * @return string|null Gibt eine Fehlermeldung zurück, wenn ein Fehler auftritt, ansonsten null.
 */
function processCategoryForm(PDO $pdo): ?string
{
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $last_change_date = date('Y-m-d H:i:s', time());
    $last_editor_id = $_SESSION['user_id'];

    if (empty($title) || empty($description)) {
        return "Bitte geben Sie Daten in alle Felder ein.";
    } else {
        $newCategoryId = insertCategory($pdo, $title, $description, $last_change_date, $last_editor_id);

        if ($newCategoryId) {
            updateCategoryCreationDate($pdo, $newCategoryId, $last_change_date);

            header('Location: /categories.php?id=' . $newCategoryId);
            exit();
        } else {
            return "Fehler beim Erstellen der Kategorie: " . implode(' ', $pdo->errorInfo());
        }
    }
}

/**
 * Diese Funktion fügt eine neue Kategorie in die Datenbank ein.
 *
 * @param PDO $pdo Die PDO-Datenbankverbindung.
 * @param string $title Der Titel der Kategorie.
 * @param string $description Die Beschreibung der Kategorie.
 * @param string $last_change_date Das Datum der letzten Änderung.
 * @param int $last_editor_id Die ID des letzten Bearbeiters.
 * @return int|false Gibt die ID der neuen Kategorie zurück oder false, wenn ein Fehler auftritt.
 */
function insertCategory(PDO $pdo, string $title, string $description, string $last_change_date, int $last_editor_id): false|int
{
    $sql = "INSERT INTO categories (title, description, last_change_date, last_editor_id) VALUES (?, ?, ?, ?)";
    $bindValues = [$title, $description, $last_change_date, $last_editor_id];

    try {
        executeStatement($pdo, $sql, $bindValues);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Diese Funktion aktualisiert das Erstellungsdatum einer Kategorie in der Datenbank.
 *
 * @param PDO $pdo Die PDO-Datenbankverbindung.
 * @param int $categoryId Die ID der Kategorie, deren Erstellungsdatum aktualisiert werden soll.
 * @param string $creationDate Das neue Erstellungsdatum.
 */
function updateCategoryCreationDate(PDO $pdo, int $categoryId, string $creationDate): void
{
    $sqlUpdate = "UPDATE categories SET date = ? WHERE id = ?";
    $bindValues = [$creationDate, $categoryId];

    try {
        executeStatement($pdo, $sqlUpdate, $bindValues);
    } catch (PDOException $e) {
        // Handle error if necessary
    }
}

// ================================== [Mark search terms in a text] ================================== //
function highlightSearchTerm($text) {
    if (!empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<span class="highlight">$1</span>', $text);
    }
    return $text;
}

// ================================== [Display für Artikel] ================================== //

function displayCard($id, $title, $date, $description): void
{
    $highlightedTitle = highlightSearchTerm($title);
    $highlightedDescription = highlightSearchTerm($description);

    echo <<<HTML
    <article class="card">
        <h3><a href="article.php?id=$id">{$highlightedTitle}</a></h3>
        <p><i>($date)</i></p>
        <p>{$highlightedDescription}</p>
    </article>
    <br>
HTML;
}

// ================================== [Bilder und Dateien unterscheiden] ================================== //

function categorizeFiles($filesDir): array
{
    $imageExtensions = array('gif', 'jpg', 'jpeg', 'png');
    $fileExtensions = array('pdf', 'txt');

    $imageFiles = array();
    $fileFiles = array();

    foreach ($filesDir as $file) {
        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $imageExtensions)) {
            $imageFiles[] = $file;
        } elseif (in_array($fileExtension, $fileExtensions)) {
            $fileFiles[] = $file;
        }
    }

    return array('images' => $imageFiles, 'files' => $fileFiles);
}

function defineUploadDir($articleId): string
{
    return "uploads/" . $articleId;
}

function displayImagesForArticle($articleId): string
{
    $articleImagesDir = defineUploadDir($articleId);
    $imageFiles = scandir($articleImagesDir);

    $categorizedFiles = categorizeFiles($imageFiles);

    $imageFiles = $categorizedFiles['images'];

    if (!empty($imageFiles)) {
        // Das erste Bild des Artikels
        $firstImage = $imageFiles[0];
        $originalFileName = pathinfo($firstImage, PATHINFO_FILENAME);
        $originalFileExtension = pathinfo($firstImage, PATHINFO_EXTENSION);

        // Prüfe alle möglichen Größen
        $sizes = [
            '320x240',
            '640x480',
            '1024x768',
            '192x192',
            '512x512',
            '1024x1024',
            '1920x1080'
        ];

        foreach ($sizes as $size) {
            $potentialImage = "{$originalFileName}_{$size}.{$originalFileExtension}";
            $potentialImagePath = "$articleImagesDir/$potentialImage";

            if (file_exists($potentialImagePath)) {
                $imagePath = $potentialImagePath;
                break; // Das erste gefundene Bild in der richtigen Größe wird verwendet
            }
        }

        // Überprüfe, ob das Bild die gewünschte Größe von 320x240 Pixeln hat
        if (isset($imagePath)) {
            // Erstelle einen anklickbaren Link um das Bild und gib den HTML-Code zurück
            return "<a href='$imagePath' target='_blank'><img src='$imagePath' alt='Bild für Artikel $articleId' width='320' height='240'></a>";
        } else {
            return "Kein Bild in der gewünschten Größe (320x240 Pixel) gefunden für Artikel $articleId.";
        }
    } else {
        return "Keine Bilder gefunden für Artikel $articleId";
    }
}

function scaleAndSaveImages($originalFilePath, $uploadDir, $originalFileName, $originalFileExtension): void
{
    $sizes = [
        'original' => ['width' => null, 'height' => null, 'aspectRatio' => 'Unbekannt'],
        '320x240' => ['width' => 320, 'height' => 240, 'aspectRatio' => '4:3'],
        '640x480' => ['width' => 640, 'height' => 480, 'aspectRatio' => '4:3'],
        '1024x768' => ['width' => 1024, 'height' => 768, 'aspectRatio' => '4:3'],
        '192x192' => ['width' => 192, 'height' => 192, 'aspectRatio' => '1:1'],
        '512x512' => ['width' => 512, 'height' => 512, 'aspectRatio' => '1:1'],
        '1024x578' => ['width' => 1024, 'height' => 578, 'aspectRatio' => '16:9'],
        '1920x1080' => ['width' => 1920, 'height' => 1080, 'aspectRatio' => '16:9'],
    ];

    if (in_array($originalFileExtension, ['jpeg', 'jpg', 'png', 'gif'])) {
        // Load the original image
        $originalImage = null;
        switch ($originalFileExtension) {
            case 'jpeg':
            case 'jpg':
                $originalImage = imagecreatefromjpeg($originalFilePath);
                break;
            case 'png':
                $originalImage = imagecreatefrompng($originalFilePath);
                break;
            case 'gif':
                $originalImage = imagecreatefromgif($originalFilePath);
                break;
        }

        $srcWidth = imagesx($originalImage);
        $srcHeight = imagesy($originalImage);

        foreach ($sizes as $size => $dimensions) {
            $width = $dimensions['width'];
            $height = $dimensions['height'];

            if ($width !== null && $height !== null) {
                // Create a blank canvas for the scaled image
                $outputImage = imagecreatetruecolor($width, $height);

                // Preserve transparency for PNG and GIF images
                if ($originalFileExtension === 'png' || $originalFileExtension === 'gif') {
                    imagealphablending($outputImage, false);
                    imagesavealpha($outputImage, true);
                    $transparent = imagecolorallocatealpha($outputImage, 255, 255, 255, 127);
                    imagefilledrectangle($outputImage, 0, 0, $width, $height, $transparent);
                }

                // Resize the image to fit within the specified dimensions without cropping
                $srcAspectRatio = $srcWidth / $srcHeight;
                $dstAspectRatio = $width / $height;

                if ($srcAspectRatio > $dstAspectRatio) {
                    // Source image is wider, scale by height
                    $newWidth = $srcHeight * $dstAspectRatio;
                    $newHeight = $srcHeight;
                } else {
                    // Source image is taller or has the same aspect ratio, scale by width
                    $newWidth = $srcWidth;
                    $newHeight = $srcWidth / $dstAspectRatio;
                }

                $xOffset = ($srcWidth - $newWidth) / 2;
                $yOffset = ($srcHeight - $newHeight) / 2;

                imagecopyresampled($outputImage, $originalImage, 0, 0, $xOffset, $yOffset, $width, $height, $newWidth, $newHeight);

                // Save the scaled image
                $outputFileName = "{$originalFileName}_{$size}.{$originalFileExtension}";
                $outputFile = "$uploadDir/$outputFileName";

                if ($originalFileExtension === 'jpeg' || $originalFileExtension === 'jpg') {
                    imagejpeg($outputImage, $outputFile, 90); // 90 is the JPEG quality
                } elseif ($originalFileExtension === 'png') {
                    imagepng($outputImage, $outputFile);
                } elseif ($originalFileExtension === 'gif') {
                    imagegif($outputImage, $outputFile);
                }

                // Clean up
                imagedestroy($outputImage);
            }
        }

        // Clean up the original image
        imagedestroy($originalImage);
    } else {
        // For non-image files, simply move them to the upload directory
        $outputFile = "$uploadDir/$originalFileName.$originalFileExtension";
        move_uploaded_file($originalFilePath, $outputFile);
    }
}

function generateSrcsetForArticleImages($articleId): string
{
    $articleImagesDir = defineUploadDir($articleId);
    $imageFiles = scandir($articleImagesDir);

    $categorizedFiles = categorizeFiles($imageFiles);

    $imageFiles = $categorizedFiles['images'];

    if (!empty($imageFiles)) {
        $srcset = '';
        $first = true;
        $originalFileName = pathinfo($imageFiles[0], PATHINFO_FILENAME);
        $originalFileExtension = pathinfo($imageFiles[0], PATHINFO_EXTENSION);

        foreach ($imageFiles as $imageFile) {
            $fileInfo = pathinfo($imageFile);
            $size = $fileInfo['filename'];

            if ($size !== $originalFileName) {
                $size = explode('_', $size);
                $size = $size[1];
            } else {
                $size = 'original';
            }

            if (!$first) {
                $srcset .= ', ';
            }

            $srcset .= "/uploads/$articleId/{$originalFileName}_$size.$originalFileExtension {$size}w";

            $first = false;
        }

        return $srcset;
    } else {
        return '';
    }
}

function displayImageGallery($articleId): void
{
    $articleImagesDir = defineUploadDir($articleId);
    $imageFiles = scandir($articleImagesDir);

    $categorizedFiles = categorizeFiles($imageFiles);

    $imageFiles = $categorizedFiles['images'];

    if (!empty($imageFiles)) {
        echo '<div class="image-gallery">';

        foreach ($imageFiles as $imageFile) {
            $fileInfo = pathinfo($imageFile);
            $size = $fileInfo['filename'];

            if ($size !== $fileInfo['filename']) {
                $size = explode('_', $size);
                $size = $size[1];
            } else {
                $size = 'original';
            }

            $originalFileName = pathinfo($imageFiles[0], PATHINFO_FILENAME);
            $originalFileExtension = pathinfo($imageFiles[0], PATHINFO_EXTENSION);

            echo '<div class="gallery-item">';
            echo '<img src="/uploads/' . $articleId . '/' . $originalFileName . '_' . $size . '.' . $originalFileExtension . '" alt="Bild ' . $size . ' für Artikel ' . $articleId . '">';
            echo '</div>';
        }

        echo '</div>';
    }
}

function uploadFilesForArticle($articleId): ?string
{
    $uploadDir = defineUploadDir($articleId);
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.'
    );

    if (!empty($_FILES['files']['name'][0])) {
        $totalFiles = count($_FILES['files']['name']);
        $uploadedFiles = 0;

        for ($i = 0; $i < $totalFiles; $i++) {
            $originalFileName = $_FILES['files']['name'][$i];
            $originalFileTmpName = $_FILES['files']['tmp_name'][$i];
            $originalFileSize = $_FILES['files']['size'][$i];
            $originalFileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            $originalFileError = $_FILES['files']['error'][$i];

            if ($originalFileError !== 0) {
                return "Fehler beim Hochladen von Datei $originalFileName: " . $fileErrors[$originalFileError];
            }

            $newFileName = "{$originalFileName}";
            $targetFilePath = "$uploadDir/$newFileName";

            if (!move_uploaded_file($originalFileTmpName, $targetFilePath)) {
                return "Fehler beim Speichern von Datei $originalFileName";
            }

            // Skaliere und speichere Bilder in verschiedenen Größen
            if (in_array($originalFileExtension, ['jpeg', 'jpg', 'png', 'gif'])) {
                scaleAndSaveImages($targetFilePath, $uploadDir, pathinfo($newFileName, PATHINFO_FILENAME), $originalFileExtension);
            }

            $uploadedFiles++;
        }

        return "Erfolgreich $uploadedFiles Dateien hochgeladen";
    } else {
        return null;
    }
}

function generateSrcSet($uploadDir, $imageFile): string
{
    $sizes = [
        '320x240',
        '640x480',
        '1024x768',
        '192x192',
        '512x512',
        '1024x578',
        '1920x1080'
    ];

    $srcSet = [];

    foreach ($sizes as $size) {
        $imageUrl = "$uploadDir/{$size}_$imageFile";
        $srcSet[] = "$imageUrl {$size}w";
    }

    return implode(', ', $srcSet);
}

// Rekursive Funktion zum Löschen eines Verzeichnisses und seiner Inhalte
function rrmdir($dir): void
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    rrmdir($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}

// ================================== [Gravatar] ================================== //

/**
 * Generiert eine Gravatar-URL oder ein komplettes Bild-Tag für eine angegebene E-Mail-Adresse.
 *
 * @param string $email Die E-Mail-Adresse
 * @param int|string $s Größe in Pixeln (1 - 2048)
 * @param string $d Standardbildset [404 | mp | identicon | monsterid | wavatar]
 * @param string $r Maximale Bewertung (inklusive) [g | pg | r | x]
 * @param bool $img True, um ein komplettes IMG-Tag zurückzugeben, False nur für die URL
 * @param array $atts Optionale zusätzliche Attribute für das IMG-Tag
 *
 * @return string Die Gravatar-URL oder das komplette Bild-Tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function get_gravatar(string $email, int|string $s = 80, string $d = 'mp', string $r = 'g', bool $img = false, array $atts = array() ): string
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

// ================================== [Additional Functions] ================================== //

// Erzeuge ein zufälliges Captcha
function generateCaptcha(): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 6;
    $captcha = '';

    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $captcha;
}
