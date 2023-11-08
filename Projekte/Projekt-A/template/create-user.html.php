<?php
include '_header.html.php';
include "_hero.html.php";
?>

<link href="css/login_style.css" rel="stylesheet" type="text/css">

<div class="container-createNewUser" id="container">

    <div class="form-container">
        <h1>Benutzer Anlegen!</h1>
        <!-- Fehlermeldung anzeigen, wenn vorhanden -->
        <?php if (!empty($errorMessage)) : ?>
            <h5 class="error-message"><?php echo $errorMessage; ?></h5>
            <?php unset($_SESSION['error_message']); // Fehlermeldung aus der Session entfernen ?>
        <?php endif; ?>
        <br>

        <!-- Hier beginnt das HTML-Formular in create-user.html.php -->
        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Vorname" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nachname" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="username" name="username" placeholder="Benutzername"
                       required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Passwort"
                       required>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1">
                    <p class="form-check-label">Ist Administrator?</p>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="active" name="active" value="1">
                    <p class="form-check-label">Aktiv?</p>
                </div>
            </div>
            <button type="submit" name="createUser" class="btn btn-primary">Benutzer erstellen</button>
            <a href="edit-users.php" class="btn btn-danger">Abbrechen</a>
        </form>
        <!-- Hier endet das HTML-Formular -->
    </div>

</div>

<?php include '_footer.html.php'; ?>
