<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. You must be logged in to update an image.");
}

require "../../Database/database.php";

$user_id = $_SESSION['user_id'];
$book_id = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;

if ($book_id <= 0) {
    die("Invalid book ID.");
}

$check_sql = "SELECT id FROM books WHERE id = ? AND user_id = ?";
$check_stmt = $mysqli->prepare($check_sql);
$check_stmt->bind_param("ii", $book_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    die("Error: Book entry not found or you do not have permission to update it.");
}
$check_stmt->close();

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

        $new_file_name = uniqid("book_img_", true) . "." . $imageFileType;
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

$update_sql = "UPDATE books SET img_url = ?, img_file_path = ? WHERE id = ? AND user_id = ?";
$update_stmt = $mysqli->prepare($update_sql);

if ($update_stmt) {
    $update_stmt->bind_param("ssii", $img_url, $img_file_path, $book_id, $user_id);
    if ($update_stmt->execute()) {
        header("Location: ./index.php");
        exit();
    } else {
        error_log("Database Update Error: " . $update_stmt->error);
        die("Error: Unable to update the image. Please try again later.");
    }
    $update_stmt->close();
} else {
    error_log("SQL Preparation Error: " . $mysqli->error);
    die("Error: Database issue. Please try again later.");
}

$mysqli->close();
