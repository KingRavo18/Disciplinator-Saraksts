<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a game.");
}
$user_id = $_SESSION['user_id'];
$movie_id = $_POST['game_id'];
if (!$movie_id) {
    die("Invalid movie ID.");
}
$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$sql = "DELETE FROM games WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $mysqli->error);
}
$stmt->bind_param("ii", $movie_id, $user_id);
if ($stmt->execute()) {
    echo "Game deleted successfully.";
} else {
    echo "Error deleting movie: " . $stmt->error;
}
$stmt->close();
$mysqli->close();

