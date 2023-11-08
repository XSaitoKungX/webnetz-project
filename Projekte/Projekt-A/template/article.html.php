<?php include '_header.html.php'; ?>
<?php include 'navigation.php'; ?>
<?php include '_hero.html.php'; ?>

<main class="p-3 mb-2 bg-transparent">
    <h2>Artikel: <?= htmlspecialchars($article['title']); ?></h2>
    <br>
    <?php if (!empty($article)): ?>
        <article class="card">
            <h3>
                <?= htmlspecialchars($article['title']); ?>
            </h3>
            <?php
            if (!empty($article['tags'])) {
                echo '<p><strong>Tags: </strong>';
                $tags = explode(' ', $article['tags']);

                foreach ($tags as $tag) {
                    echo '<span>#' . htmlspecialchars($tag) . ' </span>';
                }

                echo '</p>';
            }
            ?>
            <p>
                <i>
                    <?= '(Erstellt am ' . htmlspecialchars($article['date']) . ')' ?>
                    <?php if (isset($article['username'])): ?>
                        - Bearbeitet von
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $article['username']): ?>
                            <strong><u><a
                                        href="profile.php"><?= htmlspecialchars($article['username']) ?></a></u></strong>
                        <?php else: ?>
                            <a href="profile.php?id=<?= urlencode($article['username']) ?>"><?= htmlspecialchars($article['username']) ?></a>
                        <?php endif; ?>
                        am <?= htmlspecialchars($article['last_change_date']); ?>
                    <?php endif; ?>
                </i>
            </p>
            <p style="white-space: pre-line;">
                <?= htmlspecialchars($article['content']); ?>
            </p>
            <div class="file-list">
                <?php if (!empty($imageFiles)) : ?>
                    <h4>Bilder</h4>
                    <?php foreach ($imageFiles as $imageFile): ?>
                        <?php
                        // Überprüfe, ob das aktuelle Bild die gewünschte Größe hat
                        if (str_contains($imageFile, '1024x578')) {
                            $imageSrc = $uploadDir . '/' . $imageFile;
                            $imageAlt = $imageFile;
                            ?>

                            <div class="flex-container">
                                <div class="image-container">
                                    <a href="<?= $imageSrc ?>" data-lightbox="image-gallery" target="_blank">
                                        <img class="image-preview"
                                             src="<?= $imageSrc ?>"
                                             alt="<?= $imageAlt ?>"
                                             title="<?= $imageAlt ?>">
                                    </a>
                                </div>
                            </div>

                        <?php } ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if (!empty($fileFiles)) : ?>
                    <h4>Dateien</h4>
                    <?php foreach ($fileFiles as $file): ?>
                        <div class="">
                            <a href="<?= $uploadDir . '/' . $file ?>" download class="btn btn-success"><?= $file; ?></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <h4>
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
                    <br><br>

                    <div class="share-buttons">
                        <a href="<?= $shareLinks['twitter']; ?>" target="_blank" class="btn btn-primary">
                            <i class="fab fa-twitter"></i> Auf Twitter teilen
                        </a>
                        <a href="<?= $shareLinks['facebook']; ?>" target="_blank" class="btn btn-primary">
                            <i class="fab fa-facebook"></i> Auf Facebook teilen
                        </a>
                        <a href="<?= $shareLinks['email']; ?>" target="_blank" class="btn btn-primary">
                            <i class="fas fa-envelope"></i> Per E-Mail teilen
                        </a>
                    </div>

                </h4>
            </div>
        </article>
        <br>
    <?php else: ?>

    <?php endif; ?>

    <!-- Anzeige der Kommentare -->
    <div class="container contact-body">
        <h1 class="display-4">
            <span><i class="fas fa-mail-bulk"></i> Kommentare</span>
        </h1>
        <p><a href="#commentForm" class="btn btn-primary">Zum Kommentarformular scrollen</a></p>
        <?php if (!empty($comments)): // Überprüfen, ob $comments nicht leer ist ?>
            <p>Zu dem Artikel <span><u><?= htmlspecialchars($article['title']); ?></u></span> gibt
                es <?= count($comments); ?> Kommentare</p>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-avatar">
                        <!-- Display Gravatar image here -->
                        <img src="<?= get_gravatar($comment['email'], 50); ?>" alt="Gravatar" class="rounded-circle">
                    </div>
                    <div class="comment-details">
                        <p class="custom-font-comments">Von: <?= htmlspecialchars($comment['name']); ?></p>
                        <p class="custom-font-comments">Email:
                            <strong><?= htmlspecialchars($comment['email']); ?></strong></p>
                        <p class="custom-font-comments">
                            Kommentar: <?= htmlspecialchars($comment['comment_text']); ?></p>
                        <p class="custom-font-comments">Verfasst
                            am: <?= htmlspecialchars($comment['comment_date']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h2>Es gibt keine Kommentare zu diesem Artikel.</h2>
        <?php endif; ?>
    </div>


    <!-- Kommentarformular -->
    <div class="container contact-body">
        <h1 class="display-4">
            <span><i class="fas fa-pencil-alt"></i> Kommentar</span>
        </h1>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <p class="success"><?= $success_message ?></p>
            <a href="article.php?id=<?= htmlspecialchars($article['id']); ?>" class="btn btn-primary"><i
                    class="fas fa-spinner fa-spin"></i>Artikel neu laden..</a>
        <?php else: ?>
            <form id="commentForm" action="" method="post">
                <input type="hidden" name="article_id" value="<?= $articleId ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">E-Mail:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Kommentar:</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" id="show-form-button" class="btn btn-primary">Kommentar abschicken</button>
                <a href="index.php" class="btn btn-danger" style="">Abbrechen</a>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include 'contact.html.php'; ?>
<?php include '_aside.html.php'; ?>
<?php include '_footer.html.php'; ?>
