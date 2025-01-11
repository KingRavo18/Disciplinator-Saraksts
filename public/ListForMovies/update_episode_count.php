<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to update episodes.");
}
require "../../Database/database.php";
$movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
$episode_count = filter_input(INPUT_POST, 'episode_count', FILTER_VALIDATE_INT);

if (!$movie_id || !$episode_count) {
    die("Invalid input.");
}

$sql = "UPDATE movies SET episode_count = ? WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $episode_count, $movie_id, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo "Episode count updated successfully.";
} else {
    echo "Error updating episode count: " . $stmt->error;
}
$stmt->close();
$mysqli->close();
?>
