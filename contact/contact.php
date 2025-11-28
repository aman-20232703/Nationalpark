<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "❌Register first";
    header("Location: ../main/index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wild Trails - Contact Us</title>
    <link rel="stylesheet" href="contact.css">
    <script src="contact.js"></script>
    <style>
        /* Toast Popup Notification */
        .toast {
            position: fixed;
            top: 80px;
            right: 20px;
            min-width: 300px;
            background: #2ecc71;
            color: white;
            padding: 16px 20px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            font-size: 15px;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast.error {
            background: #e74c3c;
        }

        .toast.success {
            background: #2ecc71;
        }

        .toast::before {
            content: '✓';
            display: inline-block;
            margin-right: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .toast.error::before {
            content: '✕';
        }
    </style>
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9fafb;
            color: #333;
            overflow-x: hidden;
        }

        /* Navbar */
        .header {
            background: rgba(255, 255, 255, 0.95);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #1a334b;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-links a:hover {
            color: #27ae60;
            background: rgba(39, 174, 96, 0.1);
        }

        .login-btn {
            background-color: #1a472a;
            color:white;
            padding: 8px 16px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3 ease;

        }
        .login-btn:hover{
            transform: scale(0.9);
        }

        /* Contact Section */
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        h1 {
            color: #1a472a;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            background: #1a472a;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #145a32;
        }

        /* Support Section */
        .support {
            margin-top: 40px;
        }

        .support-card {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .support-card span {
            font-weight: bold;
        }

        /* Map */
        .map {
            margin-top: 40px;
            border-radius: 10px;
            overflow: hidden;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: 0;
        }

        /* Footer */
        footer {
            background: #1a472a;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 10px;
        }
        footer a {
            color: #90ee90;
            text-decoration: none;
        }
        .footer-links a:hover{
            color: white;
        }
        .social-icons {
            display: flex;
            justify-content:center;
            align-items: center;
            gap: 15px;
            padding-top: 15px;
        }
        .social-icons a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition:all 0.2s ease;
        }
        .social-icon:hover{
            transform: translateY(-5px);
        }
    </style> -->
</head>

<body>

    <!-- Navbar -->
    <header class="header">
        <nav class="nav">
            <div class="logo">🌿 National Park</div>
            <ul class="nav-links">
                <li><a href="../home/home.php">Explore</a></li>
                <li><a href="../gallery/gallery.php">Gallery</a></li>
                <li><a href="../review/review.php">Review</a></li>
                <li><a href="../contact/contact.php">Contact Us</a></li>
            </ul>
            <a style="color:white; " href="../contact/logout.php" class="login-btn">Logout</a>

        </nav>
    </header>

    <!-- Contact Section -->
    <div class="container">
        <h1>Contact Us</h1>
        <p>We're here to help you plan your visit to India's National Parks. Reach out with your queries, assistance
            requests, or suggestions.</p>

        <div id="contactBanner" style="display:none; background:#eafaf1; color:#1a472a; border:1px solid #c6f3d9; padding:10px 12px; border-radius:8px; margin-bottom:12px; font-weight:600;">Queries replied back soon.</div>

        <form id="contactForm">
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <select id="inquiry" name="inquiry">
                <option value="">Select Inquiry Type</option>
                <option>Ticketing</option>
                <option>Guided Tours</option>
                <option>Wildlife Emergencies</option>
                <option>General Inquiry</option>
            </select>
            <textarea id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
            <button type="submit">Submit</button>
        </form>

        <!-- Additional Support -->
        <div class="support">
            <h2>Additional Support</h2>
            <div class="support-card" style="background: #fff3cd; border: 2px solid #ffc107;">
                <strong>🔧 Admin:</strong> <a href="view_contacts.php" style="color: #1a472a; font-weight: bold; text-decoration: underline;">Manage Contact Queries & Reply to Users</a>
            </div>
            <div class="support-card">📞 <span>Wildlife Emergency Helpline</span> - Available 24/7</div>
            <div class="support-card">📞 <span>Forest Department Helpline</span> - Mon-Fri, 9 AM - 6 PM</div>
            <div class="support-card">📧 <span>Email Us:</span> support@NationalPark.com</div>
            <div class="support-card">💬 <span>WhatsApp:</span> +91-9262872022</div>
            <div class="support-card">☎️ <span>Toll-Free:</span> 1234-4567-4893</div>
        </div>

        <!-- Map -->
        <div class="map">
            <h2>Find Us</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3505.1382564304513!2d77.2603279!3d28.5355638!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce1002e48d411%3A0xa3e89a9b49aa8a3!2sGurudwara%20gali%20no.7%20govindpuri!5e0!3m2!1sen!2sin!4v1755764748389!5m2!1sen!2sin"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="../contact/contact.php">Contact Us</a>|
                <a href="../privacy/privacy.php">Privacy Policy</a>|
                <a href="../term/term.php">Terms of Service</a>
            </div>
            <div class="social-icons">
                <a href="#" class="social-icon">📧</a>
                <a href="#" class="social-icon">📱</a>
                <a href="#" class="social-icon">🌐</a>
            </div>
        </div>
        <p style="border-top: 1px solid white; padding:15px
">© 2025 National Park. All rights reserved.</p>


    </footer>

    <!-- JavaScript -->
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            // Remove any existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create new toast
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            // Show toast with animation
            setTimeout(() => toast.classList.add('show'), 100);

            // Hide and remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 5000);

            console.log(`Toast shown: ${message} (${type})`);
        }

        // Also show banner
        function showBanner(msg, isError = false) {
            const b = document.getElementById('contactBanner');
            b.textContent = msg;
            b.style.display = 'block';
            b.style.background = isError ? '#ffebee' : '#eafaf1';
            b.style.color = isError ? '#c62828' : '#1a472a';
            b.style.border = isError ? '1px solid #ef9a9a' : '1px solid #c6f3d9';

            setTimeout(() => {
                b.style.display = 'none';
            }, 5000);
        }

        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            const submitBtn = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('=== CONTACT FORM SUBMISSION ===');

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                submitBtn.style.opacity = '0.6';

                const fd = new FormData(form);
                console.log('Form data:', Object.fromEntries(fd));

                try {
                    const response = await fetch('contact_submit.php', {
                        method: 'POST',
                        body: fd
                    });

                    console.log('Response status:', response.status);
                    console.log('Content-Type:', response.headers.get('content-type'));

                    const rawText = await response.text();
                    console.log('Raw response:', rawText);

                    let data;
                    try {
                        data = JSON.parse(rawText);
                        console.log('Parsed JSON:', data);
                    } catch (parseError) {
                        console.error('JSON Parse Error:', parseError);
                        console.error('Raw text was:', rawText);
                        showToast('Server error: Invalid response', 'error');
                        showBanner('Server returned invalid response. Check console.', true);
                        return;
                    }

                    if (data.success) {
                        showToast(data.message || 'Queries replied back soon!', 'success');
                        showBanner(data.message || 'Queries replied back soon.', false);
                        form.reset();
                        console.log('✅ Form submitted successfully');
                    } else {
                        showToast(data.message || 'Failed to submit query', 'error');
                        showBanner(data.message || 'Could not submit your query.', true);

                        if (data.debug) {
                            console.error('Debug info:', data.debug);
                        }
                        if (data.sql) {
                            console.error('SQL:', data.sql);
                        }
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                    showToast('Network error. Please try again.', 'error');
                    showBanner('Network error. Please check your connection.', true);
                } finally {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit';
                    submitBtn.style.opacity = '1';
                }
            });

            console.log('✅ Contact form script loaded and ready');
        });
    </script>

</body>

</html>