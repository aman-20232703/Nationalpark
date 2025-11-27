
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
    <title>Wild Trails - National Parks of India</title>
    <link rel="stylesheet" href="gallery.css">

    <!-- <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Header */
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
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-links a:hover {
            color: #27ae60;
            background: rgba(39, 174, 96, 0.1);
        }

        /* Main Content */
        .main-content {
            margin-top: 80px;
            padding: 3rem 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero {
            text-align: left;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #2c3e50 0%, #27ae60 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.2rem;
            color: #7f8c8d;
            max-width: 600px;
        }

        /* Search and Filters */
        .search-section {
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 3rem 1rem 1rem;
            border: 2px solid #ecf0f1;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 20px rgba(39, 174, 96, 0.2);
        }

        .search-icon-input {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #bdc3c7;
        }

        .filters {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #ecf0f1;
            background: white;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            border-color: #27ae60;
            color: #27ae60;
        }

        .sort-section {
            margin-bottom: 2rem;
        }

        .sort-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .sort-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .sort-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #ecf0f1;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .sort-btn.active,
        .sort-btn:hover {
            background: #27ae60;
            color: white;
            border-color: #27ae60;
        }

        /* Parks Grid */
        .parks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .park-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 280px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .park-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .park-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.1) 100%);
            z-index: 1;
        }

        .park-card h3 {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            right: 1.5rem;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            z-index: 2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
            line-height: 1.2;
        }

        /* Park Card Backgrounds */
        .park-card:nth-child(1) {
            background: url('../gallery/Ranthambhore.jpg');
        }

        .park-card:nth-child(2) {
            background: url('../gallery/Kaziranga.jpg');
        }

        .park-card:nth-child(3) {
            background: url('../gallery/Periyar.JPG');
        }

        .park-card:nth-child(4) {
            background: url('../gallery/bandipur.webp');
        }

        .park-card:nth-child(5) {
            background: url('../gallery/Corbett_National_Park.jpg');
        }

        .park-card:nth-child(6) {
            background: url('../gallery/kanha.webp');
        }

        .park-card:nth-child(7) {
            background: url('../gallery/gir.jpg');
        }

        .park-card:nth-child(8) {
            background: url('../gallery/sunderban.webp');
        }

        .park-card:nth-child(9) {
            background: url('../gallery/valmiki.jpg');
        }

        .park-card:nth-child(10) {
            background: url('../gallery/nagarhole.webp');
        }

        .park-card:nth-child(11) {
            background: url('../gallery/hemis.webp');
        }

        .park-card:nth-child(12) {
            background: url('../gallery/nandadevi.webp');
        }

        .park-card {
            background-size: cover !important;
            background-position: center !important;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 2rem 2rem 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            justify-content: center;
            align-items: center;
            gap: 2rem;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #27ae60;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            margin-left: 130px;

        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: #27ae60;
            transform: translateY(-5px);
        }

        .copyright {
            text-align: center;
            margin-top: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #bdc3c7;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .parks-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                flex-direction: column;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .park-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .park-card:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .park-card:nth-child(even) {
            animation-delay: 0.2s;
        }
    </style> -->
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="logo">🌿 National Park</div>
            <ul class="nav-links">
                <li><a href="../home/home.php">Explore</a></li>
                <li><a href="../gallery/gallery.php">Gallery</a></li>
                <li><a href="../review/review.php">Review</a></li>
                <li><a href="../contact/contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero">
            <h1>National Parks of India</h1>
            <p>Discover the diverse wildlife and breathtaking landscapes of India's national parks. Plan your adventure
                today.</p>
        </section>

        <!-- Search Section -->
        <section class="search-section">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search for parks..." id="searchInput">
                <span class="search-icon-input">🔍</span>
            </div>

            <div class="filters">
                <div class="filter-dropdown">
                    <button class="filter-btn" id="stateFilter">
                        State
                        <select name="state" id="">
                            <option value="">▼</option>
                            <option value="">Andhra pradesh</option>
                            <option value="">Arunachal pradesh</option>
                            <option value="">Assam</option>
                            <option value="">Bihar</option>
                            <option value="">Chhatisgarh</option>
                            <option value="">Goa</option>
                            <option value="">Gujarat</option>
                            <option value="">Haryana</option>
                            <option value="">Himachal Pradesh</option>
                            <option value="">Jharkhand</option>
                            <option value="">Karnataka</option>
                            <option value="">Kerala</option>
                            <option value="">Madhya Pradesh</option>
                            <option value="">Maharashtra</option>
                            <option value="">Manipur</option>
                            <option value="">Meghalaya</option>
                            <option value="">Mizoram</option>
                            <option value="">Nagaland</option>
                            <option value="">Odisha</option>
                            <option value="">Punjab</option>
                            <option value="">Rajasthan</option>
                            <option value="">Sikkim</option>
                            <option value="">Tamil Nadu</option>
                            <option value="">Tripura</option>
                            <option value="">Uttarakhand</option>
                            <option value="">Uttar Pradesh</option>
                            <option value="">West Bengal</option>
                            <option value="">Telangana</option>
                        </select>
                    </button>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" id="wildlifeFilter">
                        Wildlife Type <span>▼</span>
                    </button>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" id="parksFilter">
                        All Parks <span>▼</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Sort Section -->
        <section class="sort-section">
            <h3 class="sort-title">Sort By</h3>
            <div class="sort-buttons">
                <button class="sort-btn active" data-sort="most-visited">Most Visited</button>
                <button class="sort-btn" data-sort="most-reviewed">Most Reviewed</button>
                <button class="sort-btn" data-sort="user-rating">User Rating</button>
            </div>
        </section>

        <!-- Parks Grid -->
        <section class="parks-grid" id="parksGrid">
            <div class="park-card" data-park="ranthambore">
                <h3>Ranthambore National Park</h3>
            </div>
            <div class="park-card" data-park="kaziranga">
                <h3>Kaziranga National Park</h3>
            </div>
            <div class="park-card" data-park="periyar">
                <h3>Periyar National Park</h3>
            </div>
            <div class="park-card" data-park="bandipur">
                <h3>Bandipur National Park</h3>
            </div>
            <div class="park-card" data-park="jim-corbett">
                <h3>Jim Corbett National Park</h3>
            </div>
            <div class="park-card" data-park="kanha">
                <h3>Kanha National Park</h3>
            </div>
            <div class="park-card" data-park="gir">
                <h3>Gir Forest National Park</h3>
            </div>
            <div class="park-card" data-park="sundarbans">
                <h3>Sundarbans National Park</h3>
            </div>
            <div class="park-card" data-park="eravikulam">
                <h3>Valmiki National Park</h3>
            </div>
            <div class="park-card" data-park="valley-flowers">
                <h3>Nagarhole National Park</h3>
            </div>
            <div class="park-card" data-park="hemis">
                <h3>Hemis National Park</h3>
            </div>
            <div class="park-card" data-park="nanda-devi">
                <h3>Nanda Devi National Park</h3>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="../contact/contact.php">Contact Us</a>|
                <a href="../privacy/privacy.php">Privacy Policy</a>|
                <a href="../term/term.php">Terms of Service</a>
            </div>
            <div class="social-icons">
                <div class="social-icon">📧</div>
                <div class="social-icon">📱</div>
                <div class="social-icon">🌐</div>
            </div>
        </div>
        <div class="copyright">
            © 2025 National Park. All rights reserved.
        </div>
    </footer>

    <!-- <script>
        // Park data
        const parksData = {
            'ranthambore': { name: 'Ranthambore National Park', state: 'Rajasthan', wildlife: 'Tigers', visits: 15000, reviews: 4500, rating: 4.8 },
            'kaziranga': { name: 'Kaziranga National Park', state: 'Assam', wildlife: 'Rhinos', visits: 12000, reviews: 3800, rating: 4.7 },
            'periyar': { name: 'Periyar National Park', state: 'Kerala', wildlife: 'Elephants', visits: 18000, reviews: 5200, rating: 4.6 },
            'bandipur': { name: 'Bandipur National Park', state: 'Karnataka', wildlife: 'Tigers', visits: 11000, reviews: 3200, rating: 4.5 },
            'jim-corbett': { name: 'Jim Corbett National Park', state: 'Uttarakhand', wildlife: 'Tigers', visits: 20000, reviews: 6800, rating: 4.9 },
            'kanha': { name: 'Kanha National Park', state: 'Madhya Pradesh', wildlife: 'Tigers', visits: 14000, reviews: 4100, rating: 4.8 },
            'gir': { name: 'Gir Forest National Park', state: 'Gujarat', wildlife: 'Lions', visits: 13000, reviews: 3900, rating: 4.7 },
            'sundarbans': { name: 'Sundarbans National Park', state: 'West Bengal', wildlife: 'Tigers', visits: 8000, reviews: 2400, rating: 4.4 },
            'eravikulam': { name: 'Eravikulam National Park', state: 'Kerala', wildlife: 'Nilgiri Tahr', visits: 9000, reviews: 2700, rating: 4.6 },
            'valley-flowers': { name: 'Nagarhole National Park', state: 'karnataka', wildlife: 'Tigers', visits: 7000, reviews: 2100, rating: 4.9 },
            'hemis': { name: 'Hemis National Park', state: 'Ladakh', wildlife: 'Snow Leopard', visits: 5000, reviews: 1500, rating: 4.8 },
            'nanda-devi': { name: 'Nanda Devi National Park', state: 'Uttarakhand', wildlife: 'Snow Leopard', visits: 4000, reviews: 1200, rating: 4.7 }
        };

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const parksGrid = document.getElementById('parksGrid');

        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const parkCards = parksGrid.querySelectorAll('.park-card');

            parkCards.forEach(card => {
                const parkName = card.querySelector('h3').textContent.toLowerCase();
                if (parkName.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.4s ease forwards';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Sort functionality
        const sortButtons = document.querySelectorAll('.sort-btn');
        const parkCards = Array.from(document.querySelectorAll('.park-card'));

        sortButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Update active state
                sortButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const sortBy = this.dataset.sort;
                let sortedCards;

                switch (sortBy) {
                    case 'most-visited':
                        sortedCards = parkCards.sort((a, b) => {
                            const aData = parksData[a.dataset.park];
                            const bData = parksData[b.dataset.park];
                            return bData.visits - aData.visits;
                        });
                        break;
                    case 'most-reviewed':
                        sortedCards = parkCards.sort((a, b) => {
                            const aData = parksData[a.dataset.park];
                            const bData = parksData[b.dataset.park];
                            return bData.reviews - aData.reviews;
                        });
                        break;
                    case 'user-rating':
                        sortedCards = parkCards.sort((a, b) => {
                            const aData = parksData[a.dataset.park];
                            const bData = parksData[b.dataset.park];
                            return bData.rating - aData.rating;
                        });
                        break;
                }

                // Re-append sorted cards
                sortedCards.forEach(card => {
                    parksGrid.appendChild(card);
                });

                // Re-animate cards
                sortedCards.forEach((card, index) => {
                    card.style.animation = 'none';
                    setTimeout(() => {
                        card.style.animation = `fadeInUp 0.4s ease forwards`;
                    }, index * 50);
                });
            });
        });

        // Park card click events
        parkCards.forEach(card => {
            card.addEventListener('click', function () {
                const parkKey = this.dataset.park;
                const parkData = parksData[parkKey];
                alert(`Welcome to ${parkData.name}!\n\nLocation: ${parkData.state}\nFamous for: ${parkData.wildlife}\nRating: ${parkData.rating}/5`);
            });
        });

        // Filter button interactions
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                this.style.backgroundColor = '#27ae60';
                this.style.color = 'white';
                this.style.borderColor = '#27ae60';

                setTimeout(() => {
                    this.style.backgroundColor = 'white';
                    this.style.color = '#333';
                    this.style.borderColor = '#ecf0f1';
                }, 200);
            });
        });

    </script> -->
    <script src="gallery.js"></script>
</body>

</html>