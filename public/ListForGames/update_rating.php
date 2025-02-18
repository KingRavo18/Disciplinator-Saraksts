<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$game_id = $_POST['game_id'] ?? null;
$new_rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

if (!$game_id || !$new_rating || $new_rating < 1 || $new_rating > 10) {
    die("Invalid data");
}

$sql = "UPDATE games SET rating = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $new_rating, $game_id, $user_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error updating rating";
}

$stmt->close();
$mysqli->close();