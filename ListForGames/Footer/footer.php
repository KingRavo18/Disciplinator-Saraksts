<!--Style-->
<link rel="stylesheet" href="../Style/gameListFooter.css">
<!--A popup for new entries-->
<div id="AddContentFullPage" class="AddContentFullPage">
    <div id="AddContentPopup" class="AddContentPopup">
    <!--the button which closes the popup for new entries-->
        <div class="CloseAddContent">
            <button Onclick="closeAddContentPopup()" class="CloseAddContentButton"></button>
        </div>
    <!--FORM for submitting new list entries to the database-->
        <form method="post" action="./Footer/sendGameData.php">
            <div class="UploadWindowWithLongInput">
                <label>
                    Image URL
                    <input type="text" class="LongInput" name="img" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    Game's Title
                    <input type="text" class="LongInput" name="title" required/>
                </label>
            </div>
            <div class="uploadReleaseDateAndDescription">
                <div class="ReleaseDate">
                    <div class="uploadReleaseDate">
                        <label>
                            Relase Date
                            <input type="date" class="uploadReleaseDate-input" name="release_date" required/>
                        </label>
                    </div>
                </div>
                <div class="Developer">
                    <div class="uploadDeveloper">
                        <label>
                            Developer
                            <input type="text" class="uploadDeveloper-input" name="developer" required/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="uploadDeveloperAndGameCompletion">
                <div class="GameCompletion">
                    <div class="uploadGameCompletion">
                        <label>
                            Game Completion
                            <input type="number" max="100" min="0" class="uploadGameCompletion-input" name="game_completion" required/>
                        </label>
                    </div>
                </div>
                <div class="GameCompletion">
                    <div class="uploadGameCompletion">
                        <label>
                            Rating
                            <div><input type="number" max="10" min="1" class="uploadGameCompletion-input" name="rating" required/></div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="NewEntrySubmit">
                <button class="NewEntrySubmitButton" type="submit">Add</button>
            </div>
        </form>
    </div>
</div>
<!--makes the footer, inside of which is located the button for making the popup for new entries appear, appears-->
<footer>
    <button onclick="OpenAddContentPopup()" title="Add a new entry to this list">New Entry</button>
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
