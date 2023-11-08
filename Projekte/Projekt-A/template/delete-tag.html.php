<?php
include '_header.html.php';
include "_hero.html.php";
?>

<div class="container">
    <h1>Tag löschen</h1>
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
        <br>
    <?php endif; ?>
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>
    <div class="text-center">
        <a class="btn btn-success" href="edit-tags.php">Zurück zur Tagseite</a>
    </div>
</div>

<?php include '_footer.html.php'; ?>
