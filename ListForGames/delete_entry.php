<?php
// Start session to get the logged-in user's ID
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a game.");
}

// Get the logged-in user's ID and the movie ID to delete
$user_id = $_SESSION['user_id'];
$movie_id = $_POST['game_id'];

// Check if the movie ID is provided
if (!$movie_id) {
    die("Invalid movie ID.");
}

// Database configuration
$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';

// Create a connection to the database
$mysqli = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// SQL query to delete the movie entry based on user_id and movie_id
$sql = "DELETE FROM games WHERE id = ? AND user_id = ?";

// Prepare the SQL statement
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $mysqli->error);
}

// Bind parameters and execute the statement
$stmt->bind_param("ii", $movie_id, $user_id);
if ($stmt->execute()) {
    echo "Game deleted successfully.";
} else {
    echo "Error deleting movie: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$mysqli->close();
?>
