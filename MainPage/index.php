<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    require '../Database/database.php'; 

    // Fetch tasks where is_deleted is 0
    $user_id = $_SESSION['id'];
    $sql = "SELECT id, task, completeTime, is_completed FROM tasks WHERE user_id = ? AND is_deleted = 0 ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
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
    <title>Disciplinators - Saraksts</title>
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
            <div class="ToDoList-Left">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($task = $result->fetch_assoc()) { ?>
                        <div class="task <?= $task['is_completed'] ? 'completed-task' : ''; ?>">
                            <div class="taskArea"><p><?= htmlspecialchars($task['task']); ?></p></div>
                            <div class="taskBottomArea">
                                <div class="timeArea"><p>Pabeigt līdz <?= htmlspecialchars($task['completeTime']); ?></p></div>
                                <form method="POST" action="completeTask.php" style="display: inline;">
                                    <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                    <button class="CompleteButton" <?= $task['is_completed'] ? 'disabled' : ''; ?>>Pabeigts</button>
                                </form>
                                <form method="POST" action="deleteTask.php" style="display: inline;">
                                    <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                    <button class="DeleteButton">Dzēst</button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <p class="no-tasks-message">Nav uzdevumu!</p>
                <?php endif; ?>
            </div>
            <div class="ToDoList-Right">
                <div class="ToDoList-Form">
                    <div class="ToDoListTitle"><h2>PIEVIENOT DARBU</h2></div>
                    <form method="POST" action="createTask.php">
                        <textarea name="task" placeholder="Uzdevums" required></textarea><br>
                        <label for="timeInput">Pabeigšanas Laiks (Obligāts)</label>
                        <input type="time" name="completeTime" id="timeInput" required><br>
                        <button>Pievienot</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php
    $stmt->close();
} else {
    header("Location: ../Registration/index.php");
    exit();
}
?>
