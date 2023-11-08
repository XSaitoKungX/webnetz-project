<?php
/**
 * profile.php
 *
 * Diese Datei ermöglicht die Profilverwaltung eines Benutzers.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  Profilverwaltung
 * @author   Nattawat Pongsuwan
 * @last-modified 2023-09-18
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once '../config/config.php';
require_once '../app/functions.php';
require_once '../app/mysql.php';

// Überprüfen, ob der Benutzer angemeldet ist
sessionManager(true);

/**
 * Die Benutzer-ID, deren Profil angezeigt oder bearbeitet wird.
 *
 * @var int $user_id Die ID des Benutzers, dessen Profil angezeigt oder bearbeitet wird.
 */
$user_id = $_GET['id'] ?? $_SESSION['user_id'];

// Überprüfen, ob der aktuelle Benutzer entweder das eigene Profil bearbeitet oder ein Administrator ist
if ($user_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
    $_SESSION['message'] = "Zugriff auf fremdes Profil verweigert!";
    header("location: /error/404.php");
    exit();
}

// Benutzerdaten aus der Datenbank abrufen
$sql = "SELECT * FROM users WHERE id = :user_id";
$statement = $pdo->prepare($sql);
$statement->execute(['user_id' => $user_id]);

/**
 * Ein Array, das die Benutzerdaten enthält.
 *
 * @var array $userData Ein Array, das die Benutzerdaten enthält.
 */
$row = $statement->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $full_name = $first_name . ' ' . $last_name;
    $email = $row['email'];
    $password = $row['password'];
    $username = $row['username'];
    $active = $row['active'];
    $last_change_date = $row['last_change_date'];
    $logo_name = $row['logo'];

    // Den Namen des aktuellen Profils in die Variable $pageTitle einfügen
    $pageTitle = "Profil von " . $full_name;
} else {
    // Benutzer nicht gefunden
    $_SESSION['message'] = "Benutzer nicht gefunden.";
    header("location: /error/404.php");
    exit();
}

// Die Profilseite rendern
include "../template/profile.html.php";
