<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div>
    <div class="settingsTitle"><h2 style="font-size: 13px"><?= $_SESSION['page_language'] === 'lv' ? 'LAPAS KRĀSA' : 'PAGE COLOUR'; ?></h2></div>
    <div class="themechange">
        <form method="POST" action="themeUpload.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <label class="container"><?= $_SESSION['page_language'] === 'lv' ? 'Zils' : 'Blue'; ?>
                <input type="radio" name="page_theme" onclick="blueButton()" value="navy" checked="checked">
                <span class="checkmarkBlue"></span>
            </label>
            <label class="container"><?= $_SESSION['page_language'] === 'lv' ? 'Sarkans' : 'Red'; ?>
                <input type="radio" name="page_theme" onclick="redButton()" value="rgb(128, 0, 0)">
                <span class="checkmarkRed"></span>
            </label>
            <label class="container"><?= $_SESSION['page_language'] === 'lv' ? 'Zaļš' : 'Green'; ?>
                <input type="radio" name="page_theme" onclick="greenButton()" value="rgb(0, 128, 0)">
                <span class="checkmarkGreen"></span>
            </label>
            <label class="container"><?= $_SESSION['page_language'] === 'lv' ? 'Violēts' : 'Purple'; ?>
                <input type="radio" name="page_theme" onclick="purpleButton()" value="purple">
                <span class="checkmarkPurple"></span>
            </label>
            <label class="container"><?= $_SESSION['page_language'] === 'lv' ? 'Rozā' : 'Pink'; ?>
                <input type="radio" name="page_theme" onclick="pinkButton()" value="rgb(230, 24, 233)">
                <span class="checkmarkPink"></span>
            </label>
            <label class="container"><?= $_SESSION['page_language'] === 'lv' ? 'Melns' : 'Black'; ?>
                <input type="radio" name="page_theme" onclick="blackButton()" value="rgb(0, 0, 0)">
                <span class="checkmarkBlack"></span>
            </label>
            <button type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Apstiprināt' : 'Upload'; ?></button>
        </form>
    </div>
    </div>
<script src="defaultColours.js" defer></script>
