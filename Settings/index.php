<?php
session_start();
require '../Database/database.php'; // Assuming you have a file to connect to the database

// Check if the session has the necessary variables set
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

    // Handling form submission for changing username
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Handling username update
        if (isset($_POST['username'])) {
            $new_username = htmlspecialchars(trim($_POST['username']));
            if (!empty($new_username)) {
                // Update username in the database
                $stmt = $mysqli->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->bind_param("si", $new_username, $_SESSION['id']);
                if ($stmt->execute()) {
                    $_SESSION['username'] = $new_username; // Update session value
                    echo "Lietotājvārds veiksmīgi atjaunināts!";
                } else {
                    echo "Kļūda atjauninot lietotājvārdu.";
                }
                $stmt->close();
            }
        }

        // Handling email update
        if (isset($_POST['email'])) {
            $new_email = htmlspecialchars(trim($_POST['email']));
            if (!empty($new_email) && filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                // Update email in the database
                $stmt = $mysqli->prepare("UPDATE users SET email = ? WHERE id = ?");
                $stmt->bind_param("si", $new_email, $_SESSION['id']);
                if ($stmt->execute()) {
                    $_SESSION['email'] = $new_email; // Update session value
                    echo "E-pasts veiksmīgi atjaunināts!";
                } else {
                    echo "Kļūda atjauninot e-pastu.";
                }
                $stmt->close();
            }
        }

        // Handling profile picture upload
        if (isset($_POST['upload_picture'])) {
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = $_FILES['profile_picture']['type'];

                if (in_array($file_type, $allowed_types)) {
                    // Define where the image will be saved
                    $uploads_dir = '../uploads/profile_pictures/';
                    if (!is_dir($uploads_dir)) {
                        mkdir($uploads_dir, 0777, true); // Create directory if it doesn't exist
                    }

                    // Create a unique filename to prevent overwriting
                    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $new_filename = $_SESSION['id'] . '_' . time() . '.' . $file_extension;

                    // Move the uploaded file to the uploads directory
                    $file_path = $uploads_dir . $new_filename;
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
                        // Update the user's profile picture in the database
                        $stmt = $mysqli->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                        $stmt->bind_param("si", $file_path, $_SESSION['id']);
                        if ($stmt->execute()) {
                            $_SESSION['profile_picture'] = $file_path; // Update session value
                            echo "Profila bilde veiksmīgi atjaunināta!";
                        } else {
                            echo "Kļūda atjauninot profila bildi.";
                        }
                        $stmt->close();
                    } else {
                        echo "Neizdevās augšupielādēt failu.";
                    }
                } else {
                    echo "Atļauts augšupielādēt tikai JPG, PNG vai GIF formātā.";
                }
            } else {
                echo "Neizdevās augšupielādēt profila bildi.";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="lv">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="../Images/FistLogoCut.png" media="(prefers-color-scheme: light)">
        <link rel="icon" type="image/x-icon" href="../Images/FistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet" href="../Style/settings.css">
        <link rel="stylesheet" href="../Style/sidebar.css">
        <link rel="stylesheet" href="../Style/mainPageTopBar.css">
        <link rel="stylesheet" href="../Style/global.css"/>
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <title>Disciplinators - Iestatījumi</title>
    </head>
    <body>
        <main>
            <div class="PageTitle">
                <h1>IESTATĪJUMI</h1>
            </div>
            <?php
                require "../Accesories/mainPageTopBar.php";
                require "../Accesories/sidebar.php";
            ?>
            <section>
                <div class="settingsdiv">
                    <div class="SettingsTitle"><h2>PROFILS</h2></div>
                    <div class="ProfileArea1">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="ProfilePicture">
                                <div class="ProfileCircle">
                                    <!-- Check if profile_picture is set in the session -->
                                    <img src="<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../uploads/default_profile.jpg'; ?>" alt="Profile Picture">
                                </div>
                                <div class="FileInput">
                                    <input type="file" name="profile_picture">
                                </div>
                                <div class="FileInput">
                                    <button type="submit" name="upload_picture">Apstiprināt</button>
                                </div>
                            </div>
                        </form>
                        <div class="ProfileNames">
                            <!-- Check if session variables are set before displaying them -->
                            <p><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown User'; ?></p>
                            <p><?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Unknown Email'; ?></p>
                        </div>
                    </div>
                    <div class="ProfileArea2">
                        <div class="ChangeArea">
                            <form method="POST">
                                <input type="text" placeholder=" Jauns Lietotājvārds" name="username" required>
                                <button type="submit">Mainīt lietotājvārdu</button>
                            </form>
                        </div>
                        <div class="ChangeArea">
                            <form method="POST">
                                <input type="email" placeholder=" Jauns e-pasts" name="email" required>
                                <button type="submit">Mainīt e-pastu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
<?php 
} else {
    header("Location: login.php"); // Redirect to login if session is not set
    exit();
}
?>
