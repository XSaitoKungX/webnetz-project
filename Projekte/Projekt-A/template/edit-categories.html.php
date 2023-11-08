<?php
include '_header.html.php';
include '_hero.html.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Startseite</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kategorien verwalten</li>
    </ol>
</nav>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li><a href="create-category.php" class="btn btn-primary">Kategorie erstellen</a></li>
    </ol>
</nav>

<div class="container-comments">
    <table class="table-comments">
        <h2 class="section-title">Kategorien verwalten</h2>
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Erstelldatum</th>
            <th>Zuletzt geändert</th>
            <th>Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($allCategories as $category): ?>
            <tr>
                <td><?= $category['id'] ?></td>
                <td><a href="edit-articles.php?id=<?= $category['id'] ?>"><?= $category['title'] ?></a></td>
                <td><?= $category['date'] ?></td>
                <td><?= $category['last_change_date'] ?></td>
                <td>
                    <a class="btn btn-info" href="edit-category.php?id=<?= $category['id'] ?>"><i
                            class="fas fa-pencil-alt"></i> Editieren</a>
                    <a class="btn btn-danger" href="delete-category.php?id=<?= $category['id'] ?>"><i
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
