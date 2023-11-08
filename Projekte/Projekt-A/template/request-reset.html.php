<?php
include '_header.html.php';
include "_hero.html.php";
?>

    <link href="css/login_style.css" rel="stylesheet" type="text/css">

    <div class="container-createNewUser" id="container">
        <div class="form-container">

            <!-- Erfolgsmeldung anzeigen, wenn vorhanden -->
            <?php if (!empty($successMessage)) : ?>
                <div class="alert alert-success">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Fehlermeldung anzeigen, wenn vorhanden -->
            <?php if (!empty($errorMessage)) : ?>
                <div class="alert alert-danger">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <p class="request-reset">Bitte geben Sie die E-Mail-Adresse ein, die mit Ihrem Konto verknüpft ist. Wir werden Ihnen einen Link zum Zurücksetzen Ihres Passworts senden.</p>

            <form method="POST" action="request-reset.php">
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" required>
                </div>
                <button type="submit" class="btn btn-primary">Anfordern</button>
            </form>
        </div>
    </div>

<?php include '_footer.html.php'; ?>

