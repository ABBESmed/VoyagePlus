// JavaScript for the burger menu functionality
const burger = document.getElementById("burger");
const nav = document.getElementById("nav");
const buttons = document.getElementById("buttons");

if (burger && nav && buttons) {
  burger.addEventListener("click", function () {
    nav.classList.toggle("active");
    buttons.classList.toggle("active");
  });
}

// JavaScript for the password visibility toggle
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("toggle-password");

if (passwordInput && togglePassword) {
  togglePassword.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      togglePassword.textContent = "🙈";
    } else {
      passwordInput.type = "password";
      togglePassword.textContent = "👁️";
    }
  });
}

// JavaScript for confirm password visibility toggle
const confirmPasswordInput = document.getElementById("confirm-password");
const toggleConfirmPassword = document.getElementById(
  "toggle-confirm-password",
);

if (confirmPasswordInput && toggleConfirmPassword) {
  toggleConfirmPassword.addEventListener("click", function () {
    if (confirmPasswordInput.type === "password") {
      confirmPasswordInput.type = "text";
      toggleConfirmPassword.textContent = "🙈";
    } else {
      confirmPasswordInput.type = "password";
      toggleConfirmPassword.textContent = "👁️";
    }
  });
}

// JavaScript for the scroll to top button functionality
const scrollTopBtn = document.getElementById("scrollTopBtn");

if (scrollTopBtn) {
  window.addEventListener("scroll", function () {
    if (window.scrollY > 300) {
      scrollTopBtn.style.display = "block";
    } else {
      scrollTopBtn.style.display = "none";
    }
  });

  scrollTopBtn.addEventListener("click", function () {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
}

// passenger fields based on number of persons
console.log("Reservation JS loaded");

const personsInput = document.getElementById("personnes");
const passengersContainer = document.getElementById("passengers-container");

if (personsInput && passengersContainer) {
  personsInput.addEventListener("input", function () {
    passengersContainer.innerHTML = "";

    const persons = Number(personsInput.value);

    for (let i = 1; i <= persons; i++) {
      passengersContainer.innerHTML += `
                <div class="passenger-box">
                    <h3>Passager ${i}</h3>

                    <label>Nom complet du passager</label>
                    <input type="text" name="passenger_fullname[]" placeholder="Nom complet du passager" required>

                    <label>Date de naissance</label>
                    <input type="date" name="passenger_birth_date[]" required>
                </div>
            `;
    }
  });
}
