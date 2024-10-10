<?php
session_start();
require '../Database/database.php';

if (isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $user_id = $_SESSION['id'];

    // Update the task to mark it as deleted
    $sql = "UPDATE tasks SET is_deleted = 1 WHERE id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ii', $task_id, $user_id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error updating task: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}
?>