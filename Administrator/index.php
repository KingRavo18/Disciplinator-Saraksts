<?php
session_start();
require '../Database/database.php'; // Include your database connection

// Initialize an empty message for feedback
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the delete user form was submitted
    if (isset($_POST['DeleteUser'])) {
        $usernameToDelete = trim($_POST['DeleteUser']);

        // Check if the username is empty
        if (empty($usernameToDelete)) {
            $message = "Lietotājvārds nevar būt tukšs.";
        } else {
            // Prepare a statement to delete the user
            $sql = "DELETE FROM users WHERE username = ?";
            $stmt = $mysqli->prepare($sql);

            // Check if the statement was prepared successfully
            if ($stmt) {
                $stmt->bind_param('s', $usernameToDelete);

                // Execute the query
                if ($stmt->execute()) {
                    // Check how many rows were affected
                    if ($stmt->affected_rows > 0) {
                        $message = "Lietotājs '$usernameToDelete' tika dzēsts.";
                    } else {
                        $message = "Lietotājs '$usernameToDelete' netika atrasts.";
                    }
                } else {
                    // If execute fails, show the error
                    $message = "Kļūda, dzēšot lietotāju: " . $stmt->error;
                }

                $stmt->close(); // Close the statement
            } else {
                $message = "Kļūda, sagatavojot vaicājumu: " . $mysqli->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/sidebar.css">
    <link rel="stylesheet" href="../Style/administrator.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Disciplinators - Admin Logs</title>
</head>
<body>
    <?php
        require "../Accesories/mainPageTopBar.php";
        require "../Accesories/sidebar.php";
    ?>
    <main>
        <div class="PageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">ADMINISTRATORA LOGS</h1>
        </div>
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2>FUNKCIJAS UN JAUNUMI</h2></div>
                <div class="AdminForm">
                    <form method="POST" action="features&news.php">
                        <input type="text" name="title" placeholder="Tēma " required><br>
                        <textarea name="info" placeholder="Informācija " required></textarea><br>
                        <button class="adminButton">Pievienot</button>
                    </form>
                </div>
            </div>
        </section>
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2>DZĒST LIETOTĀJU</h2></div>
                <div class="AdminForm">
                    <form method="POST">
                        <input type="text" name="DeleteUser" placeholder="Ievadi Lietotājvārdu" required>
                        <button class="deleteButton">Dzēst</button>
                    </form>
                </div>
                <?php if ($message): ?>
                    <div class="feedback-message">
                        <p><?= htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>
            </div>
