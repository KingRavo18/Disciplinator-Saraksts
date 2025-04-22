<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['page_language'])) {
    $_SESSION['page_language'] = 'lv'; 
}
if (!isset($_SESSION['page_theme'])) {
    $_SESSION['page_theme'] = '#fff'; 
}
$language = $_SESSION['page_language'] ?? 'lv';
require '../../Database/database.php'; 
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    function updateUserData($mysqli, $column, $value, $userId) {
        $stmt = $mysqli->prepare("UPDATE users SET $column = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $value, $userId);
            return $stmt->execute();
        }
        return false;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username'])) {
            $new_username = htmlspecialchars(trim($_POST['username']));
            if (!empty($new_username)) {
                if (updateUserData($mysqli, 'username', $new_username, $_SESSION['id'])) {
                    $_SESSION['username'] = $new_username;
                    $_SESSION['success'] = "Lietotājvārds veiksmīgi atjaunināts!";
                } else {
                    $_SESSION['error'] = "Kļūda atjauninot lietotājvārdu.";
                }
            }
        }
        if (isset($_POST['email'])) {
            $new_email = htmlspecialchars(trim($_POST['email']));
            if (!empty($new_email) && filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                if (updateUserData($mysqli, 'email', $new_email, $_SESSION['id'])) {
                    $_SESSION['email'] = $new_email;
                    $_SESSION['success'] = "E-pasts veiksmīgi atjaunināts!";
                } else {
                    $_SESSION['error'] = "Kļūda atjauninot e-pastu.";
                }
            } else {
                $_SESSION['error'] = "Lūdzu, ievadiet derīgu e-pasta adresi.";
            }
        }
        if (isset($_POST['upload_picture'])) {
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = $_FILES['profile_picture']['type'];
                if (in_array($file_type, $allowed_types)) {
                    $uploads_dir = '../All_images/ImageUploads/profile_pictures/';
                    if (!is_dir($uploads_dir)) {
                        mkdir($uploads_dir, 0777, true); 
                    }
                    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $new_filename = $_SESSION['id'] . '_' . time() . '.' . $file_extension;
                    $file_path = $uploads_dir . $new_filename;
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
                        if (updateUserData($mysqli, 'profile_picture', $file_path, $_SESSION['id'])) {
                            $_SESSION['profile_picture'] = $file_path; 
                            $_SESSION['success'] = "Profila bilde veiksmīgi atjaunināta!";
                        } else {
                            $_SESSION['error'] = "Kļūda atjauninot profila bildi.";
                        }
                    } else {
                        $_SESSION['error'] = "Neizdevās augšupielādēt failu.";
                    }
                } else {
                    $_SESSION['error'] = "Atļauts augšupielādēt tikai JPG, PNG vai GIF formātā.";
                }
            } else {
                $_SESSION['error'] = "Neizdevās augšupielādēt profila bildi.";
            }
        }
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="lv">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet" href="../Style/settings.css">
        <link rel="stylesheet" href="../Style/sidebar.css">
        <link rel="stylesheet" href="../Style/mainPageTopBar.css">
        <link rel="stylesheet" href="../Style/global.css"/>
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <title><?= $language === 'lv' ? 'Disciplinators - Iestatījumi' : 'Disciplinators - Settings'; ?></title>
    </head>
    <body>
        <main>
            <div class="pageTitle">
                <h1 id="Title" style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $language === 'lv' ? 'IESTATĪJUMI' : 'SETTINGS'; ?></h1>
            </div>
            <?php
                require "../Accesories/mainPageTopBar.php";
                require "../Accesories/sidebar.php";
                if (isset($_SESSION['error'])) {
                    echo '<div style="text-align: center; color: red;">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div style="text-align: center; color: green;">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
            ?>
            <div class="settingsPage">
                <div class="settingsTop">
                    <div class="settingsdiv">
                        <div class="settingsTitle"><h2><?= $language === 'lv' ? 'PROFILS' : 'PROFILE'; ?></h2></div>
                        <div class="profileArea1">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="profilePicture">
                                    <div class="profileCircle">
                                        <img src="<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../Images/default_profile.jpg'; ?>" alt="Profile Picture">
                                    </div>
                                    <div class="fileInput">
                                        <input type="file" name="profile_picture">
                                    </div>
                                    <div class="fileInput">
                                        <button type="submit" name="upload_picture"><?= $language === 'lv' ? 'Apstiprināt' : 'Upload'; ?></button>
                                    </div>
                                </div>
                            </form>
                            <div class="profileNames">
                                <p><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown User'; ?></p>
                                <p><?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Unknown Email'; ?></p>
                                <form method="POST">
                                    <input class="changeInput" style="margin-top:90px" type="text" placeholder="<?= $language === 'lv' ? 'Jauns Lietotājvārds' : 'New Username'; ?>" name="username" required>
                                    <button class="changeButton" type="submit"><?= $language === 'lv' ? 'Mainīt lietotājvārdu' : 'Change Username'; ?></button>
                                </form>
                                <form method="POST">
                                    <input class="changeInput" type="email" placeholder="<?= $language === 'lv' ? 'Jauns E-pasts' : 'New E-mail'; ?>" name="email" required>
                                    <button class="changeButton" type="submit"><?= $language === 'lv' ? 'Mainīt e-pastu' : 'Change E-mail'; ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="settingsThemediv">
                        <?php require "themeChange.php"; ?>
                    </div>
                </div>
                <div class="settingsLangdiv">
                    <?php require "languageChange.php"; ?>
                </div>
                <div style="margin-top: 10px; color: blue; text-decoration: underline; cursor: pointer;">
                    <a href="terms&Conditions.php"><?= $language === 'lv' ? 'Noteikumi un Nosacījumi' : 'Terms & Conditions'; ?></a>
                </div>
            </div>
        </main>
    </body>
</html>
<?php 
} else {
    header("Location: ../../index.php"); 
    exit();
}
?>
