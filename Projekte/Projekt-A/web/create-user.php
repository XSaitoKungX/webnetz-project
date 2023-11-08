<?php
/**
 * Dateiname: create-user.php
 *
 * Beschreibung: Behandelt die Anzeige und Verarbeitung des Formulars zur Erstellung eines neuen Benutzers.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    UserManagementSystem
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-19
 */

require_once '../app/functions.php';
require_once '../app/mysql.php';
require_once '../app/repository.php';
require_once '../config/config.php';

/**
 * Die Seitentitel für die Benutzererstellung.
 */
$pageTitle = "Benutzer Anlegen";

sessionManager(true);

// Prüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Prüfen, ob der angemeldete Benutzer ein Admin ist
if (!$_SESSION['is_admin']) {
    header("Location: /error/404.php"); // Weiterleiten bei eingeschränktem Zugriff
    exit();
}

// Erstelle ein Repository für Benutzer
$userRepository = new RepositoryUsers($pdo);

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userDetails = [
        'first_name' => cleanInput($_POST['firstname']),
        'last_name' => cleanInput($_POST['lastname']),
        'email' => cleanInput($_POST['email']),
        'username' => cleanInput($_POST['username']),
        'password' => cleanInput($_POST['password']),
        'active' => cleanInput($_POST['active']),
        'is_admin' => cleanInput($_POST['is_admin'])
    ];

    if (isset($_POST['createUser'])) {
        $createUserStatus = $userRepository->create($userDetails);

        if ($createUserStatus['status']) {
            $_SESSION['user'] = $userDetails['username'];
            header("Location: /edit-users.php");
            exit();
        }
    }
}

include "../template/create-user.html.php";
