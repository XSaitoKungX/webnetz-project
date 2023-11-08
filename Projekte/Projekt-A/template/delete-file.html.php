<?php
include '_header.html.php';
include "_hero.html.php";
?>

<div class="container">
    <h1 class="mt-5">Delete Image File</h1>

    <?php if (isset($serverMessage)): ?>
        <div class="alert alert-info mt-4 mb-0"><?= $serverMessage ?></div>
    <?php endif; ?>

    <div class="flex-container">
        <form id="deleteFile" name="deleteFile"
              action="delete-file.php" method="post">
            <div class="alert alert-danger">Dateiname: <?= $fileName ?></div>
            <input type="hidden" id="deleteFileId" name="id" value="<?= $articleId ?>">
            <input type="hidden" id="deleteFileName" name="filename" value="<?= $fileName ?>">
            <input class="btn btn-danger btn-block mt-3" type="submit" value="Endgültig löschen">
        </form>
        <br><br>
    </div>
</div>

<?php include '_footer.html.php'; ?>
