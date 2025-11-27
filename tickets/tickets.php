<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>National Park Ticket Booking</title>
    <link rel="stylesheet" href="tickets.css">
    <script src="tickets.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #145a32, #2ecc71);
            margin: 0;
            padding: 0;
            color: #333;
            overflow-x: hidden;
        }

        .container {
            width: 70%;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #145a32;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #27ae60;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background: #145a32;
        }

        .confirmation {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #d4edda;
            color: #155724;
            display: none;
        }

        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            width: 350px;
            position: relative;
        }

        .popup-content img {
            width: 200px;
            margin: 15px 0;
        }

        .close-btn {
            position: absolute;
            top: 8px;
            right: 12px;
            font-size: 20px;
            cursor: pointer;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🎟 National Park Ticket Booking</h2>
        <form id="bookingForm" action="ticket.php" method = "POST">

            <label for="park">Select National Park:</label>
            <select id="park" name="park" required>
                <option value="">--Choose--</option>
                <option value="Jim Corbett">Jim Corbett</option>
                <option value="Kaziranga">Kaziranga</option>
                <option value="Ranthambore">Ranthambore</option>
                <option value="Gir">Gir</option>
                <option value="Sundarbans">Sundarbans</option>
            </select>

            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Select Safari Time:</label>
            <select id="time" name="time" required>
                <option value="Morning">Morning (6:00 AM – 10:00 AM)</option>
                <option value="Afternoon">Afternoon (2:00 PM – 6:00 PM)</option>
                <option value="Evening">Evening (6:00 PM – 9:00 PM)</option>
            </select>

            <label for="gate" >Entry Gate:</label>
            <select id="gate" name="gate" required>
                <option>Main Gate</option>
                <option>East Gate</option>
                <option>West Gate</option>
            </select>

            <label for="tickets">Number of Tickets:</label>
            <input type="number" id="tickets" name="tickets" min="1" max="10" required>

            <label for="visitor">Visitor Name(s):</label>
            <input type="text" id="visitor" name="visitor" placeholder="Enter full name(s)" required>

            <label for="id">Visitor ID Proof (Aadhar/Passport):</label>
            <input type="text" id="id" name="id_proof" placeholder="Enter ID Number" maxlength=12 minlength=12 required>

            <label for="payment">Payment Method:</label>
            <select id="payment" name="payment" required>
                <option>UPI</option>
                <option>Credit Card</option>
                <option>Debit Card</option>
                <option>Net Banking</option>
            </select>

            <button type="submit" class="btn">Proceed to Payment</button>
        </form>

        <div class="confirmation" id="confirmation">
            ✅ Your booking is confirmed! Thank you for choosing our National Park.
            Details will be sent to your email.
        </div>
    </div>

    <!-- Payment Popup -->
    <div class="popup" id="paymentPopup">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h3>Scan & Pay</h3>
            <p>Use any UPI app to scan this code and complete your payment</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=example@upi&pn=NationalPark&am=500"
                alt="QR Code">
            <button class="btn" onclick="confirmBooking()">I Have Paid</button>
        </div>
    </div>

    <script>
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
    </script>
</body>

</html>