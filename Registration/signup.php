<?php
session_start();
include "../Database/database.php";

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);

    // Check for empty input fields
    if (empty($username)) {
        header("Location: index.php?signup_error=Lietotājvārds ir nepieciešams");
        exit();
    } else if (empty($email)) {
        header("Location: index.php?signup_error=E-pasts ir nepieciešams");
        exit();
    } else if (empty($pass)) {
        header("Location: index.php?signup_error=Parole ir nepieciešama");
        exit();
    } else {
        // Check if the username or email already exists
        $sql = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                // User with the same username or email exists
                header("Location: index.php?signup_error=Lietotājvārds vai E-pasts jau eksistē");
                exit();
            } else {
                // Insert new user into the database
                $sql_insert = "INSERT INTO users (username, email, password, points, user_role) VALUES (?, ?, ?, 0, 'user')";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                if ($stmt_insert) {
                    // Optionally, you can hash the password before saving it to the database
                    mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $pass);
                    mysqli_stmt_execute($stmt_insert);

                    // Registration successful, redirect to login page or main page
                    $_SESSION['username'] = $username;
                    header("Location: ../MainPage/index.php");
                    exit();
                } else {
                    header("Location: index.php?signup_error=Database error");
                    exit();
                }
            }
        } else {
            header("Location: index.php?signup_error=Database error");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}