<?php include '_header.html.php'; ?>

<div class="container">
    <h1 class="display-4">
        <span><i class="fas fa-envelope"></i> Kontakt</span>
    </h1>
    <?php if (isset($error_message) && !empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p class="success">Vielen Dank! Ihre E-Mail wurde erfolgreich gesendet.</p>
        <a href="index.php" class="btn btn-primary">Zurück zur Homepage</a>
    <?php else: ?>
        <form method="post" name="contact" id="contact-form" enctype="multipart/form-data" action="contact.php" onsubmit="return showConfirmation()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Max Mustermann" required>

            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required>

            <label for="subject">Betreff:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Nachricht:</label>
            <textarea id="message" name="message" rows="4" placeholder="Schreib dein Anliegen..." required></textarea>

            <?php if (!empty($articleId)): ?>
                <input type="hidden" name="article_id" value="<?= htmlspecialchars($articleId); ?>">
            <?php endif; ?>

            <label for="captcha">Gib die folgenden Zeichen ein: </label>
            <div class="captcha-container">
                <img class="captcha-img" src="images/captcha/captcha.png" alt="Captcha Image">
            </div>
            <input type="text" name="captcha" placeholder="Captcha eingeben" required>

            <div id="honey-pot-container">
                <label name="honey" id="honey-pot-message" for="honey-pot-message">Bitte nicht ausfüllen:</label>
                <input type="text" id="bot_check" name="bot_check" style="display: none;">
            </div>

            <button class="btn btn-success" type="submit">Senden</button>
            <a href="index.php" class="btn btn-danger" style="">Abbrechen</a>
        </form>
        <div class="contact-icons">
            <a href="https://www.discordapp.com/users/848917797501141052" target="_blank" class="discord" title="Discord">
                <i class="fab fa-discord"></i>
            </a>
            <a href="https://github.com/XSaitoKungX" target="_blank" class="github" title="Github">
                <i class="fab fa-github"></i>
            </a>
            <a href="https://twitch.tv/XSaitoKungX" target="_blank" class="twitch" title="Twitch">
                <i class="fab fa-twitch"></i>
            </a>
            <a href="https://www.facebook.com/Markung.NP/" target="_blank" class="facebook" title="Facebook">
                <i class="fab fa-facebook"></i>
            </a>
        </div>
    <?php endif; ?>
</div>
