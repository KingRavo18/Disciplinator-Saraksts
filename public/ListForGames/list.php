<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your game list.");
}

$user_id = $_SESSION['user_id'];
require "../../Database/database.php";

$sql = "SELECT id, img_url, img_file_path, title, rating, times_played, notes, date
        FROM games 
        WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$gameList = [];
while ($row = $result->fetch_assoc()) {
    $gameList[] = $row;
}

$stmt->close();
$mysqli->close();

usort($gameList, function ($a, $b) {
    return strnatcmp($a['title'], $b['title']);
});
$counter=1;
if ($result->num_rows > 0):
foreach ($gameList as $ListArticle) {
    if (!$ListArticle["id"] || (!$ListArticle["img_url"] && !$ListArticle["img_file_path"]) || !$ListArticle["title"] || !$ListArticle["rating"]) {
        error_log("Invalid game entry found: " . json_encode($ListArticle));
        continue;
    }

    $imageSource = $ListArticle["img_file_path"] ? $ListArticle["img_file_path"] : $ListArticle["img_url"];
?>
    <article id="ListBorderColor" 
        data-id="<?=$ListArticle['id']?>" 
        data-title="<?=htmlspecialchars(strtolower($ListArticle['title']))?>" 
        data-rating="<?=htmlspecialchars($ListArticle['rating'])?>" 
        data-date="<?=strtotime($ListArticle['date'])?>" 
        style="cursor:auto; border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
    <div class="listImageContainer">
        <img class="showListImg" id="entryImage-<?=$ListArticle['id']?>" src="<?=$imageSource?>" alt="<?=htmlspecialchars($ListArticle["title"])?> Title Image"/>
        <div class="topFlex">
            <p class="counter"><?=$counter?>.</p>
            <button class="openPopupBtn" onclick="openPopup(<?=$ListArticle['id']?>)"><span class="material-symbols-outlined">notes</span></button> 
            <button class="openPopupBtn" onclick="openImageUpdatePopup(<?=$ListArticle['id']?>)""><span class="material-symbols-outlined">image</span></button>
        </div> 

        <div class="deleteEntry">
            <button onclick="deleteEntry(<?=$ListArticle['id']?>)">&#x2715;</button>
        </div>
    </div>
    <p class="showListTitle"><?=htmlspecialchars($ListArticle["title"])?></p>
    <p class="showListRating"><?=htmlspecialchars($ListArticle["rating"])?>/10</p>

    <!-- Hidden Popup -->
    <div class="entryPopup" id="popup-<?=$ListArticle['id']?>">
        <div class="popupContent">
            <div class="closePopup">
                <button onclick="closePopup(<?=$ListArticle['id']?>)">&#x2715;</button>
            </div>
            <h2><?= $language === 'lv' ? 'Reizes Spēlēts: ' : 'Times Played: '; ?>
                <span class="editableTimesPlayed" data-id="<?=$ListArticle['id']?>"><?=htmlspecialchars($ListArticle["times_played"])?></span>
            </h2>
            <p><?= $language === 'lv' ? 'Pieraksti: ' : 'Notes: '; ?>
                <span class="editableNotes" data-id="<?=$ListArticle['id']?>"><?=htmlspecialchars($ListArticle["notes"])?></span>
            </p>
        </div>
    </div>
    <!-- Hidden Image Update Popup -->
    <div class="entryPopup" id="popupImage-<?=$ListArticle['id']?>">
        <div class="popupImageContent">
            <div class="closePopup">
                <button onclick="closeImageUpdatePopup(<?=$ListArticle['id']?>)">&#x2715;</button>
            </div>
            <form method="post" action="./update_image.php" enctype="multipart/form-data">
                <input type="hidden" name="game_id" value="<?=$ListArticle['id']?>" />
    
                <div class="uploadWindowWithLongInput">
                    <input type="text" class="longInput" name="img_url" placeholder="<?= $language === 'lv' ? 'Bildes URL' : 'Image URL'; ?>"/>
                </div>

                <p style="text-align: center; font-size: 14px;"><?= $language === 'lv' ? 'vai' : 'or'; ?></p>

                <div class="uploadWindowWithLongInput">
                    <input type="file" class="longInput" name="img_file" accept="image/*" title="<?= $language === 'lv' ? 'Augšupielādēt attēlu' : 'Upload Image'; ?>"/>
                </div>

                <div class="uploadWindowWithLongInput" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="useDefaultImage-<?=$ListArticle['id']?>" name="use_default_image" value="1" />
                    <label for="useDefaultImage-<?=$ListArticle['id']?>" style="font-size: 14px;">
                        <?= $language === 'lv' ? 'Izmantot noklusējuma attēlu' : 'Use default image'; ?>
                    </label>
                </div>

                <div class="newEntrySubmit">
                    <button class="newEntrySubmitButton" type="submit"><?= $language === 'lv' ? 'Atjaunot Ieraksta Bildi' : 'Update Entry Image'; ?></button>
                </div>
            </form>
        </div>
    </div>
</article>

<?php 
$counter++;
}
else: 
?>
    <p class="no-entry-message"><?= $language === 'lv' ? 'Nav Ierakstu' : 'No Entries'; ?></p>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".showListTitle").forEach(makeTitleEditable);
    document.querySelectorAll(".showListRating").forEach(makeRatingEditable);
    document.querySelectorAll(".editableTimesPlayed").forEach(makeTimesPlayedEditable);
    document.querySelectorAll(".editableNotes").forEach(makeNotesEditable);
    document.querySelectorAll(".showListTitle").forEach((titleElement) => {
        resizeText(titleElement);
    });
});

