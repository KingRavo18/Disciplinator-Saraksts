<?php
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your book list.");
}
$user_id = $_SESSION['user_id'];
require "../../Database/database.php";

$sql = "SELECT id, img_url, img_file_path, title, rating, times_read, notes, date FROM books WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookList = [];
$sqlFile = "SELECT file_path FROM bookfile WHERE book_id = ?";
$stmtFile = $mysqli->prepare($sqlFile);
while ($row = $result->fetch_assoc()) {
    $stmtFile->bind_param("i", $row['id']);
    $stmtFile->execute();
    $stmtFile->bind_result($file_path);
    $stmtFile->fetch();
    $row['file_path'] = $file_path;
    $stmtFile->reset();

    $row['file_path'] = $file_path;
    $bookList[] = $row;
}

$stmt->close();
$mysqli->close();

usort($bookList, function ($a, $b) {
    return strnatcmp($a['title'], $b['title']);
});
$counter = 1;
?>

<div class="book-list">
<?php 
    if ($result->num_rows > 0): 
        foreach ($bookList as $ListArticle): 
            $imageSource = $ListArticle["img_file_path"] ? $ListArticle["img_file_path"] : $ListArticle["img_url"];
?>
    <article id="ListBorderColor" 
        data-id="<?=htmlspecialchars($ListArticle['id'])?>"
        data-title="<?=htmlspecialchars(strtolower($ListArticle['title']))?>" 
        data-rating="<?=htmlspecialchars($ListArticle['rating'])?>" 
        data-date="<?=strtotime($ListArticle['date'])?>"
        style="border-color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>">
        <div class="listImageContainer">
            <img class="showListImg" id="entryImage-<?=$ListArticle['id']?>" src="<?=htmlspecialchars($imageSource)?>" alt="<?=htmlspecialchars($ListArticle["title"])?> Title Image"/>
            <div class="topFlex">
                <p class="counter"><?=$counter?>.</p>
                <?php $counter++; ?>
                <button class="openPopupBtn" onclick="openPopup(<?=$ListArticle['id']?>)"><span class="material-symbols-outlined">notes</span></button>
                <button class="openPopupBtn" onclick="openImageUpdatePopup(<?=$ListArticle['id']?>)""><span class="material-symbols-outlined">image</span></button>
                <button class="openPopupBtn" onclick="OpenBookList(`<?=htmlspecialchars(addslashes($ListArticle['title']))?>`, `<?=htmlspecialchars($ListArticle['id'])?>`, `<?=htmlspecialchars($ListArticle['file_path'])?>`)">
                    <span class="material-symbols-outlined">menu_book</span>
                </button>
            </div>
            <div class="deleteEntry">
                <button onclick="deleteEntry(<?=htmlspecialchars($ListArticle['id'])?>, event)">&#x2715;</button>
            </div>
        </div>
        <p class="showListTitle">
            <?=htmlspecialchars($ListArticle["title"])?>
        </p>
        <p class="showListRating">
            <?=htmlspecialchars($ListArticle["rating"])?>/10
        </p>
        <!-- Hidden Popup -->
            <div class="entryPopup" id="popup-<?=$ListArticle['id']?>">
                <div class="popupContent">
                    <div class="closePopup">
                        <button onclick="closePopup(<?=$ListArticle['id']?>)">&#x2715;</button>
                    </div>
                    <h2><?= $language === 'lv' ? 'Reizes Lasīts: ' : 'Times Read: '; ?>
                        <span class="editableTimesRead" data-id="<?=$ListArticle['id']?>"><?=htmlspecialchars($ListArticle["times_read"])?></span>
                    </h2>
                    <p><?= $language === 'lv' ? 'Pieraksti: ' : 'Notes: '; ?>
                        <span class="editableNotes" data-id="<?=$ListArticle['id']?>"><?=htmlspecialchars($ListArticle["notes"])?></span>
                    </p>
                </div>
            </div>
            <!-- Hidden Image Update Popup -->
            <div class="entryPopup" id="popupImage-<?=$ListArticle['id']?>">
                <div class="popupImageContent">
                    <div class="closePopup">
                        <button onclick="closeImageUpdatePopup(<?=$ListArticle['id']?>)">&#x2715;</button>
                    </div>
                    <form method="post" action="./update_image.php" enctype="multipart/form-data">
                        <input type="hidden" name="book_id" value="<?=$ListArticle['id']?>" />
    
                        <div class="uploadWindowWithLongInput">
                            <input type="text" class="longInput" name="img_url" placeholder="<?= $language === 'lv' ? 'Bildes URL' : 'Image URL'; ?>"/>
                        </div>

                        <p style="text-align: center; font-size: 14px;"><?= $language === 'lv' ? 'vai' : 'or'; ?></p>

                        <div class="uploadWindowWithLongInput">
                            <input type="file" class="longInput" name="img_file" accept="image/*" title="<?= $language === 'lv' ? 'Augšupielādēt attēlu' : 'Upload Image'; ?>"/>
                        </div>

                        <div class="uploadWindowWithLongInput" style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="useDefaultImage-<?=$ListArticle['id']?>" name="use_default_image" value="1" />
                            <label for="useDefaultImage-<?=$ListArticle['id']?>" style="font-size: 14px;">
                                <?= $language === 'lv' ? 'Izmantot noklusējuma attēlu' : 'Use default image'; ?>
                            </label>
                        </div>

                        <div class="newEntrySubmit">
                            <button class="newEntrySubmitButton" type="submit"><?= $language === 'lv' ? 'Atjaunot Ieraksta Bildi' : 'Update Entry Image'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
    </article>
    <?php 
        endforeach; 
        else: 
    ?>
        <p class="no-entry-message"><?= $language === 'lv' ? 'Nav Ierakstu' : 'No Entries'; ?></p>
    <?php endif; ?>
