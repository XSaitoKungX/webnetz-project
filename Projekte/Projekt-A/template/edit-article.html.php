<?php
include '_header.html.php';
include "_hero.html.php";
?>

<?php if (isset($error_message)): ?>
    <div style="text-align: center" class="alert alert-danger"><?= $error_message ?></div>
<?php elseif (isset($success_message)): ?>
    <div style="text-align: center" class="alert alert-success"><?= $success_message ?></div>
<?php endif; ?>
<br>

<div class="container">
    <h2>Artikel Editor</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <br>
        <div class="form-group row">
            <label for="title" class="col-sm-3 col-form-label d-flex align-items-center">Titel:</label>
            <div class="col-sm-9">
                <input type="text" name="title" id="title" class="form-control-edit"
                       value="<?php echo $article['title']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="description" class="col-sm-3 col-form-label d-flex align-items-center">Kurze
                Beschreibung:</label>
            <div class="col-sm-9">
                <input type="text" name="description" id="description" class="form-control-edit"
                       value="<?php echo $article['description']; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="content" class="col-sm-3 col-form-label d-flex align-items-center">Inhalt:</label>
            <div class="col-sm-9">
                <textarea name="content" class="form-control-edit" rows="8"
                          required><?php echo $article['content']; ?></textarea>
            </div>
        </div>
        <!-- Display current image -->
        <div class="form-group row">
            <label class="col-sm-3 col-form-label d-flex align-items-center">Aktuelle Dateien:</label>
            <div class="col-sm-9">
                <div class="file-list">
                    <?php if (!empty($imageFiles) || !empty($fileFiles)) : ?>
                        <div class="file-container">
                            <?php if (!empty($imageFiles)) : ?>
                                <div class="file-group">
                                    <h5>Bilder:</h5>
                                    <?php
                                    foreach ($imageFiles as $file) : ?>
                                        <?php
                                        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $filePath = "delete-file.php?filename=" . basename($file) . "&id=" . $article['id'];
                                        $originalFilePattern = "/^(.+)_\d+x\d+\.$fileExtension$/";

                                        // Überprüfe, ob der Dateiname dem Muster eines skalierten Bildes entspricht
                                        $isOriginalImage = !preg_match($originalFilePattern, basename($file));

                                        ?>

                                        <?php if ($isOriginalImage) : ?>
                                            <div class="file-item">
                                                <p><?= $file ?>&nbsp;<a href="<?= $filePath ?>">Löschen</a></p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($fileFiles)) : ?>
                                <div class="file-group">
                                    <h5>Andere Dateien:</h5>
                                    <?php foreach ($fileFiles as $file) : ?>
                                        <?php
                                        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $filePath = "delete-file.php?filename=" . basename($file) . "&id=" . $article['id'];
                                        ?>

                                        <div class="file-item">
                                            <p><?= $file ?>&nbsp;<a href="<?= $filePath ?>">Löschen</a></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <p>Keine Dateien vorhanden</p>
                    <?php endif; ?>

                    <?php if (!empty($success_message)) : ?>
                        <div class="alert alert-success"><?= $success_message ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <small class="form-text text-muted">Erlaubte Dateitypen: gif, jpg, jpeg, png, pdf, txt.</small>
        <label for="image"><i class="fas fa-image"></i> Bild ändern:</label>
        <input type="file" id="image" name="image" accept="image/gif,image/png,image/jpeg">
        <br><br>

        <label for="tags">Tags (Mehrere auswählen):</label>
        <div class="tag-columns">
            <?php
            $numColumns = 5;
            $tagsPerColumn = ceil(count($getTags) / $numColumns);

            // Iteriere über die Spalten
            for ($i = 0; $i < $numColumns; $i++) {
                echo '<div style="flex: 1; padding: 10px;">';

                // Iteriere über die Tags in dieser Spalte
                for ($j = $i * $tagsPerColumn; $j < min(($i + 1) * $tagsPerColumn, count($getTags)); $j++) {
                    $tag = htmlspecialchars($getTags[$j]);
                    $checked = in_array($tag, $articleTags) ? 'checked' : '';

                    echo '<label><input type="checkbox" name="tags[]" value="' . $tag . '" ' . $checked . '/> ' . $tag . '</label><br>';
                }

                echo '</div>';
            }
            ?>
        </div>

        <input class="btn btn-success btn-block" type="submit" name="submit_type" value="Speichern">
        <a class="btn btn-danger btn-block"
           href="article.php?id=<?php echo $articleId; ?>">Abbrechen
        </a>
    </form>
    <br><br>
</div>

<?php include '_footer.html.php'; ?>
