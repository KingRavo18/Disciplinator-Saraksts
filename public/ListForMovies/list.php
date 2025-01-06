<?php
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to view your game list.");
    }
    $user_id = $_SESSION['user_id'];
    require "../../Database/database.php"; 
    $sql = "SELECT id, img, title, rating 
            FROM movies 
            WHERE user_id = ? 
            ORDER BY title";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($ListArticle = $result->fetch_assoc()) {
        if (!$ListArticle["id"] || !$ListArticle["img"] || !$ListArticle["title"] || !$ListArticle["rating"]) {
            die("There is an empty result. Execution has been halted");
        }
?>
        <article id="ListBorderColor" style=" cursor:auto; border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
            <div class="ListImageContainer">
                <img class="ShowListImg" src="<?=$ListArticle["img"]?>" alt="<?=$ListArticle["title"]?> Title Image"/>
                <div class="DeleteListEntryArea">
                    <button onclick="deleteEntry(<?=$ListArticle['id']?>)">&#x2715;</button>
                </div>
            </div>
            <p class="ShowListTitle">
                <?=$ListArticle["title"]?>
            </p>
            <p class="ShowListRating">
                <?=$ListArticle["rating"]?>/10
            </p>
        </article>
<?php 
    }
    $stmt->close();
    $mysqli->close();
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
                location.reload();
            } else {
                alert("Error: Could not delete the entry.");
            }
        };
        xhr.send("movie_id=" + movieId);
    }
}
</script>
