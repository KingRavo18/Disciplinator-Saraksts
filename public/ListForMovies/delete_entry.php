<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: You must be logged in to delete a movie entry.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

if (!isset($_POST['movie_id']) || !filter_var($_POST['movie_id'], FILTER_VALIDATE_INT)) {
    die("Invalid movie entry ID.");
}

$movie_id = (int)$_POST['movie_id'];
$user_id = $_SESSION['user_id'];

$check_sql = "SELECT img_file_path FROM movies WHERE id = ? AND user_id = ?";
$check_stmt = $mysqli->prepare($check_sql);
$check_stmt->bind_param("ii", $movie_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: You do not have permission to delete this movie.");
}

$row = $result->fetch_assoc();
$img_file_path = $row['img_file_path'];
$check_stmt->close();

$sql = "DELETE FROM movies WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $movie_id);
    if ($stmt->execute()) {
        $default_image_path = "../ImageUploads/default_image.png";
        if ($img_file_path && $img_file_path !== $default_image_path) {
            $full_path = "../" . $img_file_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }
        echo "Success";
    } else {
        error_log("Delete Movie Error: " . $stmt->error); 
        echo "Error: Could not delete movie.";
    }
    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    echo "Error: Database issue.";
}

$mysqli->close();
