<?php
require_once '../app/mysql.php';
require_once '../app/functions.php';
include('_header.html.php');
include('_hero.html.php');
?>

<div class="container">
    <h1>Vielen Dank für Ihren Besuch auf unserer Website</h1>
    <h5><?= 'Lass eine' . '<a href="https://www.web-netz.de/kontakt/">' . ' Rückmeldung da' . '</a>' . ' damit wir Sie viel besser bedienen können' ?></h5>
    <br>
    <h5><?= 'Sie wurden erfolgreich ausgeloggt!'; ?></h5>
    <br>

    <!--Yeah! We navigate back to Home page of the site-->
    <a class="btn btn-danger" href="index.php"><i class="fas fa-times"></i> Home</a>

</div>

<?php include '_footer.html.php'; ?>