function deleteEntry(gameId) {
    if (confirm("Are you sure you want to delete this game entry?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                var responseText = xhr.responseText.trim(); 
                if (responseText === "Success") { 
                    var article = document.querySelector(`[data-id="${gameId}"]`);
                    if (article) {
                        article.remove(); 
                    }
                } else {
                    alert(responseText); 
                }
            } else {
                alert("Error: Could not delete the entry.");
            }
        };
        xhr.send("game_id=" + gameId);
    }
}

function openPopup(gameId) {
    let popup = document.getElementById(`popup-${gameId}`);
    popup.style.display = "flex";
}

function closePopup(gameId) {
    let popup = document.getElementById(`popup-${gameId}`);
    popup.style.display = "none";
}

function openImageUpdatePopup(gameId) {
    let popupImage = document.getElementById(`popupImage-${gameId}`);
    popupImage.style.display = "flex";
}

function closeImageUpdatePopup(gameId) {
    let popupImage = document.getElementById(`popupImage-${gameId}`);
    popupImage.style.display = "none";
}

function sortGameList() {
    let articles = document.querySelectorAll('article');
    let sortedArticles = Array.from(articles).sort((a, b) => {
        return a.dataset.title.localeCompare(b.dataset.title);
    });
    let container = document.querySelector('.gameListContainer');

    sortedArticles.forEach((article, index) => {
        container.appendChild(article); 

        let counterElement = article.querySelector('.counter');
        if (counterElement) {
            counterElement.innerText = (index + 1) + ".";
        }
    });
}

function resizeText(titleElement) {
    let originalFontSize = parseFloat(window.getComputedStyle(titleElement).fontSize) || 14;
    
    while (titleElement.scrollHeight > titleElement.clientHeight && originalFontSize > 12) {
        originalFontSize -= 0.5;
        titleElement.style.fontSize = originalFontSize + "px";
    }
}

function makeTitleEditable(titleElement) {
    titleElement.addEventListener("click", function () {
        let currentTitle = this.innerText;
        let article = this.closest("article");
        let gameId = article.dataset.id;

        let input = document.createElement("input");
        input.type = "text";
        input.value = currentTitle;
        input.classList.add("editTitleInput");

        this.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            let newTitle = input.value.trim();
            if (newTitle !== "" && newTitle !== currentTitle) {
                updateGameTitle(gameId, newTitle, input, article);
            } else {
                restoreTitle(input, currentTitle);
            }
        });

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                input.blur();
            }
        });
    });
}

function updateGameTitle(gameId, newTitle, inputElement, article) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_title.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let titleElement = document.createElement("p");
            titleElement.classList.add("showListTitle");
            titleElement.innerText = newTitle;
            makeTitleEditable(titleElement);

            inputElement.replaceWith(titleElement);
            article.dataset.title = newTitle.toLowerCase(); 
            sortGameList();
        } else {
            alert("Error updating title.");
        }
    };

    xhr.send("game_id=" + gameId + "&title=" + encodeURIComponent(newTitle));
}

function restoreTitle(inputElement, originalTitle) {
    let titleElement = document.createElement("p");
    titleElement.classList.add("showListTitle");
    titleElement.innerText = originalTitle;
    makeTitleEditable(titleElement);
    inputElement.replaceWith(titleElement);
}

function makeRatingEditable(ratingElement) {
    ratingElement.addEventListener("click", function () {
        let currentRating = this.innerText.replace("/10", "").trim();
        let article = this.closest("article");
        let gameId = article.dataset.id;

        let input = document.createElement("input");
        input.type = "number";
        input.value = currentRating;
        input.min = 1;
        input.max = 10;
        input.classList.add("editRatingInput");

        this.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            let newRating = parseInt(input.value.trim(), 10);
            if (!isNaN(newRating) && newRating >= 1 && newRating <= 10 && newRating !== parseInt(currentRating)) {
                updateGameRating(gameId, newRating, input, article);
            } else {
                restoreRating(input, currentRating);
            }
        });

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                input.blur();
            }
        });
    });
}

