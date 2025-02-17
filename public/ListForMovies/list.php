<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your game list.");
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
        <div class="ListImageContainer">
            <img class="ShowListImg" src="<?=$imageSource?>" alt="<?=htmlspecialchars($ListArticle["title"])?> Title Image"/>
            <div class="DeleteListEntryArea">
                <button onclick="deleteEntry(<?=$ListArticle['id']?>)">&#x2715;</button>
            </div>
        </div>
        <p class="ShowListTitle">
            <?=htmlspecialchars($ListArticle["title"])?>
        </p>
        <div style="display: flex; gap: 10px;">
            <?php if ($ListArticle["type"] === "tv_show"): ?>
                <p class="ShowListRating">
                    <?= $_SESSION['page_language'] === 'lv' ? 'Sērijas: ' : 'Episodes: '; ?>
                    <span 
                        contenteditable="true" 
                        class="EditableEpisodeCount" 
                        onblur="updateEpisodeCount(<?=$ListArticle['id']?>, this.textContent)">
                        <?=$ListArticle["episode_count"]?>
                    </span>
                </p>
            <?php else: ?>
                <p class="ShowListRating">
                    <?= $_SESSION['page_language'] === 'lv' ? 'Filma' : 'Movie'; ?>
                </p>
            <?php endif; ?>
            <p class="ShowListRating">
                <?=htmlspecialchars($ListArticle["rating"])?>/10
            </p>
        </div>
    </article>
<?php 
}
?>

<script>
function deleteEntry(movieId) {
    if (confirm("Are you sure you want to delete this movie?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText); 
                const articleElement = document.querySelector(`#ListBorderColor[data-id='${movieId}']`);
                if (articleElement) {
                    articleElement.remove();
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
        if (xhr.status === 200) {
            alert("Episode count updated successfully.");
        } else {
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
</script>
