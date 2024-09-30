<?php
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to view your game list.");
    }
    $user_id = $_SESSION['user_id'];
    require "../Database/database.php"; 
    $sql = "SELECT id, img, title, release_date, description, developer, game_completion, rating 
            FROM games 
            WHERE user_id = ? 
            ORDER BY title";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($ListArticle = $result->fetch_assoc()) {
        if (!$ListArticle["id"] || !$ListArticle["img"] || !$ListArticle["title"] || !$ListArticle["release_date"] || !$ListArticle["description"] || !$ListArticle["developer"] || !$ListArticle["game_completion"] || !$ListArticle["rating"]) {
            die("There is an empty result. Execution has been halted");
        }
?>
        <article id="ListBorderColor">
            <img class="ShowListImg" src="<?=$ListArticle["img"]?>" alt="<?=$ListArticle["title"]?> Title Image"/>
            <p class="ShowListTitle">
                <?=$ListArticle["title"]?>
            </p>
            <p class="ListArticle">
                Release Date: <?=$ListArticle["release_date"]?> 
            </p>
            <div class="ListArticle">
                <div class="ShowListDescription"> 
                    <span>Description</span>
                    <div class="ShowListDescription-Content"> 
                        <p><?=$ListArticle["description"]?></p>
                    </div>
                </div>
            </div>
            <p class="ListArticle">
                Developer: <?=$ListArticle["developer"]?>
            </p>
            <p class="ListArticle">
                Game Completion: <?=$ListArticle["game_completion"]?>%
            </p>
            <p class="ShowListRating">
                Rating: <?=$ListArticle["rating"]?>
            </p>
        </article>
<?php 
    }
    
    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
?>