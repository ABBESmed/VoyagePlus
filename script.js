// JavaScript for the burger menu functionality
const burger = document.getElementById("burger");
const nav = document.getElementById("nav");
const buttons = document.getElementById("buttons");

burger.addEventListener("click", function(){
    nav.classList.toggle("active");
    buttons.classList.toggle("active"); 
});


// JavaScript for the password visibility toggle
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("toggle-password");

const confirmPasswordInput = document.getElementById("confirm-password");
const toggleConfirmPassword = document.getElementById("toggle-confirm-password");

togglePassword.addEventListener("click", function() {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePassword.textContent = "🙈"; // Change icon to indicate visibility
    } else {
        passwordInput.type = "password";
        togglePassword.textContent = "👁️"; // Change icon to indicate hidden
    }
});

toggleConfirmPassword.addEventListener("click", function() {
    if (confirmPasswordInput.type === "password") {
        confirmPasswordInput.type = "text";
        toggleConfirmPassword.textContent = "🙈"; // Change icon to indicate visibility
    } else {
        confirmPasswordInput.type = "password";
        toggleConfirmPassword.textContent = "👁️"; // Change icon to indicate hidden
    }
});