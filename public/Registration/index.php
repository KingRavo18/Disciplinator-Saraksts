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
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <title>Disciplinators</title>
    </head>
    <body>
        <main>
            <?php 
                require "featureSidebar.php"; 
                require "terms&conditions.php"; 
            ?> 
            <div class="openFeatures"><button onclick="FeatureSidebar()">Lapas Funkcijas</button></div>
            <div class="pageTitle"><h1>DISCIPLINATORS</h1></div>
            <div class="inputWindows">
                <div class="explanation"><p>Šī mājaslapa piedāvā lietotājam ērtu pieju dažādiem sarakstiem vienā mājaslapā.</p></div>  
                <div class="registration">
                    <div class="registrationTitle"><h2>PIESLĒGTIES</h2></div>
                    <form action="login.php" method="POST">
                        <div class="registrationInput">
                            <input type="text" placeholder=" Lietotājvārds" name="username" title="Ievadiet lietotājvārdu" required>
                            <div class="registrationInputPassword">
                                <input type="password" id="password-1" placeholder=" Parole" name="password" title="Ievadiet paroli" required>
                                <i id="visibilityBtn-1" title="Parādīt/paslēpt paroli"><span id="icon-1" class="material-symbols-outlined">visibility</span></i>
                            </div>
                            <div id="errorArea">
                                <?php
                                    if (isset($_GET['login_error'])) {
                                        echo '<p>' . htmlspecialchars($_GET['login_error']) . '</p>';
                                    }
                                ?>
                            </div>  
                        </div>
                        <div class="registrationButton">
                            <button>Pieslēgties</button>
                        </div>
                    </form>
                </div>
                <div class="registration">
                    <div class="registrationTitle"><h2>REĢISTRĒTIES</h2></div>
                        <form action="signup.php" method="POST">
                        <div class="registrationInput">
                            <input type="text" placeholder=" Lietotājvārds" name="username" title="Ievadiet lietotājvārdu" required>
                            <input type="email" placeholder=" E-pasts" name="email" title="Ievadiet e-pastu" required>
                            <div class="registrationInputPassword2">
                                <input type="password" id="password-2" placeholder=" Parole" name="password" title="Ievadiet paroli" required>
                                <i id="visibilityBtn-2" title="Parādīt/paslēpt paroli"><span id="icon-2" class="material-symbols-outlined">visibility</span></i>
                            </div>
                        </div>
                        <div id="errorArea">
                            <?php 
                                if (isset($_GET['signup_error'])) {
                                    echo '<p>' . htmlspecialchars($_GET['signup_error']) . '</p>';
                                }
                            ?>
                        </div>
                        <div class="registrationButton">
                            <button>Reģistrēties</button>
                        </div>
                    </form>
                </div>
            </div>
            <script src="registration.js"></script>
        </main>
    </body>
</html>