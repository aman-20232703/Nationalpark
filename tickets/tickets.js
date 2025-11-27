const bookingForm = document.getElementById("bookingForm");
const paymentPopup = document.getElementById("paymentPopup");
const confirmation = document.getElementById("confirmation");

bookingForm.addEventListener("submit", function (e) {
  e.preventDefault();
  paymentPopup.style.display = "flex"; // Show popup
});

function closePopup() {
  paymentPopup.style.display = "none";
}

function confirmBooking() {
  paymentPopup.style.display = "none";
  confirmation.style.display = "block";
}

// Get today's date
const today = new Date();

// Minimum = today
const minDate = today.toISOString().split("T")[0];

// Maximum = today + 2 months
const maxDateObj = new Date();
maxDateObj.setMonth(maxDateObj.getMonth() + 2);
const maxDate = maxDateObj.toISOString().split("T")[0];

// Apply to date input
document.getElementById("date").setAttribute("min", minDate);
document.getElementById("date").setAttribute("max", maxDate);
