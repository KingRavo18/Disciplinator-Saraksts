<div id="addContentFullPage">
    <div id="addContentPopup">
        <div class="closeAddContent">
            <button onclick="closeAddContentPopup()" class="closeAddContentButton"></button>
        </div>
        <form method="post" action="./sendMovieData.php" enctype="multipart/form-data">
            <div class="uploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Bildes URL' : 'Image URL'; ?>
                    <input type="text" class="longInput" name="img_url"/>
                </label>
            </div>
            <p style="text-align: center; font-size: 14px;"><?= $_SESSION['page_language'] === 'lv' ? 'vai' : 'or'; ?></p>
            <div class="uploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Augšupielādēt attēlu' : 'Upload Image'; ?>
                    <input type="file" class="longInput" name="img_file" accept="image/*"/>
                </label>
            </div>
            <div class="uploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Nosaukums' : 'Title'; ?>
                    <input type="text" class="longInput" name="title" required/>
                </label>
            </div>
            <div class="uploadWindowWithLongInput">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Reitings' : 'Rating'; ?>
                    <input type="number" max="10" min="1" class="longInput" name="rating" required/>
                </label>
            </div>
            <div class="uploadWindowWithLongInput">
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
            <div id="EpisodeCountInput" class="uploadWindowWithLongInput" style="display: none;">
                <label>
                    <?= $_SESSION['page_language'] === 'lv' ? 'Sēriju skaits' : 'Episode Count'; ?>
                    <input type="number" class="longInput" name="episode_count" min="1">
                </label>
            </div>
            <div class="newEntrySubmit">
                <button class="newEntrySubmitButton" type="submit">
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
        const popup = document.getElementById("addContentPopup");
        const overlay = document.getElementById("addContentFullPage");
        
        overlay.style.display = "block";
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

    function toggleEpisodeInput(show) {
        const episodeInput = document.getElementById("episodeCountInput");
        episodeInput.style.display = show ? "block" : "none";
    }
</script>
