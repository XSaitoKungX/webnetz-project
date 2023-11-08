<!-- content here -->
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<aside class="sidebar">

    <div class="sidebar-section">
        <h3>Neueste Artikel</h3>
        <ul>
            <li><a href="article.php?id=1">ChatGPT</a></li>
            <li><a href="article.php?id=2">Valorant</a></li>
            <li><a href="article.php?id=7">Discord</a></li>
        </ul>
    </div>

    <div class="sidebar-section">
        <h3>Beliebte Kategorien</h3>
        <ul>
            <li><a href="category.php?id=1">Artificial Intelligence</a></li>
            <li><a href="category.php?id=2">Programmieren</a></li>
            <li><a href="category.php?id=3">Gaming</a></li>
        </ul>
    </div>

    <div class="sidebar-section">
        <h3>Tags</h3>
        <ul>
            <?php
            if (!empty($article['tags'])) {
                $tags = explode(' ', $article['tags']);

                foreach ($tags as $tag) {
                    echo '<li><a href="tag.php?tag=' . urlencode($tag) . '">#' . htmlspecialchars($tag) . '</a></li>';
                }
            }
            ?>
            <li><strong><a href="tags.php">All Tags</a></strong></li>
        </ul>
    </div>

    <!-- Weitere Elemente für die Seitenleiste können hier hinzugefügt werden -->
</aside>
