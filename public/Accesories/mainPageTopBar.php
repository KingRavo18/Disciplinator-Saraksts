<header style="border-bottom: 2px solid <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">   
    <div id="pageTitle">
        <a href="../Home/index.php">DISCIPLINATORS</a>
    </div>
    <div id="pointArea">
        <div class="points"><p><?= $language === 'lv' ? 'PUNKTI:' : 'POINTS:'; ?> <?= $_SESSION['points']; ?></p></div>
        <div class="topBarProfilePicture"><img src="<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../Images/default_profile.jpg'; ?>" alt="Profile Picture"></div>
    </div>
</header>