<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
$notes = trim($_POST['notes'] ?? "");

if (!$book_id || $book_id <= 0) {
    http_response_code(400);
    die("Invalid data.");
}

$sql = "UPDATE books SET notes = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    error_log("SQL Error: " . $mysqli->error);
    http_response_code(500);
    die("Database error.");
}

$stmt->bind_param("sii", $notes, $book_id, $user_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    error_log("Update Error: " . $stmt->error);
    http_response_code(500);
    echo "Error updating notes.";
}

$stmt->close();
$mysqli->close();
