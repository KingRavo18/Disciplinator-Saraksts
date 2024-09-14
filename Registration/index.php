<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/registration.css">
    <link rel="icon" type="image/x-icon" href="../Images/FistLogoCut.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplinators</title>
</head>
<body>
    <main>
        <div id="OpenFeatures"><button onclick="FeatureSidebar()">Lapas Funkcijas</button></div>
        <div id="PageTitle"><h1>DISCIPLINATORS</h1></div>
        <div id="InputWindows">
            <div id="Explanation"><p>Informācija</p></div>
            <div class="Registration">
                <div class="RegistrationTitle"><h2>PIESLĒGTIES</h2></div>
                <form action="login.php" method="POST">
                    <div class="RegistrationInput">
                        <input type="text" placeholder="Lietotājvārds" name="username" required>
                        <div class="RegistrationInputPassword">
                            <input type="password" id="password-1" placeholder="Parole" name="password" required>
                            <i id="visibilityBtn-1"><span id="icon-1" class="material-symbols-outlined">visibility</span></i>
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
                    <div id="RegistrationButton1">
                        <button>Pieslēgties</button>
                    </div>
                </form>
            </div>
            <?php
                require "FeatureSidebar.php";
            ?> 
            <?php
                require "../forgotPassword/forgot-password.php";
            ?> 
            <div class="Registration">
                <div class="RegistrationTitle"><h2>REĢISTRĒTIES</h2></div>
                    <form action="signup.php" method="POST">
                    <div class="RegistrationInput">
                        <input type="text" placeholder="Lietotājvārds" name="username" required>
                        <input type="email" placeholder="E-pasts" name="email" required>
                        <div class="RegistrationInputPassword">
                            <input type="password" id="password-2" placeholder="Parole" name="password" required>
                            <i id="visibilityBtn-2"><span id="icon-2" class="material-symbols-outlined">visibility</span></i>
                        </div>
                    </div>
                    <div id="ErrorArea2">
                        <?php 
                            if (isset($_GET['signup_error'])) {
                                echo '<p>' . htmlspecialchars($_GET['signup_error']) . '</p>';
                            }
                        ?>
                    </div>
                    <div class="RegistrationButton2">
                        <button>Reģistrēties</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="registration.js"></script>
    </main>
    <div class="footer">
        <p>&copy; 2024 Disciplinators | Versija 1.0.0</p>
    </div>
</body>
</html>