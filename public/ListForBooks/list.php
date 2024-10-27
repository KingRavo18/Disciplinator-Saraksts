<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your book list.");
}

$user_id = $_SESSION['user_id'];
require "../../Database/database.php";

$sql = "SELECT id, img, title, release_date, author, rating 
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
            // Fetch the current PDF file path for this book (if any)
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
            <p class="ListArticle">
                <?= $_SESSION['page_language'] === 'lv' ? 'Izlaides Datums:' : 'Release Date:'; ?> <?=htmlspecialchars($ListArticle["release_date"])?>
            </p>
            <p class="ListArticle">
                <?= $_SESSION['page_language'] === 'lv' ? 'Autors:' : 'Author:'; ?> <?=htmlspecialchars($ListArticle["author"])?>
            </p>
            <p class="ShowListRating">
                <?= $_SESSION['page_language'] === 'lv' ? 'Reitings:' : 'Rating:'; ?> <?=htmlspecialchars($ListArticle["rating"])?>
            </p>
        </article>
        <?php endwhile; ?>
    </div>

    <!-- Popup for Book Details (only one instance) -->
    <div id="BookListFullPage" class="BookListFullPage" style="display: none;">
    <div id="BookListPopup" class="BookListPopup">
        <button onclick="CloseBookList()" class="CloseAddContentButton"></button>
        <div class="ShowListTitle"></div>
        <form action="upload_bookfile.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="book_id" id="book_id">
    <canvas id="pdf-canvas"></canvas> <!-- Make sure this is closed properly -->
    <input type="file" class="bookfileupload" name="file" accept=".pdf" required onchange="previewPDF(this)">
    <button type="submit" class="NewEntrySubmitButton">Add</button>
</form>
    </div>
</div>

    <!-- Fullscreen PDF Viewer -->
    <div id="pdf-viewer" style="display: none;">
    <a id="close-pdf-btn" class="close-pdf-button" href="index.php">✖</a>
    <embed id="pdf-embed" type="application/pdf" width="100%" height="100%">
    </div>
    <script src="pdfViewer.js"></script>
</body>
</html>
