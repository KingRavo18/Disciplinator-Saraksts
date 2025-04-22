<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add a game.");
}

require "../../Database/database.php";

$user_id = $_SESSION['user_id'];

$title = trim($_POST["title"] ?? "");
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);

if (empty($title) || !$rating) {
    die("All entries must be filled.");
}

$use_default_image = isset($_POST['use_default_image']);
$img_url = null;
$img_file_path = null;

if ($use_default_image) {
    $img_file_path = "../ImageUploads/default_image.png";
} else {
    $img_url = isset($_POST["img_url"]) ? filter_var(trim($_POST["img_url"]), FILTER_SANITIZE_URL) : null;

    if (!empty($_FILES['img_file']['name'])) {
        $target_dir = "../ImageUploads/articleImages/";
        $file_tmp_name = $_FILES["img_file"]["tmp_name"];
        $file_name = basename($_FILES["img_file"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowed_types)) {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        if ($_FILES["img_file"]["size"] > 5 * 1024 * 1024) {
            die("Error: File size must be less than 5MB.");
        }

        $check = getimagesize($file_tmp_name);
        if ($check === false) {
            die("Error: The uploaded file is not a valid image.");
        }

        $new_file_name = uniqid("game_img_", true) . "." . $imageFileType;
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_name, $target_file)) {
            $img_file_path = "../ImageUploads/articleImages/" . $new_file_name;
        } else {
            die("Error: There was an issue uploading your file.");
        }
    }

    if (!$img_url && !$img_file_path) {
        die("Error: You must provide an image URL, upload an image, or choose the default image.");
    }
}

$sql = "INSERT INTO books (user_id, img_url, img_file_path, title, rating) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    error_log("SQL Error: " . $mysqli->error);
    die("Database error.");
}

$stmt->bind_param("isssi", $user_id, $img_url, $img_file_path, $title, $rating);

if ($stmt->execute()) {
    $book_id = $stmt->insert_id;

    $default_file_path = "../ImageUploads/defaultFile.pdf";
    $sqlFile = "INSERT INTO bookfile (book_id, user_id, file_path) VALUES (?, ?, ?)";
    $stmtFile = $mysqli->prepare($sqlFile);

    if ($stmtFile) {
        $stmtFile->bind_param("iis", $book_id, $user_id, $default_file_path);
        $stmtFile->execute();
        $stmtFile->close();
    } else {
        error_log("Error preparing statement for bookfile: " . $mysqli->error);
    }

    header("Location: index.php");
    exit;
} else {
    error_log("Insert Error: " . $stmt->error);
    die("Database error.");
}

$stmt->close();
$mysqli->close();
