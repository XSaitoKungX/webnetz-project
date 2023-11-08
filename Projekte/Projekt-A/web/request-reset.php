<?php
/**
 * request-reset.php
 *
 * Diese Datei ermöglicht Benutzern, einen Link zum Zurücksetzen ihres Passworts anzufordern.
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

$pageTitle = "Anfrage zum Passwortzurücksetzen";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Benutzer hat das Formular abgeschickt, E-Mail-Adresse überprüfen und Passwort-Reset-Link anzeigen
    $email = $_POST['email'];

    // Überprüfen, ob die E-Mail-Adresse in der Datenbank existiert
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    // Initialisierung von Variablen zur Fehlerbehandlung.
    $error = false;
    $error_message = "";

    if ($rowCount > 0) {
        // E-Mail-Adresse existiert, einen zufälligen Hash generieren
        try {
            $hash = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            // Fehlerbehandlung, falls die Generierung des Hash fehlschlägt
            $errorMessage = "Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.";
        }

        if (!isset($errorMessage)) {
            // Hash in der Datenbank speichern
            $updateSql = "UPDATE users SET password_reset = :hash WHERE email = :email";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':hash', $hash, PDO::PARAM_STR);
            $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateStmt->execute();

            // Erfolgsmeldung anzeigen
            $successMessage = "Ihr Passwort-Reset-Link wurde an Ihre E-Mail-Adresse gesendet.";

            // Wenn keine Fehler aufgetreten sind, wird versucht, die E-Mail zu senden.
            if (!$error) {
                $to = $email_to;
                $headers = "From: no-reply@reset-password.com\r\n";

                // Die Nachricht wird formatiert.
                $message = "Um Ihr Passwort zurückzusetzen, klicken Sie auf den folgenden Link: http://projekt-a.local/web/reset-password.php?email=" . urlencode($email) . "&hash=" . $hash;

                // Versuche, die E-Mail zu senden.
                if (mail($to, $requestPasswordSubject, $message, $headers)) {
                    // Erfolgsmeldung anzeigen
                    $successMessage = "Ihr Passwort-Reset-Link wurde an Ihre E-Mail-Adresse gesendet.";
                } else {
                    // Wenn das Senden fehlschlägt, wird eine Fehlermeldung angezeigt.
                    $error_message = "Es gab ein Problem beim Senden der E-Mail. Bitte versuchen Sie es später erneut.";
                }
            }
        }
    } else {
        // Fehlermeldung anzeigen, dass die E-Mail-Adresse nicht existiert
        $errorMessage = "Die angegebene E-Mail-Adresse wurde nicht gefunden.";
    }
}

include '../template/request-reset.html.php';
