<?php
session_start();

$isDevMode = false;

function respond($message, $status = false) {
    echo $status ? "Success" : $message;
    exit;
}

if (!isset($_SESSION['user_id'])) {
    respond("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond("Invalid request method.");
}

if (!isset($_POST['book_id']) || !is_numeric($_POST['book_id'])) {
    respond("Invalid or missing book ID.");
}

require "../../Database/database.php";

$user_id = $_SESSION['user_id'];
$book_id = (int)$_POST['book_id'];

$checkOwnershipSql = "SELECT bf.file_path FROM books b 
                      LEFT JOIN bookfile bf ON bf.book_id = b.id 
                      WHERE b.id = ? AND b.user_id = ?";
$stmtOwnership = $mysqli->prepare($checkOwnershipSql);
if (!$stmtOwnership) {
    respond($isDevMode ? "SQL Error: " . $mysqli->error : "Database error.");
}
$stmtOwnership->bind_param("ii", $book_id, $user_id);
$stmtOwnership->execute();
$stmtOwnership->bind_result($file_path);
$found = $stmtOwnership->fetch();
$stmtOwnership->close();

if (!$found) {
    respond("Book not found or access denied.");
}

$default_file = '../ImageUploads/defaultFile.pdf';
if (!empty($file_path) && $file_path !== $default_file && file_exists($file_path)) {
    if (!unlink($file_path)) {
        respond("Failed to delete the file from the server.");
    }
}

$sqlDeleteFile = "DELETE FROM bookfile WHERE book_id = ?";
$stmtDeleteFile = $mysqli->prepare($sqlDeleteFile);
if (!$stmtDeleteFile) {
    respond($isDevMode ? "SQL Error: " . $mysqli->error : "Error deleting file record.");
}
$stmtDeleteFile->bind_param("i", $book_id);
$stmtDeleteFile->execute();
$stmtDeleteFile->close();

$sqlDeleteBook = "DELETE FROM books WHERE id = ? AND user_id = ?";
$stmtDeleteBook = $mysqli->prepare($sqlDeleteBook);
if (!$stmtDeleteBook) {
    respond($isDevMode ? "SQL Error: " . $mysqli->error : "Error deleting book.");
}
$stmtDeleteBook->bind_param("ii", $book_id, $user_id);
if ($stmtDeleteBook->execute()) {
    respond("Success", true);
} else {
    respond("Error deleting book.");
}

$stmtDeleteBook->close();
$mysqli->close();
