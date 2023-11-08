<?php
include '_header.html.php';
include '_hero.html.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Startseite</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tagverwalten</li>
    </ol>
</nav>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li><a href="create-tag.php" class="btn btn-primary">Neuen Tag anlegen</a></li>
    </ol>
</nav>

<div class="container-comments">
    <table class="table-comments">
        <h2 class="section-title">Tagverwalten</h2>
        <thead>
        <tr>
            <th>Tag</th>
            <th>Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($allTags as $tag): ?>
            <tr>
                <td><?= $tag['tag'] ?></td>
                <td>
                    <a class="btn btn-info btn-block" href="edit-tag.php?tag=<?= $tag['tag'] ?>"><i
                            class="fas fa-edit"></i> Editieren</a>
                    <a class="btn btn-danger btn-block" href="delete-tag.php?tag=<?= $tag['tag'] ?>"><i
                            class="fas fa-trash"></i> Löschen</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <a href="index.php" class="btn btn-primary">Zurück zur Startseite</a>
    </div>
</div>

<?php include '_footer.html.php'; ?>
