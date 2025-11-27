
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Visitor Reviews</title>
    <link rel="stylesheet" href="revi.css">
    <script src="revi.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f8f8ef;
            overflow-x: hidden;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
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

        .reviews-section h1 {
            background: linear-gradient(135deg, #a873a1 0%, #27cdd6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
        }

        .reviews-section {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }

        #searchBar {
            width: 98%;
            padding: 10px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filters select {
            padding: 8px;
            border-radius: 5px;
            border: 2px solid #0dd217;
        }

        .review-card {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);

        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .review-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .review-rating {
            color: gold;
        }

        .review-footer {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .review-footer span {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .review-footer span:hover {
            transform: scale(1.1);
        }

        .submit-review {
            text-align: center;
            margin-top: 30px;
        }

        .submit-review button {
            padding: 10px 20px;
            background: #3c8c5b;
            border: none;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .submit-review a {
            text-decoration: none;
        }

        footer {
            background: white;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            border-top: 1px solid #ddd;
        }

        footer a {
            margin: 0 10px;
            text-decoration: none;
            color: #555;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .social-link:hover {
            color: green;
        }

        .social-icon:hover {
            font-size: large;
        }

        .social-icon {
            padding-top: 20px;
            transition: all 0.2s ease;

        }
    </style>
</head>

<body>

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

    <main>
        <section class="reviews-section">
            <h1>Visitor Reviews</h1>
            <p>Read what other adventurers have to say about their experiences in India's National Parks.</p>

            <input type="text" id="searchBar" placeholder="Search for a park or keyword">

            <div class="filters">
                <select id="filterPark">
                    <option value="">All Parks</option>
                    <option value="Kaziranga">Kaziranga</option>
                    <option value="Jim Corbett">Jim Corbett</option>
                </select>

                <select id="filterRating">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                </select>

                <select id="sortDate">
                    <option value="">Sort By</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>

            <div id="reviewsContainer"></div>

            <div class="submit-review">
                <h3>Submit Your Review</h3>
                <p>Share your experience with other travelers. Log in to submit your review.</p>
                <button><a href="../login/login.php">Log In to review</a></button>
            </div>
        </section>
    </main>

    <footer>
        <div class="social-links">
            <a href="../contact/contact.php" class="social-link">Contact Us</a>|
            <a href="../privacy/privacy.php" class="social-link">Privacy Policy</a>|
            <a href="../term/term.php" class="social-link">Terms of Service</a>
        </div>
        <div class="social-icons">
            <a href="#" class="social-icon">📧</a>
            <a href="#" class="social-icon">📱</a>
            <a href="#" class="social-icon">🌐</a>
        </div>
        <p style="border-top: 1px solid black; padding-top:10px
">© 2025 National Park. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
<script>
    const reviews = [
        {
            name: "Aman Kumar",
            date: "2025-07-20",
            rating: 5,
            text: "An unforgettable experience at the park! The wildlife was abundant, and the guides were incredibly knowledgeable. Highly recommend for any nature enthusiast.",
            likes: 12,
            dislikes: 2,
            avatar: "https://i.pravatar.cc/40?img=54",
            park: "Kaziranga"
        },
        {
            name: "Ashish Gupta",
            date: "2025-06-20",
            rating: 4,
            text: "The park was beautiful, but the trails could be better marked. Overall, a good experience, but there's room for improvement.",
            likes: 8,
            dislikes: 3,
            avatar: "https://i.pravatar.cc/40?img=13",
            park: "Jim Corbett"
        },
        {
            name: "Sahil kumar",
            date: "2025-05-23",
            rating: 5,
            text: "Absolutely breathtaking! The park exceeded all expectations. The staff was friendly, and the scenery was stunning. A must-visit!",
            likes: 15,
            dislikes: 1,
            avatar: "https://i.pravatar.cc/40?img=62",
            park: "Kaziranga"
        },
        {
            name: "js singh",
            date: "2023-07-15",
            rating: 4,
            text: "all good vibes as well as outstanding.really it provide us a virtual national park view",
            likes: 9,
            dislikes: 2,
            avatar: "https://i.pravatar.cc/40?img=24",
            park: "Hemis"
        },
        {
            name: "misha roy",
            date: "2023-08-02",
            rating: 3,
            text: "although good but it can be more ellobrative. need some exctra space for the wilds also could be better pathway for the visitors ",
            likes: 14,
            dislikes: 3,
            avatar: "https://i.pravatar.cc/40?img=37",
            park: "Kanha"
        }
    ];

    const searchBar = document.getElementById("searchBar");
    const filterPark = document.getElementById("filterPark");
    const filterRating = document.getElementById("filterRating");
    const sortDate = document.getElementById("sortDate");
    const reviewsContainer = document.getElementById("reviewsContainer");

    function renderReviews(list) {
        reviewsContainer.innerHTML = "";
        list.forEach((r, index) => {
            const review = document.createElement("div");
            review.classList.add("review-card");
            review.innerHTML = `
      <div class="review-header">
        <img src="${r.avatar}" alt="${r.name}">
        <div>
          <strong>${r.name}</strong><br>
          <small>${new Date(r.date).toLocaleDateString()}</small>
        </div>
      </div>
      <div class="review-rating">${"★".repeat(r.rating)}${"☆".repeat(5 - r.rating)}</div>
      <p>${r.text}</p>
      <div class="review-footer">
        <span onclick="likeReview(${index})">👍 ${r.likes}</span>
        <span onclick="dislikeReview(${index})">👎 ${r.dislikes}</span>
      </div>
    `;
            reviewsContainer.appendChild(review);
        });
    }

    function applyFilters() {
        let filtered = [...reviews];

        const searchTerm = searchBar.value.toLowerCase();
        if (searchTerm) {
            filtered = filtered.filter(r =>
                r.text.toLowerCase().includes(searchTerm) ||
                r.name.toLowerCase().includes(searchTerm) ||
                r.park.toLowerCase().includes(searchTerm)
            );
        }

        if (filterPark.value) {
            filtered = filtered.filter(r => r.park === filterPark.value);
        }

        if (filterRating.value) {
            filtered = filtered.filter(r => r.rating === parseInt(filterRating.value));
        }

        if (sortDate.value === "newest") {
            filtered.sort((a, b) => new Date(b.date) - new Date(a.date));
        } else if (sortDate.value === "oldest") {
            filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
        }

        renderReviews(filtered);
    }

    function likeReview(index) {
        reviews[index].likes++;
        applyFilters();
    }

    function dislikeReview(index) {
        reviews[index].dislikes++;
        applyFilters();
    }

    // Event listeners
    searchBar.addEventListener("input", applyFilters);
    filterPark.addEventListener("change", applyFilters);
    filterRating.addEventListener("change", applyFilters);
    sortDate.addEventListener("change", applyFilters);

    // Initial render
    renderReviews(reviews);

</script>

</html>