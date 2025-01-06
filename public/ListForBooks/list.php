<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your book list.");
}

$user_id = $_SESSION['user_id'];
require "../../Database/database.php";

$sql = "SELECT id, img, title, rating 
        FROM books 
        WHERE user_id = ? 
        ORDER BY title";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
    <div class="book-list">
        <?php while ($ListArticle = $result->fetch_assoc()) : 
            $sqlFile = "SELECT file_path FROM bookfile WHERE book_id = ?";
            $stmtFile = $mysqli->prepare($sqlFile);
            $stmtFile->bind_param("i", $ListArticle['id']);
            $stmtFile->execute();
            $stmtFile->bind_result($file_path);
            $stmtFile->fetch();
            $stmtFile->close();
        ?>
        <article id="ListBorderColor" 
            onclick="OpenBookList('<?=htmlspecialchars($ListArticle['title'])?>', '<?=htmlspecialchars($ListArticle['id'])?>', '<?=htmlspecialchars($file_path)?>')"  
            style="border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
            <div class="ListImageContainer">
                <img class="ShowListImg" src="<?=htmlspecialchars($ListArticle['img'])?>" alt="<?=htmlspecialchars($ListArticle["title"])?> Title Image"/>
                <div class="DeleteListEntryArea">
                    <button onclick="deleteEntry(<?=htmlspecialchars($ListArticle['id'])?>)">&#x2715;</button>
                </div>
            </div>
            <p class="ShowListTitle"><?=htmlspecialchars($ListArticle["title"])?></p>
            <p class="ShowListRating">
                <?=$ListArticle["rating"]?>/10
            </p>
        </article>
        <?php endwhile; ?>
    </div>

    <div id="BookListFullPage" class="BookListFullPage" style="display: none;">
        <div id="BookListPopup" class="BookListPopup">
            <button onclick="CloseBookList()" class="CloseAddContentButton"></button>
            <div class="ShowListTitle"></div>
            <form action="upload_bookfile.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="book_id" id="book_id">
                <canvas id="pdf-canvas"></canvas>
                <input type="file" class="bookfileupload" name="file" accept=".pdf" required onchange="previewPDF(this)">
                <button type="submit" class="NewEntrySubmitButton">Add</button>
            </form>
        </div>
    </div>

    <div id="pdf-viewer" style="display: none;">
        <a id="close-pdf-btn" class="close-pdf-button" href="index.php">✖</a>
        <embed id="pdf-embed" type="application/pdf" width="100%" height="100%">
    </div>
<script src="pdfViewer.js"></script>
<script>  
    function deleteEntry(bookId) {
        if (confirm("Are you sure you want to delete this book?")) {
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
            xhr.send("book_id=" + bookId);
        }
    }
    function saveLastViewedPage(fileId, page) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_last_page.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log("Last viewed page saved:", page);
            } else {
                console.log("Error saving last viewed page");
            }
        };
        xhr.send("file_id=" + fileId + "&last_page=" + page);
    }
</script>
