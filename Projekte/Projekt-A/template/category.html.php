<?php include '_header.html.php'; ?>
<?php include 'navigation.php'; ?>
<?php include '_hero.html.php'; ?>

<title>Kategorie: <?= htmlspecialchars($pageTitle) ?></title>

<main class="p-3 mb-2 bg-transparent">

    <h2>Kategorie: <?= htmlspecialchars($pageTitle) ?></h2>
    <br>
    <div class="textRight">
        <a class="btn btn-success" href="create-category.php">➕ Erstellen</a>
    </div>
    <br>
    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <article class="card">
                <h3>
                    <a href="article.php?id=<?= htmlspecialchars($article['id']); ?>"><?= htmlspecialchars($article['title']); ?></a>
                </h3>
                <p><?= htmlspecialchars($article['content']); ?></p>
                <h4>
                    &nbsp;<a class="btn btn-outline-info"
                             href="article.php?id=<?= htmlspecialchars($article['id']); ?>">📰 Weiterlesen..</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        &nbsp;<a class="btn btn-outline-warning"
                                 href="edit-category.php?id=<?= htmlspecialchars($article['id']); ?>">📝 Edit</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        &nbsp;<a class="btn btn-outline-danger"
                                 href="delete-category.php?id=<?= htmlspecialchars($article['id']); ?>">🗑
                            Löschen</a>
                    <?php endif; ?>
                </h4>
            </article>
            <br>
        <?php endforeach; ?>
    <?php else: ?>
        <!--        <script>-->
        <!--            window.location.href = "../error/404.html.php";-->
        <!--        </script>-->
    <?php endif; ?>
    <a href="categories.php" class="btn btn-primary mr-2">🔙 Back to categories page</a>
</main>

<?php include '_pagination.html.php'; ?>
<?php include '_footer.html.php'; ?>
