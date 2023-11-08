<?php
include '_header.html.php';
include "_hero.html.php";
?>

<link href="css/login_style.css" rel="stylesheet" type="text/css">

<div class="container-createNewUser" id="container">

    <div class="form-container">
        <h1>Passwort Ändern</h1>
        <!-- Fehlermeldung anzeigen, wenn vorhanden -->
        <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <br>

        <form method="post">
            <div class="form-group">
                <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current_password" placeholder="Aktuelles Passwort" required />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password" placeholder="Neues Passwort" required />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Passwort wiederholen" required />
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Passwort ändern</button>
            <a class="btn btn-danger" href="profile.php">Abbrechen</a>
        </form>
    </div>

</div>

<?php include '_footer.html.php'; ?>
