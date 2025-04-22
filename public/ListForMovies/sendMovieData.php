<?php
session_start();
require "../../Database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$title = trim($_POST["title"] ?? '');
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
$type = $_POST["type"] ?? '';
$episode_count = filter_input(INPUT_POST, "episode_count", FILTER_VALIDATE_INT);

if (empty($title) || !$rating || empty($type) || ($type === "tv_show" && !$episode_count)) {
    die("All required fields must be filled.");
}

if ($rating < 1 || $rating > 10) {
    die("Invalid rating. It must be between 1 and 10.");
}

if ($type === "tv_show" && ($episode_count < 1 || $episode_count > 100000)) {
    die("Invalid episode count.");
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
$sql = "INSERT INTO movies (user_id, img_url, img_file_path, title, rating, type, episode_count) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("isssisi", $user_id, $img_url, $img_file_path, $title, $rating, $type, $episode_count);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo "Database error. Please try again later.";
    }

    $stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    die("Database error.");
}

$mysqli->close();
