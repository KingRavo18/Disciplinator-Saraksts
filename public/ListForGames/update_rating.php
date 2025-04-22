<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: You must be logged in to update the rating.");
}

$user_id = $_SESSION['user_id'];

$game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;
$new_rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

if (!$game_id || !$new_rating || $new_rating < 1 || $new_rating > 10) {
    die("Invalid data. Rating must be an integer between 1 and 10.");
}

$sql = "UPDATE games SET rating = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iii", $new_rating, $game_id, $user_id);

    if ($stmt->execute()) {
        echo "Success"; 
    } else {
        error_log("Error updating rating: " . $stmt->error);
        echo "Error updating rating."; 
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    echo "Database error.";
}

$mysqli->close();
