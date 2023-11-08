<?php
/**
 * register.php
 *
 * Diese Datei ermöglicht die Benutzerregistrierung.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  Registrierung
 * @author   Nattawat Pongsuwan
 * @last-modified 2023-09-18
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Daten aus dem Formular übernehmen
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Aufruf der Funktion registerUser
    if (isset($_POST['register'])) {
        /**
         * Das Ergebnis der Benutzerregistrierung.
         *
         * @var array $registrationStatus Ein Array, das den Registrierungsstatus und
         *                               gegebenenfalls Fehlermeldungen enthält.
         */
        $registrationStatus = registerUser($pdo);

        // Überprüfen, ob die Registrierung erfolgreich war
        if ($registrationStatus['status']) {
            $_SESSION['user'] = $username; // Setze den Benutzernamen in die Session
            header("Location: /login.php");
            exit();
        } else {
            /**
             * Die Fehlermeldung, die bei fehlgeschlagener Registrierung angezeigt wird.
             *
             * @var string $errorMessage Die Fehlermeldung, die bei fehlgeschlagener Registrierung angezeigt wird.
             */
            $errorMessage = $registrationStatus['errorMessage'];
        }
    }
}
