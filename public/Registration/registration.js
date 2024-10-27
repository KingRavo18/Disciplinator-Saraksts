function FeatureSidebar(){
    var FeatureSidebar = document.getElementById("FeatureSidebar").style.left = 0;
}
function closeFeatureSidebar() {
    var closeFeatureSidebar = document.getElementById("FeatureSidebar").style.left= "-400rem";
}
// Toggle visibility
const visibilityBtn1 = document.getElementById("visibilityBtn-1")
visibilityBtn1.addEventListener("click", function() {
    toggleVisibility("password-1", "icon-1")
})
const visibilityBtn2 = document.getElementById("visibilityBtn-2")
visibilityBtn2.addEventListener("click", function() {
    toggleVisibility("password-2", "icon-2")
})
function toggleVisibility(passwordId, iconId) {
    const passwordInput = document.getElementById(passwordId)
    const icon = document.getElementById(iconId)
    if (passwordInput.type === "password") {
        passwordInput.type = "text"
        icon.innerText = "visibility_off"
    } else {
        passwordInput.type = "password"
        icon.innerText = "visibility"
    }
}
window.addEventListener('load', function() {
    const url = new URL(window.location);
    // Check and remove 'login_error'
    if (url.searchParams.has('login_error')) {
        url.searchParams.delete('login_error');
        window.history.replaceState(null, null, url);
    }
    // Check and remove 'signup_error'
    if (url.searchParams.has('signup_error')) {
        url.searchParams.delete('signup_error');
        window.history.replaceState(null, null, url);
    }
});
//Forgot Password Window
function ShowForgotPasswordArea(){
    var ShowForgotPassword = document.getElementById("forgotPasswordArea").style.display = "block";
    var ShowForgotPasswordArea = document.getElementById("forgotPasswordFullArea").style.display = "block";
}
function HideForgotPasswordArea(){
    var HideForgotPassword = document.getElementById("forgotPasswordArea").style.display = "none";
    var HideForgotPasswordArea = document.getElementById("forgotPasswordFullArea").style.display = "none";
}