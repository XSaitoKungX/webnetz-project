<?php

// =================== AMP =================== //

const DOMAIN_NON_AMP = 'azubi-blog.local';
const DOMAIN_AMP = 'amp.azubi-blog.local';
define('IS_AMP', ($_SERVER['HTTP_HOST'] === DOMAIN_AMP));

// =================== Mail =================== //

// Ziel-E-Mail-Adresse, an die die Formulardaten gesendet werden sollen
$email_to = "mark@localhost";

// E-Mail-Betreff für die empfangenen Formulardaten
$email_subject = "Neue Kontaktanfrage von deinem Blog";

// E-Mail-Betreff für die empfangenen Formulardaten
$requestPasswordSubject = "Neue Anfrage zum Zurücksetzen des Passworts";

// E-Mail-Signatur
$email_signature = "Viele Grüße,\nNattapat Pongsuwan";

// =================== MIME-Type =================== //

$mimeTypes = [
    'txt' => 'text/plain',
    'html' => 'text/html',
    'json' => 'application/json',
    'css' => 'text/css',
    'csv' => 'text/csv',
    'xml' => 'application/xml',
    'pdf' => 'application/pdf',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'mp3' => 'audio/mpeg',
    'mp4' => 'video/mp4',
    'zip' => 'application/zip',
    'tar' => 'application/x-tar',
    'gz' => 'application/gzip',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];
