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

function loadPDF(filePath, fileId) {
    pdfjsLib.getDocument(filePath).promise
        .then(pdf => {
            pdfDoc = pdf;
            totalPages = pdf.numPages;

            renderPage(currentPage);

            document.getElementById('close-pdf-btn').addEventListener('click', () => {
                saveLastViewedPage(fileId, currentPage);
            });
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            alert("Failed to load PDF. Please check the file and try again.");
        });
}

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

function toggleFullScreen() {
    if (!document.fullscreenElement) {
        pdfViewer.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

function OpenBookList(title, bookId, filePath) {
    const BookListPopup = document.getElementById("BookListPopup");
    BookListPopup.querySelector(".ShowListTitle").textContent = title;
    BookListPopup.querySelector("#book_id").value = bookId;
    document.getElementById("BookListFullPage").style.display = "block";
    BookListPopup.style.display = "block";
    
    if (filePath) {
        loadExistingPDF(filePath); 
    }
}

function handleError(message) {
    alert(message);
}

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
                    renderPage(1, previewScale); 
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

function renderPdfPreview(pdfUrl) {
    pdfjsLib.getDocument({ data: pdfUrl }).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: previewScale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            page.render({ canvasContext: context, viewport: viewport });

        
            canvas.onclick = () => openFullScreenPDF(pdfUrl);
        });
    });
}

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

function openPDFViewer() {
    if (currentPdfUrl) {
        const embedElement = document.getElementById("pdf-embed");
        embedElement.src = currentPdfUrl; 
        pdfViewer.style.display = "block";
    } else {
        console.error("No PDF URL set for viewing.");
    }
}
function openFullScreenPDF() {
    if (currentPdfUrl) {
        pdfViewer.style.display = 'block';
        renderPage(currentPage, 1.5); 
    } else {
        console.error("No PDF URL set for viewing.");
    }
}

function loadExistingPDF(filePath) {
    currentPdfUrl = filePath;
    pdfjsLib.getDocument(filePath).promise.then(pdf => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        renderPage(1, previewScale); 
    }).catch(error => console.error("Error loading existing PDF:", error));
}


function CloseBookList() {
    document.getElementById("BookListPopup").style.display = "none";
    document.getElementById("BookListFullPage").style.display = "none";
    context.clearRect(0, 0, canvas.width, canvas.height);
}

document.getElementById("pdf-canvas").onclick = function () {
    openPDFViewer();
};

function closePDFViewer() {
    pdfViewer.style.display = 'none';
}

document.getElementById("close-pdf-btn").onclick = closePDFViewer;

