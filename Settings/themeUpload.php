<?php
session_start();
require '../Database/database.php';
// CSRF protection: Generate and validate tokens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    if (isset($_POST['page_theme'])) {
        // Function to validate input data
        function validate($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }
        // Validate and sanitize user input
        $page_theme = validate($_POST['page_theme']);
        // Check for empty input fields
        if (empty($page_theme)) {
            $_SESSION['error'] = "Informācija ir nepieciešama";
            header("Location: index.php");
            exit();
        } 
        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Autorizācija ir nepieciešama.";
            header("Location: index.php");
            exit();
        }
        // Update the page theme for the logged-in user
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE users SET page_theme = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            // Bind the parameters and execute
            $stmt->bind_param("si", $page_theme, $user_id);
            if ($stmt->execute()) {
                // Update session theme and success message
                $_SESSION['page_theme'] = $page_theme;
                $_SESSION['success'];
            } else {
                $_SESSION['error'] = "Datubāzes kļūda atjaunināšanas laikā";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Datubāzes kļūda";
        }
        // Redirect back to the main page after processing
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Nepareiza pieprasījuma metode";
        header("Location: index.php");
        exit();
    }
} else {
    // If not a POST request, redirect to the index page
    header("Location: index.php");
    exit();
}
?>
