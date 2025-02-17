<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your game list.");
}

$user_id = $_SESSION['user_id'];
require "../../Database/database.php";

$sql = "SELECT id, img_url, img_file_path, title, rating 
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

foreach ($gameList as $ListArticle) {
    if (!$ListArticle["id"] || (!$ListArticle["img_url"] && !$ListArticle["img_file_path"]) || !$ListArticle["title"] || !$ListArticle["rating"]) {
        die("There is an empty result. Execution has been halted.");
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
        <p class="showListRating">
            <?=htmlspecialchars($ListArticle["rating"])?>/10
        </p>
    </article>
<?php 
}
?>

<script>
function deleteEntry(gameId) {
    if (confirm("Are you sure you want to delete this game?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);

                var article = document.querySelector(`article[data-id="${gameId}"]`);
                if (article) {
                    article.remove();
                }
            } else {
                alert("Error: Could not delete the entry.");
            }
        };
        xhr.send("game_id=" + gameId);
    }
}
</script>
