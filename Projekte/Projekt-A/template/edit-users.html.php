<?php
include '_header.html.php';
include '_hero.html.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Startseite</a></li>
        <li class="breadcrumb-item active" aria-current="page">Benutzerverwalten</li>
    </ol>
</nav>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li><a href="create-user.php" class="btn btn-primary">Neuen Nutzer anlegen</a></li>
    </ol>
</nav>

<div class="container-comments">
    <table class="table-comments">
        <h2 class="section-title">Nutzerverwalten</h2>
        <thead>
        <tr>
            <th>ID</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Email</th>
            <th>Nutzername</th>
            <th>Zuletzt geändert</th>
            <th>Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($allUsers as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['first_name'] ?></td>
                <td><?= $user['last_name'] ?></td>
                <td><strong><?= $user['email'] ?></strong></td>
                <td><a href="edit-user.php?id=<?= $user['id'] ?>"><?= $user['username'] ?></a></td>
                <td><?= $user['last_change_date'] ?></td>
                <td>
                    <a class="btn btn-info btn-block" href="edit-user.php?id=<?= $user['id'] ?>"><i
                            class="fas fa-edit"></i> Editieren</a>
                    <a class="btn btn-danger btn-block" href="delete-user.php?id=<?= $user['id'] ?>"><i
                            class="fas fa-trash"></i> Löschen</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div>
    <a href="index.php" class="btn btn-primary">Zurück zur Startseite</a>
    </div>
</div>

<?php include '_footer.html.php'; ?>
