<?php require_once __DIR__ . '/../../app/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ERROR - 404</title>
    <link type="text/css" rel="stylesheet" href="404.css"/>
</head>

<body class="permission_denied">
<div id="tsparticles"></div>
<div class="denied__wrapper">
    <h1>ERROR - 404</h1>
    <h3>LOST CONNECT <span>Webnetz</span> Hmm, looks like that page doesn't exist.</h3>
    <div class="session-message">
        <?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        } ?>
    </div>
    <img id="astronaut" src="astronaut.svg" alt="logo"/>
    <img id="planet" src="planet.svg" alt="logo"/>
    <a href="/index.php">
        <button class="denied__link">Go Home</button>
    </a>
</div>

<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/tsparticles@2.3.4/tsparticles.bundle.min.js"></script>
<script type="text/javascript" src="404.js"></script>

</html>
