<?php
session_start();
require '../../Database/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    if (isset($_POST['page_language'])) {
        function validate($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }
        $page_language = validate($_POST['page_language']);
        if (empty($page_language)) {
            $_SESSION['error'] = "Informācija ir nepieciešama";
            header("Location: index.php");
            exit();
        } 
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Autorizācija ir nepieciešama.";
            header("Location: index.php");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE users SET page_language = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $page_language, $user_id);
            if ($stmt->execute()) {
                $_SESSION['page_language'] = $page_language;
                $_SESSION['success'];
            } else {
                $_SESSION['error'] = "Datubāzes kļūda atjaunināšanas laikā";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Datubāzes kļūda";
        }
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Nepareiza pieprasījuma metode";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
