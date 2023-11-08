<?php
/**
 * edit-user.php
 *
 * Diese Datei ermöglicht die Bearbeitung eines Benutzerprofils.
 *
 * PHP-Version 7.4
 *
 * @category Backend
 * @package  UserManagementSystem
 * @author   Nattapat Pongsuwan
 * @last-modified 2023-09-19
 */

// Erforderliche Dateien einbinden
require_once '../config/config.php';
require_once '../app/repository.php';
require_once '../app/mysql.php';
require_once '../app/functions.php';

// Sitzungsverwaltung
sessionManager(true);

// Prüfen, ob Benutzer angemeldet ist, sonst zur Anmeldeseite weiterleiten
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

$userId = $_GET['id'] ?? ''; // Falls keine ID übergeben wird, setze den Wert auf einen leeren String

// Überprüfen, ob die ID des zu bearbeitenden Benutzers übergeben wurde oder nicht leer ist
if (!$userId) {
    header("Location: /profile.php");
    exit();
}

// Erstelle ein Repository für Benutzer
$userRepository = new RepositoryUsers($pdo);

$is_admin = $_SESSION['is_admin'] ?? false;

// Prüfen, ob der angemeldete Benutzer der Eigentümer des zu bearbeitenden Profils ist oder ein Admin ist
if ($_SESSION['user_id'] != $userId && !$is_admin) {
    header("Location: /error/404.php"); // Weiterleiten bei eingeschränktem Zugriff
    exit();
}

$error_message = '';

// Benutzerdaten aus der Datenbank abrufen
$user = $userRepository->findOne($userId);

if (!$user) {
    // Benutzer nicht gefunden, hier eine Fehlerbehandlung durchführen
    header('Location: /profile.php?id=' . $userId);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulardaten validieren und mit der Datenbank überprüfen
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $active = $_POST['active'] ?? 0;
    $last_change_date = date('Y-m-d H:i:s', time());
    $last_editor_id = $_SESSION['user_id'];

    // Überprüfen, ob Vorname, Nachname oder Benutzername geändert wurden
    $data_changed = $first_name !== $user['first_name'] || $last_name !== $user['last_name'] || $username !== $user['username'];

    // Überprüfen, ob das Passwort korrekt ist, wenn es eingegeben wurde und der angemeldete Benutzer kein Admin ist oder Daten geändert wurden
    if ((!$is_admin && ($data_changed || !empty($_POST['password'])))) {
        $current_password = $_POST['password'];

        // Überprüfen, ob das aktuelle Passwort korrekt ist
        $user_password_hash = $user['password'];
        if (!password_verify($current_password, $user_password_hash)) {
            $error_message = 'Das aktuelle Passwort ist nicht korrekt!';
        }
    }

    if (empty($error_message)) {
        // Daten für das Update vorbereiten
        $updateData = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'last_change_date' => $last_change_date,
            'last_editor_id' => $last_editor_id,
            'active' => $active,
            'is_admin' => $_POST['is_admin']
        ];

        // SQL-Abfrage ausführen, um den Benutzer in der Datenbank zu aktualisieren
        $updateStatus = $userRepository->update($userId, $updateData);

        if ($updateStatus) {
            // Update des Logos nur, wenn eine neue Logodatei hochgeladen wurde
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logo_tmp_name = $_FILES['logo']['tmp_name'];
                $logo_name = basename($_FILES['logo']['name']); // Escaping
                $logo_path = 'uploads/user-logo/' . $logo_name;

                // Datei in das Zielverzeichnis verschieben
                move_uploaded_file($logo_tmp_name, $logo_path);

                // SQL-Abfrage ausführen, um das Logo in der Datenbank zu aktualisieren
                $sql_logo = "UPDATE users SET logo=? WHERE id=?";
                $statement_logo = $pdo->prepare($sql_logo);
                $statement_logo->execute([$logo_name, $userId]);
            }

            header('Location: /profile.php?id=' . $userId);
            exit();
        } else {
            $error_message = "Fehler beim Aktualisieren des Benutzers.";
        }
    }
}

// SQL-Abfrage, um den Benutzernamen abzurufen
$username = $user['username'];

if ($username) {
    $pageTitle = "Edit - " . $username . " Profile";
} else {
    $pageTitle = "Edit - Profile";
}

include '../template/edit-user.html.php';
