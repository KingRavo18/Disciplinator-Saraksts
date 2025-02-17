<link rel="stylesheet" href="../Style/movieListFooter.css">
<div id="AddContentFullPage" class="AddContentFullPage">
    <div id="AddContentPopup" class="AddContentPopup">
        <div class="CloseAddContent">
            <button onclick="closeAddContentPopup()" class="CloseAddContentButton"></button>
        </div>
        <form method="post" action="./sendMovieData.php" enctype="multipart/form-data">
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Bildes URL' : 'Image URL'; ?>
                    <input type="text" class="LongInput" name="img_url"/>
                </label>
            </div>
            <p style="text-align: center; font-size: 14px;"><?= $_SESSION['page_language'] === 'lv' ? 'vai' : 'or'; ?></p>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Augšupielādēt attēlu' : 'Upload Image'; ?>
                    <input type="file" class="LongInput" name="img_file" accept="image/*"/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Nosaukums' : 'Title'; ?>
                    <input type="text" class="LongInput" name="title" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                    <input type="number" max="10" min="1" class="LongInput" name="rating" required/>
                </label>
            </div>
            <div class="UploadWindowWithLongInput">
                <label>
                    <div>
                    <label>
                            <input type="radio" name="type" value="movie" onclick="adjustPopupHeight()" required>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Filma' : 'Movie'; ?>
                        </label>
                        <label>
                            <input type="radio" name="type" value="tv_show" onclick="adjustPopupHeight()" required>
                            <?= $_SESSION['page_language'] === 'lv' ? 'Seriāls' : 'TV Show'; ?>
                        </label>
                    </div>
                </label>
            </div>
            <div id="EpisodeCountInput" class="UploadWindowWithLongInput" style="display: none;">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Sēriju skaits' : 'Episode Count'; ?>
                    <input type="number" class="LongInput" name="episode_count" min="1">
                </label>
            </div>
            <div class="NewEntrySubmit">
                <button class="NewEntrySubmitButton" type="submit">
                    <?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?>
                </button>
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

    function toggleEpisodeInput(show) {
        const episodeInput = document.getElementById("episodeCountInput");
        episodeInput.style.display = show ? "block" : "none";
    }
</script>
