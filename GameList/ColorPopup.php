<link rel="stylesheet" href="../Style/changeColorPopup.css">
<script src="defaultColours.js" defer></script>
<div>
    <button onclick="ColorPopup()" class="ColorPopupButton" title="Change the webpage's colour style">Change Style</button>
</div>
<div id="ColorPopup" class="ColorPopup">
    <div class="styleColors">
        <button class="blueButton" onclick="blueButton()"></button>
        <button class="redButton" onclick="redButton()"></button>
        <button class="greenButton" onclick="greenButton()"></button>
        <button class="purpleButton" onclick="purpleButton()"></button>
        <button class="pinkButton" onclick="pinkButton()"></button>
        <button class="blackButton" onclick="blackButton()"></button>
        <div>
            <button Onclick="closeColorPopup()" class="ColorCloseButton"></button>
        </div>
    </div>
</div>