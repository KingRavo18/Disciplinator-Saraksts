// Global variables to store PDF state
let pdfDoc = null;
let currentPage = 1;
let totalPages = 0;
const scale = 1.5; // Scale for full-screen viewing
const previewScale = 1.0; // Scale for preview mode

// Get HTML elements
const canvas = document.getElementById('pdf-canvas');
const context = canvas.getContext('2d');
const pdfViewer = document.getElementById('pdf-viewer');
const pageNumDisplay = document.getElementById('page-num');
const totalPagesDisplay = document.getElementById('total-pages');

// Load and render a PDF file
function loadPDF(filePath) {
    pdfjsLib.getDocument(filePath).promise
        .then(pdf => {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            totalPagesDisplay.textContent = totalPages;
            renderPage(currentPage);
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            handleError("Failed to load PDF. Please try again.");
        });
}

// Render a page in the PDF
function renderPage(pageNumber, fullScreen = false) {
    pdfDoc.getPage(pageNumber).then(page => {
        const viewport = page.getViewport({ scale: fullScreen ? scale : previewScale });

        // Set the canvas size based on viewport dimensions
        canvas.width = fullScreen ? window.innerWidth : viewport.width;
        canvas.height = fullScreen ? window.innerHeight : viewport.height;

        // Clear the canvas before rendering new content
        context.clearRect(0, 0, canvas.width, canvas.height);

        // Render the page content
        page.render({ canvasContext: context, viewport: viewport });
        pageNumDisplay.textContent = pageNumber;
    });
}

// Navigation: Next and Previous Page
function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        renderPage(currentPage, true);
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderPage(currentPage, true);
    }
}

// Full-screen toggle
function toggleFullScreen() {
    if (!document.fullscreenElement) {
        pdfViewer.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

// Open Book List Popup and load PDF preview if available
function OpenBookList(title, bookId, filePath) {
    const BookListPopup = document.getElementById("BookListPopup");
    BookListPopup.querySelector(".ShowListTitle").textContent = title;
    BookListPopup.querySelector("#book_id").value = bookId;
    document.getElementById("BookListFullPage").style.display = "block";
    BookListPopup.style.display = "block";
    
    if (filePath) {
        currentPdfUrl = filePath; // Use the file path for existing PDF
    }
}

// Error handling function
function handleError(message) {
    alert(message);
}

// Function to preview a PDF file when uploaded
let currentPdfUrl = null;

// Function to handle PDF preview when uploaded
function previewPDF(input) {
    const file = input.files[0];
    if (file && file.type === "application/pdf") {
        const reader = new FileReader();
        reader.onload = function (e) {
            currentPdfUrl = e.target.result; // Store the PDF data URL
            renderPdfPreview(currentPdfUrl); // Optionally render the preview
        };
        reader.readAsDataURL(file);
    }
}

// Load a PDF file for preview on the canvas
function renderPdfPreview(pdfUrl) {
    pdfjsLib.getDocument({ data: pdfUrl }).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: previewScale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            page.render({ canvasContext: context, viewport: viewport });

            // Open full-screen on preview click
            canvas.onclick = () => openFullScreenPDF(pdfUrl);
        });
    });
}

function openPDFViewer() {
    if (currentPdfUrl) {
        const embedElement = document.getElementById("pdf-embed");
        embedElement.src = currentPdfUrl; // Set the PDF URL to the embed source
        pdfViewer.style.display = "block"; // Show the viewer
    } else {
        console.error("No PDF URL set for viewing.");
    }
}

// Open Full-Screen PDF Viewer
function openFullScreenPDF(filePath) {
    pdfjsLib.getDocument(filePath).promise.then(pdf => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        currentPage = 1;
        totalPagesDisplay.textContent = totalPages;
        
        // Show PDF viewer and render the first page in full-screen
        pdfViewer.style.display = 'block';
        renderPage(currentPage, true);
        CloseBookList();
    }).catch(error => {
        console.error("Error loading PDF:", error);
    });

    // Ensure full-screen background styling
    pdfViewer.style.zIndex = '1000';
    pdfViewer.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
}

// Load an existing PDF into the popup preview
function loadExistingPDF(filePath) {
    clearPDFPreview();
    pdfjsLib.getDocument(filePath).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: previewScale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            page.render({ canvasContext: context, viewport: viewport });

            // Open full-screen on preview click
            canvas.onclick = () => openFullScreenPDF(filePath);
        });
    }).catch(error => console.error("Error loading existing PDF:", error));
}

// Clear the PDF preview canvas
function clearPDFPreview() {
    context.clearRect(0, 0, canvas.width, canvas.height);
}

// Close Book List Popup
function CloseBookList() {
    document.getElementById("BookListPopup").style.display = "none";
    document.getElementById("BookListFullPage").style.display = "none";
    clearPDFPreview(); // Optionally clear the preview when closing
}

document.getElementById("pdf-canvas").onclick = function () {
    openPDFViewer();
};

// Close PDF Viewer
function closePDFViewer() {
    const embedElement = document.getElementById("pdf-embed");
    pdfViewer.style.display = "none";
    embedElement.src = ""; // Clear src to stop loading
}

// Delete Book Entry Function
function deleteEntry(bookId) {
    if (confirm("Are you sure you want to delete this book?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_entry.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            xhr.status === 200 ? location.reload() : alert("Error deleting entry.");
        };
        xhr.send("book_id=" + bookId);
    }
}

// Close PDF Viewer button
document.getElementById("close-pdf-btn").onclick = closePDFViewer;

