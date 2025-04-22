<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
$episode_count = filter_input(INPUT_POST, 'episode_count', FILTER_VALIDATE_INT);

if (!$movie_id || !$episode_count) {
    die("Invalid input.");
}

if ($episode_count < 1 || $episode_count > 100000) {
    die("Invalid episode count. Must be between 1 and 100000.");
}

$sql = "UPDATE movies SET episode_count = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iii", $episode_count, $movie_id, $user_id);

    if ($stmt->execute()) {
        echo "Episode count updated successfully.";
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo "Error updating episode count. Please try again later.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    die("Database error.");
}

$mysqli->close();
?>
