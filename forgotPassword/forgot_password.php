<?php
// Include the database connection
include "../Database/database.php";

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Check if the form with the email has been submitted
if (isset($_POST['email'])) {
    // Sanitize the email input
    $email = htmlspecialchars(trim($_POST['email']));

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        // Generate a unique token for the password reset link
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Save the token and its expiry date in the database
        $sql_update = "UPDATE users SET reset_token=?, token_expiry=? WHERE email=?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "sss", $token, $expiry, $email);
        mysqli_stmt_execute($stmt_update);

        // Construct the password reset link
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;

        // Use PHPMailer to send the email with the reset link
        $mail = new PHPMailer(true);

        try {
            // Set up the mail server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '';  // Replace with your Gmail address
            $mail->Password   = '';  // Replace with your Gmail password or app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->SMTPDebug = 2;
            // Set the sender and recipient information
            $mail->setFrom('rkingovskis14@gmail.com', 'Kornēlijs Bahs');  // Replace with your email and name
            $mail->addAddress($email);  // The email of the user requesting password reset

            // Set the email format and content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click on this link to reset your password: <a href='$reset_link'>$reset_link</a>";

            // Send the email
            $mail->send();
            echo 'A password reset link has been sent to your email.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "This email is not registered.";
    }
} else {
    echo "Please enter your email.";
}
?>
