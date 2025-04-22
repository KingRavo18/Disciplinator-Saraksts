<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['page_language'])) {
    $_SESSION['page_language'] = 'lv'; 
}
if (!isset($_SESSION['page_theme'])) {
    $_SESSION['page_theme'] = '#fff'; 
}
$language = $_SESSION['page_language'] ?? 'lv';
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCut.png" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="../Images/fistLogoCutDarkMode.png" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../Style/sidebar.css">
    <link rel="stylesheet" href="../Style/settings.css">
    <link rel="stylesheet" href="../Style/mainPageTopBar.css">
    <link rel="stylesheet" href="../Style/global.css"/>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title><?= $language === 'lv' ? 'Disciplinators - Terms & Conditions' : 'Disciplinators - Noteikumi un Nosacījumi'; ?></title>
</head>
<body>
    <main>
        <div class="pageTitle">
            <h1 style="color: <?= isset($_SESSION['page_theme']) ? $_SESSION['page_theme'] : '#fff'; ?>"><?= $language === 'lv' ? 'NOTEIKUMI UN NOSACĪJUMI' : 'TERMS & CONDITIONS'; ?></h1>
        </div>
        <?php
            require "../Accesories/mainPageTopBar.php";
            require "../Accesories/sidebar.php";
        ?>
        <div class="terms">
        <p class="date">Efektīvs no: 2025. gada marta</p>
        <div id="terms">
        <ol>
            <p>Laipni lūgti mūsu disciplinators.kesug.com, mūsu sarakstu mājas lapā. Disciplinators piedāvā savus paklpojumus ja jūs piekritīsiet sekojošajiem nosacījumiem
            Ja jūs apmeklējat šo mājas lapu, apstipriniet šos nosacījumus. Piekrītot šim, jūs arī piekrītam mūsu <a>privātuma politikai.</a> Lūdzu lasiet tos uzmanīgi.</p>
          <li>
            <h3>Juridiski</h3>
            <p>Šie nosacījumi ir veidoti saskaņā ar Latvijas Republikas likumu. Ja jebkas mūsu nosacījumos vai darbībās neatbilst ar Latvijas Republikas juridisko likumu, tas ir ignorējams.</p>
          </li>
          <li>
            <h3>Brīdinājums un Atbildības Ierobežojums</h3>
            <p>Izņemot kā iepriekš minēts, mēs nēesam atbildīgi par lietotāju darbībām kamēr tie atrodas mūsu mājas lapā. Mēs nēesam atbildīgi par jebkādām bojājumiem radīti izmantojot šo mājas lapu vai dēļ tās. Bez šaubām mēs neatbalstam un nēesam atbildīgi par lietotāju autortiesību pārkāpumos, ieskaitot un it īpaši svešu īpašuma iegūšanu izmantojot pirātiskas metodes.</p>
          </li>
          <li>
            <h3>Kurš Drīkst Izmantot Disciplators?</h3>
            <ol>
                <li>Jums jābūt vismaz 13 gadu vecam.</li>
                <li>Tie kuriem nav jau ipriekš idzēsts mājas lapas konts.</li>
            </ol>
          </li>
          <li>
            <h3>Satura Noņemšana un Atslēgšana un Lietotāju Kontu Dzēšana.</h3>
            <p>Mēs drīkstam dzēst jebkādu informāciju, kuru esat ievietojuši mājas lapā - Disciplators, kā arī jūsu kontu, jebkad, kad mēs vēlamies.</p>
          </li>
          <li>
            <h3>Papildus Palīdzība.</h3>
            <p>Ja jums ir kādi jautāmi par mūsu noteikumiem, lūdzu sazinaties:
            rkingovskis14@gmail.com</p>
          </li>
        </ol>
        </div>
        </div>
    </main>
</body>
</html>
<?php 
} else {
    header("Location: ../../index.php"); 
    exit();
}
?>