<?php
// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the index page
header("Location: ../Registration/index.php");
exit(); // It's good practice to call exit after a header redirection
?>