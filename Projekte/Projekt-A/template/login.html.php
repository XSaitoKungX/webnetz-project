<?php
require_once '../app/mysql.php';
require_once '../app/functions.php';
include('_header.html.php');
include('_hero.html.php');
?>

<link href='css/login_style.css' rel='stylesheet' type='text/css'>

<!--<a href="https://www.web-netz.de"><img src="/images/web-netz.png"></a> Site Logo-->
<div id="full-body"> <!--Site background-->

    <div class="container-login" id="container">
        <ul class="tab-group">
            <li class="tab"><a href="#signup">Sign Up</a></li>
            <li class="tab active"><a href="#login">Log In</a></li>
        </ul>

        <div class="tab-content login-box">

            <!--Start Login Form-->
            <div id="login">
                <h1>WILLKOMMEN!</h1>
                <!-- Fehlermeldung anzeigen, wenn vorhanden -->
                <?php if (!empty($errorMessage)) : ?>
                    <h5 class="error-message"><?php echo $errorMessage; ?></h5>
                    <?php unset($_SESSION['error_message']); // Fehlermeldung aus der Session entfernen ?>
                <?php endif; ?>
                <br>

                <!--Launching Login Form-->
                <form class="login" action="login.php" method="post" autocomplete="off"
                      oninput="toggleLabel(this)">
                    <div class="field-wrap">
                        <label>
                            <span name="require1" id="require1" class="require1">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name="login_input" oninput="toggleLabel(this)"
                               placeholder="Username or Email"/>
                    </div>

                    <div class="field-wrap">
                        <label>
                            <span name="require2" id="require2" class="require2">*</span>
                        </label>
                        <input type="password" required autocomplete="off" name="password" id="togglePassword"
                               oninput="toggleLabel(this)" placeholder="Passwort"/>
                    </div>

                    <p class="forgot"><a href="/request-reset.php" target="_blank">Passwort vergessen?</a></p>

                    <button class="button button-block" name="login">Log In</button>
                    <span class="login-separator"></span>
                    <button class="button button-block" name="home" onclick="window.location.href='/index.php'">
                        Home
                    </button>
                </form>
                <!--Terminating Login Form-->

            </div>
            <!--End Login Form-->


            <!--Note: Das Anmeldeformular wird online geladen, da die Cloudfare-Datei Jquery.min.js online geladen wird-->
            <!--Start SignUp Form-->
            <div id="signup">
                <h1>Konto erstellen</h1>
                <?php if (!empty($registrationStatus['errorMessage'])) : ?>
                    <h5 class="error-message"><?php echo $registrationStatus['errorMessage']; ?></h5>
                    <?php unset($_SESSION['error_message']); // Fehlermeldung aus der Session entfernen ?>
                <?php endif; ?>
                <br>

                <!--Launching SignUp Form-->
                <form class="login" action="login.php" method="post" autocomplete="off">

                    <div class="top-row">
                        <div class="field-wrap">
                            <label>
                                Vorname<span class="req">*</span>
                            </label>
                            <input type="text" required autocomplete="off" name='firstname' oninput="toggleLabel(this)">
                        </div>

                        <div class="field-wrap">
                            <label>
                                Nachname
                            </label>
                            <input type="text" autocomplete="off" name='lastname' oninput="toggleLabel(this)">
                        </div>
                    </div>

                    <div class="field-wrap">
                        <label>
                            Benutzername<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name='username' oninput="toggleLabel(this)">
                    </div>

                    <div class="field-wrap">
                        <label>
                            Email Address<span class="req">*</span>
                        </label>
                        <input type="email" required autocomplete="off" name='email' oninput="toggleLabel(this)">
                    </div>

                    <div class="field-wrap">
                        <label>
                            Passwort<span class="req">*</span>
                        </label>
                        <input type="password" name="password" required autocomplete="off" id="passwordInput"
                               oninput="toggleLabel(this)">
                    </div>
                    <span class="password-toggle" onclick="togglePasswordVisibility()">Show Password</span>

                    <br>
                    <button type="submit" class="button button-block" name="register">Sign Up</button>
                    <span class="login-separator"></span>
                    <button class="button button-block" name="home" onclick="window.location.href='/index.php'">
                        Home
                    </button>

                </form>
                <!--Terminating SignUp Form-->

            </div>
            <!--End SignUp Form-->


        </div><!-- tab-content -->

    </div> <!-- /form -->


</div>

<script>
    function toggleLabel(inputElement) {
        const labelElement = inputElement.previousElementSibling;
        labelElement.style.display = inputElement.value ? "none" : "block";
    }

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("passwordInput");
        const passwordToggle = document.querySelector(".password-toggle");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordToggle.textContent = "Hide Password";
        } else {
            passwordInput.type = "password";
            passwordToggle.textContent = "Show Password";
        }
    }
</script>

<!--Load Cloudflare jquery.min.js online-->
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<!--Load index.js from the resource folder-->
<script type="text/javascript" src="javascript/app.js"></script>


<?php include '_footer.html.php'; ?>

