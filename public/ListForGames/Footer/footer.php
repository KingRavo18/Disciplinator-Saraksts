<link rel="stylesheet" href="../Style/gameListFooter.css">
<div id="AddContentFullPage" class="AddContentFullPage">
    <div id="AddContentPopup" class="AddContentPopup">
        <div class="CloseAddContent">
            <button Onclick="closeAddContentPopup()" class="CloseAddContentButton"></button>
        </div>
        <form method="post" action="./Footer/sendGameData.php">
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Bildes URL' : 'Image URL'; ?>
                    <input type="text" class="LongInput" name="img" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Spēles Nosaukums' : 'Videogame Title'; ?>
                    <input type="text" class="LongInput" name="title" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                    <div><input type="number" max="10" min="1" class="LongInput" name="rating" required/></div>
                </label>
            </div>
            <div class="NewEntrySubmit">
                <button class="NewEntrySubmitButton" type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?></button>
            </div>
        </form>
    </div>
</div>
<footer>
    <button onclick="OpenAddContentPopup()" title="Add a new entry to this list"><?= $_SESSION['page_language'] === 'lv' ? 'Jauns Ieraksts' : 'New Entry'; ?></button>
</footer>
<script>
        function OpenAddContentPopup() {
    const popup = document.getElementById("AddContentPopup");
    const overlay = document.getElementById("AddContentFullPage");
    
    overlay.style.display = "block";
    popup.classList.remove("hide"); // Remove the hide class if it exists
    popup.classList.add("show");   // Add the show class
    popup.style.display = "block"; // Ensure it is displayed
}

function closeAddContentPopup() {
    const popup = document.getElementById("AddContentPopup");
    const overlay = document.getElementById("AddContentFullPage");
    
    popup.classList.remove("show"); // Remove the show class if it exists
    popup.classList.add("hide");    // Add the hide class
    setTimeout(() => {
        popup.style.display = "none"; // Hide the popup after animation
        overlay.style.display = "none";
    }, 300); // Match the duration of the fadeOutDown animation
}
</script>
