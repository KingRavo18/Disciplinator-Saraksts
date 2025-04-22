<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your movie list.");
}
$user_id = $_SESSION['user_id'];
require "../../Database/database.php"; 

$sql = "SELECT id, img_url, img_file_path, title, rating, type, episode_count, times_watched, notes, date
        FROM movies 
        WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$movieList = [];
while ($row = $result->fetch_assoc()) {
    $movieList[] = $row;
}

$stmt->close();
$mysqli->close();

usort($movieList, function ($a, $b) {
    return strnatcmp($a['title'], $b['title']);
});
$counter = 1;
if ($result->num_rows > 0):
foreach ($movieList as $ListArticle) {
    if (!$ListArticle["id"] || (!$ListArticle["img_url"] && !$ListArticle["img_file_path"]) || !$ListArticle["title"] || !$ListArticle["rating"]) {
        error_log("Invalid movie entry found: " . json_encode($ListArticle));
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
        <p class="showListTitle">
            <?=htmlspecialchars($ListArticle["title"])?>
        </p>
        <div style="display: flex; gap: 10px;">
            <?php if ($ListArticle["type"] === "tv_show"): ?>
                <p class="showListCounter">
                    <?= $language === 'lv' ? 'Sērijas: ' : 'Episodes: '; ?>
                    <span 
                        contenteditable="true" 
                        class="EditableEpisodeCount" 
                        onblur="updateEpisodeCount(<?=$ListArticle['id']?>, this.textContent)">
                        <?=$ListArticle["episode_count"]?>
                    </span>
                </p>
            <?php else: ?>
                <p class="showListCounter" style="cursor: auto;">
                    <?= $language === 'lv' ? 'Filma' : 'Movie'; ?>
                </p>
            <?php endif; ?>
            <p class="showListRating">
                <?=htmlspecialchars($ListArticle["rating"])?>/10
            </p>
        </div>

        <!-- Hidden Popup -->
        <div class="entryPopup" id="popup-<?=$ListArticle['id']?>">
            <div class="popupContent">
                <div class="closePopup">
                    <button onclick="closePopup(<?=$ListArticle['id']?>)">&#x2715;</button>
                </div>
                <h2><?= $language === 'lv' ? 'Reizes Redzēts: ' : 'Times Watched: '; ?>
                    <span class="editableTimesWatched" data-id="<?=$ListArticle['id']?>"><?=htmlspecialchars($ListArticle["times_watched"])?></span>
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
                    <input type="hidden" name="movie_id" value="<?=$ListArticle['id']?>" />
    
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
function openImageUpdatePopup(movieId) {
    let popupImage = document.getElementById(`popupImage-${movieId}`);
    popupImage.style.display = "flex";
}

function closeImageUpdatePopup(movieId) {
    let popupImage = document.getElementById(`popupImage-${movieId}`);
    popupImage.style.display = "none";
}
function deleteEntry(movieId) {
    if (confirm("Are you sure you want to delete this movie entry?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                var responseText = xhr.responseText.trim(); 
                if (responseText === "Success") { 
                    var article = document.querySelector(`article[data-id="${movieId}"]`);
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
        xhr.send("movie_id=" + movieId);
    }
}
function updateEpisodeCount(movieId, newCount) {
    if (!newCount || isNaN(newCount) || parseInt(newCount) < 1) {
        alert("Invalid episode count. Please enter a valid number.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_episode_count.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (!xhr.status === 200) { 
            alert("Error: Could not update the episode count.");
        }
    };
    xhr.send("movie_id=" + movieId + "&episode_count=" + newCount);
}

document.querySelectorAll(".EditableEpisodeCount").forEach((element) => {
    element.addEventListener("input", (event) => {
        const newValue = event.target.textContent.trim();
        if (newValue && !isNaN(newValue) && parseInt(newValue) > 0) {
            element.style.color = "rgb(51, 51, 51)"; 
        } else {
            element.style.color = "red"; 
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".showListTitle").forEach(makeTitleEditable);
    document.querySelectorAll(".showListRating").forEach(makeRatingEditable);
});

function makeTitleEditable(titleElement) {
    titleElement.addEventListener("click", function () {
        let currentTitle = this.innerText;
        let article = this.closest("article");
        let movieId = article.dataset.id;

        let input = document.createElement("input");
        input.type = "text";
        input.value = currentTitle;
        input.classList.add("editTitleInput");

        this.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            let newTitle = input.value.trim();
            if (newTitle !== "" && newTitle !== currentTitle) {
                updateMovieTitle(movieId, newTitle, input, article);
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

function updateMovieTitle(movieId, newTitle, inputElement, article) {
    if (!newTitle.trim()) {
        alert("Title cannot be empty.");
        return;
    }

    fetch("update_title.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ movie_id: movieId, title: newTitle })
    })
    .then(response => {
        if (!response.ok) throw new Error("Failed to update title.");
        return response.text();
    })
    .then(responseText => {
        if (responseText.trim() === "Success") {
            let titleElement = document.createElement("p");
            titleElement.classList.add("showListTitle");
            titleElement.textContent = newTitle;
            makeTitleEditable(titleElement);

            inputElement.replaceWith(titleElement);
            article.dataset.title = newTitle.toLowerCase();
        } else {
            throw new Error(responseText);
        }
    })
    .catch(error => {
        alert("Error updating title: " + error.message);
    });
}
function makeRatingEditable(ratingElement) {
    ratingElement.addEventListener("click", function () {
        let currentRating = this.innerText.replace("/10", "").trim();
        let article = this.closest("article");
        let movieId = article.dataset.id;

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
                updateMovieRating(movieId, newRating, input, article);
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

function updateMovieRating(movieId, newRating, inputElement, article) {
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

    xhr.send("movie_id=" + movieId + "&rating=" + encodeURIComponent(newRating));
}
function restoreTitle(inputElement, originalTitle) {
    let titleElement = document.createElement("p");
    titleElement.classList.add("showListTitle");
    titleElement.innerText = originalTitle;
    makeTitleEditable(titleElement);
    inputElement.replaceWith(titleElement);
}
function restoreRating(inputElement, originalRating) {
    let ratingElement = document.createElement("p");
    ratingElement.classList.add("showListRating");
    ratingElement.innerText = originalRating + "/10";
    makeRatingEditable(ratingElement);
    inputElement.replaceWith(ratingElement);
}
function openPopup(movieId) {
    let popup = document.getElementById(`popup-${movieId}`);
    popup.style.display = "flex";
}

function closePopup(movieId) {
    let popup = document.getElementById(`popup-${movieId}`);
    popup.style.display = "none";
}
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".editableTimesWatched").forEach(makeTimesWatchedEditable);
    document.querySelectorAll(".editableNotes").forEach(makeNotesEditable);
});

function makeTimesWatchedEditable(element) {
    element.addEventListener("click", function () {
        let movieId = this.dataset.id; // Ensure we get the movie ID
        if (!movieId) {
            console.error("Error: movieId is undefined for element", this);
            return;
        }

        let currentValue = this.innerText.trim();
        let input = document.createElement("input");
        input.type = "number";
        input.value = currentValue;
        input.dataset.id = movieId; 

        input.addEventListener("blur", function () {
            let newValue = this.value.trim();
            let movieId = this.dataset.id;

            if (!movieId) {
                console.error("Error: movieId is undefined in input field", this);
                return;
            }

            updateTimesWatched(movieId, newValue, input);
        });

        this.replaceWith(input);
        input.focus();
    });
}

function updateTimesWatched(movieId, newValue, inputElement) {
    console.log("Updating movie ID:", movieId, "New Value:", newValue); 

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_times_watched.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        console.log("Response from server:", xhr.responseText); 

        if (xhr.status === 200 && xhr.responseText.trim() === "Success") {
            let span = document.createElement("span");
            span.classList.add("editableTimesWatched");
            span.dataset.id = movieId;
            span.innerText = newValue;
            makeTimesWatchedEditable(span);
            inputElement.replaceWith(span);
        } else {
            alert("Error updating times watched.");
        }
    };

    xhr.send("movie_id=" + movieId + "&times_watched=" + encodeURIComponent(newValue));
}

function restoreTimesWatched(inputElement, originalValue) {
    let span = document.createElement("span");
    span.classList.add("editableTimesWatched");
    span.dataset.id = inputElement.dataset.id;
    span.innerText = originalValue;
    makeTimesWatchedEditable(span);
    inputElement.replaceWith(span);
}

function makeNotesEditable(element) {
    element.addEventListener("click", function () {
        let currentValue = this.innerText.trim();
        let movieId = this.dataset.id;

        let textarea = document.createElement("textarea");
        textarea.value = currentValue;
        textarea.classList.add("editNotesInput");

        this.replaceWith(textarea);
        textarea.focus();

        textarea.addEventListener("blur", function () {
            let newValue = textarea.value.trim();
            if (newValue !== currentValue) {
                updateNotes(movieId, newValue, textarea);
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

function updateNotes(movieId, newValue, textareaElement) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_notes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let span = document.createElement("span");
            span.classList.add("editableNotes");
            span.dataset.id = movieId;
            span.innerText = newValue;
            makeNotesEditable(span);
            textareaElement.replaceWith(span);
        } else {
            alert("Error updating notes.");
        }
    };

    xhr.send("movie_id=" + movieId + "&notes=" + encodeURIComponent(newValue));
}

function restoreNotes(textareaElement, originalValue) {
    let span = document.createElement("span");
    span.classList.add("editableNotes");
    span.dataset.id = textareaElement.dataset.id;
    span.innerText = originalValue;
    makeNotesEditable(span);
    textareaElement.replaceWith(span);
}
function resizeText(titleElement) {
    let originalFontSize = parseFloat(window.getComputedStyle(titleElement).fontSize);
    
    while (titleElement.scrollHeight > titleElement.clientHeight && originalFontSize > 12) {
        originalFontSize -= 0.5;
        titleElement.style.fontSize = originalFontSize + "px";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".showListTitle").forEach((titleElement) => {
        resizeText(titleElement);
    });
});
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
        const movieId = formData.get('movie_id');
        const imageElement = document.querySelector(`#entryImage-${movieId}`);

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
