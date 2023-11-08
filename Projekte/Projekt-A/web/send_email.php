<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "n.pongsuwan@web-netz.de"; // Deine E-Mail-Adresse
    $subject = $_POST['asunto'];
    $message = $_POST['mensaje'];
    $headers = "From: " . $_POST['email'] . "\r\n" .
        "Reply-To: " . $_POST['email'] . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    // Mailtrap SMTP-Einstellungen
    $smtpHost = "wnexch01.web-netz.de";
    $smtpPort = 587;
    $smtpUsername = "n.pongsuwan@web-netz.de";
    $smtpPassword = "!Markung2547.!";

    // E-Mail senden über Mailtrap
    if (mail($to, $subject, $message, $headers, "-f$smtpUsername")) {
        echo "E-Mail sent successfully!";
    } else {
        echo "Failed to send the E-mail.";
    }
}
