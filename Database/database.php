<?php
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Disciplinators";
    // Create connection using mysqli
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: Please try again later."); // Generic error message
    }