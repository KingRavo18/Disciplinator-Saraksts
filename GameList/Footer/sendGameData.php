<?php
// Get POST data
$img = $_POST["img"];
$title = $_POST["title"];
$release_date = $_POST["release_date"];
$description = $_POST["description"];
$developer = $_POST["developer"];
$game_completion = filter_input(INPUT_POST, "game_completion", FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

// Check if any required field is missing
if (!$img || !$title || !$release_date || !$description || !$developer || !$game_completion || !$rating) {
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

// SQL query to insert data into the `games` table
$sql = "INSERT INTO games (img, title, release_date, description, developer, game_completion, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";

// Initialize a prepared statement
$stmt = $mysqli->stmt_init();

// Prepare the SQL statement
if (!$stmt->prepare($sql)) {
    die("SQL Error: " . $mysqli->error);
}

// Bind the variables to the statement parameters
$stmt->bind_param("sssssii", $img, $title, $release_date, $description, $developer, $game_completion, $rating);

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
