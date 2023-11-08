# Azubi-Blog / Article Management System (AMS)

## Inhaltsverzeichnis

- [Allgemeine Anforderungen](#allgemeine-anforderungen)
- [Dokumentation der PHP-Dateien](#dokumentation-der-php-dateien)
  - [index.php](#indexphp)
  - [login.php](#loginphp)
  - [logout.php](#logoutphp)
  - [navigation.php](#navigationphp)
  - [profile.php](#profilephp)
  - [register.php](#registerphp)
  - [search.php](#searchphp)
- [Einführung](#einführung)
- [Systemanforderungen](#systemanforderungen)
- [Installation](#installation)
- [Verwendung](#verwendung)
  - [Anmelden](#anmelden)
  - [Artikel verwalten (Administratoren)](#artikel-verwalten-administratoren)
  - [Benutzer verwalten (Administratoren)](#benutzer-verwalten-administratoren)
  - [Benutzerprofil bearbeiten (Benutzer)](#benutzerprofil-bearbeiten-benutzer)
  - [Beitragserstellung](#beitragserstellung)
- [Mitwirkende](#mitwirkende)
- [Lizenz](#lizenz)

---

## Allgemeine Anforderungen

- PHP-Version 7.4 oder höher
- Verwendung von PHP-Doc-Kommentaren zur Verbesserung der Codeverständlichkeit
- Hinzufügen des "last-modified"-Datums zu jeder Datei, um den Zeitpunkt der letzten Änderung anzugeben

---

## Dokumentation der PHP-Dateien

### index.php

- Zuständig für die Darstellung der Startseite
- Nutzt Konfigurationsdateien und Funktionen aus `config.php`, `mysql.php` und `functions.php`
- Verwendet die `modifyUrlParameter`-Funktion

### login.php

- Ermöglicht Benutzern das Anmelden
- Verwendet Funktionen aus `mysql.php` und `functions.php`
- Klare Dokumentation von Funktionen und Variablen durch PHP-Doc-Kommentare

### logout.php

- Ermöglicht Benutzern das Abmelden
- Verwendet Funktionen aus `mysql.php` und `functions.php`
- Klare Dokumentation der Funktionen

### navigation.php

- Erstellt die Navigationsleiste und ruft Kategoriedaten ab
- Verwendet Funktionen aus `mysql.php` und `functions.php`
- Dokumentation zur Funktionsweise der Navigation und zur Verwendung von SQL-Abfragen

### profile.php

- Ermöglicht die Anzeige und Bearbeitung von Benutzerprofilen
- Verwendet Funktionen aus `mysql.php` und `functions.php`
- Dokumentation zur Anzeige und Bearbeitung von Profilen und zur Verwendung von Benutzerdaten aus der Datenbank

### register.php

- Ermöglicht die Benutzerregistrierung
- Verwendet Funktionen aus `mysql.php` und `functions.php`
- Dokumentation zur Registrierungsfunktion und zur Verwendung von Benutzerdaten aus dem Formular

### search.php

- Ermöglicht die Suche nach Artikeln und die Anzeige von Suchergebnissen
- Verwendet Funktionen aus `config.php`, `mysql.php` und `functions.php`
- Dokumentation zur Suche, Paginierung und Verwendung von Suchkriterien und Kategorien

---

## Einführung

Das Article Management System (AMS) ist eine einfache Anwendung zur Verwaltung von Artikeln und Benutzerprofilen. Es wurde entwickelt, um Administratoren die Verwaltung von Artikeln und Benutzern zu erleichtern.

---

## Systemanforderungen

- PHP 7.4 oder höher
- MySQL-Datenbank
- Webserver (z. B. Apache)

---

## Installation

1. **XAMPP Herunterladen und Installieren**:
   - Laden Sie XAMPP von der [offiziellen Website](https://www.apachefriends.org/de/download.html) herunter und installieren Sie es auf Ihrem Computer.

2. **XAMPP Starten**:
   - Starten Sie XAMPP nach der Installation und stellen Sie sicher, dass der Apache- und MySQL-Server aktiviert sind.

3. **Datenbank erstellen**:
   - Öffnen Sie Ihren Webbrowser und rufen Sie `http://localhost/phpmyadmin` auf.
   - Klicken Sie auf "Datenbanken" in der oberen Menüleiste.
   - Geben Sie einen Namen für Ihre Datenbank ein, z.B. `ams_db`, und wählen Sie "utf8_general_ci" als Kollation aus.
   - Klicken Sie auf "Erstellen" bzw. "Anlegen", um die Datenbank zu erstellen.
   - ![Lokales Bild](images/Datenbankanlegen.png)

4. **Konfiguration**:
   - Kopieren Sie die Datei `app/mysql.example.php` und benennen Sie sie in `mysql.php` um.
   - Öffnen Sie `mysql.php` und passen Sie die Datenbankverbindung an, indem Sie Ihren Benutzernamen, Ihr Passwort und den Datenbanknamen (`ams_db`) entsprechend eintragen.

5. **Webserver**: Stellen Sie sicher, dass das Projektverzeichnis hier gespeichert wird:
   - Navigieren Sie zu `C:\xampp\htdocs` auf Ihrem Computer.
   - Löschen Sie alle vorhandenen Dateien und Ordner in diesem Verzeichnis.

6. **Projekt kopieren**:
   - Kopieren Sie den gesamten Inhalt Ihres Projektverzeichnisses in `C:\xampp\htdocs`.

7. **Localhost aufrufen**:
   - Öffnen Sie Ihren Webbrowser und rufen Sie `http://localhost` auf.

8. **Web/ anklicken**:
   - Klicken Sie auf den Link zu Ihrem Projekt, normalerweise unter dem Namen des Projekts, z.B. `http://localhost/web`.

---

## Verwendung

### Anmelden

1. Besuchen Sie die Startseite und klicken Sie auf "Anmelden".
2. Geben Sie Ihre Anmeldeinformationen ein (Benutzername und Passwort) und klicken Sie auf "Anmelden".

### Artikel verwalten (Administratoren)

1. Melden Sie sich als Administrator an.
2. Klicken Sie im Dashboard auf "Artikel verwalten", um eine Liste der Artikel anzuzeigen.
3. Sie können Artikel hinzufügen, bearbeiten und löschen.

### Benutzer verwalten (Administratoren)

1. Melden Sie sich als Administrator an.
2. Klicken Sie im Dashboard auf "Benutzer verwalten", um eine Liste der Benutzer anzuzeigen.
3. Sie können Benutzerprofile bearbeiten und löschen.

### Benutzerprofil bearbeiten (Benutzer)

1. Melden Sie sich an und klicken Sie auf Ihr Profilbild oben rechts.
2. Klicken Sie auf "Profil bearbeiten", um Ihre Profilinformationen zu ändern.

### Beitragserstellung

1. Klicken Sie im Dashboard auf "Neuer Artikel".
2. Geben Sie einen Titel, eine Beschreibung und den Inhalt des Artikels ein.
3. Klicken Sie auf "Veröffentlichen", um den Artikel zu speichern.

---

## Mitwirkende

- Nattapat Pongsuwan

---

## Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert. Weitere Informationen finden Sie in der Datei:
- Text version: [LICENSE-2.0.txt](https://www.apache.org/licenses/LICENSE-2.0.txt)
- SPDX short identifier: [Apache-2.0](https://spdx.org/licenses/Apache-2.0.html)
- OSI Approved License: [Apache-2.0](https://opensource.org/license/apache-2-0/)
