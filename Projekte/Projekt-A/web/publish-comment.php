<?php
/**
 * publish-comment.php
 *
 * Diese Datei ermöglicht die Freigabe von Kommentaren durch einen Administrator.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  Kommentarverwaltung
 * @author   Nattawat Pongsuwan
 * @last-modified 2023-09-18
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once '../config/config.php';
require_once '../app/mysql.php';  // Use the PDO connection
require_once '../app/functions.php';

/**
 * Der Titel der Seite.
 *
 * @var string $pageTitle Der Titel der Kommentarverwaltungsseite.
 */
$pageTitle = "Kommentare verwalten";

// Überprüfen, ob der Benutzer angemeldet ist
sessionManager(true);

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob der angemeldete Benutzer Administrator ist
$sqlAdminCheck = "SELECT is_admin FROM users WHERE id = ?";
$stmtAdminCheck = $pdo->prepare($sqlAdminCheck);
$stmtAdminCheck->execute([$_SESSION['user_id']]);
$isAdmin = $stmtAdminCheck->fetchColumn();

// Wenn der Benutzer kein Administrator ist, wird er auf sein Profil umgeleitet
if (!$isAdmin) {
    header("Location: /profile.php?id=" . $_SESSION['user_id']);
    exit();
}

if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);

    // Aktualisiere den Kommentar in der Datenbank und setze is_public auf 1 (freigegeben)
    $sqlCheck = "SELECT is_public, name FROM comments WHERE id = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$comment_id]);
    $commentData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($commentData) {
        $isPublic = $commentData['is_public'];
        $author_id = $commentData['name'];

        // Toggle is_public Wert (0 auf 1 und umgekehrt)
        $newIsPublic = $isPublic == 0 ? 1 : 0;

        // Aktualisiere den Kommentar in der Datenbank
        $sqlUpdate = "UPDATE comments SET is_public = ? WHERE id = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$newIsPublic, $comment_id]);

        // Holen Sie den Namen des Autors des freigegebenen Kommentars
        $sqlAuthorName = "SELECT username FROM users WHERE id = ?";
        $stmtAuthorName = $pdo->prepare($sqlAuthorName);
        $stmtAuthorName->execute([$author_id]);
        $authorName = $stmtAuthorName->fetchColumn();

        if ($authorName) {
            // Aktualisiere den $pageTitle-String mit dem dynamisch eingefügten Autorennamen
            $pageTitle = "Kommentar von '$authorName' freigegeben";
        }
    }

    // Nach dem Aktualisieren des Kommentars kannst du den Benutzer auf die Kommentarseite zurückleiten
    header('Location: edit-comments.php');
    exit;
}

// Lade alle Kommentare aus der Datenbank, die noch nicht freigegeben sind
$sql = "SELECT * FROM comments WHERE is_public = 0";
$stmt = $pdo->query($sql);
$unapprovedComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
