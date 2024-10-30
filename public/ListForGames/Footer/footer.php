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
                            <?= $_SESSION['page_language'] === 'lv' ? 'Izstrādātājs' : 'Producer'; ?>
                            <input type="text" class="uploadDeveloper-input" name="developer" required/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="uploadDeveloperAndGameCompletion">
                <div class="GameCompletion">
                    <div class="uploadGameCompletion">
                        <label>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Spēles Pabeigšana (%)' : 'Game Completion (%)'; ?>
                            <input type="number" max="100" min="0" class="uploadGameCompletion-input" name="game_completion" required/>
                        </label>
                    </div>
                </div>
                <div class="GameCompletion">
                    <div class="uploadGameCompletion">
                        <label>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                            <div><input type="number" max="10" min="1" class="uploadGameCompletion-input" name="rating" required/></div>
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
