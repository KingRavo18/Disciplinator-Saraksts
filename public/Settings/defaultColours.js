function changeColor(color) {
    document.getElementById("TopBar").style.background = color;
    document.getElementById("sidebar").style.background = color;
    document.getElementById("Title").style.color = color;
}
function blueButton() {
    changeColor("navy");
}
function redButton() {
    changeColor("rgb(128, 0, 0)");
}
function greenButton() {
    changeColor("rgb(1, 102, 18)");
}
function purpleButton() {
    changeColor("purple");
}
function pinkButton() {
    changeColor("rgb(230, 24, 233)");
}
function blackButton() {
    changeColor("rgb(0, 0, 0)");
}
