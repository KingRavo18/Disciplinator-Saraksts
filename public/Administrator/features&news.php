<?php
session_start();
require '../../Database/database.php';
if (isset($_POST['title']) && isset($_POST['info']) && isset($_POST['author'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $title = validate($_POST['title']);
    $info = validate($_POST['info']);
    $author = validate($_POST['author']); 
    if (empty($title)) {
        header("Location: index.php?error=Nosaukums ir nepieciešams");
        exit();
    } else if (empty($info)) {
        header("Location: index.php?error=Informācija ir nepieciešama");
        exit();
    } else {
        $sql_insert = "INSERT INTO features_news (title, message, date, author) VALUES (?, ?, NOW(), ?)";
        $stmt_insert = mysqli_prepare($mysqli, $sql_insert);
        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "sss", $title, $info, $author); 
            mysqli_stmt_execute($stmt_insert);
            header("Location: index.php?success=Jaunā funkcija/jaunums ir veiksmīgi pievienota");
            exit();
        } else {
            header("Location: index.php?error=Database error during insertion");
            exit();
        }
    }
}
