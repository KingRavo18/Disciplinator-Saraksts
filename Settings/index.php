<?php
    session_start();
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/FistLogoCut.png">
    <link rel="stylesheet" href="../Style/settings.css">
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplinators - Iestatījumi</title>
</head>
<body>
    <main>
        <?php
            require "../Accesories/mainPageTopBar.php";
        ?>
    </main>
</body>
</html>
<?php } ?>