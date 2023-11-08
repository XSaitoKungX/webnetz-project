<?php
include '_header.html.php';
include "_hero.html.php";
?>

<link href="css/login_style.css" rel="stylesheet" type="text/css">

<div class="container-createNewUser" id="container">

    <div class="form-container">
        <h1>Password zurücksetzen</h1>
        <!-- Fehlermeldung anzeigen, wenn vorhanden -->
        <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <br>

        <form method="post">
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Neues Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Passwort wiederholen" required>
            </div>
            <button type="submit" name="reset_password" class="btn btn-primary">Passwort zurücksetzen</button>
        </form>
    </div>

</div>

<?php include '_footer.html.php'; ?>
