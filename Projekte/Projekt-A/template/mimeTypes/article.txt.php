<?php
include(__DIR__ . '/../../config/config.php');

header('Content-Type: ' . $mimeTypes['txt']);

echo "Artikelüberschrift: " . $article['title'] . "\n\n";
echo "Geändert von: " . $article['username'] . "\n\n";
echo "Artikeldatum: " . $article['last_change_date'] . "\n\n";
echo "Artikelbeschreibung: " . $article['description'] . "\n\n";
echo "Artikeltext:\n" . wordwrap($article['content'], 80) . "\n\n";