</div>

<div id="bookListFullPage" style="display: none;">
    <div id="bookListPopup">
        <button onclick="CloseBookList()" class="closeAddContentButton" style="border-radius: 0;"></button>
        <div class="showListTitle"></div>
        <form action="upload_bookfile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="book_id" id="book_id">
            <canvas id="pdf-canvas">
                <div id="loading-spinner" class="hidden">
                    <div class="spinner"></div>
                </div>
            </canvas>
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
function openImageUpdatePopup(bookId) {
    let popupImage = document.getElementById(`popupImage-${bookId}`);
    popupImage.style.display = "flex";
}

function closeImageUpdatePopup(bookId) {
    let popupImage = document.getElementById(`popupImage-${bookId}`);
    popupImage.style.display = "none";
}
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
        let bookId = article.dataset.id;

        let input = document.createElement("input");
        input.type = "text";
        input.value = currentTitle;
        input.classList.add("editTitleInput");

        this.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            let newTitle = input.value.trim();
            if (newTitle !== "" && newTitle !== currentTitle) {
                updateBookTitle(bookId, newTitle, input, article);
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

function updateBookTitle(bookId, newTitle, inputElement, article) {
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
            sortBookList();
        } else {
            alert("Error updating title.");
        }
    };

    xhr.send("book_id=" + bookId + "&title=" + encodeURIComponent(newTitle));
}

function makeRatingEditable(ratingElement) {
    ratingElement.addEventListener("click", function () {
        let currentRating = this.innerText.replace("/10", "").trim();
        let article = this.closest("article");
        let bookId = article.dataset.id;

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
                updateBookRating(bookId, newRating, input, article);
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

function updateBookRating(bookId, newRating, inputElement, article) {
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

    xhr.send("book_id=" + bookId + "&rating=" + encodeURIComponent(newRating));
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
function openPopup(bookId) {
    let popup = document.getElementById(`popup-${bookId}`);
    popup.style.display = "flex";
}

function closePopup(bookId) {
    let popup = document.getElementById(`popup-${bookId}`);
    popup.style.display = "none";
}
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".editableTimesRead").forEach(makeTimesReadEditable);
    document.querySelectorAll(".editableNotes").forEach(makeNotesEditable);
});

function makeTimesReadEditable(element) {
    element.addEventListener("click", function () {
        let currentValue = this.innerText.trim();
        let bookId = this.dataset.id;

        let input = document.createElement("input");
        input.type = "number";
        input.value = currentValue;
        input.min = 0;
        input.classList.add("editTimesReadInput");

        this.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            let newValue = parseInt(input.value.trim(), 10);
            if (!isNaN(newValue) && newValue >= 0 && newValue !== parseInt(currentValue)) {
                updateTimesRead(bookId, newValue, input);
            } else {
                restoreTimesRead(input, currentValue);
            }
        });

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                input.blur();
            }
        });
    });
}

