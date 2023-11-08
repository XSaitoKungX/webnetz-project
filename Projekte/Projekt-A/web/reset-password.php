<?php
/**
 * reset-password.php
 *
 * Diese Datei ermöglicht Benutzern das Zurücksetzen ihres Passworts.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  UserManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

if (isset($_GET['email']) && isset($_GET['hash'])) {
    $email = $_GET['email'];
    $hash = $_GET['hash'];

    // Überprüfen, ob die E-Mail-Adresse und der Hash in der Datenbank existieren
    $sql = "SELECT id, username FROM users WHERE email = :email AND password_reset = :hash";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Benutzer existiert, Formular zum Passwort-Reset anzeigen
        $pageTitle = "Passwort zurücksetzen für " . $user['username'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
            // Passwort-Reset-Formular wurde abgesendet
            $newPassword = $_POST['password'];
            $repeatPassword = $_POST['repeat_password'];

            if ($newPassword === $repeatPassword) {
                // Passwort-Hash erstellen und in die Datenbank speichern
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sqlUpdate = "UPDATE users SET password = :password WHERE id = :id";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $stmtUpdate->execute();

                // Passwort-Reset-Flag löschen
                $sqlReset = "UPDATE users SET password_reset = NULL WHERE id = :id";
                $stmtReset = $pdo->prepare($sqlReset);
                $stmtReset->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $stmtReset->execute();

                // Weiterleitung zur Login-Seite
                header("Location: login.php");
                exit();
            } else {
                // Passwörter stimmen nicht überein
                $errorMessage = "Die eingegebenen Passwörter stimmen nicht überein.";
            }
        }

        include '../template/reset-password.html.php';
    } else {
        // Redirect zur Login-Seite, wenn keine Übereinstimmung gefunden wurde
        header("Location: login.php");
        exit();
    }
} else {
    // Redirect zur Login-Seite, wenn keine gültigen Parameter übergeben wurden
    header("Location: login.php");
    exit();
}
