<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot.css">
    <script src="forgot.js"></script>
    <!-- <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1a472a, #145a32);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            width: 380px;
            text-align: center;
        }

        h2 {
            color: #145a32;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background: #145a32;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #0f3621;
        }

        .hidden {
            display: none;
        }

        .message {
            margin-top: 10px;
            font-size: 14px;
            color: #27ae60;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style> -->
</head>

<body>

    <div class="container">
        <h2>Forgot Password</h2>

        <!-- Step 1: Enter Email -->
        <div id="step1">
            <div class="input-group">
                <label>Enter your Email</label>
                <input type="email" id="email" placeholder="Enter your registered email" required>
            </div>
            <button onclick="sendOTP()">Send OTP</button>
            <div class="message" id="otpSentMsg"></div>
        </div>

        <!-- Step 2: Verify OTP -->
        <div id="step2" class="hidden">
            <div class="input-group">
                <label>Enter OTP</label>
                <input type="text" id="otpInput" placeholder="Enter OTP">
            </div>
            <button onclick="verifyOTP()">Verify OTP</button>
            <div class="error" id="otpError"></div>
        </div>

        <!-- Step 3: Reset Password -->
        <div id="step3" class="hidden">
            <div class="input-group">
                <label>New Password</label>
                <input type="password" id="newPassword" placeholder="Enter new password">
            </div>
            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" id="confirmPassword" placeholder="Confirm new password">
            </div>
            <button onclick="resetPassword()">Reset Password</button>
            <div class="message" id="successMsg"></div>
        </div>
    </div>

    <!-- <script>
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
                document.getElementById("otpError").textContent = "❌ Invalid OTP. Try again.";
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

            document.getElementById("successMsg").textContent = "🎉 Password reset successful!";
            setTimeout(() => {
                window.location.href = "login.php"; // redirect to login page
            }, 2000);
        }
    </script> -->

</body>

</html>