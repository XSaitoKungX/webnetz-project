Das `web`-Verzeichnis
=====================

Dieses Verzeichnis ist das einzige Verzeichnis, was dein Webserver ausgeben darf. Alle anderen Verzeichnisse sind geschützt. Bei einem normalen Internet-Auftritt würde z.B. der Auruf von http://localhost/index.php die `web/index.php` aufrufen.

Alle PHP-Dateien in diesem Ordner bezeichnen wir als [Controller](https://de.wikipedia.org/wiki/Model_View_Controller#Controller): Hier werden die Anfragen eines Browsers (Client) an den Webserver bearbeitet, und das Ergebnis zurückgeschickt.

Um uns zu große Verwirrung zu ersparen, sollte jeder Controler folgende Struktur haben:

```php
<?php
// Lade die Konfigurationsdatei aus `config`
// Lade die benötigten Bibliotheken aus `app`

// Bearbeite die eigentliche Anfrage

// Lade das Template, dass dem Browser das Ergebnis anzeigt
```

Das `web/css`-Verzeichnis
-------------------------

Hier liegen CSS-Dateien, bzw. alles was mit Styling und Layout zu tun hat.


Das `web/images`- und `web/uploads`-Verzeichnis
-----------------------------------------------

In `web/images` liegen Bilder, die du in den Template verwendest - aber nicht die Bilder, die ein Nutzer hochgeladen hat. Alle Dateien, die von Nutzern der Internetseite hochgeladen werden, liegen in `web/uploads`. Als Betreiber der Internetseite wissen wir damit, dass nicht alles dort vertrauenswürdig ist, und wo wir im Fall der Fälle löschen müssen.

Das `web/javascript`-Verzeichnis
-------------------------

Hier liegen JavaScript-Dateien, bzw. alles was mit Interaktivität auf deiner Seite zu tun hat.
