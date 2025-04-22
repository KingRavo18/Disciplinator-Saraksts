<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
if (!isset($_SESSION['page_language'])) {
    $_SESSION['page_language'] = 'lv'; 
}
if (!isset($_SESSION['page_theme'])) {
    $_SESSION['page_theme'] = '#fff'; 
}
$language = $_SESSION['page_language'] ?? 'lv';
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
    <link rel="stylesheet" href="../Style/home.css">
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title><?= $language === 'lv' ? 'Disciplinators - Mājaslapa' : 'Disciplinators - Homepage'; ?></title>
</head>
<body>
    <main>
        <div class="pageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $language === 'lv' ? 'PIEDĀVĀJAM' : 'WE OFFER'; ?></h1>
        </div>
        <?php
            require "../Accesories/mainPageTopBar.php";
            require "../Accesories/sidebar.php";
        ?>
        <div class="option" onclick="window.location.href='../MainPage/index.php';">
            <h2><?= $language === 'lv' ? 'Darāmo Darbu Saraksts' : 'To-Do List'; ?></h2>
        </div>
        <div class="option" onclick="window.location.href='../ListForGames/index.php';">
            <h2><?= $language === 'lv' ? 'Videospēļu Saraksts' : 'Videogame List'; ?></h2>
        </div>
        <div class="option" onclick="window.location.href='../ListForBooks/index.php';">
            <h2><?= $language === 'lv' ? 'Grāmatu Saraksts' : 'Book List'; ?></h2>
        </div>
        <div class="option" onclick="window.location.href='../ListForMovies/index.php';">
            <h2><?= $language === 'lv' ? 'Filmu/Seriālu Saraksts' : 'Movie/Show List'; ?></h2>
        </div>
        <div class="option" onclick="window.location.href='../Leaderboard/index.php';">
            <h2><?= $language === 'lv' ? 'Līderu Saraksts' : 'Leaderboard'; ?></h2>
        </div>
    </main>
</body>
</html>
<?php 
} else {
    header("Location: ../../index.php"); 
    exit();
}
?>