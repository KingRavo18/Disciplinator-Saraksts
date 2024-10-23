<!DOCTYPE html>
<html lang="lv">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../Style/registration.css">
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <title>Disciplinators</title>
    </head>
    <body>
        <main>
            <?php 
                require "featureSidebar.php"; 
                require "../ForgotPassword/forgot-password.php";
            ?> 
            <div class="OpenFeatures"><button onclick="FeatureSidebar()">Lapas Funkcijas</button></div>
            <div class="PageTitle"><h1>DISCIPLINATORS</h1></div>
            <div class="InputWindows">
                <div class="Explanation"><p>Šī mājaslapa piedāvā lietotājam ērtu pieju dažādiem sarakstiem vienā mājaslapā.</p></div>  
                <div class="Registration">
                    <div class="RegistrationTitle"><h2>PIESLĒGTIES</h2></div>
                    <form action="login.php" method="POST">
                        <div class="RegistrationInput">
                            <input type="text" placeholder="Lietotājvārds" name="username" required>
                            <div class="RegistrationInputPassword">
                                <input type="password" id="password-1" placeholder=" Parole" name="password" required>
                                <i id="visibilityBtn-1" title="parādīt/paslēpt paroli"><span id="icon-1" class="material-symbols-outlined">visibility</span></i>
                            </div>
                            <div class="RegistrationLink"><a onclick="ShowForgotPasswordArea()">Aizmirstāt Paroli?</a></div>
                        </div>
                        <div id="ErrorArea1">
                            <?php
                                if (isset($_GET['login_error'])) {
                                    echo '<p>' . htmlspecialchars($_GET['login_error']) . '</p>';
                                }
                            ?>
                        </div>  
                        <div class="RegistrationButton">
                            <button>Pieslēgties</button>
                        </div>
                    </form>
                </div>
                <div class="Registration">
                    <div class="RegistrationTitle"><h2>REĢISTRĒTIES</h2></div>
                        <form action="signup.php" method="POST">
                        <div class="RegistrationInput">
                            <input type="text" placeholder=" Lietotājvārds" name="username" title="Ievadiet lietotājvārdu" required>
                            <input type="email" placeholder=" E-pasts" name="email" title="Ievadiet e-pastu" required>
                            <div class="RegistrationInputPassword">
                                <input type="password" id="password-2" placeholder=" Parole" name="password" title="Ievadiet paroli" required>
                                <i id="visibilityBtn-2" title="parādīt/paslēpt paroli"><span id="icon-2" class="material-symbols-outlined">visibility</span></i>
                            </div>
                        </div>
                        <div id="ErrorArea2">
                            <?php 
                                if (isset($_GET['signup_error'])) {
                                    echo '<p>' . htmlspecialchars($_GET['signup_error']) . '</p>';
                                }
                            ?>
                        </div>
                        <div class="RegistrationButton">
                            <button>Reģistrēties</button>
                        </div>
                    </form>
                </div>
            </div>
            <script src="registration.js"></script>
        </main>
        <div class="footer">
            <p>Suggested &copy; 2024 Disciplinators | Versija 1.0.0</p>
        </div>
    </body>
</html>