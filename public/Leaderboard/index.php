<?php
session_start();
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../Style/leaderboard.css">
    <link rel="stylesheet" href="../Style/sidebar.css">
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title><?= $_SESSION['page_language'] === 'lv' ? 'Disciplinators - Līderu Saraksts' : 'Disciplinators - Leaderboard'; ?></title>
</head>
<body>
    <main>
        <div class="PageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $_SESSION['page_language'] === 'lv' ? 'LĪDERU SARAKSTS' : 'LEADERBOARD'; ?></h1>
        </div>
        <?php
            require "../Accesories/mainPageTopBar.php";
            require "../Accesories/sidebar.php";
        ?>
        <div class="leaderboardDiv">
            <div class="LeaderTable">
                <table>
                    <tr>
                        <th class="TableOne"><?= $_SESSION['page_language'] === 'lv' ? 'Vieta' : 'Place'; ?></th>
                        <th class="TableTwo"><?= $_SESSION['page_language'] === 'lv' ? 'Lietotājvārds' : 'Username'; ?></th>
                        <th class="TableThree"><?= $_SESSION['page_language'] === 'lv' ? 'Punktu Skaits' : 'Point Count'; ?></th>
                    </tr>
                <?php 
                    $place = 1;
                    if (!isset($_SESSION['user_id'])) {
                        die("You must be logged in to view the leaderboard.");
                    }
                    require "../../Database/database.php"; 
                    $sql = "SELECT username, points FROM users ORDER BY points DESC";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($leaderboardEntry = $result->fetch_assoc()) {
                        if (!$leaderboardEntry["username"]) {
                            die("There is an empty result. Execution has been halted.");
                        }
                ?>
                    <tr>
                        <td onclick="ShowUserArea()"><?= $place ?>.</td>
                        <td onclick="ShowUserArea()"><?= $leaderboardEntry["username"] ?></td>
                        <td onclick="ShowUserArea()"><?= $leaderboardEntry["points"] ?></td>
                    </tr>
                    <div id="UserFullArea">
                        <div id="UserArea">
                            <div id="UserTitle">
                                <h2>Vai esat pārliecināti?</h2>
                            </div>
                        </div>
                    </div>
                <?php 
                    $place++; 
                    }
                    $stmt->close();
                    $mysqli->close();
                ?>       
                </table>
            </div>
        </div>
        </div>
    </main>
</body>
<script>
    function ShowUserArea(){
        var ShowLogout = document.getElementById("UserArea").style.display = "block";
        var ShowLogoutArea = document.getElementById("UserFullArea").style.display = "block";
    }
    function HideUserArea(){
        var HideLogout = document.getElementById("UserArea").style.display = "none";
        var HideLogoutArea = document.getElementById("UserFullArea").style.display = "none";
    }
</script>
</html>