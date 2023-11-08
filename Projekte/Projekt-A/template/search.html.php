<?php
include '_header.html.php';
include 'navigation.php';
include '_hero.html.php';
?>

<main class="p-3 mb-2 bg-transparent">
    <h2>Search Results</h2>
    <br>

    <form class="form-inline" action="search.php" method="GET">
        <div class="input-group">
            <input class="form-control custom-search" type="search" name="search" placeholder="Search.." aria-label="Search" value="<?php echo $_GET['search'] ?? ''; ?>">
            <select class="form-select custom-select" name="searchCategory" id="searchCategory">
                <option value="all" disabled selected style="display: none;"><i class="fas fa-do-not-enter fa-spin" style="color: #ed121d;"></i>Keine Auswahl<i class="fas fa-do-not-enter fa-spin" style="color: #ed121d;"></i></option>
                <?php foreach ($navbarCategories as $navbarCategory): ?>
                    <option value="<?= $navbarCategory['id'] ?>"><?= $navbarCategory['title'] ?></option>
                <?php endforeach; ?>
            </select>
            <button class="custom-search-btn" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <br>
    <?php if (isset($_GET['search'])): ?>
        <?php if (!empty($articles) || !empty($categories)): ?>
            <br>
            <?php if (!empty($articles)): ?>
                <h3>Articles</h3>
                <br>
                <?php foreach ($articles as $article): ?>
                    <?php displayCard($article['id'], $article['title'], $article['date'], $article['description']); ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($categories)): ?>
                <h3>Categories</h3>
                <br>
                <?php foreach ($categories as $category): ?>
                    <?php displayCard($category['id'], $category['title'], $category['date'], $category['description']); ?>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php else: ?>
            <h1>No results found!</h1>
        <?php endif; ?>

    <?php endif; ?>

</main>

<?php include '_pagination.html.php'; ?>
<?php include '_footer.html.php'; ?>
