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

    $language = $_SESSION['page_language'] ?? 'en';
    require "../../Database/database.php";
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
        <title><?= $language === 'lv' ? 'Disciplinators - Līderu Saraksts' : 'Disciplinators - Leaderboard'; ?></title>
    </head>
    <body>
        <main>
            <div class="pageTitle">
                <h1 style="color: <?= htmlspecialchars($_SESSION['page_theme'] ?? '#fff'); ?>">
                    <?= $language === 'lv' ? 'LĪDERU SARAKSTS' : 'LEADERBOARD'; ?>
                </h1>
            </div>
            <?php
            require "../Accesories/mainPageTopBar.php";
            require "../Accesories/sidebar.php";
            ?>
            <div class="leaderboardDiv">
                <div class="leaderTable">
                    <table>
                        <tr>
                            <th style="width: 10%"><?= $language === 'lv' ? 'Vieta' : 'Place'; ?></th>
                            <th style="width: 60%"><?= $language === 'lv' ? 'Lietotājvārds' : 'Username'; ?></th>
                            <th style="width: 30%"><?= $language === 'lv' ? 'Punktu Skaits' : 'Point Count'; ?></th>
                        </tr>
                        <?php
                        $place = 1;
                        $sql = "SELECT username, points FROM users WHERE username IS NOT NULL ORDER BY points DESC LIMIT 100";
                        $stmt = $mysqli->prepare($sql);

                        if ($stmt && $stmt->execute()) {
                            $result = $stmt->get_result();
                            while ($entry = $result->fetch_assoc()) {
                                $username = htmlspecialchars($entry['username']);
                                $points = (int) $entry['points'];
                                ?>
                                <tr onclick="ShowUserArea()">
                                    <td><?= $place ?>.</td>
                                    <td><?= $username ?></td>
                                    <td><?= $points ?></td>
                                </tr>
                                <?php
                                $place++;
                            }
                            $stmt->close();
                        } else {
                            error_log("Leaderboard query failed: " . $mysqli->error);
                            echo "<tr><td colspan='3' style='color:red;'>Error loading leaderboard.</td></tr>";
                        }
                        $mysqli->close();
                        ?>
                    </table>
                </div>
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
