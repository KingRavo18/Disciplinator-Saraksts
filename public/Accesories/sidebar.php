<div class="sidebar" id="sidebar" style="border-right: 2px solid <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
    <ul>
        <li><a href="../Home/Index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="toDoList">home</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Māja' : 'Home'; ?></span></a></li>
        <li><a href="../MainPage/Index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="toDoList">lists</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Darāmo Darbu Saraksts' : 'To Do List'; ?></span></a></li>
        <li><a href="../ListForGames/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="toDoList">sports_esports</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Vidiospēļu Saraksts' : 'Videogame List'; ?></span></a></li>
        <li><a href="../ListForBooks/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="toDoList">book_ribbon</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Grāmatu Saraksts' : ' Book List'; ?></span></a></li>
        <li><a href="../ListForMovies/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="toDoList">live_tv</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Filmu Saraksts' : 'Movie List'; ?></span></a></li>
        <li><a href="../Leaderboard/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="toDoList">trophy</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Līderu Saraksts' : 'Leaderboard'; ?></span></a></li>
        <li><a href="../Settings/index.php"><span class="material-symbols-outlined">settings</span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Iestatījumi' : 'Settings'; ?></span></a></li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator'): ?>
            <li><a href="../Administrator/index.php"><span class="material-symbols-outlined">admin_panel_settings</span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Administratora Logs' : 'Administrator Window'; ?></span></a></li>
        <?php endif; ?>
        <li><a onclick="ShowLogoutArea()"><span class="material-symbols-outlined">logout</span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Atslēgties' : 'Log Out'; ?></span></a></li>
    </ul>
</div>
<div id="logoutFullArea">
    <div id="logoutArea">
        <div id="logoutTitle">
            <h2><?= $_SESSION['page_language'] === 'lv' ? 'Vai esat pārliecināti?' : 'Are you sure?'; ?></h2>
        </div>
        <div class="logoutYesNoButtons">
                <a href="../MainPage/logout.php"><?= $_SESSION['page_language'] === 'lv' ? 'Jā' : 'Yes'; ?></a>
                <button onclick="HideLogoutArea()"><?= $_SESSION['page_language'] === 'lv' ? 'Nē' : 'No'; ?></button>
        </div>
    </div>
</div>
<script>
    function ShowLogoutArea(){
        const popup = document.getElementById("logoutArea");
        const overlay = document.getElementById("logoutFullArea");
    
        overlay.style.display = "block";
        popup.classList.remove("hide");
        popup.classList.add("show");   
        popup.style.display = "block";
    }
    function HideLogoutArea(){
        const popup = document.getElementById("logoutArea");
        const overlay = document.getElementById("logoutFullArea");
    
    popup.classList.remove("show"); 
    popup.classList.add("hide");   
    setTimeout(() => {
        popup.style.display = "none"; 
        overlay.style.display = "none";
    }, 300);
    }
</script>