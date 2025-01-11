<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add a game.");
}

$user_id = $_SESSION['user_id'];
$title = $_POST["title"];
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

if (!$title || !$rating) {
    die("All entries must be filled");
}

$img_url = $_POST["img_url"] ?? null;
$img_file_path = null;

if (!empty($_FILES['img_file']['name'])) {
    $target_dir = "../ImageUploads/articleImages/";
    $target_file = $target_dir . basename($_FILES["img_file"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        die("Invalid file type. Only JPG, PNG, JPEG, and GIF are allowed.");
    }

    if (move_uploaded_file($_FILES["img_file"]["tmp_name"], $target_file)) {
        $img_file_path = $target_file;
    } else {
        die("There was an error uploading your file.");
    }
}

if (!$img_url && !$img_file_path) {
    die("You must provide an image URL or upload an image.");
}

$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT INTO games (user_id, img_url, img_file_path, title, rating) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($sql)) {
    die("SQL Error: " . $mysqli->error);
}

$stmt->bind_param("isssi", $user_id, $img_url, $img_file_path, $title, $rating);
if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();

