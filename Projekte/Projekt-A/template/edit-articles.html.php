<?php
include '_header.html.php';
include '_hero.html.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Startseite</a></li>
        <li class="breadcrumb-item active" aria-current="page">Artikeln verwalten</li>
    </ol>
</nav>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li><a href="create-article.php" class="btn btn-primary">Artikel erstellen</a></li>
    </ol>
</nav>

<div class="container-comments">
    <table class="table-comments">
        <h2 class="section-title">Artikeln verwalten</h2>
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Erstelldatum</th>
            <th>Kategorie ID</th>
            <th>Zuletzt verändert</th>
            <th>Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($allArticles as $article): ?>
            <tr>
                <td><?= $article['id'] ?></td>
                <td><a href="edit-articles.php?id=<?= $article['id'] ?>"><?= $article['title'] ?></a></td>
                <td><?= $article['date'] ?></td>
                <td><?= $article['category_id'] ?></td>
                <td><?= $article['last_change_date'] ?></td>
                <td>
                    <a class="btn btn-info btn-group" href="edit-article.php?id=<?= $article['id'] ?>"><i
                            class="fas fa-pencil-alt"></i> Editieren</a>
                    <a class="btn btn-danger btn-group" href="delete-article.php?id=<?= $article['id'] ?>"><i
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
