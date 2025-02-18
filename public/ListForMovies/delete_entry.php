<?php
session_start();
if (!isset($_POST['movie_id'])) {
    die("movie ID is required");
}

$movie_id = $_POST['movie_id'];
require "../../Database/database.php";

$sql = "DELETE FROM movies WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $movie_id);

if ($stmt->execute()) {
    echo "Success"; 
} else {
    echo "Error: " . $stmt->error; 
}

$stmt->close();
$mysqli->close();