<?php
include(__DIR__ . '/../../config/config.php');
require_once '../../app/mysql.php';

header('Content-Type: ' . $mimeTypes['json']);

// Artikel-ID aus der URL-Parameter abrufen
$articleId = $_GET['id'] ?? null;

if (!$articleId) {
    http_response_code(400);
    echo json_encode(['error' => 'Article ID not provided']);
    exit();
}

// Abfrage, um den Artikel aus der Datenbank abzurufen
$query = "SELECT title, last_change_date, description, content FROM articles WHERE id = :articleId";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);

try {
    $stmt->execute();
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit();
}

if (!$article) {
    http_response_code(404);
    echo json_encode(['error' => 'Article not found']);
    exit();
}

// Generiere den JSON-Ausgabe für den Artikel
$articleJson = [
    'title' => $article['title'],
    'last_change_date' => $article['last_change_date'],
    'description' => $article['description'],
    'content' => $article['content']
];

echo json_encode($articleJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
