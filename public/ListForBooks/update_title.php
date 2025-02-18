<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$game_id = $_POST['game_id'] ?? null;
$new_title = trim($_POST['title'] ?? '');

if (!$game_id || !$new_title) {
    die("Invalid data");
}

$sql = "UPDATE books SET title = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sii", $new_title, $game_id, $user_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error updating title";
}

$stmt->close();
$mysqli->close();