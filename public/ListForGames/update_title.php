<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: You must be logged in to update the game title.");
}

$user_id = $_SESSION['user_id'];

$game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;
$new_title = isset($_POST['title']) ? trim($_POST['title']) : null;

if (!$game_id || empty($new_title)) {
    die("Invalid data. Game ID and title must be provided.");
}

$sql = "UPDATE games SET title = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sii", $new_title, $game_id, $user_id);

    if ($stmt->execute()) {
        echo "Success"; 
    } else {
        error_log("Error updating game title: " . $stmt->error);
        echo "Error updating title.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    echo "Database error.";
}

$mysqli->close();
