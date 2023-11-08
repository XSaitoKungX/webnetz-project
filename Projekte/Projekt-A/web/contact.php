<?php
/**
 * Dateiname: contact.php
 *
 * Beschreibung: Behandelt die Anzeige und Verarbeitung des Kontaktformulars.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    Kontakt
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

// Zuerst werden die notwendigen Konfigurationen und Funktionen eingebunden.
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Festlegen der Seitentitel
$pageTitle = "Kontakt";

// Überprüfe, ob das Formular gesendet wurde (über die POST-Methode).
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Hier werden die eingegebenen Formulardaten abgerufen.
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $postCaptcha = isset($_POST['captcha']) ? ($_POST['captcha']) : '';

    // Initialisierung von Variablen zur Fehlerbehandlung.
    $error = false;
    $error_message = "";

    // ====================== [1. Captcha-Prüfung] ====================== //

    $sessionCaptcha = $_SESSION['sessionCaptcha']; // Das Captcha aus der Session abrufen

    if ($postCaptcha !== $sessionCaptcha) {
        $error = true;
        $error_message = "Das eingegebene Captcha ist falsch.";
    }

    // ====================== [2. Honey-Pot-Prüfung] ====================== //

    if (!empty(trim($_POST['bot_check']))) {
        // Honey-Pot-Feld wurde ausgefüllt, vermutlich ein Bot
        $botIP = $_SERVER['REMOTE_ADDR'];
        $logMessage = "Bot detected from IP: $botIP at " . date('Y-m-d H:i:s') . "\n";

        // Schicke eine E-Mail an mark@localhost mit den Fehlerdetails
        $detectedTo = 'mark@localhost';
        $subject = 'Bot Detected';
        $headers = "From: no-reply@web-netz.de\r\n";
        $headers .= "Reply-To: n.pongsuwan@web-netz.de\r\n";
        $message = "Ein Bot wurde erkannt:\n\n$logMessage";
        mail($detectedTo, $subject, $message, $headers);

        // Speichere die Fehlermeldung auch in die Log-Datei
        $logPath = '/error/logs/bot_log.txt';
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        header("Location: /error/silent.html");
        exit(); // Beende die Verarbeitung des Formulars
    } else {

        // ====================== [3. Validierung der Formulardaten] ====================== //

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $error = true;
            $error_message = "Bitte füllen Sie alle erforderlichen Felder aus.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $error_message = "Die eingegebene E-Mail-Adresse ist ungültig.";
        }
    }

    // ====================== [4. E-Mail senden] ====================== //

    // Wenn keine Fehler aufgetreten sind, wird versucht, die E-Mail zu senden.
    if (!$error) {
        $to = $email_to;
        $headers = "From: $email\r\nReply-To: $email";

        // Die Nachricht wird formatiert.
        $message = "Name: $name\r\nE-Mail: $email\r\nBetreff: $subject\r\nNachricht: $message";

        if (!empty($articleId)) {
            $message .= "\r\nArtikel-ID: $articleId"; // Hinzufügen der Artikel-ID zur Nachricht
        }

        // Versuche, die E-Mail zu senden.
        if (mail($to, $requestPasswordSubject, $message, $headers)) {
            // Bei Erfolg wird der Benutzer auf eine Erfolgsseite weitergeleitet.
            header("Location: contact.php?success=1");
            exit();
        } else {
            // Wenn das Senden fehlschlägt, wird eine Fehlermeldung angezeigt.
            $error_message = "Es gab ein Problem beim Senden der E-Mail. Bitte versuchen Sie es später erneut.";
        }
    }
}

// Captcha generieren und Kontaktformular anzeigen
generateCaptcha(); // Funktion zum Generieren des Captcha
include '../template/contact.html.php';
