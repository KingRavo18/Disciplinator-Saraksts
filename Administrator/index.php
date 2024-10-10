<?php
session_start();
require '../Database/database.php'; // Include your database connection

// Initialize an empty message for feedback and an empty array for tasks
$message = '';
$tasks = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete user form submission
    if (isset($_POST['DeleteUser']) && !isset($_POST['SearchUser'])) {
        $usernameToDelete = trim($_POST['DeleteUser']);

        if (empty($usernameToDelete)) {
            $message = "Lietotājvārds nevar būt tukšs.";
        } else {
            // Prepare a statement to delete the user
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

    // Search user tasks form submission
    if (isset($_POST['SearchUser'])) {
        $usernameToSearch = trim($_POST['SearchUser']);

        if (empty($usernameToSearch)) {
            $message = "Lietotājvārds nevar būt tukšs.";
        } else {
            // Prepare a statement to fetch the user's tasks
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
                        $tasks[] = $task; // Store the user's tasks in an array
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
        <!-- Section to delete user -->
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2>DZĒST LIETOTĀJU</h2></div>
                <div class="AdminForm">
                    <form method="POST">
                        <input type="text" name="DeleteUser" placeholder="Ievadi Lietotājvārdu" required>
                        <button class="deleteButton">Dzēst</button>
                    </form>
                </div>
                <?php if ($message && isset($_POST['DeleteUser'])): ?>
                    <div class="feedback-message">
                        <p><?= htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section to search for user tasks -->
        <section>
            <div class="AdminDiv">
                <div class="AdminTitle"><h2>LIETOTĀJU UZDEVUMI</h2></div>
                <div class="AdminForm">
                    <form method="POST">
                        <input type="text" name="SearchUser" placeholder="Ievadi Lietotājvārdu" required>
                        <button class="adminButton">Meklēt</button>
                    </form>
                </div>
            

            <?php if (!empty($tasks)): ?>
                <div class="TaskList">
                    <h3>Uzdevumi Lietotājam: <?= htmlspecialchars($usernameToSearch); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Uzdevums</th>
                                <th>Pabeigšanas Laiks</th>
                                <th>Statuss</th>
                                <th>Dzēsts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['task']); ?></td>
                                    <td><?= htmlspecialchars($task['completeTime']); ?></td>
                                    <td><?= $task['is_completed'] ? 'Pabeigts' : 'Nepabeigts'; ?></td>
                                    <td><?= $task['is_deleted'] ? 'Jā' : 'Nē'; ?></td>
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
