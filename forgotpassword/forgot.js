let generatedOTP = null;

// Step 1: Generate OTP
function sendOTP() {
  const email = document.getElementById("email").value;
  if (!email) {
    alert("Please enter your email");
    return;
  }

  // Generate random 6-digit OTP
  generatedOTP = Math.floor(100000 + Math.random() * 900000);
  document.getElementById("otpSentMsg").textContent =
    "✅ OTP sent to your email (demo: " + generatedOTP + ")";

  // Show OTP step
  document.getElementById("step1").classList.add("hidden");
  document.getElementById("step2").classList.remove("hidden");
}

// Step 2: Verify OTP
function verifyOTP() {
  const enteredOTP = document.getElementById("otpInput").value;
  if (enteredOTP == generatedOTP) {
    document.getElementById("otpError").textContent = "";
    document.getElementById("step2").classList.add("hidden");
    document.getElementById("step3").classList.remove("hidden");
  } else {
    document.getElementById("otpError").textContent =
      "❌ Invalid OTP. Try again.";
  }
}

// Step 3: Reset Password
function resetPassword() {
  const newPass = document.getElementById("newPassword").value;
  const confirmPass = document.getElementById("confirmPassword").value;

  if (!newPass || !confirmPass) {
    alert("Please fill all password fields");
    return;
  }

  if (newPass !== confirmPass) {
    alert("❌ Passwords do not match");
    return;
  }

  document.getElementById("successMsg").textContent =
    "🎉 Password reset successful!";
  setTimeout(() => {
    window.location.href = "login.php"; // redirect to login page
  }, 2000);
}
