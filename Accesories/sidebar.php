<div class="sidebar">
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="../Settings/index.php">Iestatījumi</a></li>
        <li><a onclick="ShowLogoutArea()">Atslēgties</a></li>
    </ul>
</div>
<div id="LogoutFullArea">
    <div id="LogoutArea">
        <div id="LogoutTitle">
            <h2>Vai esat pārliecināti?</h2>
        </div>
        <div class="LogoutYesNoButtons">
                <a href="logout.php">Jā</a>
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