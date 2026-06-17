// ===============================
// Burger menu
// ===============================
const burger = document.getElementById("burger");
const nav = document.getElementById("nav");
const buttons = document.getElementById("buttons");

if (burger && nav && buttons) {
  burger.addEventListener("click", function () {
    nav.classList.toggle("active");
    buttons.classList.toggle("active");
  });
}

// ===============================
// Password visibility toggle
// ===============================
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

// ===============================
// Confirm password visibility toggle
// ===============================
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

// ===============================
// Scroll to top button
// ===============================
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

// ===============================
// Dynamic passenger fields
// ===============================
const personsInput = document.getElementById("personnes");
const passengersContainer = document.getElementById("passengers-container");

function generatePassengerFields() {
  if (!personsInput || !passengersContainer) {
    return;
  }

  passengersContainer.innerHTML = "";

  const numberOfPeople = parseInt(personsInput.value);

  if (isNaN(numberOfPeople) || numberOfPeople <= 1) {
    return;
  }

  // The connected user is already included.
  // 1 person = user only = 0 passenger fields
  // 2 people = user + 1 passenger field
  // 3 people = user + 2 passenger fields
  const extraPassengers = numberOfPeople - 1;

  for (let i = 1; i <= extraPassengers; i++) {
    const passengerBox = document.createElement("div");
    passengerBox.classList.add("passenger-box");

    passengerBox.innerHTML = `
      <h3>Passager ${i}</h3>

      <label>Nom complet du passager</label>
      <input 
        type="text" 
        name="passenger_fullname[]" 
        placeholder="Nom complet du passager" 
        required
      >

      <label>Date de naissance</label>
      <input 
        type="date" 
        name="passenger_birth_date[]" 
        required
      >
    `;

    passengersContainer.appendChild(passengerBox);
  }
}

if (personsInput && passengersContainer) {
  personsInput.addEventListener("input", generatePassengerFields);
  personsInput.addEventListener("change", generatePassengerFields);
}
