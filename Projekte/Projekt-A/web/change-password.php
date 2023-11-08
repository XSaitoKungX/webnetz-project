<?php
/**
 * change-password.php
 *
 * Diese Datei ermöglicht Benutzern das Ändern ihres Passworts.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  UserManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-19
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php'; // Use the PDO connection
require_once '../app/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        $last_change_date = date('Y-m-d H:i:s', time());
        $last_editor_id = $_SESSION['is_admin'] ? $userId : $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $repeatPassword = $_POST['repeat_password'];

        // Überprüfen, ob das neue Passwort übereinstimmt
        if ($newPassword !== $repeatPassword) {
            $errorMessage = "Die eingegebenen Passwörter stimmen nicht überein.";
        } else {
            // Das aktuelle Passwort überprüfen
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($currentPassword, $row['password'])) {
                // Das aktuelle Passwort ist korrekt, neues Passwort speichern
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("UPDATE users SET password = :password, last_change_date = :last_change_date, last_editor_id = :last_editor_id WHERE id = :id");
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':last_change_date', $last_change_date, PDO::PARAM_STR);
                $stmt->bindParam(':last_editor_id', $last_editor_id, PDO::PARAM_INT);
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    header("Location: profile.php");
                    exit();
                } else {
                    $errorMessage = "Fehler beim Ändern des Passworts.";
                }
            } else {
                $errorMessage = "Das eingegebene aktuelle Passwort ist falsch.";
            }
        }
    } else {
        $errorMessage = "Ungültige Benutzer-ID.";
    }
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Das aktuelle Passwort überprüfen
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $username = $row['username'];
        $pageTitle = "Passwort ändern für $username";
    }
}

include('../template/change-password.html.php');
