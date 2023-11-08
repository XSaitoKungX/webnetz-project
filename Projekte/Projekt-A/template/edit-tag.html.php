<?php
include '_header.html.php';
include '_hero.html.php';
?>

<?php if (isset($error_message)): ?>
    <div style="text-align: center" class="alert alert-danger"><?= $error_message ?></div>
<?php endif; ?>
<br>

<div class="container">
    <h2 class="mb-4">Tag Bearbeiten</h2>

    <form method="post" action="">
        <div class="form-group row">
            <label for="tag" class="col-sm-3 col-form-label d-flex align-items-center">Tag:</label>
            <div class="col-sm-9">
                <input type="text" name="tag" id="tag" class="form-control-edit" value="<?php echo $tag['tag']; ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block">Speichern</button>
        <a href="edit-tags.php" class="btn btn-danger btn-block">Abbrechen</a>
    </form>
</div>

<?php include '_footer.html.php'; ?>
