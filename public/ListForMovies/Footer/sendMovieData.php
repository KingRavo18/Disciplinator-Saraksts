<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add an entry.");
}

$user_id = $_SESSION['user_id'];
$img = $_POST["img"];
$title = $_POST["title"];
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
$type = $_POST["type"];
$episode_count = isset($_POST["episode_count"]) ? filter_input(INPUT_POST, "episode_count", FILTER_VALIDATE_INT) : null;

if (!$img || !$title || !$rating || !$type || ($type === "tv_show" && !$episode_count)) {
    die("All required fields must be filled.");
}

$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO movies (user_id, img, title, rating, type, episode_count) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($sql)) {
    die("SQL Error: " . $mysqli->error);
}

$stmt->bind_param("issisi", $user_id, $img, $title, $rating, $type, $episode_count);

if ($stmt->execute()) {
    header("Location: ../index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();

