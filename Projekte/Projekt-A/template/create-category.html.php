<?php
include '_header.html.php';
include 'navigation.php';
include "_hero.html.php";
?>

<?php if (isset($error_message)) : ?>
    <div><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="container">
    <h2>Neuen Kategorie erstellen</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <br>
        <label for="description">Title:</label>
        <input type="text" name="title" id="title" required>
        <br><br>
        <label for="description">Beschreibung:</label>
        <textarea name="description" class="custom-textarea" rows="10" cols="70" required></textarea>
        <br><br>
        <input class="btn btn-success btn-block" type="submit" value="Erstellen">
        <a class="btn btn-danger btn-block" href="edit-categories.php">Abbrechen</a>
    </form>
    <br><br>
</div>

<?php include '_footer.html.php'; ?>
