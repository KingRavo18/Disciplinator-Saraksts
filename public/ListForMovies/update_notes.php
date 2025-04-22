<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
$notes = trim($_POST['notes'] ?? '');

if (!$movie_id) {
    die("Invalid movie ID.");
}
if (strlen($notes) > 5000) {
    die("Notes are too long. Limit is 5000 characters.");
}

$sql = "UPDATE movies SET notes = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sii", $notes, $movie_id, $user_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo "Error updating notes. Please try again later.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    die("Database error.");
}

$mysqli->close();
