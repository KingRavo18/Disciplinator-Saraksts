<?php
    // Database configuration
    $host = 'localhost';
    $db = 'Disciplinators';
    $user = 'root';
    $pass = '';
    // Create connection using mysqli
    $mysqli = new mysqli($host, $user, $pass, $db);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die('Connection failed: ' . $mysqli->connect_error);
    }

    return $mysqli;
    