<?php
include 'amp/_header.amp.php';
include 'navigation.php';
include 'amp/_hero.amp.php';
?>

<section class="p-3 mb-2 bg-transparent">
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <br>
    <div class="textRight">
        <a class="btn btn-success" href="create-article.php">
            <i class="fas fa-plus"></i> Erstellen
        </a>
    </div>
    <br>
    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <article class="card">
                <amp-img src="" alt="An image" width="300" height="200"></amp-img>
                <br>
                <h3>
                    <a href="article.php?id=<?= htmlspecialchars($article['id']); ?>"><?= htmlspecialchars($article['title']); ?></a>
                </h3>
                <p>
                    <i>
                        <?= '(Erstellt am ' . htmlspecialchars($article['date']) . ')' ?>
                        <?php if (isset($article['username'])): ?>
                            - Bearbeitet von
                            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $article['username']): ?>
                                <strong><u><a href="profile.php"><?= htmlspecialchars($article['username']) ?></a></u></strong>
                            <?php else: ?>
                                <span class="username"><?= htmlspecialchars($article['username']) ?></span>
                            <?php endif; ?>
                            am <?= htmlspecialchars($article['last_change_date']); ?>
                        <?php endif; ?>
                    </i>
                </p>
                <p><?= htmlspecialchars($article['description']); ?></p>
                <h4>
                    &nbsp;<a class="btn btn-outline-info"
                             href="article.php?id=<?= htmlspecialchars($article['id']); ?>">
                        <i class="fas fa-newspaper"></i> Weiterlesen..
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        &nbsp;<a class="btn btn-outline-warning"
                                 href="edit-article.php?id=<?= htmlspecialchars($article['id']); ?>">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        &nbsp;<a class="btn btn-outline-danger"
                                 href="delete-article.php?id=<?= htmlspecialchars($article['id']); ?>">
                            <i class="fas fa-trash"></i> Löschen
                        </a>
                    <?php endif; ?>
                </h4>
            </article><br>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Hier der Code für den Fall, dass keine Artikel vorhanden sind -->
    <?php endif; ?>
</section>

<?php include 'amp/_pagination.amp.php'; ?>
<?php include 'amp/_footer.amp.php'; ?>
