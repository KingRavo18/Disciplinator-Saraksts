<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your movie list.");
}
$user_id = $_SESSION['user_id'];
require "../../Database/database.php"; 

$sql = "SELECT id, img_url, img_file_path, title, rating, type, episode_count 
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

foreach ($movieList as $ListArticle) {
    if (!$ListArticle["id"] || (!$ListArticle["img_url"] && !$ListArticle["img_file_path"]) || !$ListArticle["title"] || !$ListArticle["rating"]) {
        die("There is an empty result. Execution has been halted");
    }

    $imageSource = $ListArticle["img_file_path"] ? $ListArticle["img_file_path"] : $ListArticle["img_url"];
?>
    <article id="ListBorderColor" data-id="<?=$ListArticle['id']?>" style="cursor:auto; border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
        <div class="listImageContainer">
            <img class="showListImg" src="<?=$imageSource?>" alt="<?=htmlspecialchars($ListArticle["title"])?> Title Image"/>
            <div class="deleteListEntryArea">
                <button onclick="deleteEntry(<?=$ListArticle['id']?>)">&#x2715;</button>
            </div>
        </div>
        <p class="showListTitle">
            <?=htmlspecialchars($ListArticle["title"])?>
        </p>
        <div style="display: flex; gap: 10px;">
            <?php if ($ListArticle["type"] === "tv_show"): ?>
                <p class="showListCounter">
                    <?= $_SESSION['page_language'] === 'lv' ? 'Sērijas: ' : 'Episodes: '; ?>
                    <span 
                        contenteditable="true" 
                        class="EditableEpisodeCount" 
                        onblur="updateEpisodeCount(<?=$ListArticle['id']?>, this.textContent)">
                        <?=$ListArticle["episode_count"]?>
                    </span>
                </p>
            <?php else: ?>
                <p class="showListCounter">
                    <?= $_SESSION['page_language'] === 'lv' ? 'Filma' : 'Movie'; ?>
                </p>
            <?php endif; ?>
            <p class="showListRating">
                <?=htmlspecialchars($ListArticle["rating"])?>/10
            </p>
        </div>
    </article>
<?php 
}
?>

<script>
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
function adjustPopupHeight() {
    const popup = document.getElementById("AddContentPopup");
    const typeInput = document.querySelector('input[name="type"]:checked');
    const episodeCountInput = document.getElementById("EpisodeCountInput");

    if (typeInput && typeInput.value === "tv_show") {
        episodeCountInput.style.display = "block";
        popup.style.height = "auto"; 
    } else {
        episodeCountInput.style.display = "none";
        popup.style.height = "auto"; 
    }
}
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
            sortMovieList();
        } else {
            alert("Error updating title.");
        }
    };

    xhr.send("movie_id=" + movieId + "&title=" + encodeURIComponent(newTitle));
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
</script>
