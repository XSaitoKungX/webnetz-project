<?php
include '_header.html.php';
?>

<div class="container-profile">
    <h1>Willkommen <?php echo $first_name . ' ' . $last_name; ?></h1>
    <?php if ($logo_name) : ?>
        <img src="uploads/user-logo/<?php echo $logo_name; ?>" alt="Logo">
    <?php endif; ?>
    <h4>Name: <?php echo $first_name . ' ' . $last_name; ?></h4>
    <h4>Email: <?php echo $email; ?></h4>
    <h4>Benutzername: <?php echo $username; ?></h4>
    <h4>Aktiv: <?php echo $active ? 'Ja' : 'Nein'; ?></h4>
    <h4>Administrator: <?php echo isUserAdmin($user_id, $pdo) ? 'Ja' : 'Nein'; ?></h4>
    <h4>Letzte Änderung: <?php echo $last_change_date; ?></h4>
    <div>
        <a href="index.php" class="button">Startseite</a>
        <a href="edit-user.php?id=<?php echo $user_id; ?>" class="button">Bearbeiten</a>
        <a href="change-password.php?id=<?php echo $user_id; ?>" class="button">Passwort Ändern</a>
        <a href="delete-user.php?id=<?php echo $user_id; ?>" class="button">Löschen</a>
        <a href="edit-users.php" class="button">Benutzerliste</a>
        <a href="logout.php" class="button" name="logout">Log Out</a>
    </div>
</div>
