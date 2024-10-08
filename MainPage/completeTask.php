<?php
session_start();
if (isset($_SESSION['id'])) {
    require '../Database/database.php';  // Include the database connection

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $task_id = $_POST['task_id'];
        $user_id = $_SESSION['id'];

        // First, check if the task is already completed
        $sql = "SELECT is_completed FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ii', $task_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($is_completed);
        $stmt->fetch();
        $stmt->close();

        // If the task is not completed, update it and increment the user's points
        if (!$is_completed) {
            // Update the task as completed
            $sql_update_task = "UPDATE tasks SET is_completed = 1 WHERE id = ? AND user_id = ?";
            $stmt_update_task = $mysqli->prepare($sql_update_task);
            $stmt_update_task->bind_param('ii', $task_id, $user_id);
            $stmt_update_task->execute();
            $stmt_update_task->close();

            // Increment the user's points
            $sql_update_points = "UPDATE users SET points = COALESCE(points, 0) + 1 WHERE id = ?";
            $stmt_update_points = $mysqli->prepare($sql_update_points);
            $stmt_update_points->bind_param('i', $user_id);
            $stmt_update_points->execute();
            $stmt_update_points->close();

            // Update the session with the new points value
            $sql_get_points = "SELECT points FROM users WHERE id = ?";
            $stmt_get_points = $mysqli->prepare($sql_get_points);
            $stmt_get_points->bind_param('i', $user_id);
            $stmt_get_points->execute();
            $stmt_get_points->bind_result($updated_points);
            $stmt_get_points->fetch();
            $_SESSION['points'] = $updated_points;  // Update session points
            $stmt_get_points->close();
        }

        // Redirect back to the main page after completing the task
        header("Location: index.php");
        exit();
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../Registration/index.php");
    exit();
}
?>
