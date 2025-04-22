<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
if (!isset($_SESSION['page_language'])) {
    $_SESSION['page_language'] = 'lv'; 
}
if (!isset($_SESSION['page_theme'])) {
    $_SESSION['page_theme'] = '#fff'; 
}
$language = $_SESSION['page_language'] ?? 'lv';
?>
<!DOCTYPE html>
<html lang="lv">
    <head>
        <title><?= $language === 'lv' ? 'Disciplinators - Filmu/Seriālu Saraksts' : 'Disciplinators - Movie/Show List'; ?></title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
        <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <link rel="stylesheet" href="../Style/gameMovieBookList.css"/>
        <link rel="stylesheet" href="../Style/global.css"/>
        <link rel="stylesheet" href="../Style/mainPageTopBar.css">
        <link rel="stylesheet" href="../Style/sidebar.css">
    </head>
    <body>
        <main>
            <div style="display: flex;">
                <select id="sortEntry">
                    <option value="abc"><?= $language === 'lv' ? 'A-Z (Noklusējuma)' : 'A-Z (Default)'; ?></option>
                    <option value="cba">Z-A</option>
                    <option value="byRating"><?= $language === 'lv' ? 'Pēc Reitinga' : 'By Rating'; ?></option>   
                    <option value="byDate"><?= $language === 'lv' ? 'Pēc Jaunākā' : 'By Newest'; ?></option>
                    <option value="byDateRev"><?= $language === 'lv' ? 'Pēc Vecākā' : 'By Oldest'; ?></option>
                </select>
                <input type="text" id="searchEntry" placeholder="<?= $language === 'lv' ? 'Meklēt ierakstu' : 'Search for an Entry'; ?>">
            </div>
            <div class="pageTitle">
                <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $language === 'lv' ? 'FILMU/SERIĀLU SARAKSTS' : 'MOVIE/SHOW LIST'; ?></h1>
            </div>
            <?php
                require "../Accesories/mainPageTopBar.php";
                require "../Accesories/sidebar.php";
            ?>
            <section class="list">
                <?php 
                    require "./list.php"; 
                ?>
            </section>
        </main>
        <?php
            require "./footer.php";
        ?>
    </body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchEntry");
            const movieArticles = document.querySelectorAll("article");

            searchInput.addEventListener("input", function () {
                const searchQuery = searchInput.value.toLowerCase();

                movieArticles.forEach(article => {
                    const titleElement = article.querySelector(".showListTitle");
                    const movieTitle = titleElement.textContent.toLowerCase();

                    if (movieTitle.includes(searchQuery)) {
                        article.style.display = "block";
                    } else {
                        article.style.display = "none"; 
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const sortSelect = document.getElementById("sortEntry");
            const movieContainer = document.querySelector("section"); 

            sortSelect.addEventListener("change", function () {
                sortMovieList(sortSelect.value);
            });

            function sortMovieList(criteria) {
                let articles = Array.from(document.querySelectorAll("article"));

                articles.sort((a, b) => {
                    let titleA = a.dataset.title;
                    let titleB = b.dataset.title;
                    let ratingA = parseFloat(a.dataset.rating) || 0;
                    let ratingB = parseFloat(b.dataset.rating) || 0;
                    let dateA = parseInt(a.dataset.date) || 0;
                    let dateB = parseInt(b.dataset.date) || 0;

                    switch (criteria) {
                        case "abc": 
                            return titleA.localeCompare(titleB);
                        case "cba":
                            return titleB.localeCompare(titleA);
                        case "byRating":
                            return ratingB - ratingA;
                        case "byDate": 
                            return dateB - dateA;
                        case "byDateRev":
                            return dateA - dateB;
                        default:
                            return 0;
                    }
            });

            articles.forEach((article, index) => {
                movieContainer.appendChild(article);

                const counterElement = article.querySelector(".counter");
                if (counterElement) {
                    counterElement.innerText = (index + 1) + ".";
                }
            });
            }
        });
    </script>
</html>
<?php 
} else {
    header("Location: ../../index.php"); 
    exit();
}
?>