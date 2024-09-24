<div class="sidebar">
    <ul>
        <li><a href="../MainPage/Index.php"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">lists</span></span><span class="label">Saraksts</span></a></li>
        <li><a href="#"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">event</span></span><span class="label">Ilgtermiņa plānotājs</span></a></li>
        <li><a href="#"><span class="material-symbols-outlined"><span class="material-symbols-outlined" id="ToDoList">event_list</span></span><span class="label">Dienas plānotājs</span></a></li>
        <li><a href="../Settings/index.php"><span class="material-symbols-outlined">settings</span><span class="label">Iestatījumi</span></a></li>
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