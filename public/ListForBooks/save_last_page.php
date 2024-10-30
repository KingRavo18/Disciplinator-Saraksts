<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

require "../../Database/database.php";

if (isset($_POST['file_id'], $_POST['last_page'])) {
    $file_id = $_POST['file_id'];
    $last_page = $_POST['last_page'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE bookfile SET last_page = ? WHERE id = ? AND EXISTS (
                SELECT 1 FROM books WHERE books.user_id = ? AND books.id = bookfile.book_id
            )";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iii", $last_page, $file_id, $user_id);

    if ($stmt->execute()) {
        echo "Last page updated successfully.";
    } else {
        echo "Error updating last page.";
    }

    $stmt->close();
} else {
    echo "Invalid data.";
}

