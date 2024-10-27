if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';
} else {
    console.error("pdfjsLib is not defined. Ensure PDF.js is loaded.");
}
let pdfDoc = null;
let currentPage = 1;
let totalPages = 0;
const previewScale = 1.0; // Scale for preview size
let currentPdfUrl = null; // Tracks current PDF URL

// HTML elements
const canvas = document.getElementById('pdf-canvas');
const context = canvas.getContext('2d');
const pdfViewer = document.getElementById('pdf-viewer');

// Load and render a PDF file
function loadPDF(filePath) {
    pdfjsLib.getDocument(filePath).promise
        .then(pdf => {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            renderPage(1, previewScale); // Render the first page in preview mode
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            alert("Failed to load PDF. Please check the file and try again.");
        });
}

// Render a page in the PDF
function renderPage(pageNumber, scale = 1.0) {
    pdfDoc.getPage(pageNumber).then(page => {
        const viewport = page.getViewport({ scale });

        // Set canvas dimensions to match the viewport
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        // Render PDF page onto the canvas
        const renderContext = { canvasContext: context, viewport: viewport };
        page.render(renderContext).promise
            .then(() => console.log("Page rendered with higher quality!"))
            .catch(renderError => console.error("Error rendering page:", renderError));
    }).catch(pageError => console.error("Error retrieving page:", pageError));
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
        loadExistingPDF(filePath); // Load PDF if filePath is provided
    }
}

// Error handling function
function handleError(message) {
    alert(message);
}

// Function to handle PDF preview when uploaded
function previewPDF(input) {
    const file = input.files[0];
    if (file && file.type === "application/pdf") {
        const reader = new FileReader();
        reader.onload = function(e) {
            const arrayBuffer = e.target.result;
            pdfjsLib.getDocument({ data: arrayBuffer }).promise
                .then(pdf => {
                    pdfDoc = pdf;
                    totalPages = pdf.numPages;
                    renderPage(1, previewScale); // Render first page as a preview
                })
                .catch(error => {
                    console.error("Failed to load PDF:", error);
                    alert("Failed to preview PDF. Please try another file.");
                });
        };
        reader.readAsArrayBuffer(file);
    } else {
        console.warn("Invalid file format. Please select a PDF file.");
        alert("Please select a valid PDF file.");
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
function openFullScreenPDF() {
    if (currentPdfUrl) {
        pdfViewer.style.display = 'block'; // Show full-screen viewer
        renderPage(currentPage, 1.5); // Render in larger scale for full screen
    } else {
        console.error("No PDF URL set for viewing.");
    }
}

// Load an existing PDF into the popup preview
function loadExistingPDF(filePath) {
    currentPdfUrl = filePath;
    pdfjsLib.getDocument(filePath).promise.then(pdf => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        renderPage(1, previewScale); // Render the first page in preview mode
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
    pdfViewer.style.display = 'none';
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

