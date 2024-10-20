<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload files.");
}

// Database connection
require "../Database/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // Validate file type
        $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (strtolower($fileType) !== 'pdf') {
            die("Only PDF files are allowed.");
        }

        // Set upload directory
        $uploadDir = '../ImageUploads/'; // Ensure this directory exists and is writable
        $fileName = uniqid() . '_' . basename($_FILES['file']['name']); // Generate a unique file name
        $uploadFilePath = $uploadDir . $fileName;

        // Move uploaded file to the specified directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)) {
            // Insert file path into the database
            $user_id = $_SESSION['user_id'];
            $book_id = $_POST['book_id']; // Ensure this is the correct book_id from the form

            // Debugging output to verify received book_id
            echo "Received Book ID: $book_id"; // For debugging

            // Check if book_id is provided
            if (!empty($book_id)) {
                $sql = "INSERT INTO bookfile (book_id, user_id, file_path) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("iis", $book_id, $user_id, $uploadFilePath);

                if ($stmt->execute()) {
                    header("Location: index.php");
                } else {
                    echo "Error uploading file to the database.";
                }

                $stmt->close();
            } else {
                die("Error: Book ID is required.");
            }
        } else {
            die("Error moving the uploaded file.");
        }
    } else {
        die("Error: No file uploaded.");
    }
}

// Close the database connection
$mysqli->close();
?>








