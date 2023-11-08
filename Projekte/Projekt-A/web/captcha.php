<?php
/**
 * Dateiname: captcha.php
 *
 * Beschreibung: Generierung von Captcha-Bildern.
 *
 * PHP-Version: 7.0 oder höher
 *
 * @category Webentwicklung
 * @package    Captcha
 * @author     Nattapat Pongsuwan
 * @last-modified 2023-09-18
 */

// Inkludiere die erforderlichen Dateien und Konfigurationen
require_once('../app/functions.php');
require_once('../config/config.php');

// Generiere ein zufälliges Captcha aus Buchstaben und Zahlen
$sessionCaptcha = generateCaptcha();

$_SESSION['sessionCaptcha'] = $sessionCaptcha;

$imageWidth = 500; // Größere Breite des Bildes
$imageHeight = 200; // Größere Höhe des Bildes

$im = imagecreatetruecolor($imageWidth, $imageHeight);

// Hintergrund und Textfarben festlegen
$text_color = imagecolorallocate($im, 0, 0, 0);
$bg_color = imagecolorallocate($im, 255, 255, 255);

// Farbverlauf im Hintergrund erstellen
$gradientStart = imagecolorallocate($im, 240, 240, 240);
$gradientEnd = imagecolorallocate($im, 255, 255, 255);
imagefilledrectangle($im, 0, 0, $imageWidth, $imageHeight, $gradientStart);
imagefilledrectangle($im, 0, $imageHeight / 2, $imageWidth, $imageHeight, $gradientEnd);

// Add noise to the background
for ($i = 0; $i < 1000; $i++) { // Mehr Rauschen für größeres Bild
    $noise_color = imagecolorallocate($im, rand(100, 200), rand(100, 200), rand(100, 200));
    imagesetpixel($im, rand(0, $imageWidth), rand(0, $imageHeight), $noise_color);
}

$font = $_SERVER['DOCUMENT_ROOT'] . '/images/captcha/captcha.ttf';

// Verzicht auf zufällige Rotation und Größe
$angle = 0;
$fontSize = 70; // Größere Schriftgröße

// Schatten hinzufügen
$shadowColor = imagecolorallocate($im, 200, 200, 200);

// Hervorhebungseffekte
$highlightColor = imagecolorallocate($im, 255, 255, 255);

// Zeichne die Schrift
$textX = 40; // Größerer Abstand
$textY = $imageHeight / 2 + 20; // Größerer Abstand

for ($i = 0; $i < strlen($sessionCaptcha); $i++) {
    $char = $sessionCaptcha[$i];

    // Schatten
    imagettftext($im, $fontSize, $angle, $textX + 3, $textY + 3, $shadowColor, $font, $char);

    // Hervorhebung
    imagettftext($im, $fontSize, $angle, $textX - 2, $textY - 2, $highlightColor, $font, $char);

    // Schrift
    imagettftext($im, $fontSize, $angle, $textX, $textY, $text_color, $font, $char);

    $textX += rand(50, 60); // Größerer Abstand zwischen Buchstaben
}

// Ausgabe des Captcha-Bildes in eine Datei
$captchaImagePath = $_SERVER['DOCUMENT_ROOT'] . '/images/captcha/captcha.png';
imagepng($im, $captchaImagePath);
imagedestroy($im);
