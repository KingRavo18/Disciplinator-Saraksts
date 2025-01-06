<div class="sidebar" id="sidebar" style="border-right: 2px solid <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
    <ul>
        <li><a href="../Home/Index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">home</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Māja' : 'Home'; ?></span></a></li>
        <li><a href="../MainPage/Index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">lists</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Darāmo Darbu Saraksts' : 'To Do List'; ?></span></a></li>
        <li><a href="../ListForGames/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">sports_esports</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Vidiospēļu Saraksts' : 'Videogame List'; ?></span></a></li>
        <li><a href="../ListForBooks/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">book_ribbon</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Grāmatu Saraksts' : ' Book List'; ?></span></a></li>
        <li><a href="../ListForMovies/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">live_tv</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Filmu Saraksts' : 'Movie List'; ?></span></a></li>
        <li><a href="../Leaderboard/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">trophy</span></span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Līderu Saraksts' : 'Leaderboard'; ?></span></a></li>
        <li><a href="../Settings/index.php"><span class="material-symbols-outlined">settings</span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Iestatījumi' : 'Settings'; ?></span></a></li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator'): ?>
            <li><a href="../Administrator/index.php"><span class="material-symbols-outlined">admin_panel_settings</span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Administratora Logs' : 'Administrator Window'; ?></span></a></li>
        <?php endif; ?>
        <li><a onclick="ShowLogoutArea()"><span class="material-symbols-outlined">logout</span><span class="label"><?= $_SESSION['page_language'] === 'lv' ? 'Atslēgties' : 'Log Out'; ?></span></a></li>
    </ul>
</div>
<div id="LogoutFullArea">
    <div id="LogoutArea">
        <div id="LogoutTitle">
            <h2><?= $_SESSION['page_language'] === 'lv' ? 'Vai esat pārliecināti?' : 'Are you sure?'; ?></h2>
        </div>
        <div class="LogoutYesNoButtons">
                <a href="../MainPage/logout.php"><?= $_SESSION['page_language'] === 'lv' ? 'Jā' : 'Yes'; ?></a>
                <button onclick="HideLogoutArea()"><?= $_SESSION['page_language'] === 'lv' ? 'Nē' : 'No'; ?></button>
        </div>
    </div>
</div>
<script>
    function ShowLogoutArea(){
        const popup = document.getElementById("LogoutArea");
        const overlay = document.getElementById("LogoutFullArea");
    
        overlay.style.display = "block";
        popup.classList.remove("hide"); // Remove the hide class if it exists
        popup.classList.add("show");   // Add the show class
        popup.style.display = "block"; // Ensure it is displayed
    }
    function HideLogoutArea(){
        const popup = document.getElementById("LogoutArea");
    const overlay = document.getElementById("LogoutFullArea");
    
    popup.classList.remove("show"); // Remove the show class if it exists
    popup.classList.add("hide");    // Add the hide class
    setTimeout(() => {
        popup.style.display = "none"; // Hide the popup after animation
        overlay.style.display = "none";
    }, 300); // Match the duration of the fadeOutDown animation
    }
</script>