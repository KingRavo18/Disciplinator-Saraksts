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
    // Function to check if the password meets the minimum requirements
    function isValidPassword($password) {
        // Check for minimum length of 8 characters
        if (strlen($password) < 8) {
            return false;
        }
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        // Check for at least one digit
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        // Check for at least one special character
        if (!preg_match('/[\W]/', $password)) { // \W matches any non-word character
            return false;
        }
        return true;
    }
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
    } else if (!isValidPassword($pass)) {
        // If the password doesn't meet the criteria, send an error message
        header("Location: index.php?signup_error=Parolei jābūt vismaz 8 rakstzīmēm, jāiekļauj vismaz viens lielais burts, viens mazais burts, viens cipars un viena speciālā rakstzīme");
        exit();
    } else {
        // Check if the username or email already exists
        $sql = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = mysqli_prepare($mysqli, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                // User with the same username or email exists
                header("Location: index.php?signup_error=Lietotājvārds vai E-pasts jau eksistē");
                exit();
            } else {
                // Hash the password before saving it to the database
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                // Insert new user into the database
                $sql_insert = "INSERT INTO users (username, email, password, points, user_role) VALUES (?, ?, ?, 0, 'user')";
                $stmt_insert = mysqli_prepare($mysqli, $sql_insert);
                if ($stmt_insert) {
                    mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $hashed_pass);
                    mysqli_stmt_execute($stmt_insert);
                    // Fetch the newly inserted user's ID (optional, but good for session management)
                    $new_user_id = mysqli_insert_id($mysqli);
                    // Start a session for the newly registered user
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['id'] = $new_user_id; 
                    $_SESSION['user_role'] = 'user';
                    // Redirect to the main page
                    header("Location: ../Home/index.php");
                    exit();
                } else {
                    header("Location: index.php?signup_error=Database error during insertion");
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
?>

