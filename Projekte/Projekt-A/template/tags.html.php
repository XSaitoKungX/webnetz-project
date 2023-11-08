<?php
include '_header.html.php';
include '_hero.html.php';
?>

<div class="container">
    <h2 class="section-title">Tags</h2>
    <ul class="tag-list">
        <?php foreach ($allTags as $tag): ?>
            <li><a href="tag.php?tag=<?= urlencode($tag['tag']) ?>"><?= $tag['tag'] ?></a></li>
        <?php endforeach; ?>
    </ul>

    <a class="btn btn-danger btn-block"
       href="index.php">Abbrechen
    </a>
</div>

<?php include '_footer.html.php'; ?>
