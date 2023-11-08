<?php
include '_header.html.php';
include "_hero.html.php";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center p-2 mb-3">
                <h1 class="mb-4">Benutzerverwalten</h1>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                <?php elseif (isset($success_message)): ?>
                    <div class="alert alert-success"><?= $success_message ?></div>
                    <a class="btn btn-success" href="../edit-users.php">Zurück zur Benutzerliste</a>
                <?php else: ?>
                    <p>Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?</p>
                    <form action="delete-user.php?id=<?= $userId ?>" method="post">
                        <button type="submit" class="btn btn-danger mr-2" name="delete_article"><i class="fas fa-check"></i> Ja, löschen</button>
                        <a class="btn btn-danger" href="edit-users.php?id=<?php echo $userId; ?>"><i class="fas fa-times"></i> Abbrechen</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '_footer.html.php'; ?>
