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
    <link rel="icon" type="image/x-icon" href="../Images/FistLogoCut.png">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplanators - Saraksts</title>
</head>
<body>
    <?php
        require "../Accesories/mainPageTopBar.php";
        require "../Accesories/sidebar.php";
    ?>
    <main>
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></h1> <!-- htmlspecialchars for security -->
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