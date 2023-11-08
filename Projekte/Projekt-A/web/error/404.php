<?php

http_response_code(404);

include '404.html.php';

// Zeigen Sie die Nachricht an, wenn sie vorhanden ist
if (isset($_SESSION['message'])) {
    unset($_SESSION['message']); // Die Nachricht entfernen, nachdem sie angezeigt wurde
}
