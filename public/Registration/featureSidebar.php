<div id="FeatureSidebar">
    <div id="FeatureSidebar-Content">
        <div id="FeatureSidebarCloseButtonArea">
            <button id="CloseButton" onclick="closeFeatureSidebar()">&#x2715;</button>
        </div>
        <div id="FeatureSidebarTitle">
            <h2>FUNKCIJAS UN JAUNUMI</h2>
        </div>
<?php
    require "../../Database/database.php"; 
    $stmt = $mysqli->prepare("SELECT title, message, date FROM features_news ORDER BY date DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if (empty($row["title"]) || empty($row["message"]) || empty($row["date"])) {
                die("There is an empty result. Execution has been halted");
            }
            $safeTitle = htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8');
            $safeMessage = htmlspecialchars($row["message"], ENT_QUOTES, 'UTF-8');
            $safeDate = htmlspecialchars($row["date"], ENT_QUOTES, 'UTF-8');
?>
        <div class="FeatureSidebarMessage">
            <div class="FeatureSidebarMessageDate">
                <p><?= $safeDate ?></p>
            </div>
            <div class="FeatureSidebarMessageTitle">
                <p><?= $safeTitle ?></p>
            </div>
            <div class="FeatureSidebarMessageText">
                <p><?= $safeMessage ?></p>
            </div>
        </div>
<?php
        }
    } else {
        echo "No features or news found.";
    }
    $stmt->close();
    $mysqli->close();
?>
    </div>
</div>