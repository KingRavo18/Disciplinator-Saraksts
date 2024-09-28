<header id="TopBar">   
    <div id="PageTitle">
        <a href="../MainPage/Index.php">DISCIPLINATORS</a>
    </div>
    <div id="PointArea">
        <div><p>POINTS: <?= $_SESSION['points']; ?></p></div>
        <div class="TopBarProfilePicture"><img src="<?php isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../uploads/default_profile.png'; ?>" alt="Profile Picture"></div>
    </div>
</header>