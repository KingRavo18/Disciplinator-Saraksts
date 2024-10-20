<?php
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to view your game list.");
    }
    $user_id = $_SESSION['user_id'];
    require "../Database/database.php"; 
    $sql = "SELECT id, img, title, release_date, author, rating 
            FROM books 
            WHERE user_id = ? 
            ORDER BY title";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($ListArticle = $result->fetch_assoc()) {
        if (!$ListArticle["id"] || !$ListArticle["img"] || !$ListArticle["title"] || !$ListArticle["release_date"] || !$ListArticle["author"] || !$ListArticle["rating"]) {
            die("There is an empty result. Execution has been halted");
        }
?>
        <article id="ListBorderColor" 
            onclick="OpenBookList('<?=$ListArticle['title']?>', '<?=$ListArticle['id']?>')"  
            style="border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
            <div class="ListImageContainer">
                <img class="ShowListImg" src="<?=$ListArticle['img']?>" alt="<?=$ListArticle["title"]?> Title Image"/>
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
                <?= $_SESSION['page_language'] === 'lv' ? 'Autors:' : 'Author:'; ?> <?=$ListArticle["author"]?>
            </p>
            <p class="ShowListRating">
                <?= $_SESSION['page_language'] === 'lv' ? 'Reitings:' : 'Rating:'; ?> <?=$ListArticle["rating"]?>
            </p>
        </article>
        <!-- Popup -->
        <div id="BookListFullPage" class="BookListFullPage">
            <div id="BookListPopup" class="BookListPopup">
                <div class="CloseAddContent">
                    <button onclick="CloseBookList()" class="CloseAddContentButton"></button>
                </div>
                <div class="specListTitle"><p class="ShowListTitle"></p></div>
                <form action="upload_bookfile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="book_id" id="book_id"> <!-- Hidden book_id field -->
                    <div class="fileslot">
                        <input type="file" class="bookfileupload" name="file" accept=".pdf" required>
                    </div>
                    <button class="NewEntrySubmitButton" type="submit">
                        <?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?>
                    </button>
                </form>
            </div>
        </div> 
<?php 
    }
    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
?>
<script>
function deleteEntry(bookId) {
    if (confirm("Are you sure you want to delete this book?")) {
        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // Define what happens on successful data submission
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText); // Show the response message
                location.reload(); // Reload the page to update the list
            } else {
                alert("Error: Could not delete the entry.");
            }
        };
        // Send the request with the book ID
        xhr.send("book_id=" + bookId);
    }
}

function OpenBookList(title, bookId) {
    // Get the elements where the information will be updated
    var BookListPopup = document.getElementById("BookListPopup");
    
    // Update the popup content dynamically
    BookListPopup.querySelector(".ShowListTitle").innerHTML = title;
    BookListPopup.querySelector("#book_id").value = bookId;  // Set the correct book ID in the form
    
    // Show the popup
    document.getElementById("BookListFullPage").style.display = "block";
    BookListPopup.style.display = "block";
}

function CloseBookList() {
    document.getElementById("BookListPopup").style.display = "none";
    document.getElementById("BookListFullPage").style.display = "none";
}
</script>
