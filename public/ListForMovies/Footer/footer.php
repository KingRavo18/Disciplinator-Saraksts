<link rel="stylesheet" href="../Style/movieListFooter.css">
<div id="AddContentFullPage" class="AddContentFullPage">
    <div id="AddContentPopup" class="AddContentPopup">
        <div class="CloseAddContent">
            <button Onclick="closeAddContentPopup()" class="CloseAddContentButton"></button>
        </div>
        <form method="post" action="./Footer/sendMovieData.php">
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Bildes URL' : 'Image URL'; ?>
                    <input type="text" class="LongInput" name="img" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Filmas Nosaukums' : 'Movie Name'; ?>
                    <input type="text" class="LongInput" name="title" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                    <input type="number" max="10" min="1" class="LongInput" name="rating" required/>
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
    popup.classList.remove("hide"); 
    popup.classList.add("show");  
    popup.style.display = "block"; 
}

function closeAddContentPopup() {
    const popup = document.getElementById("AddContentPopup");
    const overlay = document.getElementById("AddContentFullPage");
    
    popup.classList.remove("show"); 
    popup.classList.add("hide");  
    setTimeout(() => {
        popup.style.display = "none";
        overlay.style.display = "none";
    }, 300); 
}
</script>
