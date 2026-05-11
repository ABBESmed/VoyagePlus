const burger = document.getElementById("burger");
const nav = document.getElementById("nav");
const buttons = document.getElementById("buttons");

burger.addEventListener("click", function(){
    nav.classList.toggle("active");
    buttons.classList.toggle("active"); 
});