<link rel="stylesheet" href="../Style/bookListFooter.css">
<div id="AddContentFullPage" class="AddContentFullPage">
    <div id="AddContentPopup" class="AddContentPopup">
        <div class="CloseAddContent">
            <button Onclick="closeAddContentPopup()" class="CloseAddContentButton"></button>
        </div>
        <form method="post" action="footer/sendBookData.php">
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Bildes URL' : 'Image URL'; ?>
                    <input type="text" class="LongInput" name="img" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Grāmatas Nosaukums' : 'Book Title'; ?>
                    <input type="text" class="LongInput" name="title" required/>
                </label>
            </div>
            <div class="uploadReleaseDateAndDescription">
                <div class="ReleaseDate">
                    <div class="uploadReleaseDate">
                        <label>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                            <input type="number" max="10" min="1" class="LongInput" name="rating" required/>
                        </label>
                    </div>
                </div>
                <div class="Developer">
                    <div class="uploadDeveloper">
                        <label>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Autors' : 'Author'; ?>
                            <input type="text" class="uploadDeveloper-input" name="author" required/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="NewEntrySubmit">
                <button class="NewEntrySubmitButton2" type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?></button>
            </div>
        </form>
    </div>
</div>
<footer>
    <button onclick="OpenAddContentPopup()" title="Add a new entry to this list"><?= $_SESSION['page_language'] === 'lv' ? 'Jauns Ieraksts' : 'New Entry'; ?></button>
</footer>
<script>
    function OpenAddContentPopup() {
        var OpenAddContentPopup = document.getElementById("AddContentPopup").style.display = "block";
        var OpenAddContentFullPagePopup = document.getElementById("AddContentFullPage").style.display = "block";
    }
    function closeAddContentPopup() {
        var AddContentPopup = document.getElementById("AddContentPopup").style.display = "none";
        var AddContentFullPagePopup = document.getElementById("AddContentFullPage").style.display = "none";
    }
</script>
