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
    <link rel="stylesheet" href="../Style/sidebar.css">
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplinators</title>
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
    </main>
</body>
</html>