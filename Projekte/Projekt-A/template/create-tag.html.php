<?php include '_header.html.php'; ?>
<?php include 'navigation.php'; ?>
<?php include '_hero.html.php'; ?>

<?php if (!empty($error_message)) : ?>
    <div style="text-align: center" class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<?php if (!empty($success_message)) : ?>
    <div style="text-align: center" class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>
<br>

<div class="container">
    <h2>Neuen Tag erstellen</h2>
    <form method="post" action="create-tag.php">
        <br>
        <label for="tag">Tag:</label>
        <input type="text" name="tag" id="tag" required>
        <br><br>

        <input class="btn btn-success btn-block" type="submit" value="Erstellen">
        <a class="btn btn-danger btn-block" href="edit-tags.php">Abbrechen</a>
    </form>
    <br><br>
</div>

<?php include '_footer.html.php'; ?>
