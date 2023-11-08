<?php
include '_header.html.php';
include "_hero.html.php";
?>

<?php if (isset($error_message)): ?>
    <div style="text-align: center" class="alert alert-danger"><?= $error_message ?></div>
<?php elseif (isset($success_message)): ?>
    <div style="text-align: center" class="alert alert-success"><?= $success_message ?></div>
<?php endif; ?>
<br>

<div class="container">
    <h2 class="mb-4">Kategorie Bearbeiten</h2>

    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="title" class="col-sm-3 col-form-label d-flex align-items-center">Titel:</label>
            <div class="col-sm-9">
            <input type="text" name="title" id="title" class="form-control-edit" value="<?php echo $category['title']; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="description" class="col-sm-3 col-form-label d-flex align-items-center">Beschreibung:</label>
            <div class="col-sm-9">
            <textarea name="description" class="form-control-edit" rows="5" required><?php echo $category['description']; ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block">Speichern</button>
<!--        <input class="btn btn-success btn-block" type="submit" name="submit_type" value="Speichern">-->
        <a href="categories.php?id=<?php echo $categoryId; ?>" class="btn btn-danger btn-block">Abbrechen</a>
    </form>
</div>

<?php include '_footer.html.php'; ?>
