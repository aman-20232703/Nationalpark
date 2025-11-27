<?php
include '../dbconnect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "❌Register first";
    header("Location: ../main/index.php");
    exit;
}
$id = $_SESSION['user_id'];
$sql = "select profile_image from users where id = '$id'";
$res = mysqli_query($conn,$sql);
// while(mysqli_num_rows($res)>0){
//     $d = mysqli_fetch_assoc($res);
// }
$d = mysqli_fetch_assoc($res);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wild Trails - Explore India's National Parks</title>
    <link rel="stylesheet" href="home.css">
    <script src="home.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            aspect-ratio: 16/9;
            overflow-x: hidden;

        }

        .park-card:nth-child(11) {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%), url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23e8f8f0"/><polygon points="50,20 65,45 90,45 72,60 78,85 50,70 22,85 28,60 10,45 35,45" fill="%2396f0a8"/></svg>');
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d5016;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav-links a:hover {
            background-color: #1ef83f;
            color: white;
            padding: 5px;
            border-radius: 5px;
        }

        .user-profile {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-image: url('<?= '../uploads/'. $d['profile_image'] ?> ');
            background-size: cover;
            text-decoration: none;
        }

        /* Hero Section */
        .video-container {
            position: relative;
            height: 100vh;
            /* full screen */
            overflow: hidden;
        }

        #bgVideo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* makes video cover the screen */
            z-index: -1;
            /* keeps video behind content */
        }

        .overlay {
            position: relative;
            color: white;
            text-align: center;
            top: 40%;
            font-size: 2rem;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.7);
        }

        .hero {
            background: linear-gradient(rgba(209, 100, 100, 0.4), rgba(0, 0, 0, 0.4));
            background-image: url("home.jpg");
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            color: linear-gradient(135deg, #2c3e50 0%, #27ae60 100%);
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: 40px;
            font-weight: 400;
        }

        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.2rem;
            font-weight: 400;
            color: black;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #45a049;
        }

        /* Main Content */
        main {
            padding: 4rem 0;
        }

        .section {
            margin-bottom: 4rem;
        }

        .section h2 {
            font-size: 2rem;
            margin-bottom: 0.3rem;
            color: #2d5016;
        }

        .section p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #666;
        }

        /* Map Section */
        .map-container {
            background: #f0f8f0;
            padding: 1rem;
            border-radius: 10px;
            text-align: left;
            margin: 1rem 0;
        }

        .map-container .map {
            width: 100%;
        }

        /* Statistics */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #2d5016;
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #666;
            margin-top: 0.5rem;
        }

        /* Testimonials */
        .testimonials {
            display: flex;
            gap: 2rem;
            margin: 2rem 0;
        }

        .testimonials {
            display: flex;
            justify-content: space-evenly;
            background: #efebeb;
            padding: 1rem;
            border-radius: 10px;
            width: 100%;
            height: 360px;
            gap: 3px;
        }

        .testimonial img {
            width: 100%;
            height: 300px;
            border-radius: 5px;
        }

        .testimonials h4 {
            text-align: center;
            font-size: 20px;
        }

        .testimonial-text {
            font-style: italic;
            color: #666;
        }

        /* Quick Facts */
        .quick-facts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .fact-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .fact-label {
            font-weight: 500;
            color: #333;
        }

        .fact-value {
            color: #2d5016;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background: #2d5016;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: none;
            color: black;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .social-links a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
        }

        .social-link:hover {
            transform: translateY(-5px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;

            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .testimonials {
                grid-template-columns: 1fr;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <div class="logo">🌿 National Park</div>
            <ul class="nav-links">
                <li><a href="../home/home.php">Explore</a></li>
                <li><a href="../gallery/gallery.php">Gallery</a></li>
                <li><a href="../review/review.php">Review</a></li>
                <li><a href="../contact/contact.php">Contact</a></li>
                <!-- <li><a href="#login">Login</a></li> -->
            </ul>
            <div class="user-profile">
                <a href="../profile/profile.php">
                    <img src="<?= '../uploads/'. $d['profile_image'] ?>" class="profile-img">
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <div class="video-container">
        <video autoplay muted loop id="bgVideo">
            <source src="home.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="overlay">
            <h1>Welcome to India's National Parks</h1>
            <p>Explore the wild like never before</p>
            <a style="font-size: 15px;" href="../gallery/gallery.php" class="btn">Gallery</a>

        </div>
    </div>

    <!-- <section class="hero">
        <div class="hero-content">
            <h1>About National Park</h1>
            <p>Discover the heart of India's wilderness. Our mission is to connect you with the breathtaking beauty and vital conservation efforts of India's National Parks. Explore more and contribute to preserving these natural treasures for generations to come.</p>
        </div>
    </section> -->

    <!-- Main Content -->
    <main class="container">
        <section class="section">
            <h2>Why National Parks Matter</h2>
            <p>National Parks are more than just scenic landscapes; they are crucial ecosystems that play a vital role
                in maintaining ecological balance, conserving wildlife, and supporting sustainable tourism. These
                protected areas safeguard biodiversity, offering sanctuary to endangered species and preserving natural
                habitats. They also serve as living laboratories for scientific research and education, enhancing our
                understanding of the natural world. Furthermore, National Parks contribute significantly to local
                economies through tourism, providing livelihoods and fostering a deeper appreciation for nature.</p>
        </section>

        <section class="section">
            <h2>India's National Parks</h2>
            <div class="map-container">
                <iframe class="map"
                    src="https://www.google.com/maps/d/embed?mid=1oTSUaZtaBbb4qLV1s_wxL2RaUinece8&ehbc=2E312F"
                    width="640" height="480"></iframe>
            </div>
        </section>

        <section class="section">
            <h2>Key Statistics</h2>
            <div class="stats">
                <div class="stat-card">
                    <span class="stat-number">107</span>
                    <div class="stat-label">Number of National Parks</div>
                </div>
                <div class="stat-card">
                    <span class="stat-number">544,402 sq km</span>
                    <div class="stat-label">area</div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Government Initiatives</h2>
            <p>The Indian government has launched several initiatives to protect and enhance its National Parks. Project
                Tiger, a flagship program, has been instrumental in increasing the tiger population and improving
                habitat quality. Other initiatives focus on combating poaching, promoting eco-tourism, and engaging
                local communities in conservation efforts. These programs reflect a commitment to preserving India's
                natural heritage and ensuring the long-term sustainability of its National Parks.</p>
        </section>

        <section class="section">
            <h2>Why Visit National Parks?</h2>
            <p>Visiting National Parks offers a unique opportunity to connect with nature, witness wildlife in their
                natural habitats, and experience the tranquility of unspoiled landscapes. It's a chance to escape the
                hustle and bustle of city life, rejuvenate your mind and body, and gain a deeper appreciation for the
                natural world. Moreover, tourism in National Parks supports conservation efforts, contributing to the
                protection of these vital ecosystems and the livelihoods of local communities. Whether you're an avid
                wildlife enthusiast, a nature lover, or simply seeking a peaceful retreat, India's National Parks offer
                an unforgettable experience.</p>
        </section>

        <section class="section">
            <h2>Testimonials</h2>
            <div class="testimonials">
                <div class="testimonial 1">
                    <img src="path.png" alt="">
                    <h4>path</h4>
                </div>
                <div class="testimonial 2">
                    <img src="conservation.png" alt="">
                    <h4>conservation</h4>
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Quick Facts</h2>
            <div class="quick-facts">
                <div class="fact-item">
                    <span class="fact-label">Largest Park</span>
                    <span class="fact-value">Hemis National Park</span>
                </div>
                <div class="fact-item">
                    <span class="fact-label">Floating Park</span>
                    <span class="fact-value">Keibul Lamjao National Park</span>
                </div>
                <div class="fact-item">
                    <span class="fact-label">Most Tigers</span>
                    <span class="fact-value">Corbett National Park</span>
                </div>
            </div>
        </section>

        <div style="text-align: center; margin: 3rem 0;">
            <a href="../tickets/tickets.php" class="btn">Plan Your Trip</a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="../contact/contact.php">Contact Us</a>|
                <a href="../privacy/privacy.php">Privacy Policy</a>|
                <a href="../term/term.php">Terms of Service</a>
            </div>
            <div class="social-links">
                <a href="#" class="social-link">📧</a>
                <a href="#" class="social-link">📱</a>
                <a href="#" class="social-link">🌐</a>
            </div>
            <p style="border-top:1px solid white; padding-top:10px">&copy; 2025 National Park. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>