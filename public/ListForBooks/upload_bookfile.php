<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to upload files.";
    exit;
}

require "../../Database/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (strtolower($fileType) !== 'pdf') {
            echo "Only PDF files are allowed."; 
            exit; 
        }

        $uploadDir = '../ImageUploads/';
        $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        $user_id = $_SESSION['user_id'];
        $book_id = $_POST['book_id'];

        if (!empty($book_id)) {
            $default_file = '../ImageUploads/defaultFile.pdf';

            $sqlSelect = "SELECT file_path FROM bookfile WHERE book_id = ?";
            $stmtSelect = $mysqli->prepare($sqlSelect);
            if (!$stmtSelect) {
                echo "SQL Error: " . $mysqli->error;
                exit;
            }

            $stmtSelect->bind_param("i", $book_id);
            $stmtSelect->execute();
            $stmtSelect->bind_result($existingFilePath);

            if ($stmtSelect->fetch()) {
                $stmtSelect->free_result();

                if ($existingFilePath !== $default_file && file_exists($existingFilePath)) {
                    unlink($existingFilePath);
                }

                $sqlDelete = "DELETE FROM bookfile WHERE book_id = ?";
                $stmtDelete = $mysqli->prepare($sqlDelete);
                $stmtDelete->bind_param("i", $book_id);
                $stmtDelete->execute();
                $stmtDelete->close();
            }
            $stmtSelect->close();

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)) {
                $sqlInsert = "INSERT INTO bookfile (book_id, user_id, file_path) VALUES (?, ?, ?)";
                $stmtInsert = $mysqli->prepare($sqlInsert);
                $stmtInsert->bind_param("iis", $book_id, $user_id, $uploadFilePath);

                if ($stmtInsert->execute()) {
                    $stmtInsert->close(); 
                    header("Location: index.php");
                    exit; 
                } else {
                    $stmtInsert->close(); 
                    echo "Error uploading file to the database.";
                    exit; 
                }
            } else {
                echo "Error moving the uploaded file.";
                exit; 
            }
        } else {
            echo "Error: Book ID is required.";
            exit; 
        }
    } else {
        echo "Error: No file uploaded.";
        exit;
    }
}

$mysqli->close();
