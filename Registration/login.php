<?php
session_start();
include "../Database/database.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $pass = validate($_POST['password']);

    // Check for empty input fields
    if (empty($username)) {
        header("Location: index.php?login_error=Lietotājvārds ir nepieciešams");
        exit();
    } else if (empty($pass)) {
        header("Location: index.php?login_error=Parole ir nepieciešama");
        exit();
    } else {
        // Use a prepared statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if a matching user is found
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                // Verify the password using password_verify()
                if (password_verify($pass, $row['password'])) {
                    // Successful login
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['id'] = $row['id'];
                    header("Location: ../MainPage/index.php");
                    exit();
                } else {
                    header("Location: index.php?login_error=Nepareizs lietotājvārds vai parole");
                    exit();
                }
            } else {
                header("Location: index.php?login_error=Nepareizs lietotājvārds vai parole");
                exit();
            }
        } else {
            header("Location: index.php?login_error=Database error");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>