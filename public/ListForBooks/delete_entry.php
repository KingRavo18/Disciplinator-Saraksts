<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a book.");
}

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];
if (!$book_id) {
    die("Invalid book ID.");
}

$host = 'localhost';
$db = 'Disciplinators';
$user = 'root';
$pass = '';
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sqlSelectFile = "SELECT file_path FROM bookfile WHERE book_id = ?";
$stmtSelectFile = $mysqli->prepare($sqlSelectFile);
if (!$stmtSelectFile) {
    die("SQL Error: " . $mysqli->error);
}
$stmtSelectFile->bind_param("i", $book_id);
$stmtSelectFile->execute();
$stmtSelectFile->bind_result($file_path);

if ($stmtSelectFile->fetch()) {
    $default_file = '../ImageUploads/defaultFile.pdf';

    if ($file_path !== $default_file && file_exists($file_path)) {
        unlink($file_path);
    }
}

$stmtSelectFile->close();

$sqlDeleteFile = "DELETE FROM bookfile WHERE book_id = ?";
$stmtDeleteFile = $mysqli->prepare($sqlDeleteFile);
if (!$stmtDeleteFile) {
    die("SQL Error: " . $mysqli->error);
}
$stmtDeleteFile->bind_param("i", $book_id);

if ($stmtDeleteFile->execute()) {
    $sqlDeleteBook = "DELETE FROM books WHERE id = ? AND user_id = ?";
    $stmtDeleteBook = $mysqli->prepare($sqlDeleteBook);
    if (!$stmtDeleteBook) {
        die("SQL Error: " . $mysqli->error);
    }

    $stmtDeleteBook->bind_param("ii", $book_id, $user_id);
    if ($stmtDeleteBook->execute()) {
        echo "Success"; 
    } else {
        echo "Error: " . $stmt->error; 
    }

    $stmtDeleteBook->close();
} else {
    echo "Error deleting associated files: " . $stmtDeleteFile->error;
}

$stmtDeleteFile->close();
$mysqli->close();
