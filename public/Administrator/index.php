<?php
session_start();
require '../../Database/database.php'; 
$message = '';
$tasks = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['DeleteUser']) && !isset($_POST['SearchUser'])) {
        $usernameToDelete = trim($_POST['DeleteUser']);
        if (empty($usernameToDelete)) {
            $message = "Lietotājvārds nevar būt tukšs.";
        } else {
            $sql = "DELETE FROM users WHERE username = ?";
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
    <title><?= $_SESSION['page_language'] === 'lv' ? 'Disciplinators - Admin Logs' : 'Disciplinators - Admin Window'; ?></title>
</head>
<body>
    <?php
        require "../Accesories/mainPageTopBar.php";
        require "../Accesories/sidebar.php";
    ?>
    <main>
        <div class="PageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $_SESSION['page_language'] === 'lv' ? 'ADMINISTRATORA LOGS' : 'ADMINISTRATOR WINDOW'; ?></h1>
        </div>
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2><?= $_SESSION['page_language'] === 'lv' ? 'FUNKCIJAS UN JAUNUMI' : 'FUNCTIONS AND NEWS'; ?></h2></div>
                <div class="AdminForm">
                    <form method="POST" action="features&news.php">
                        <input type="text" name="title" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Tēma ' : 'Subject '; ?>" required><br>
                        <textarea name="info" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Informācija' : 'Information'; ?>" required></textarea><br>
                        <input type="hidden" name="author" value="<?= $_SESSION['username']; ?>">
                        <button class="adminButton"><?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?></button>
                    </form>
                </div>
            </div>
        </section>
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2><?= $_SESSION['page_language'] === 'lv' ? 'DZĒST LIETOTĀJU' : 'DELETE USER'; ?></h2></div>
                <div class="AdminForm">
                    <form method="POST">
                        <input type="text" name="DeleteUser" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Ievadi Lietotājvārdu' : 'Enter Username'; ?>" required>
                        <button class="deleteButton"><?= $_SESSION['page_language'] === 'lv' ? 'Dzēst' : 'Delete'; ?></button>
                    </form>
                </div>
                <?php if ($message && isset($_POST['DeleteUser'])): ?>
                    <div class="feedback-message">
                        <p><?= htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2><?= $_SESSION['page_language'] === 'lv' ? 'LIETOTĀJU UZDEVUMI' : 'USER TASKS'; ?></h2></div>
                <div class="AdminForm">
                    <form method="POST">
                        <input type="text" name="SearchUser" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Ievadi Lietotājvārdu' : 'Enter Username'; ?>" required>
                        <button class="adminButton"><?= $_SESSION['page_language'] === 'lv' ? 'Meklēt' : 'Search'; ?></button>
                    </form>
                </div>
            <?php if (!empty($tasks)): ?>
                <div class="TaskList">
                    <h3><?= $_SESSION['page_language'] === 'lv' ? 'Uzdevumi Lietotājam' : 'Tasks for the User'; ?><?= htmlspecialchars($usernameToSearch); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th><?= $_SESSION['page_language'] === 'lv' ? 'Uzdevumus' : 'Tasks'; ?></th>
                                <th><?= $_SESSION['page_language'] === 'lv' ? 'Pabeigšanas Laiks' : 'Finish Time'; ?></th>
                                <th><?= $_SESSION['page_language'] === 'lv' ? 'Statuss' : 'Status'; ?></th>
                                <th><?= $_SESSION['page_language'] === 'lv' ? 'Dzēsts' : 'Deleted'; ?></th>
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
                    <p><?= htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
