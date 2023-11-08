<?php
include '_header.html.php';
include '_hero.html.php';
?>

<div class="container">
    <h2 class="section-title">Artikel für Tag: <?= $tag ?></h2>
    <ul class="article-list">
        <?php foreach ($taggedArticles as $article): ?>
            <li>
                <a>
                    <a href="article.php?id=<?= $article['id'] ?>"
                    <h3><?= $article['title'] ?></h3>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include '_footer.html.php'; ?>
