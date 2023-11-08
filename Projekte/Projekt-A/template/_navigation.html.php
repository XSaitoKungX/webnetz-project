<?php
require_once '../app/functions.php';
sessionManager(false);
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarExample01"
                aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarExample01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item active">
                    <a class="btn btn-custom-info" aria-current="page" href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                &nbsp;
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle btn btn-custom-info" id="navbarDropdown" role="button"
                       data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Kategorien
                    </a>
                    <div class="dropdown-menu btn btn-light" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="categories.php"><i class="fas fa-newspaper"></i> Alle Kategorien anzeigen</a>
                        <?php if (isLoggedIn()) : ?>
                            <a class="dropdown-item" href="test.php">Testseite</a>
                        <?php endif; ?>
                        <hr class="dropdown-divider">
                        <?php foreach ($navbarCategories as $navbarCategory): ?>
                            <a class="dropdown-item"
                               href="category.php?id=<?= $navbarCategory['id'] ?>"><?= $navbarCategory['title'] ?></a>
                        <?php endforeach; ?>
                    </div>
                </li>
            </ul>

            <!-- Suchformular (zentriert) -->
            <div class="d-flex justify-content-center"> <!-- Hinzugefügt -->
                <!-- Suchformular (zentriert) -->
                <form class="form-inline" action="search.php" method="GET">
                    <div class="input-group">
                        <input class="form-control custom-search" type="search" name="search" placeholder="Search.." aria-label="Search" value="<?php echo $_GET['search'] ?? ''; ?>">
                        <select class="form-select custom-select" name="searchCategory" id="searchCategory">
                            <option value="all" disabled selected style="display: none;">Keine Auswahl</option>
                            <?php foreach ($navbarCategories as $navbarCategory): ?>
                                <option value="<?= $navbarCategory['id'] ?>"><?= $navbarCategory['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="custom-search-btn" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Link for "Nutzer" only for logged-in administrators -->
            <?php if (isLoggedIn() && $isAdmin) : ?>
                <div class="d-flex justify-content-start position-relative">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="adminDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Verwaltungen
                    </button>
                    <div class="dropdown-menu" aria-labelledby="adminDropdown"
                         style="position: absolute; top: 100%; left: 0; min-width: 100%;">
                        <a class="dropdown-item" href="edit-users.php"><i class="fas fa-user-edit"></i> Nutzerverwaltung</a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="edit-articles.php"><i class="fas fa-edit"></i> Artikelverwaltung</a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="edit-categories.php"><i class="fas fa-edit"></i> Kategorieverwaltung</a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="edit-comments.php"><i class="fas fa-comments"></i> Kommentarverwaltung</a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="edit-tags.php"><i class="fas fa-tags"></i> Tagverwaltung</a>
                    </div>
                </div>
            <?php endif; ?>
            &nbsp;

            <div class="d-flex justify-content-start position-relative">
                <button class="btn btn-outline-success dropdown-toggle auth-dropdown auth-dropdown-long" type="button"
                        id="authDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo isLoggedIn() ? '<i class="fas fa-user-check"></i> Willkommen ' . $_SESSION['username'] : '<i class="fas fa-user-alt"></i> Gast'; ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="authDropdown"
                     style="position: absolute; top: 100%; left: 0; min-width: 100%;">
                    <?php if (isLoggedIn()) : ?>
                        <a class="dropdown-item btn btn-outline-info"
                           href="profile.php<?= isset($_SESSION['user_id']) ? '?id=' . htmlspecialchars($_SESSION['user_id']) : ''; ?>"><i class="fas fa-user"></i> Profil</a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item btn btn-outline-danger" href="logout.php"><i class='fas fa-sign-out-alt' style='font-size:22px;color:red'></i> Abmelden</a>
                    <?php else : ?>
                        <a class="dropdown-item btn btn-outline-success" href="login.php"><i class='fas fa-sign-in-alt' style='font-size:22px;color:green'></i> Anmelden</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar -->
