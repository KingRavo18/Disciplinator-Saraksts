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
            onclick="OpenBookList('<?=$ListArticle['title']?>', '<?=$ListArticle['id']?>', '<?=$file_path?>')"  
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
                        <canvas id="pdf-canvas"></canvas> <!-- The canvas fills the fileslot -->
                    </div>
                    <!-- File input is placed below the fileslot -->
                    <input type="file" class="bookfileupload" name="file" accept=".pdf" required onchange="previewPDF(this)">
                    <button class="NewEntrySubmitButton" type="submit">
                        <?= $_SESSION['page_language'] === 'lv' ? 'Pievienot' : 'Add'; ?>
                    </button>
                </form>
            </div>
        </div> 
<?php 
    }
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

// Function to preview the first page of the PDF
function previewPDF(input) {
    const file = input.files[0];
    const canvas = document.getElementById('pdf-canvas');
    const context = canvas.getContext('2d');
    
    // Clear any existing canvas content before rendering a new PDF
    context.clearRect(0, 0, canvas.width, canvas.height);

    // Hide canvas if no valid PDF file is selected
    if (!file || file.type !== 'application/pdf') {
        canvas.style.display = 'none';
        return;
    }
    
    // Show canvas if a valid PDF is selected
    canvas.style.display = 'block';

    const fileReader = new FileReader();
    fileReader.onload = function () {
        const typedArray = new Uint8Array(this.result);

        // Load the PDF file using pdf.js
        pdfjsLib.getDocument(typedArray).promise.then(function (pdf) {
            // Get the first page of the PDF
            pdf.getPage(1).then(function (page) {
                const viewport = page.getViewport({ scale: 1 });

                // Adjust canvas size to fit the page's dimensions
                canvas.width = canvas.clientWidth; // Full width of the slot
                canvas.height = canvas.clientHeight; // Full height of the slot

                // Render the new PDF page onto the canvas
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        });
    };

    fileReader.readAsArrayBuffer(file);
}

function OpenBookList(title, bookId, filePath) {
    // Get the elements where the information will be updated
    var BookListPopup = document.getElementById("BookListPopup");
    
    // Update the popup content dynamically
    BookListPopup.querySelector(".ShowListTitle").innerHTML = title;
    BookListPopup.querySelector("#book_id").value = bookId;  // Set the correct book ID in the form
    
    // Show the popup
    document.getElementById("BookListFullPage").style.display = "block";
    BookListPopup.style.display = "block";
    
    // If there's an existing PDF file, load it into the canvas
    if (filePath) {
        loadExistingPDF(filePath);
    } else {
        clearPDFPreview(); // Clear the canvas if no PDF is present
    }
}

function loadExistingPDF(filePath) {
    const canvas = document.getElementById('pdf-canvas');
    const context = canvas.getContext('2d');
    
    // Clear any existing canvas content before rendering a new PDF
    context.clearRect(0, 0, canvas.width, canvas.height);

    // Hide canvas if no valid PDF file is selected
    canvas.style.display = 'block';

    // Load the PDF file using pdf.js
    pdfjsLib.getDocument(filePath).promise.then(function (pdf) {
        // Get the first page of the PDF
        pdf.getPage(1).then(function (page) {
            const viewport = page.getViewport({ scale: 1 });

            // Adjust canvas size to fit the page's dimensions
            canvas.width = canvas.clientWidth; // Full width of the slot
            canvas.height = canvas.clientHeight; // Full height of the slot

            // Render the new PDF page onto the canvas
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    });
}

function clearPDFPreview() {
    const canvas = document.getElementById('pdf-canvas');
    const context = canvas.getContext('2d');
    context.clearRect(0, 0, canvas.width, canvas.height);
    canvas.style.display = 'none'; // Hide the canvas if no PDF to show
}

function CloseBookList() {
    document.getElementById("BookListPopup").style.display = "none";
    document.getElementById("BookListFullPage").style.display = "none";
}
</script>
