<?php
/**
 * Kommentar löschen - Seite
 *
 * Diese Seite ermöglicht autorisierten Administratoren die Verwaltung von Kommentaren. Sie überprüft die Benutzerauthentifizierung,
 * Admin-Berechtigungen und kümmert sich um das Löschen von Kommentaren.
 */

require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

$pageTitle = "Kommentare verwalten";

sessionManager(true);

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Überprüfen, ob der angemeldete Benutzer Administrator ist
$sqlAdminCheck = "SELECT is_admin FROM users WHERE id = ?";
$stmtAdminCheck = $pdo->prepare($sqlAdminCheck);
$stmtAdminCheck->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtAdminCheck->execute();
$resultAdminCheck = $stmtAdminCheck->fetch(PDO::FETCH_ASSOC);

if ($resultAdminCheck === false || !$resultAdminCheck['is_admin']) {
    header("Location: /profile.php?id=" . $_SESSION['user_id']);
    exit();
}

// Überprüfe, ob die Kommentar-ID im Query-String vorhanden ist
if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);

    // Führe eine SQL-Abfrage aus, um den Kommentar zu löschen (Verwendung von PDO)
    $sql = "DELETE FROM comments WHERE id = :comment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Nach dem Löschen des Kommentars kannst du den Benutzer auf die Kommentarseite zurückleiten
        header('Location: edit-comments.php');
        exit;
    }
}

// Lade alle Kommentare aus der Datenbank, die noch nicht freigegeben sind
$sql = "SELECT * FROM comments WHERE is_public = 0";
$unapprovedComments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