function updateGameRating(gameId, newRating, inputElement, article) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_rating.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let ratingElement = document.createElement("p");
            ratingElement.classList.add("showListRating");
            ratingElement.innerText = newRating + "/10";
            makeRatingEditable(ratingElement);

            inputElement.replaceWith(ratingElement);
        } else {
            alert("Error updating rating.");
        }
    };

    xhr.send("game_id=" + gameId + "&rating=" + encodeURIComponent(newRating));
}

function restoreRating(inputElement, originalRating) {
    let ratingElement = document.createElement("p");
    ratingElement.classList.add("showListRating");
    ratingElement.innerText = originalRating + "/10";
    makeRatingEditable(ratingElement);
    inputElement.replaceWith(ratingElement);
}

function makeTimesPlayedEditable(element) {
    element.addEventListener("click", function () {
        let currentValue = this.innerText.trim();
        let gameId = this.dataset.id;

        let input = document.createElement("input");
        input.type = "number";
        input.value = currentValue;
        input.min = 0;
        input.classList.add("editTimesPlayedInput");

        this.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            let newValue = parseInt(input.value.trim(), 10);
            if (!isNaN(newValue) && newValue >= 0 && newValue !== parseInt(currentValue)) {
                updateTimesPlayed(gameId, newValue, input);
            } else {
                restoreTimesPlayed(input, currentValue);
            }
        });

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                input.blur();
            }
        });
    });
}

function updateTimesPlayed(gameId, newValue, inputElement) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_times_played.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let span = document.createElement("span");
            span.classList.add("editableTimesPlayed");
            span.dataset.id = gameId;
            span.innerText = newValue;
            makeTimesPlayedEditable(span);
            inputElement.replaceWith(span);
        } else {
            alert("Error updating times played.");
        }
    };

    xhr.send("game_id=" + gameId + "&times_played=" + encodeURIComponent(newValue));
}

function restoreTimesPlayed(inputElement, originalValue) {
    let span = document.createElement("span");
    span.classList.add("editableTimesPlayed");
    span.dataset.id = inputElement.dataset.id;
    span.innerText = originalValue;
    makeTimesPlayedEditable(span);
    inputElement.replaceWith(span);
}

function makeNotesEditable(element) {
    element.addEventListener("click", function () {
        let currentValue = this.innerText.trim();
        let gameId = this.dataset.id;

        let textarea = document.createElement("textarea");
        textarea.value = currentValue;
        textarea.classList.add("editNotesInput");

        this.replaceWith(textarea);
        textarea.focus();

        textarea.addEventListener("blur", function () {
            let newValue = textarea.value.trim();
            if (newValue !== currentValue) {
                updateNotes(gameId, newValue, textarea);
            } else {
                restoreNotes(textarea, currentValue);
            }
        });

        textarea.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                textarea.blur();
            }
        });
    });
}

function updateNotes(gameId, newValue, textareaElement) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_notes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let span = document.createElement("span");
            span.classList.add("editableNotes");
            span.dataset.id = gameId;
            span.innerText = newValue;
            makeNotesEditable(span);
            textareaElement.replaceWith(span);
        } else {
            alert("Error updating notes.");
        }
    };

    xhr.send("game_id=" + gameId + "&notes=" + encodeURIComponent(newValue));
}

function restoreNotes(textareaElement, originalValue) {
    let span = document.createElement("span");
    span.classList.add("editableNotes");
    span.dataset.id = textareaElement.dataset.id;
    span.innerText = originalValue;
    makeNotesEditable(span);
    textareaElement.replaceWith(span);
}
function resizeCounterFont(counterElement, minFontSize = 16) {
    counterElement.style.fontSize = ''; 
    let fontSize = parseFloat(window.getComputedStyle(counterElement).fontSize);
    
    while (counterElement.scrollWidth > counterElement.clientWidth && fontSize > minFontSize) {
        fontSize -= 1.3;
        counterElement.style.fontSize = fontSize + 'px';
    }
}

function updateCounter(value) {
    const counter = document.querySelector('.counter');
    counter.textContent = value;
    resizeCounterFont(counter);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.counter').forEach(el => resizeCounterFont(el));
});
document.querySelectorAll('.update-image-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const gameId = formData.get('game_id');
        const imageElement = document.querySelector(`#entryImage-${gameId}`);

        try {
            const res = await fetch('./update_image.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success && data.new_image) {
                if (imageElement) {
                    imageElement.src = data.new_image;
                }
            } else {
                alert(data.error || 'Something went wrong.');
            }
        } catch (err) {
            console.error(err);
            alert('Error while updating image.');
        }
    });
});
</script>