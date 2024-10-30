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

$sqlDelete = "DELETE FROM bookfile WHERE book_id = ?";
$stmtDelete = $mysqli->prepare($sqlDelete);
if (!$stmtDelete) {
    die("SQL Error: " . $mysqli->error);
}
$stmtDelete->bind_param("i", $book_id);

if ($stmtDelete->execute()) {
    $sql = "DELETE FROM books WHERE id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    $stmt->bind_param("ii", $book_id, $user_id);
    if ($stmt->execute()) {
        echo "Book and associated files deleted successfully.";
    } else {
        echo "Error deleting book: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error deleting associated files: " . $stmtDelete->error;
}

$stmtDelete->close();
$mysqli->close();

