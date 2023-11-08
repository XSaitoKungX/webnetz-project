<?php include '_header.html.php'; ?>
<?php include 'navigation.php'; ?>
<?php include '_hero.html.php'; ?>

<?php if (!empty($error_message)) : ?>
    <div style="text-align: center" class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>
<br>

<div class="container">
    <h2>Neuen Artikel erstellen</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <br>
        <label for="title">Titel:</label>
        <input type="text" name="title" id="title" required>
        <br><br>

        <label for="description">Kurze Beschreibung:</label>
        <textarea name="description" class="custom-textarea" rows="8" cols="50" required></textarea>
        <br><br>

        <label for="content">Inhalt:</label>
        <textarea name="content" class="custom-textarea" rows="8" cols="50" required></textarea>
        <br><br>

        <label for="category">Kategorie:</label>
        <select name="category_id">
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['title']); ?></option>
            <?php endforeach; ?>
        </select>
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
                    echo '<label><input type="checkbox" name="tags[]" value="' . htmlspecialchars($getTags[$j]) . '"/> ' . htmlspecialchars($getTags[$j]) . '</label><br>';
                }

                echo '</div>';
            }
            ?>
        </div>

        <label for="image">Bild hochladen:</label>
        <input type="file" name="image">
        <br><br>

        <input class="btn btn-success btn-block" type="submit" value="Erstellen">
        <a class="btn btn-danger btn-block" href="index.php">Abbrechen</a>
    </form>
    <br><br>
</div>

<?php include '_footer.html.php'; ?>
