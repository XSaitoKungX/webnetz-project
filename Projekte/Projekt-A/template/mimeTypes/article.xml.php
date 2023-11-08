<?php
include(__DIR__ . '/../../config/config.php');

header('Content-Type: ' . $mimeTypes['xml']);
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<articles>' . PHP_EOL;
echo '<article>' . PHP_EOL;
echo '<title>' . htmlspecialchars($article['title']) . '</title>' . PHP_EOL;
echo '<last_change_date>' . htmlspecialchars($article['last_change_date']) . '</last_change_date>' . PHP_EOL;
echo '<description>' . htmlspecialchars($article['description']) . '</description>' . PHP_EOL;
echo '<content>' . htmlspecialchars($article['content']) . '</content>' . PHP_EOL;
echo '</article>' . PHP_EOL;
echo '</articles>' . PHP_EOL;
