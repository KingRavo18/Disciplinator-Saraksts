<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
$times_watched = filter_input(INPUT_POST, 'times_watched', FILTER_VALIDATE_INT);

if (!$movie_id || $times_watched === false || $times_watched < 0) {
    http_response_code(400);
    die("Invalid input.");
}

$sql = "UPDATE movies SET times_watched = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iii", $times_watched, $movie_id, $user_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        error_log("SQL Error: " . $stmt->error);
        http_response_code(500);
        echo "Error updating times watched. Please try again later.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    http_response_code(500);
    die("Database error.");
}

$mysqli->close();
