<?php
session_start();
if (isset($_SESSION['id'])) {
    require '../../Database/database.php';  
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $task = $mysqli->real_escape_string($_POST['task']);
        $completeTime = $mysqli->real_escape_string($_POST['completeTime']);
        $sql = "INSERT INTO tasks (user_id, task, completeTime) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('iss', $user_id, $task, $completeTime);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }
} else {
    header("Location: ../../index.php");
    exit();
}

