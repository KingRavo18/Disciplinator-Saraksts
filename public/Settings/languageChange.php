<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div>
     <div class="settingsTitle"><h2 style="font-size: 13px"><?= $_SESSION['page_language'] === 'lv' ? 'LAPAS VALODA' : 'PAGE LANGUAGE'; ?></h2></div>
     <div class="themechange">
        <form method="POST" action="languageUpload.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <select name="page_language">
                <option value="lv" <?= $_SESSION['page_language'] === 'lv' ? 'selected' : ''; ?>>Latviešu</option>
                <option value="en" <?= $_SESSION['page_language'] === 'en' ? 'selected' : ''; ?>>English</option>
            </select>
            <div class="langChangeButtonEnd">
                <button type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Apstiprināt' : 'Upload'; ?></button>
            </div>
        </form>
     </div>
</div>