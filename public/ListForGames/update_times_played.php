<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: You must be logged in to update the times played.");
}

$user_id = $_SESSION['user_id'];

$game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;
$times_played = filter_input(INPUT_POST, "times_played", FILTER_VALIDATE_INT);

if (!$game_id || $times_played === false || $times_played < 0) {
    die("Invalid data. The number of times played must be a non-negative integer.");
}

$sql = "UPDATE games SET times_played = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iii", $times_played, $game_id, $user_id);

    if ($stmt->execute()) {
        echo "Success"; 
    } else {
        error_log("Error updating times played: " . $stmt->error);
        echo "Error updating times played.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    echo "Database error.";
}

$mysqli->close();
