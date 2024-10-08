<?php
session_start();

if (isset($_SESSION['id'])) {
    require '../Database/database.php';

    // Get task ID from POST request
    $task_id = $_POST['task_id'];
    $user_id = $_SESSION['id'];

    // Fetch the task to check if it is completed
    $sql = "SELECT is_completed FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();

        // If the task is not completed, reduce the user's points
        if (!$task['is_completed']) {
            $update_points_sql = "UPDATE users SET points = points - 1 WHERE id = ?";
            $update_stmt = $mysqli->prepare($update_points_sql);
            $update_stmt->bind_param('i', $user_id);
            $update_stmt->execute();
            $update_stmt->close();

            // Optional: update session points if you're keeping them in session
            $_SESSION['points'] = $_SESSION['points'] - 1;
        }

        // Now delete the task
        $delete_sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
        $delete_stmt = $mysqli->prepare($delete_sql);
        $delete_stmt->bind_param('ii', $task_id, $user_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }

    $stmt->close();
    $mysqli->close();

    // Redirect back to the main page
    header("Location: index.php");
    exit();
} else {
    header("Location: ../Registration/index.php");
    exit();
}
