<?php
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to view your game list.");
    }
    $user_id = $_SESSION['user_id'];
    require "../../Database/database.php"; 
    $sql = "SELECT id, img, title, release_date, developer, game_completion, rating 
            FROM games 
            WHERE user_id = ? 
            ORDER BY title";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($ListArticle = $result->fetch_assoc()) {
        if (!$ListArticle["id"] || !$ListArticle["img"] || !$ListArticle["title"] || !$ListArticle["release_date"] || !$ListArticle["developer"] || !$ListArticle["game_completion"] || !$ListArticle["rating"]) {
            die("There is an empty result. Execution has been halted");
        }
?>
        <article id="ListBorderColor" style="border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
            <div class="ListImageContainer">
                <img class="ShowListImg" src="<?=$ListArticle["img"]?>" alt="<?=$ListArticle["title"]?> Title Image"/>
                <div class="DeleteListEntryArea">
                    <button onclick="deleteEntry(<?=$ListArticle['id']?>)">&#x2715;</button>
                </div>
            </div>
            <p class="ShowListTitle">
                <?=$ListArticle["title"]?>
            </p>
            <p class="ListArticle">
            <?= $_SESSION['page_language'] === 'lv' ? 'Izlaides Datums:' : 'Release Date:'; ?> <?=$ListArticle["release_date"]?> 
            </p>
            <p class="ListArticle">
            <?= $_SESSION['page_language'] === 'lv' ? 'Izstrādātājs:' : 'Producer:'; ?> <?=$ListArticle["developer"]?>
            </p>
            <p class="ListArticle">
            <?= $_SESSION['page_language'] === 'lv' ? 'Spēles Pabeigšana: ' : 'Game Completion:'; ?> <?=$ListArticle["game_completion"]?>%
            </p>
            <p class="ListArticle">
            <?= $_SESSION['page_language'] === 'lv' ? 'Reitings:' : 'Rating:'; ?> <?=$ListArticle["rating"]?>
            </p>
        </article>
<?php 
    }
    $stmt->close();
    $mysqli->close();
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
                location.reload(); 
            } else {
                alert("Error: Could not delete the entry.");
            }
        };
        xhr.send("game_id=" + gameId);
    }
}
</script>