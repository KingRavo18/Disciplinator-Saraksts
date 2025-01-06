<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add a game.");
}
$user_id = $_SESSION['user_id'];
$img = $_POST["img"];
$title = $_POST["title"];
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
if (!$img || !$title || !$rating) {
    die("All entries must be filled");
}
$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$sql = "INSERT INTO movies (user_id, img, title, rating) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($sql)) {
    die("SQL Error: " . $mysqli->error);
}
$stmt->bind_param("issi", $user_id, $img, $title, $rating);
if ($stmt->execute()) {
    header("Location: ../index.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$mysqli->close();
