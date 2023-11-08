<?php
/**
 * login.php
 *
 * Diese Datei ermöglicht Benutzern das Einloggen und die Registrierung.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  Authentifizierung
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once('../app/mysql.php');  // Use the PDO connection
require_once('../app/functions.php');
require_once '../config/config.php';

/**
 * Der Titel der Seite.
 */
$pageTitle = "Signup - Login";

/**
 * Fehlermeldung, die angezeigt wird, wenn die Anmeldung fehlschlägt.
 */
$errorMessage = "";

// Überprüfen, ob ein POST-Request gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Benutzereingaben aus dem Formular
        $loginInput = $_POST['login_input'];
        $password = $_POST['password'];

        // Überprüfen, ob die Eingabe eine E-Mail oder ein Benutzername ist
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            // Anmeldung mit E-Mail
            $loginStatus = loginWithEmail($loginInput, $password, $pdo);
        } else {
            // Anmeldung mit Benutzername
            $loginStatus = loginWithUsername($loginInput, $password, $pdo);
        }

        // Überprüfen, ob die Anmeldung erfolgreich war
        if (!$loginStatus['status']) {
            $errorMessage = $loginStatus['errorMessage'];
        } else {
            // Anmeldung erfolgreich: Benutzer in der Session speichern
            $_SESSION['user'] = $loginStatus['user'];

            // Überprüfen, ob der Benutzer ein Administrator ist
            isUserAdmin($_SESSION['user']['id'], $pdo);

            // Weiterleitung zur Erfolgsseite
            header("Location: /profile.php?id=" . $_SESSION['user_id']);
            exit();
        }
    } elseif (isset($_POST['register'])) {
        // Weiterleitung zur Registrierungsseite
        require_once 'register.php';
    }
}

// Die Login-Seite rendern
include '../template/login.html.php';
