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
    <link rel="stylesheet" href="gallery.css?v=<?php echo time(); ?>">

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
                    <select class="filter-select" id="stateFilter">
                        <option value="">All States</option>
                        <option value="Rajasthan">Rajasthan</option>
                        <option value="Assam">Assam</option>
                        <option value="Kerala">Kerala</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Uttarakhand">Uttarakhand</option>
                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="West Bengal">West Bengal</option>
                        <option value="Bihar">Bihar</option>
                        <option value="Ladakh">Ladakh</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select class="filter-select" id="wildlifeFilter">
                        <option value="">All Wildlife</option>
                        <option value="Tigers">Tigers</option>
                        <option value="Lions">Lions</option>
                        <option value="Rhinos">Rhinos</option>
                        <option value="Elephants">Elephants</option>
                        <option value="Snow Leopard">Snow Leopard</option>
                    </select>
                </div>
                <button class="filter-btn" id="clearFilters">Clear Filters</button>
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
            <!-- Parks will be loaded dynamically from API -->
            <div class="loading" id="loadingMessage">Loading national parks...</div>
        </section>
    </main>

    <!-- Modal Popup -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <button class="modal-close" id="modalClose">&times;</button>
            <div class="modal-content" id="modalContent">
                <!-- Content will be dynamically loaded -->
            </div>
        </div>
    </div>

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
    <script src="gallery.js?v=<?php echo time(); ?>"></script>
</body>

</html>