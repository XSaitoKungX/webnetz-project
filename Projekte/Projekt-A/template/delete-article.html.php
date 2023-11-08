<?php
include '_header.html.php';
include "_hero.html.php";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center p-2 mb-3">
                <h1 class="mb-4">Delete - Artikel</h1>
                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger"><?= $errorMessage ?></div>
                    <a class="btn btn-success" href="index.php">Zurück zur Startseite</a>
                <?php elseif (isset($successMessage)): ?>
                    <div class="alert alert-success"><?= $successMessage ?></div>
                <a class="btn btn-success" href="index.php">Zurück zur Startseite</a>
                <?php else: ?>
                <div class="alert alert-success">
                    <p>Sind Sie sicher, dass Sie diesen Artikel löschen möchten?</p>
                </div>
                    <form action="delete-article.php?id=<?= $articleId ?>" method="post">
                        <button type="submit" class="btn btn-danger mr-2" name="delete_article"><i class="fas fa-check"></i> Ja, löschen</button>
                        <a class="btn btn-danger" href="article.php?id=<?php echo $articleId; ?>"><i class="fas fa-times"></i> Abbrechen</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.html.php'; ?>
