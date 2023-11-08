<?php

// MySQL-Datenbankverbindungseinstellungen
$servername = 'localhost'; // Hostname der Datenbank
$username = 'root'; // Benutzername der Datenbank
$password = ''; // Passwort der Datenbank
$dbname = 'webnetz_db'; // Name der Datenbank

// Erstellen einer Verbindung zur Datenbank mit PDO
$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Verbindung zur Datenbank fehlgeschlagen: ' . $e->getMessage());
}

// Man könnte die Zeile $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); hinzufügen, um vorbereitete Anweisungen ohne Emulation zu verwenden.
