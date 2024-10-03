<?php
session_start();
require '../Database/database.php';

if (isset($_POST['title']) && isset($_POST['info'])) {

    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $title = validate($_POST['title']);
    $info = validate($_POST['info']);

    // Check for empty input fields
    if (empty($title)) {
        header("Location: index.php?error=Nosaukums ir nepieciešams");
        exit();
    } else if (empty($info)) {
        header("Location: index.php?error=Informācija ir nepieciešama");
        exit();
    } else {
        // Insert the new feature or news into the database
        $sql_insert = "INSERT INTO features_news (title, message) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($mysqli, $sql_insert);

        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "ss", $title, $info);
            mysqli_stmt_execute($stmt_insert);

            // After insertion, redirect to the admin logs page with a success message
            header("Location: index.php?success=Jaunā funkcija/jaunums ir veiksmīgi pievienota");
            exit();
        } else {
            header("Location: index.php?error=Database error during insertion");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}