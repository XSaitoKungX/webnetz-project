<?php
/**
 * logout.php
 *
 * Diese Datei ermöglicht Benutzern das Ausloggen.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  Authentifizierung
 * @author   Nattawat Pongsuwan
 * @last-modified 2023-09-04
 */

// Inkludieren der erforderlichen Dateien und Konfigurationen
require_once('../app/mysql.php');
require_once('../app/functions.php');
require_once '../config/config.php';

$pageTitle = "Logout";

/**
 * Führt die Abmeldung (Logout) des Benutzers durch.
 *
 * Diese Funktion beendet die aktuelle Sitzung und führt den Benutzer aus.
 *
 * @return void
 */
// Logout-Funktion aufrufen, um den Benutzer auszuloggen
logout();

// Die Logout-Seite rendern
include('../template/logout.html.php');
