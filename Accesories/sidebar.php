<div class="sidebar" id="sidebar" style="background-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
    <ul>
        <li><a href="../MainPage/Index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">lists</span></span><span class="label">Darāmo Darbu Saraksts</span></a></li>
        <li><a href="../ListForGames/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">sports_esports</span></span><span class="label">Vidiospēļu Saraksts</span></a></li>
        <li><a href="../ListForBooks/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">book_ribbon</span></span><span class="label">Grāmatu Saraksts</span></a></li>
        <li><a href="../ListForMovies/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">live_tv</span></span><span class="label">Filmu Saraksts</span></a></li>
        <li><a href="../Leaderboard/index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">trophy</span></span><span class="label">Līderu Saraksts</span></a></li>
        <li><a href="../Settings/index.php"><span class="material-symbols-outlined">settings</span><span class="label">Iestatījumi</span></a></li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator'): ?>
            <li><a href="../Administrator/index.php"><span class="material-symbols-outlined">admin_panel_settings</span><span class="label">Administratora Logs</span></a></li>
        <?php endif; ?>
        <li><a onclick="ShowLogoutArea()"><span class="material-symbols-outlined">logout</span><span class="label">Atslēgties</span></a></li>
    </ul>
</div>
<div id="LogoutFullArea">
    <div id="LogoutArea">
        <div id="LogoutTitle">
            <h2>Vai esat pārliecināti?</h2>
        </div>
        <div class="LogoutYesNoButtons">
                <a href="../MainPage/logout.php">Jā</a>
                <button onclick="HideLogoutArea()">Nē</button>
        </div>
    </div>
</div>
<script>
    function ShowLogoutArea(){
        var ShowLogout = document.getElementById("LogoutArea").style.display = "block";
        var ShowLogoutArea = document.getElementById("LogoutFullArea").style.display = "block";
    }
    function HideLogoutArea(){
        var HideLogout = document.getElementById("LogoutArea").style.display = "none";
        var HideLogoutArea = document.getElementById("LogoutFullArea").style.display = "none";
    }
</script>