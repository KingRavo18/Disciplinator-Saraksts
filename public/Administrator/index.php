<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['user_role'] !== 'administrator') {
    header("Location: ../../index.php");
    exit();
}
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
if (!isset($_SESSION['page_language'])) {
    $_SESSION['page_language'] = 'lv'; 
}
if (!isset($_SESSION['page_theme'])) {
    $_SESSION['page_theme'] = '#fff'; 
}
$language = $_SESSION['page_language'] ?? 'lv';
require '../../Database/database.php'; 
$message = '';
$tasks = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['DeleteUser']) && !isset($_POST['SearchUser'])) {
        $usernameToDelete = filter_var(trim($_POST['DeleteUser']), FILTER_SANITIZE_STRING);
        if (empty($usernameToDelete)) {
            $message = "Lietotājvārds nevar būt tukšs.";
        } elseif ($usernameToDelete === 'Kingravo18') {
            $message = "Jums nav autoritātes dzēst šo kontu!";
        } else {
            $sql = "DELETE FROM users WHERE username = ? AND protected = 0";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('s', $usernameToDelete);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message = "Lietotājs '$usernameToDelete' tika dzēsts.";
                    } else {
                        $message = "Lietotājs '$usernameToDelete' netika atrasts.";
                    }
                } else {
                    $message = "Kļūda, dzēšot lietotāju: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Kļūda, sagatavojot vaicājumu: " . $mysqli->error;
            }
        }
    }
    if (isset($_POST['SearchUser'])) {
        $usernameToSearch = trim($_POST['SearchUser']);
        if (empty($usernameToSearch)) {
            $message = "Lietotājvārds nevar būt tukšs.";
        } elseif ($usernameToSearch === 'KingRavo18') {
            $message = "Jums nav autoritātes redzēt šī konta darbības!";
        } else {
            $sql = "SELECT t.id, t.task, t.completeTime, t.is_completed, t.is_deleted 
                    FROM users u 
                    JOIN tasks t ON u.id = t.user_id 
                    WHERE u.username = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('s', $usernameToSearch);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($task = $result->fetch_assoc()) {
                        $tasks[] = $task; 
                    }
                } else {
                    $message = "Lietotājam '$usernameToSearch' nav uzdevumu vai lietotājs netika atrasts.";
                }
                $stmt->close();
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
    <title><?= $language === 'lv' ? 'Disciplinators - Admin Logs' : 'Disciplinators - Admin Window'; ?></title>
</head>
<body>
    <?php
        require "../Accesories/mainPageTopBar.php";
        require "../Accesories/sidebar.php";
    ?>
    <main>
        <div class="pageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $language === 'lv' ? 'ADMINISTRATORA LOGS' : 'ADMINISTRATOR WINDOW'; ?></h1>
        </div>
        <section>
            <div class="adminDiv">
                <div class="adminTitle"><h2><?= $language === 'lv' ? 'FUNKCIJAS UN JAUNUMI' : 'FUNCTIONS AND NEWS'; ?></h2></div>
                <div class="adminForm">
                    <form method="POST" action="features&news.php">
                        <input type="text" name="title" placeholder="<?= $language === 'lv' ? 'Tēma ' : 'Subject '; ?>" required><br>
                        <textarea name="info" placeholder="<?= $language === 'lv' ? 'Informācija' : 'Information'; ?>" required></textarea><br>
                        <input type="hidden" name="author" value="<?= $_SESSION['username']; ?>">
                        <button class="adminButton"><?= $language === 'lv' ? 'Pievienot' : 'Add'; ?></button>
                    </form>
                </div>
            </div>
        </section>
        <section>
            <div class="adminDiv">
                <div class="adminTitle"><h2><?= $language === 'lv' ? 'DZĒST LIETOTĀJU' : 'DELETE USER'; ?></h2></div>
                <div class="adminForm">
                    <form method="POST">
                        <input type="text" name="DeleteUser" placeholder="<?= $language === 'lv' ? 'Ievadi Lietotājvārdu' : 'Enter Username'; ?>" required>
                        <?php if ($message && isset($_POST['DeleteUser'])): ?>
                            <div class="feedback-message">
                                <p style="color:red; font-size: 80%; margin-top: 20px;"><?= htmlspecialchars($message); ?></p>
                            </div>
                        <?php endif; ?>
                        <button class="adminButton deleteButton"><?= $language === 'lv' ? 'Dzēst' : 'Delete'; ?></button>
                    </form>
                </div>
                
            </div>
        </section>
        <section>
            <div class="adminDiv">
                <div class="adminTitle"><h2><?= $language === 'lv' ? 'LIETOTĀJU UZDEVUMI' : 'USER TASKS'; ?></h2></div>
                <div class="adminForm">
                    <form method="POST">
                        <input type="text" name="SearchUser" placeholder="<?= $language === 'lv' ? 'Ievadi Lietotājvārdu' : 'Enter Username'; ?>" required>
                        <button class="adminButton"><?= $language === 'lv' ? 'Meklēt' : 'Search'; ?></button>
                    </form>
                </div>
            <?php if (!empty($tasks)): ?>
                <div>
                    <h3><?= $language === 'lv' ? 'Uzdevumi Lietotājam ' : 'Tasks for the User '; ?><?= htmlspecialchars($usernameToSearch); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th><?= $language === 'lv' ? 'Uzdevumus' : 'Tasks'; ?></th>
                                <th><?= $language === 'lv' ? 'Pabeigšanas Laiks' : 'Finish Time'; ?></th>
                                <th><?= $language === 'lv' ? 'Statuss' : 'Status'; ?></th>
                                <th><?= $language === 'lv' ? 'Dzēsts' : 'Deleted'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['task']); ?></td>
                                    <td><?= htmlspecialchars($task['completeTime']); ?></td>
                                    <td><?= $task['is_completed'] ? 'Finished' : 'Not Finished'; ?></td>
                                    <td><?= $task['is_deleted'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            <?php endif; ?>
            <?php if ($message && isset($_POST['SearchUser'])): ?>
                <div class="feedback-message">
                    <p style="color:red; font-size: 80%;"><?= htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
<?php 
} else {
    header("Location: ../../index.php"); 
    exit();
}
?>
