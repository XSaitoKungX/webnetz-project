<?php
include '_header.html.php';
include '_hero.html.php';
?>

<div class="container">
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <h2>Profil bearbeiten</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="profile-picture">
            <label for="logo" class="profile-picture-label">
                <?php if (!empty($user['logo'])) : ?>
                    <img id="profile-image" src="uploads/user-logo/<?php echo $user['logo']; ?>" alt="Profile Picture">
                <?php else : ?>
                    <img src="images/user-logo/User-Logo.png" alt="Default Profile Picture">
                <?php endif; ?>
                <input type="file" id="logo" name="logo" accept="image/*" style="display: none;">
            </label>
        </div>
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
        <br>
        <label for="first_name">Vorname:</label>
        <input type="text" name="first_name" id="first_name" value="<?php echo $user['first_name']; ?>" required>
        <br>
        <label for="last_name">Nachname:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo $user['last_name']; ?>">
        <br>
        <label for="username">Benutzername:</label>
        <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required>
        <br>
        <label for="password">Aktuelles Passwort (nur erforderlich bei Änderungen):</label>
        <input type="password" id="password" name="password">
        <label><a href="reset-password.php">Passwort vergessen?</a></label>
        <label for="active">Benutzerstatus:</label>
        <select id="active" name="active">
            <option value="1" <?php echo $user['active'] == 1 ? 'selected' : ''; ?>>Aktiv</option>
            <option value="0" <?php echo $user['active'] == 0 ? 'selected' : ''; ?>>Deaktiviert</option>
        </select>
        <br>
        <?php if ($is_admin) : ?>
            <br>
            <label for="is_admin">Administrator:</label>
            <select id="is_admin" name="is_admin">
                <option value="1" <?php echo $user['is_admin'] ? 'selected' : ''; ?>>Ja</option>
                <option value="0" <?php echo !$user['is_admin'] ? 'selected' : ''; ?>>Nein</option>
            </select>
        <?php endif; ?>
        <br><br>
        <input class="btn btn-success" type="submit" value="Speichern">
        <a class="btn btn-danger" href="edit-users.php">Abbrechen</a>
    </form>
</div>

<script>
    document.querySelector('#logo').addEventListener('change', function(event) {
        const selectedFile = event.target.files[0];
        if (selectedFile) {
            const profileImage = document.querySelector('#profile-image');
            profileImage.src = URL.createObjectURL(selectedFile);
        }
    });
</script>

<?php include '_footer.html.php'; ?>
