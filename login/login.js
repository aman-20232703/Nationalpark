const form = document.getElementById("loginForm");
const popup = document.getElementById("popup");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  // Here you can add real authentication (AJAX/Backend API)
  popup.style.display = "flex";

  setTimeout(() => {
    popup.style.display = "none";
    form.reset();
  }, 3000);
});
