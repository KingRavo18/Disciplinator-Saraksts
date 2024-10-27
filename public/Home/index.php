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
    <link rel="stylesheet" href="../Style/home.css">
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplinators</title>
</head>
<body>
    <main>
        <div class="PageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $_SESSION['page_language'] === 'lv' ? 'PIEDĀVĀJAM' : 'WE OFFER'; ?></h1>
        </div>
        <?php
            require "../Accesories/mainPageTopBar.php";
            require "../Accesories/sidebar.php";
        ?>
        <div class="home">
            <div class="option">
                <div class="homeIMG">
                    <a href="../MainPage/index.php"><img src="../Images/toDoListPicture.png" alt="<?= $_SESSION['page_language'] === 'lv' ? 'Darāmo Darbu Saraksta Lapa' : 'To Do List Page'; ?>"></a>
                </div>
                <h2><?= $_SESSION['page_language'] === 'lv' ? 'Darāmo Darbu Saraksts' : 'To Do List'; ?></h2>
            </div>
            <div class="option">
                <div class="homeIMG">
                    <a href="../ListForGames/index.php"><img src="../Images/gameListPicture.png" alt="<?= $_SESSION['page_language'] === 'lv' ? 'Vidiospēļu Saraksta Lapa' : 'Videogame List Page'; ?>"></a>
                </div>
                <h2><?= $_SESSION['page_language'] === 'lv' ? 'Vidiospēļu Saraksts' : 'Videogame List'; ?></h2>
            </div>
            <div class="option">
                <div class="homeIMG">
                    <a href="../ListForBooks/index.php"><img src="../Images/bookListPicture.png" alt="<?= $_SESSION['page_language'] === 'lv' ? 'Grāmatu Saraksta Lapa' : 'Book List Page'; ?>"></a>
                </div>
                <h2><?= $_SESSION['page_language'] === 'lv' ? 'Grāmatu Saraksts' : 'Book List'; ?></h2>
            </div>
            <div class="option">
                <div class="homeIMG">
                    <a href="../ListForMovies/index.php"><img src="../Images/movieListPicture.png" alt="<?= $_SESSION['page_language'] === 'lv' ? 'Filmu/Šovu Saraksta Lapa' : 'Movie/Show List Page'; ?>"></a>
                </div>
                <h2><?= $_SESSION['page_language'] === 'lv' ? 'Filmu/Šovu Saraksts' : 'Movie/Show List'; ?></h2>
            </div>
            <div class="option">
                <div class="homeIMG">
                    <a href="../Leaderboard/index.php"><img src="../Images/leaderboardPicture.png" alt="<?= $_SESSION['page_language'] === 'lv' ? 'Līderu Saraksta Lapa' : 'Leaderboard Page'; ?>"></a>
                </div>
                <h2><?= $_SESSION['page_language'] === 'lv' ? 'Līderu Saraksts' : 'Leaderboard'; ?></h2>
            </div>
        </div>
    </main>
</body>
</html>