function updateTimesRead(bookId, newValue, inputElement) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_times_read.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let span = document.createElement("span");
            span.classList.add("editableTimesRead");
            span.dataset.id = bookId;
            span.innerText = newValue;
            makeTimesReadEditable(span);
            inputElement.replaceWith(span);
        } else {
            alert("Error updating times read.");
        }
    };

    xhr.send("book_id=" + bookId + "&times_read=" + encodeURIComponent(newValue));
}

function restoreTimesRead(inputElement, originalValue) {
    let span = document.createElement("span");
    span.classList.add("editableTimesRead");
    span.dataset.id = inputElement.dataset.id;
    span.innerText = originalValue;
    makeTimesReadEditable(span);
    inputElement.replaceWith(span);
}

function makeNotesEditable(element) {
    element.addEventListener("click", function () {
        let currentValue = this.innerText.trim();
        let bookId = this.dataset.id;

        let textarea = document.createElement("textarea");
        textarea.value = currentValue;
        textarea.classList.add("editNotesInput");

        this.replaceWith(textarea);
        textarea.focus();

        textarea.addEventListener("blur", function () {
            let newValue = textarea.value.trim();
            if (newValue !== currentValue) {
                updateNotes(bookId, newValue, textarea);
            } else {
                restoreNotes(textarea, currentValue);
            }
        });

        textarea.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                textarea.blur();
            }
        });
    });
}

function updateNotes(bookId, newValue, textareaElement) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_notes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let span = document.createElement("span");
            span.classList.add("editableNotes");
            span.dataset.id = bookId;
            span.innerText = newValue;
            makeNotesEditable(span);
            textareaElement.replaceWith(span);
        } else {
            alert("Error updating notes.");
        }
    };

    xhr.send("book_id=" + bookId + "&notes=" + encodeURIComponent(newValue));
}

function restoreNotes(textareaElement, originalValue) {
    let span = document.createElement("span");
    span.classList.add("editableNotes");
    span.dataset.id = textareaElement.dataset.id;
    span.innerText = originalValue;
    makeNotesEditable(span);
    textareaElement.replaceWith(span);
}
function resizeText(titleElement) {
    let originalFontSize = parseFloat(window.getComputedStyle(titleElement).fontSize);
    
    while (titleElement.scrollHeight > titleElement.clientHeight && originalFontSize > 12) {
        originalFontSize -= 0.5;
        titleElement.style.fontSize = originalFontSize + "px";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".showListTitle").forEach((titleElement) => {
        resizeText(titleElement);
    });
});
function resizeCounterFont(counterElement, minFontSize = 16) {
    counterElement.style.fontSize = ''; 
    let fontSize = parseFloat(window.getComputedStyle(counterElement).fontSize);
    
    while (counterElement.scrollWidth > counterElement.clientWidth && fontSize > minFontSize) {
        fontSize -= 1.3;
        counterElement.style.fontSize = fontSize + 'px';
    }
}

function updateCounter(value) {
    const counter = document.querySelector('.counter');
    counter.textContent = value;
    resizeCounterFont(counter);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.counter').forEach(el => resizeCounterFont(el));
});
document.querySelectorAll('.update-image-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const bookId = formData.get('book_id');
        const imageElement = document.querySelector(`#entryImage-${bookId}`);

        try {
            const res = await fetch('./update_image.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success && data.new_image) {
                if (imageElement) {
                    imageElement.src = data.new_image;
                }
            } else {
                alert(data.error || 'Something went wrong.');
            }
        } catch (err) {
            console.error(err);
            alert('Error while updating image.');
        }
    });
});
</script>
