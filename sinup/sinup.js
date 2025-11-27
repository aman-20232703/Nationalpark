const form = document.getElementById("signupForm");
const popup = document.getElementById("popup");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  // Show success popup
  popup.style.display = "flex";

  setTimeout(() => {
    popup.style.display = "none";
    form.reset();
  }, 2500);
});
