<!--Style-->
<link rel="stylesheet" href="../Style/bookListFooter.css">
<!--A popup for new entries-->
<div id="AddContentFullPage" class="AddContentFullPage">
    <div id="AddContentPopup" class="AddContentPopup">
    <!--the button which closes the popup for new entries-->
        <div class="CloseAddContent">
            <button Onclick="closeAddContentPopup()" class="CloseAddContentButton"></button>
        </div>
    <!--FORM for submitting new list entries to the database-->
        <form method="post" action="./Footer/sendMovieData.php">
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
                            <?= $_SESSION['page_language'] === 'lv' ? 'Izlaides Datums' : 'Release Date'; ?>
                            <input type="date" class="uploadReleaseDate-input" name="release_date" required/>
                        </label>
                    </div>
                </div>
                <div class="Developer">
                    <div class="uploadDeveloper">
                        <label>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Autors' : 'Author'; ?>
                            <input type="text" class="uploadDeveloper-input" name="director" required/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="uploadDeveloperAndGameCompletion">
                <div class="GameCompletion">
                    <div class="uploadGameCompletion">
                        <label>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                            <input type="number" max="10" min="1" class="LongInput" name="rating" required/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="NewEntrySubmit">
                <button class="NewEntrySubmitButton" type="submit"><?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?></button>
            </div>
        </form>
    </div>
</div>
<!--makes the footer, inside of which is located the button for making the popup for new entries appear, appears-->
<footer>
    <button onclick="OpenAddContentPopup()" title="Add a new entry to this list"><?= $_SESSION['page_language'] === 'lv' ? 'Jauns Ieraksts' : 'New Entry'; ?></button>
</footer>
<script>
// the function which opens the popup for new entries
    function OpenAddContentPopup() {
        var OpenAddContentPopup = document.getElementById("AddContentPopup").style.display = "block";
        var OpenAddContentFullPagePopup = document.getElementById("AddContentFullPage").style.display = "block";
    }
// the function which closes the popup for new entries
    function closeAddContentPopup() {
        var AddContentPopup = document.getElementById("AddContentPopup").style.display = "none";
        var AddContentFullPagePopup = document.getElementById("AddContentFullPage").style.display = "none";
    }
</script>
