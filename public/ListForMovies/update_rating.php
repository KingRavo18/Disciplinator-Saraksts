<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'] ?? null;
$new_rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

if (!$movie_id || !$new_rating || $new_rating < 1 || $new_rating > 10) {
    die("Invalid data");
}

$sql = "UPDATE movies SET rating = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $new_rating, $movie_id, $user_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error updating rating";
}

$stmt->close();
$mysqli->close();