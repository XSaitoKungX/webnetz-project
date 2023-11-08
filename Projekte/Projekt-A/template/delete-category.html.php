<?php
include '_header.html.php';
include "_hero.html.php";
?>

<div class="container">
    <h1>Delete Category</h1>
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
        <br>
    <?php endif; ?>
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <div class="text-center">
        <a class="btn btn-success" href="categories.php">Zurück zur Kategorieseite</a>
    </div>
</div>

<?php include '_footer.html.php'; ?>
