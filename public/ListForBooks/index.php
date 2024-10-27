<?php
session_start();
?>
<!DOCTYPE html>
<html lang="lv">
    <head>
        <title><?= $_SESSION['page_language'] === 'lv' ? 'Disciplinators - Grāmatu Saraksts' : 'Disciplinators - Book List'; ?></title>
        <meta charset="UTF-8"/>
        <meta name="author" content="Raivo Kingovskis"/>
        <meta name="description" content="The Ultimate List is a webpage for me and me only to 
        make a list of games, I've played, anime I've watched and books I've read. If someone else comes 
        across this page, please kindly go away."/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta property="og:image" content="../Style/index.css"/>
        <meta property="og:description" content="The Ultimate List is a webpage for me and me only to 
        make a list of games, I've played, anime I've watched and books I've read. If someone else comes 
        across this page, please kindly go away."/>
        <meta property="og:title" content="Game List"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <link rel="stylesheet" href="../Style/gameMovieBookList.css"/>
        <link rel="stylesheet" href="../Style/global.css"/>
        <link rel="stylesheet" href="../Style/mainPageTopBar.css">
        <link rel="stylesheet" href="../Style/sidebar.css">
    </head>
    <body>
    <main>
            <div class="PageTitle">
                <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $_SESSION['page_language'] === 'lv' ? 'GRĀMATU SARAKSTS' : 'BOOK LIST'; ?></h1>
            </div>
            <?php
                require "../Accesories/mainPageTopBar.php";
                require "../Accesories/sidebar.php";
            ?>
            <section>
                <?php 
                    //Creates articles that contain list data
                    require "./list.php"; 
                ?>
            </section>
        </main>
        <?php
            //Creates the footer
            require "./Footer/footer.php";
        ?>
    </body>
</html>