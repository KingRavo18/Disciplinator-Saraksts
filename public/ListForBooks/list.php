<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your book list.");
}
$user_id = $_SESSION['user_id'];
require "../../Database/database.php";

$sql = "SELECT id, img_url, img_file_path, title, rating FROM books WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookList = [];
while ($row = $result->fetch_assoc()) {
    $sqlFile = "SELECT file_path FROM bookfile WHERE book_id = ?";
    $stmtFile = $mysqli->prepare($sqlFile);
    $stmtFile->bind_param("i", $row['id']);
    $stmtFile->execute();
    $stmtFile->bind_result($file_path);
    $stmtFile->fetch();
    $stmtFile->close();

    $row['file_path'] = $file_path;
    $bookList[] = $row;
}

$stmt->close();
$mysqli->close();

usort($bookList, function ($a, $b) {
    return strnatcmp($a['title'], $b['title']);
});
?>

<div class="book-list">
    <?php foreach ($bookList as $ListArticle): 
        $imageSource = $ListArticle["img_file_path"] ? $ListArticle["img_file_path"] : $ListArticle["img_url"];
    ?>
    <article id="ListBorderColor" 
        data-id="<?=htmlspecialchars($ListArticle['id'])?>"
        style="border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
        <div class="listImageContainer">
            <img class="showListImg" 
            onclick="OpenBookList(`<?=htmlspecialchars(addslashes($ListArticle['title']))?>`, `<?=htmlspecialchars($ListArticle['id'])?>`, `<?=htmlspecialchars($ListArticle['file_path'])?>`)"  
            src="<?=htmlspecialchars($imageSource)?>" alt="<?=htmlspecialchars($ListArticle["title"])?> Title Image"/>
            <div class="deleteListEntryArea">
                <button onclick="deleteEntry(<?=htmlspecialchars($ListArticle['id'])?>, event)">&#x2715;</button>
            </div>
        </div>
        <p class="showListTitle">
            <?=htmlspecialchars($ListArticle["title"])?>
        </p>
        <p class="showListRating">
            <?=htmlspecialchars($ListArticle["rating"])?>/10
        </p>
    </article>
    <?php endforeach; ?>
</div>

<div id="bookListFullPage" style="display: none;">
    <div id="bookListPopup">
        <button onclick="CloseBookList()" class="closeAddContentButton"></button>
        <div class="showListTitle"></div>
        <form action="upload_bookfile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="book_id" id="book_id">
            <canvas id="pdf-canvas"></canvas>
            <input type="file" class="bookfileupload" name="file" accept=".pdf" required onchange="previewPDF(this)">
            <button type="submit" class="newEntrySubmitButton">Add</button>
        </form>
    </div>
</div>

<div id="pdf-viewer">
    <a id="close-pdf-btn" class="close-pdf-button" href="index.php">✖</a>
    <embed id="pdf-embed" type="application/pdf" width="100%" height="100%">
</div>

<script src="pdfViewer.js"></script>
<script>  
function deleteEntry(bookId) {
    if (confirm("Are you sure you want to delete this book entry?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                var responseText = xhr.responseText.trim(); 
                if (responseText === "Success") { 
                    var article = document.querySelector(`article[data-id="${bookId}"]`);
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
        xhr.send("book_id=" + bookId);
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
