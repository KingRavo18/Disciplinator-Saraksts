<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
$new_rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

if (!$movie_id || !$new_rating || $new_rating < 1 || $new_rating > 10) {
    die("Invalid rating. Must be between 1 and 10.");
}

$sql = "UPDATE movies SET rating = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iii", $new_rating, $movie_id, $user_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo "Error updating rating. Please try again later.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    die("Database error.");
}

$mysqli->close();
