<?php
include "../Database/database.php";

// Handle form submission (Step 5)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['token']) && isset($_POST['password'])) {
        $token = $_POST['token'];
        $new_password = htmlspecialchars(trim($_POST['password']));

        // Password validation
        function isValidPassword($password) {
            return strlen($password) >= 8 &&
                   preg_match('/[a-z]/', $password) &&
                   preg_match('/[A-Z]/', $password) &&
                   preg_match('/[0-9]/', $password) &&
                   preg_match('/[\W]/', $password);
        }

        if (!isValidPassword($new_password)) {
            echo "Password doesn't meet requirements.";
            exit();
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Find the user by token and update the password
        $sql = "SELECT * FROM users WHERE reset_token=? AND token_expiry > NOW()";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            // Update the password and clear the reset token
            $sql_update = "UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?";
            $stmt_update = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "ss", $hashed_password, $token);
            mysqli_stmt_execute($stmt_update);

            echo "Password successfully reset. You can now log in.";
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        echo "Invalid request.";
    }
} 
// Display form if GET request with token (Step 4)
else if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database and hasn't expired
    $sql = "SELECT * FROM users WHERE reset_token=? AND token_expiry > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        // Token is valid, display the form to reset the password
        echo '<form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="'.$token.'">
                <label for="password">Enter your new password:</label>
                <input type="password" name="password" required>
                <button type="submit">Reset Password</button>
              </form>';
    } else {
        echo "Invalid or expired token.";
    }
} 
else {
    echo "Invalid request.";
}
?>
