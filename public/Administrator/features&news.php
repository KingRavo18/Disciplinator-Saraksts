<?php
session_start();
require '../../Database/database.php';

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

function validate($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if (!isset($_POST['title'], $_POST['info'], $_POST['author'])) {
    header("Location: index.php?error=" . urlencode("Invalid form submission."));
    exit();
}

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$info = filter_input(INPUT_POST, 'info', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (empty($title)) {
    header("Location: index.php?error=" . urlencode("Nosaukums ir nepieciešams"));
    exit();
}

if (empty($info)) {
    header("Location: index.php?error=" . urlencode("Informācija ir nepieciešama"));
    exit();
}

if (!$mysqli) {
    header("Location: index.php?error=" . urlencode("Database connection error."));
    exit();
}

$sql_insert = "INSERT INTO features_news (title, message, date, author) VALUES (?, ?, NOW(), ?)";
$stmt_insert = mysqli_prepare($mysqli, $sql_insert);

if ($stmt_insert) {
    mysqli_stmt_bind_param($stmt_insert, "sss", $title, $info, $author);
    $execute = mysqli_stmt_execute($stmt_insert);

    if ($execute) {
        mysqli_stmt_close($stmt_insert);
        header("Location: index.php?success=" . urlencode("Jaunā funkcija/jaunums ir veiksmīgi pievienota"));
        exit();
    } else {
        mysqli_stmt_close($stmt_insert);
        header("Location: index.php?error=" . urlencode("Database error during insertion"));
        exit();
    }
} else {
    header("Location: index.php?error=" . urlencode("Failed to prepare statement."));
    exit();
}
