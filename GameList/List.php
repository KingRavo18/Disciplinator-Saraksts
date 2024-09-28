<?php
    require "../Database/database.php"; 
    $sql = "SELECT id, img, title, release_date, description, developer, game_completion, rating FROM games ORDER BY title";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    while($row = $result->fetch_assoc()){
        if(!$row["id"]||!$row["img"]||!$row["title"]||!$row["release_date"]||!$row["description"]||!$row["developer"]||!$row["game_completion"]||!$row["rating"]){
            die("There is an empty result. Execution has been halted");
        }
    }
    $mysqli->close();
    foreach($result as $ListArticle){
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
