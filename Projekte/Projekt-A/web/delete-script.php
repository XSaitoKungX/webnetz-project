<?php
// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

/**
 * Datei löschen - API-Endpunkt
 *
 * Dieser API-Endpunkt ermöglicht das Löschen von Dateien und antwortet auf HTTP DELETE-Anfragen.
 */

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $filename = $_GET['filename'] ?? '';
    $articleId = $_GET['id'] ?? '';

    if (!empty($filename) && !empty($articleId)) {
        $filePath = '../uploads/' . $articleId . '/' . $filename;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                // Erfolgreich gelöscht
                echo json_encode(['success' => true]);
                exit();
            }
        }
    }
}

// Fehler beim Löschen
echo json_encode(['success' => false]);
