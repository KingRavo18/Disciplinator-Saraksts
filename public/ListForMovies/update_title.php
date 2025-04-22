<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("Unauthorized access.");
}

require "../../Database/database.php"; 

if (!isset($_POST['movie_id'], $_POST['title']) || empty(trim($_POST['title']))) {
    http_response_code(400);
    exit("Invalid input.");
}

$movie_id = filter_var($_POST["movie_id"], FILTER_VALIDATE_INT);
$title = trim($_POST["title"]);

if (!$movie_id || !$title) {
    http_response_code(400);
    exit("Invalid input.");
}

$sql = "UPDATE movies SET title = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    exit("Database error.");
}

$stmt->bind_param("sii", $title, $movie_id, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo "Success";
} else {
    http_response_code(500);
    exit("Error updating title.");
}

$stmt->close();
$mysqli->close();
