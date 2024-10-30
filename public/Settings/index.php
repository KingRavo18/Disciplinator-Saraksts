<?php
session_start();
require '../../Database/database.php'; 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
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
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "CSRF token validation failed";
            header("Location: index.php");
            exit();
        }
        if (isset($_POST['username'])) {
            $new_username = htmlspecialchars(trim($_POST['username']));
            if (!empty($new_username)) {
                if (updateUserData($mysqli, 'username', $new_username, $_SESSION['id'])) {
                    $_SESSION['username'] = $new_username; // Update session value
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
        <title><?= $_SESSION['page_language'] === 'lv' ? 'Disciplinators - Iestatījumi' : 'Disciplinators - Settings'; ?></title>
    </head>
    <body>
        <main>
            <div class="PageTitle">
                <h1 id="Title" style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $_SESSION['page_language'] === 'lv' ? 'IESTATĪJUMI' : 'SETTINGS'; ?></h1>
            </div>
            <?php
                require "../Accesories/mainPageTopBar.php";
                require "../Accesories/sidebar.php";
                if (isset($_SESSION['error'])) {
                    echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="success-message">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
            ?>
            <section class="SettingsPage">
                <div class="settingsdiv">
                    <div class="SettingsTitle"><h2><?= $_SESSION['page_language'] === 'lv' ? 'PROFILS' : 'PROFILE'; ?></h2></div>
                    <div class="ProfileArea1">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="ProfilePicture">
                                <div class="ProfileCircle">
                                    <img src="<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../Images/default_profile.jpg'; ?>" alt="Profile Picture">
                                </div>
                                <div class="FileInput">
                                    <input type="file" name="profile_picture">
                                </div>
                                <div class="FileInput">
                                    <button type="submit" name="upload_picture"><?= $_SESSION['page_language'] === 'lv' ? 'Apstiprināt' : 'Upload'; ?></button>
                                </div>
                            </div>
                        </form>
                        <div class="ProfileNames">
                            <p><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown User'; ?></p>
                            <p><?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Unknown Email'; ?></p>
                        </div>
                    </div>
                    <div class="ProfileArea2">
                        <div class="ChangeArea">
                            <form method="POST">
                                <input type="text" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Jauns Lietotājvārds' : 'New Username'; ?>" name="username" required>
                                <button type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Mainīt lietotājvārdu' : 'Change Username'; ?></button>
                            </form>
                        </div>
                        <div class="ChangeArea">
                            <form method="POST">
                                <input type="email" placeholder="<?= $_SESSION['page_language'] === 'lv' ? 'Jauns E-pasts' : 'New E-mail'; ?>" name="email" required>
                                <button type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Mainīt e-pastu' : 'Change E-mail'; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="settingsThemediv">
                    <?php require "themeChange.php"; ?>
                    <?php require "languageChange.php"; ?>
                </div>
            </section>
        </main>
    </body>
</html>
<?php 
} else {
    header("Location: ../Registration/index.php"); 
    exit();
}
?>
