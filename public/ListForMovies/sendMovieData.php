<?php
session_start();

require "../../Database/database.php";

// Pārbauda vai lietotājs ir savā kontā
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id']; 

// Iegūt datus, nodrošināt tos un ielikt mainīgos
$title = trim($_POST["title"] ?? '');
$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
$type = $_POST["type"] ?? '';
$episode_count = filter_input(INPUT_POST, "episode_count", FILTER_VALIDATE_INT);

// Pārbaudīt vai mainīgie ir aizpildīti ar minimāliem pieprasījumiem
if (empty($title) || !$rating || empty($type) || ($type === "tv_show" && !$episode_count)) {
    die("All required fields must be filled.");
}

// Pārbauda vērtējuma diapazonu
if ($rating < 1 || $rating > 10) {
    die("Invalid rating. It must be between 1 and 10.");
}

// Ja tiek veidots seriāla ieraksts, pārbauda sēriju diapazonu 
if ($type === "tv_show" && ($episode_count < 1 || $episode_count > 100000)) {
    die("Invalid episode count.");
}

// Pārbauda vai lietotājs vēlas izmantot noklusējuma attēlu
$use_default_image = isset($_POST['use_default_image']);
$img_url = null;
$img_file_path = null;

if ($use_default_image) {
    $img_file_path = "../ImageUploads/default_image.png";
} else {
    $img_url = isset($_POST["img_url"]) ? filter_var(trim($_POST["img_url"]), FILTER_SANITIZE_URL) : null;

    // Apstrādājiet attēla faila augšupielādi, ja tāds ir
    if (!empty($_FILES['img_file']['name'])) {
        $target_dir = "../ImageUploads/articleImages/";
        $file_tmp_name = $_FILES["img_file"]["tmp_name"];
        $file_name = basename($_FILES["img_file"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        // Pārbaudiet, vai failam ir atļauts paplašinājums
        if (!in_array($imageFileType, $allowed_types)) {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        // Pievieno attēla faila limitu
        if ($_FILES["img_file"]["size"] > 5 * 1024 * 1024) {
            die("Error: File size must be less than 5MB.");
        }

        // Pārbauda vai fails ir derīgs attēls
        $check = getimagesize($file_tmp_name);
        if ($check === false) {
            die("Error: The uploaded file is not a valid image.");
        }

        // Ģenerējiet unikālu faila nosaukumu un pārvietojiet augšupielādēto failu uz serveri
        $new_file_name = uniqid("game_img_", true) . "." . $imageFileType;
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_name, $target_file)) {
            $img_file_path = "../ImageUploads/articleImages/" . $new_file_name;
        } else {
            die("Error: There was an issue uploading your file.");
        }
    }

    // Pārbauda vai vismaz viens attēla resurs ir piedāvāts
    if (!$img_url && !$img_file_path) {
        die("Error: You must provide an image URL, upload an image, or choose the default image.");
    }
}

// Sagatavo SQL paziņojumu, kas ieliks datus datubāzē
$sql = "INSERT INTO movies (user_id, img_url, img_file_path, title, rating, type, episode_count) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Saistīt parametrus sagatavotajam paziņojumam
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
    // Ierakstīt un parādīt kļūdu, ja sagatavošana neizdodas
    error_log("SQL Preparation Error: " . $mysqli->error);
    die("Database error.");
}

$mysqli->close();
