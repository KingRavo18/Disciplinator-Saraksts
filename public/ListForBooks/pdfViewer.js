// Set up PDF.js worker
if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';
} else {
    console.error("pdfjsLib is not defined. Ensure PDF.js is loaded.");
}

let pdfDoc = null;
let currentPage = 1;
let totalPages = 0;
const previewScale = 1.0; 
let currentPdfUrl = null;

const canvas = document.getElementById('pdf-canvas');
const context = canvas.getContext('2d');
const pdfViewer = document.getElementById('pdf-viewer');

// Load PDF from file path
function loadPDF(filePath, fileId) {
    pdfjsLib.getDocument(filePath).promise
        .then(pdf => {
            pdfDoc = pdf;
            totalPages = pdf.numPages;

            renderPage(currentPage); // Render first page

            // Save the last viewed page when closing
            document.getElementById('close-pdf-btn').addEventListener('click', () => {
                saveLastViewedPage(fileId, currentPage);
            });
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            alert("Failed to load PDF. Please check the file and try again.");
        });
}

// Render a specific page with a given scale
function renderPage(pageNumber, scale = 1.5) {
    pdfDoc.getPage(pageNumber).then(page => {
        const viewport = page.getViewport({ scale });
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const renderContext = { canvasContext: context, viewport: viewport };
        page.render(renderContext).promise
            .then(() => console.log("Page rendered successfully!"))
            .catch(renderError => console.error("Error rendering page:", renderError));

        currentPage = pageNumber;
    }).catch(pageError => console.error("Error retrieving page:", pageError));
}

// Open PDF in full-screen mode
function toggleFullScreen() {
    if (!document.fullscreenElement) {
        pdfViewer.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

function OpenBookList(title, bookId, filePath) {
    const BookListPopup = document.getElementById("bookListPopup");
    const BookListFullPage = document.getElementById("bookListFullPage");

    // Set title and book ID for the popup
    BookListPopup.querySelector(".showListTitle").textContent = title;
    BookListPopup.querySelector("#book_id").value = bookId;

    // Show the popup
    BookListFullPage.style.display = "block";
    BookListPopup.classList.remove("hide"); 
    BookListPopup.classList.add("show");
    BookListPopup.style.display = "block";

    // If a file path is available, load the existing PDF
    if (filePath) {
        loadExistingPDF(filePath);
    }
}

// Function to load existing PDF into the viewer
function loadExistingPDF(filePath) {
    currentPdfUrl = filePath;
    pdfjsLib.getDocument(filePath).promise.then(pdf => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        renderPage(1, previewScale); 
    }).catch(error => console.error("Error loading existing PDF:", error));
}

// Preview PDF file when selected
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
                    renderPage(1, previewScale); // Preview first page
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

// Render preview of a PDF in canvas
function renderPdfPreview(pdfUrl) {
    pdfjsLib.getDocument({ data: pdfUrl }).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: previewScale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            page.render({ canvasContext: context, viewport: viewport });

            // Allow clicking the canvas to open full-screen PDF
            canvas.onclick = () => openFullScreenPDF(pdfUrl);
        });
    });
}

// Open the full-screen PDF viewer
function openFullScreenPDF() {
    if (currentPdfUrl) {
        pdfViewer.style.display = 'block';
        renderPage(currentPage, 1.5); // Render current page
    } else {
        console.error("No PDF URL set for viewing.");
    }
}

// Close the book list popup and clear the canvas
function CloseBookList() {
    const BookListPopup = document.getElementById("bookListPopup");
    const BookListFullPage = document.getElementById("bookListFullPage");
    const canvas = document.getElementById("pdf-canvas");
    const context = canvas.getContext("2d");

    BookListPopup.classList.remove("show");
    BookListPopup.classList.add("hide");   

    setTimeout(() => {
        BookListPopup.style.display = "none";
        BookListFullPage.style.display = "none";

        // Clear canvas on close
        context.clearRect(0, 0, canvas.width, canvas.height);
    }, 300); 
}

// Close the PDF viewer
function closePDFViewer() {
    pdfViewer.style.display = 'none';
}

document.getElementById("close-pdf-btn").onclick = closePDFViewer;

// Allow canvas click to open full-screen PDF
document.getElementById("pdf-canvas").onclick = function () {
    openPDFViewer();
};

// Open the full-screen PDF viewer
function openPDFViewer() {
    if (currentPdfUrl) {
        const embedElement = document.getElementById("pdf-embed");
        embedElement.src = currentPdfUrl; 
        pdfViewer.style.display = "block";
    } else {
        console.error("No PDF URL set for viewing.");
    }
}
