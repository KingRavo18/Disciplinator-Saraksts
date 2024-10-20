<?php
session_start();
if (isset($_SESSION['id'])) {
    // Include your database connection
    require '../Database/database.php';  // Assuming this is the file where your MySQLi connection exists
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the task details from the form
        $user_id = $_SESSION['user_id'];
        $task = $mysqli->real_escape_string($_POST['task']);
        $completeTime = $mysqli->real_escape_string($_POST['completeTime']);
        // Prepare an SQL statement to insert the task into the database
        $sql = "INSERT INTO tasks (user_id, task, completeTime) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        // Check if the statement was prepared correctly
        if ($stmt) {
            // Bind parameters and execute the statement
            $stmt->bind_param('iss', $user_id, $task, $completeTime);
            $stmt->execute();
            $stmt->close();
            // Redirect back to the task list page after adding the task
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../Registration/index.php");
    exit();
}
?>
