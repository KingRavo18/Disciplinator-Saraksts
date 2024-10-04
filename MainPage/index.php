<?php
    session_start();

    // Check if user is logged in by verifying session variables
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/sidebar.css">
    <link rel="stylesheet" href="../Style/mainPage.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplanators - Saraksts</title>
</head>
<body>
    <?php
        require "../Accesories/mainPageTopBar.php";
        require "../Accesories/sidebar.php";
    ?>
    <main>
        <div class="PageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">DARĀMO DARBU SARAKSTS</h1>
        </div>
        <div class="ToDoList">
            <div class="ToDoList-Left"></div>
            <div class="ToDoList-Right">
                <div class="ToDoList-Form">
                    <div class="ToDoListTitle"><h2>PIEVIENOT DARBU</h2></div>
                    <textarea></textarea><br>
                    <button>Pievienot</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php
    } else {
        // Redirect to index page if user is not logged in
        header("Location: ../Registration/index.php");
        exit();
    }
?>