<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

require "../../Database/database.php"; 

$movie_id = $_POST["movie_id"];
$title = trim($_POST["title"]);

if (!$movie_id || !$title) {
    die("Invalid input.");
}

$sql = "UPDATE movies SET title = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sii", $title, $movie_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>