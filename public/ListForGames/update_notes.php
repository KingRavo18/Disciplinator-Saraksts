<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: You must be logged in to update notes.");
}

$user_id = $_SESSION['user_id'];

$game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : "";

if (!$game_id) {
    die("Invalid game ID.");
}

if (strlen($notes) > 1000) { 
    die("Notes are too long.");
}

$sql = "UPDATE games SET notes = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sii", $notes, $game_id, $user_id);

    if ($stmt->execute()) {
        echo "Success"; 
    } else {
        error_log("Error updating notes: " . $stmt->error);
        echo "Error updating notes."; 
    }

    // Close the statement
    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    echo "Database error.";
}

$mysqli->close();
