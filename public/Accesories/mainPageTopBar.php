<header id="TopBar" style="background-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">   
    <div id="PageTitle">
        <a href="../MainPage/Index.php">DISCIPLINATORS</a>
    </div>
    <div id="PointArea">
        <div><p><?= $_SESSION['page_language'] === 'lv' ? 'PUNKTI:' : 'POINTS:'; ?> <?= $_SESSION['points']; ?></p></div>
        <div class="TopBarProfilePicture"><img src="<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../Images/default_profile.jpg'; ?>" alt="Profile Picture"></div>
    </div>
</header>