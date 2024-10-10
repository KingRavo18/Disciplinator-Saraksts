<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="settingsThemediv">
    <div class="SettingsTitle"><h2>LAPAS KRĀSA</h2></div>
    <div class="themechange">
        <form method="POST" action="themeUpload.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <label class="container">Zils
                <input type="radio" name="page_theme" onclick="blueButton()" value="navy" checked="checked">
                <span class="checkmarkBlue"></span>
            </label>
            <label class="container">Sarkans
                <input type="radio" name="page_theme" onclick="redButton()" value="rgb(128, 0, 0)">
                <span class="checkmarkRed"></span>
            </label>
            <label class="container">Zaļš
                <input type="radio" name="page_theme" onclick="greenButton()" value="rgb(0, 128, 0)">
                <span class="checkmarkGreen"></span>
            </label>
            <label class="container">Violēts
                <input type="radio" name="page_theme" onclick="purpleButton()" value="purple">
                <span class="checkmarkPurple"></span>
            </label>
            <label class="container">Rozā
                <input type="radio" name="page_theme" onclick="pinkButton()" value="rgb(230, 24, 233)">
                <span class="checkmarkPink"></span>
            </label>
            <label class="container">Melns
                <input type="radio" name="page_theme" onclick="blackButton()" value="rgb(0, 0, 0)">
                <span class="checkmarkBlack"></span>
            </label>
            <button type="submit">Apstiprināt</button>
        </form>
    </div>
</div>
<script src="defaultColours.js" defer></script>
