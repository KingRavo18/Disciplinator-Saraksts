function FeatureSidebar(){
    var FeatureSidebar = document.getElementById("featureSidebar").style.left = 0;
}
function closeFeatureSidebar() {
    var closeFeatureSidebar = document.getElementById("featureSidebar").style.left= "-400rem";
}
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
    if (url.searchParams.has('login_error')) {
        url.searchParams.delete('login_error');
        window.history.replaceState(null, null, url);
    }
    if (url.searchParams.has('signup_error')) {
        url.searchParams.delete('signup_error');
        window.history.replaceState(null, null, url);
    }
});

function HideTermsArea() {
    const termsArea = document.getElementById("termsArea");
    const termsFullArea = document.getElementById("termsFullArea");

    termsArea.classList.add("hide");

    termsArea.addEventListener('animationend', function() {
        termsArea.style.display = "none"; 
        termsFullArea.style.display = "none"; 
    });

    termsFullArea.style.display = "block";
    termsArea.style.visibility = "visible";
}


