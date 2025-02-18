<?php
session_start();
if (!isset($_POST['game_id'])) {
    die("Game ID is required");
}

$game_id = $_POST['game_id'];
require "../../Database/database.php";

$sql = "DELETE FROM games WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $game_id);

if ($stmt->execute()) {
    echo "Success"; 
} else {
    echo "Error: " . $stmt->error; 
}

$stmt->close();
$mysqli->close();

