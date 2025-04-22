<div id="addContentFullPage">
    <div id="addContentPopup">
        <div class="closeAddContent">
            <button Onclick="closeAddContentPopup()" class="closeAddContentButton"></button>
        </div>
        <form method="post" action="./sendBookData.php" enctype="multipart/form-data">
            <div class="uploadWindowWithLongInput">
                <input type="text" class="longInput" name="img_url" placeholder="<?= $language === 'lv' ? 'Bildes URL' : 'Image URL'; ?>"/>
            </div>
            <p style="text-align: center; font-size: 14px;"><?= $language === 'lv' ? 'vai' : 'or'; ?></p>
            <div class="uploadWindowWithLongInput">
                <input type="file" class="longInput" name="img_file" accept="image/*" title="<?= $language === 'lv' ? 'Augšupielādēt attēlu' : 'Upload Image'; ?>"/>
            </div>
            <div class="uploadWindowWithLongInput" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" id="useDefaultImage" name="use_default_image" />
                <label for="useDefaultImage" style="font-size: 14px;">
                    <?= $language === 'lv' ? 'Izmantot noklusējuma attēlu' : 'Use default image'; ?>
                </label>
            </div>
            <div class="uploadWindowWithLongInput">
                <input type="text" class="longInput" name="title" placeholder="<?= $language === 'lv' ? 'Grāmatas Nosaukums' : 'Book Title'; ?>" required/>
            </div>
            <div class="uploadWindowWithLongInput">
                <input type="number" max="10" min="1" class="longInput" name="rating" placeholder="<?= $language === 'lv' ? 'Reitings' : 'Rating'; ?>" required/>
            </div>
            <div class="newEntrySubmit">
                <button class="newEntrySubmitButton" type="submit"><?= $language === 'lv' ? 'Pievienot' : 'Add'; ?></button>
            </div>
        </form>
    </div>
</div>
<footer>
    <button onclick="OpenAddContentPopup()" title="Add a new entry to this list"><?= $language === 'lv' ? 'Jauns Ieraksts' : 'New Entry'; ?></button>
</footer>
<script>
    function OpenAddContentPopup() {
    const popup = document.getElementById("addContentPopup");
    const overlay = document.getElementById("addContentFullPage");
    
    overlay.style.display = "flex";
    popup.classList.remove("hide"); 
    popup.classList.add("show");  
    popup.style.display = "block";
}

function closeAddContentPopup() {
    const popup = document.getElementById("addContentPopup");
    const overlay = document.getElementById("addContentFullPage");
    
    popup.classList.remove("show"); 
    popup.classList.add("hide");    
    setTimeout(() => {
        popup.style.display = "none"; 
        overlay.style.display = "none";
    }, 300); 
}
document.getElementById("useDefaultImage").addEventListener("change", function () {
    const urlInput = document.querySelector('input[name="img_url"]');
    const fileInput = document.querySelector('input[name="img_file"]');

    if (this.checked) {
        urlInput.disabled = true;
        fileInput.disabled = true;
        urlInput.value = "";
        fileInput.value = "";
    } else {
        urlInput.disabled = false;
        fileInput.disabled = false;
    }
});
</script>
