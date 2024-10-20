<?php
// Start the session to retrieve the logged-in user's ID
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add a game.");
}
// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];
// Get POST data
$img = $_POST["img"];
$title = $_POST["title"];
$release_date = $_POST["release_date"];
$author = $_POST["author"];
$status = $_POST["status"];
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
// Check if any required field is missing
if (!$img || !$title || !$release_date || !$author || !$status || !$rating) {
    die("All entries must be filled");
}
// Database configuration from the second code
$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';
// Create a connection to the Disciplinators database
$mysqli = new mysqli($host, $user, $pass, $db);
// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// SQL query to insert data into the `games` table, including the user_id
$sql = "INSERT INTO books (user_id, img, title, release_date, author, status, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
// Initialize a prepared statement
$stmt = $mysqli->stmt_init();
// Prepare the SQL statement
if (!$stmt->prepare($sql)) {
    die("SQL Error: " . $mysqli->error);
}
// Bind the variables to the statement parameters
$stmt->bind_param("isssssi", $user_id, $img, $title, $release_date, $author, $status, $rating);
// Execute the statement
if ($stmt->execute()) {
    header("Location: ../index.php");
} else {
    echo "Error: " . $stmt->error;
}
// Close the statement and connection
$stmt->close();
$mysqli->close();
?>