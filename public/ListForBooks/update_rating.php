<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$book_id = filter_input(INPUT_POST, "book_id", FILTER_VALIDATE_INT);
$new_rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

if (!$book_id || $book_id <= 0 || !$new_rating || $new_rating < 1 || $new_rating > 10) {
    http_response_code(400);
    die("Invalid data.");
}

$sql = "UPDATE books SET rating = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    error_log("SQL Error: " . $mysqli->error);
    http_response_code(500);
    die("Database error.");
}

$stmt->bind_param("iii", $new_rating, $book_id, $user_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    error_log("Update Error: " . $stmt->error);
    http_response_code(500);
    echo "Error updating rating.";
}

$stmt->close();
$mysqli->close();
