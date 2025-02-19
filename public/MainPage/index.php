<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    require '../../Database/database.php'; 
    $user_id = $_SESSION['id'];

    $currentTime = date('Y-m-d H:i:s'); 

    $sql_update_overdue = "UPDATE tasks SET is_failed = 1 WHERE completeTime < ? AND is_completed = 0 AND is_failed = 0 AND user_id = ?";
    $stmt_update_overdue = $mysqli->prepare($sql_update_overdue);
    $stmt_update_overdue->bind_param('si', $currentTime, $user_id);
    $stmt_update_overdue->execute();
    $stmt_update_overdue->close();

    $sql = "SELECT id, task, completeTime, is_completed, is_failed FROM tasks WHERE user_id = ? AND is_deleted = 0 ORDER BY created_at DESC";
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
    <link rel="stylesheet" href="../Style/global.css">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title><?= $_SESSION['page_language'] === 'lv' ? 'Disciplinators - Darbu Saraksts' : 'Disciplinators - To-Do List'; ?></title>
</head>
<body>
    <?php
        require "../Accesories/mainPageTopBar.php";
        require "../Accesories/sidebar.php";
    ?>
    <main>
        <div class="pageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $_SESSION['page_language'] === 'lv' ? 'DARĀMO DARBU SARAKSTS' : 'TO DO LIST'; ?></h1>
        </div>
        <div class="toDoList">
            <div class="toDoList-Left">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($task = $result->fetch_assoc()) { ?>
                        <div class="task <?= $task['is_completed'] ? 'completed-task' : ($task['is_failed'] ? 'failed-task' : ''); ?>">
                            <div class="taskArea"><p><?= htmlspecialchars($task['task']); ?></p></div>
                            <div style="display: flex;">
                                <div class="timeArea">
                                    <p><?= $_SESSION['page_language'] === 'lv' ? 'Pabeigt līdz' : 'Finish By'; ?> <?= htmlspecialchars($task['completeTime']); ?></p>
                                </div>
                                <?php if (!$task['is_completed'] && !$task['is_failed']): ?>
                                    <form method="POST" action="completeTask.php" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <button class="completeButton"><?= $_SESSION['page_language'] === 'lv' ? 'Pabeigts' : 'Finished'; ?></button>
                                    </form>
                                    <form method="POST" action="failedTask.php" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <button class="completeButton deleteButton"><?= $_SESSION['page_language'] === 'lv' ? 'Nepabeigts' : 'Not Finished'; ?></button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($task['is_completed'] || $task['is_failed']): ?>
                                    <form method="POST" action="deleteTask.php" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <button class="completeButton deleteButton"><?= $_SESSION['page_language'] === 'lv' ? 'Dzēst' : 'Delete'; ?></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <p class="no-tasks-message"><?= $_SESSION['page_language'] === 'lv' ? 'Nav Uzdevumu!' : 'No Tasks'; ?></p>
                <?php endif; ?>
            </div>
            <div class="toDoList-Right">
                <div class="toDoList-Form">
                    <div class="toDoListTitle"><h2><?= $_SESSION['page_language'] === 'lv' ? 'PIEVIENOT DARBU' : 'ADD TASK'; ?></h2></div>
                    <form method="POST" action="createTask.php">
                        <textarea name="task" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Uzdevums' : 'Task'; ?>" required></textarea><br>
                        <label for="timeInput"><?= $_SESSION['page_language'] === 'lv' ? 'Pabeigšanas Laiks (Obligāts)' : 'Finish Time (Manditory)'; ?></label>
                        <input type="datetime-local" name="completeTime" id="timeInput" required><br>
                        <button><?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?></button>
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
