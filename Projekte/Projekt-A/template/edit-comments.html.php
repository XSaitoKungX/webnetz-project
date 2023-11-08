<?php
include '_header.html.php';
include '_hero.html.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Startseite</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kommentarverwalten</li>
    </ol>
</nav>

<div class="container-comments">
    <h2 class="section-title">Kommentarverwalten</h2>
    <div class="table-container">
        <table class="table-comments">
            <thead>
            <tr>
                <th>ID</th>
                <th>Artikel ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Kommentar</th>
                <th>Datum</th>
                <th>Öffentlich</th>
                <th>Aktionen</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allComments as $comment): ?>
                <tr>
                    <td><?= $comment['id'] ?></td>
                    <td><?= $comment['article_id'] ?></td>
                    <td><?= $comment['name'] ?></td>
                    <td><strong><?= $comment['email'] ?></strong></td>
                    <td><?= $comment['comment_text'] ?></td>
                    <td><?= $comment['comment_date'] ?></td>
                    <td><?= $comment['is_public'] ? '<i class="fas fa-check-square fa-spin" style="color: #53ec12;"></i>' : '<i class="fas fa-ban fa-spin" style="color: #f91515;"></i>' ?></td>
                    <td>
                        <a class="btn btn-info" href="publish-comment.php?id=<?= $comment['id'] ?>">
                            <i class="fas fa-check-circle"></i> Freigeben
                        </a>
                        <a class="btn btn-danger" href="delete-comment.php?id=<?= $comment['id'] ?>">
                            <i class="fas fa-bomb"></i> Löschen
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="index.php" class="btn btn-primary">Zurück zur Startseite</a>
    </div>
</div>

<?php include '_footer.html.php'; ?>
