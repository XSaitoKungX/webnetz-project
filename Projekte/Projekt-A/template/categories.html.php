<?php include '_header.html.php'; ?>
<?php include 'navigation.php'; ?>
<?php include '_hero.html.php'; ?>

<section class="p-3 mb-2 bg-transparent">
    <h2>Alle Kategorien</h2>
    <br>
    <div class="textRight">
        <a class="btn btn-success" href="create-category.php">➕ Erstellen</a>
    </div>
    <br>
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <article class="card">
                <h3>
                    <a href="category.php?id=<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['title']); ?></a>
                </h3>
                <p>
                    <i>
                        <?= '(Erstellt am ' . htmlspecialchars($category['date']) . ')'; ?>
                        <?php if (isset($category['username'])): ?>
                            - Bearbeitet von
                            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $category['username']): ?>
                                <strong><u><a href="profile.php"><?= htmlspecialchars($category['username']) ?></a></u></strong>
                            <?php else: ?>
                                <a href="profile.php?id=<?= urlencode($category['username']) ?>"><?= htmlspecialchars($category['username']) ?></a>
                            <?php endif; ?>
                            am <?= htmlspecialchars($category['last_change_date']); ?>
                        <?php endif; ?>
                    </i>
                </p>
                <p><?= htmlspecialchars($category['description']); ?></p>
                <h4>
                    &nbsp;
                    <a class="btn btn-outline-info"
                       href="category.php?id=<?= htmlspecialchars($category['id']); ?>">
                        <i class="fas fa-newspaper"></i> Weiterlesen..
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        &nbsp;<a class="btn btn-outline-warning"
                                 href="edit-category.php?id=<?= htmlspecialchars($category['id']); ?>"><i
                                class="fas fa-edit"></i> Edit</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        &nbsp;<a class="btn btn-outline-danger"
                                 href="delete-category.php?id=<?= htmlspecialchars($category['id']); ?>"><i
                                class="fas fa-trash"></i> Löschen</a>
                    <?php endif; ?>
                </h4>
            </article><br>
        <?php endforeach; ?>
    <?php else: ?>

    <?php endif; ?>
</section>


<?php include '_pagination.html.php'; ?>
<?php include '_footer.html.php'; ?>
