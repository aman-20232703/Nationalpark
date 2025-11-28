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
            margin-top: 35px;
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

        /* Review Form Styles */
        .submit-review-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 40px;
        }

        .submit-review-form h3 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .submit-review-form>p {
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #27ae60;
        }

        .star-rating {
            display: flex;
            gap: 8px;
            font-size: 2.5rem;
            cursor: pointer;
        }

        .star-rating.small {
            font-size: 1.5rem;
            gap: 5px;
        }

        .star-rating .star {
            color: #ddd;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .star-rating .star:hover,
        .star-rating .star.active {
            color: #ffd700;
            transform: scale(1.1);
        }

        .rating-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 25px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }

        .category-rating {
            text-align: center;
        }

        .category-rating label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: #555;
        }

        .char-count {
            display: block;
            margin-top: 5px;
            color: #888;
            font-size: 0.85rem;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .review-card .rating-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 0.85rem;
        }

        .review-card .rating-details span {
            display: flex;
            align-items: center;
            gap: 5px;
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

        /* Toast (popup) notifications */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2ecc71;
            /* success default */
            color: #fff;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            z-index: 3000;
            opacity: 0;
            transform: translateY(-10px);
            animation: toast-in 0.25s forwards, toast-out 0.3s 3s forwards;
            font-weight: 600;
            letter-spacing: .2px;
        }

        .toast-error {
            background: #e74c3c;
        }

        .toast-success {
            background: #2ecc71;
        }

        @keyframes toast-in {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes toast-out {
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
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
                    <option value="Ranthambore">Ranthambore</option>
                    <option value="Bandhavgarh">Bandhavgarh</option>
                    <option value="Kanha">Kanha</option>
                    <option value="Hemis">Hemis</option>
                    <option value="Sundarbans">Sundarbans</option>
                    <option value="Periyar">Periyar</option>
                    <option value="Gir">Gir</option>
                    <option value="Valley of Flowers">Valley of Flowers</option>
                </select>

                <select id="filterRating">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars ⭐⭐⭐⭐⭐</option>
                    <option value="4">4 Stars ⭐⭐⭐⭐</option>
                    <option value="3">3 Stars ⭐⭐⭐</option>
                    <option value="2">2 Stars ⭐⭐</option>
                    <option value="1">1 Star ⭐</option>
                </select>

                <select id="sortDate">
                    <option value="">Sort By</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="rating">Highest Rated</option>
                </select>
            </div>

            <div id="reviewsContainer"></div>

            <!-- Review Submission Form -->
            <div class="submit-review-form">
                <h3>✍️ Submit Your Review</h3>
                <p>Share your experience with other travelers and help them plan their visit!</p>

                <form id="reviewForm">
                    <div class="form-group">
                        <label for="parkSelect">Select National Park *</label>
                        <select id="parkSelect" name="park_name" required>
                            <option value="">Choose a park...</option>
                            <option value="Kaziranga">Kaziranga National Park</option>
                            <option value="Jim Corbett">Jim Corbett National Park</option>
                            <option value="Ranthambore">Ranthambore National Park</option>
                            <option value="Bandhavgarh">Bandhavgarh National Park</option>
                            <option value="Kanha">Kanha National Park</option>
                            <option value="Hemis">Hemis National Park</option>
                            <option value="Sundarbans">Sundarbans National Park</option>
                            <option value="Periyar">Periyar National Park</option>
                            <option value="Gir">Gir National Park</option>
                            <option value="Valley of Flowers">Valley of Flowers National Park</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Overall Rating *</label>
                        <div class="star-rating" id="overallRating">
                            <span class="star" data-rating="1">☆</span>
                            <span class="star" data-rating="2">☆</span>
                            <span class="star" data-rating="3">☆</span>
                            <span class="star" data-rating="4">☆</span>
                            <span class="star" data-rating="5">☆</span>
                        </div>
                        <input type="hidden" id="ratingValue" name="rating" required>
                    </div>

                    <div class="rating-categories">
                        <div class="category-rating">
                            <label>🧹 Cleanliness</label>
                            <div class="star-rating small" data-category="cleanliness">
                                <span class="star" data-rating="1">☆</span>
                                <span class="star" data-rating="2">☆</span>
                                <span class="star" data-rating="3">☆</span>
                                <span class="star" data-rating="4">☆</span>
                                <span class="star" data-rating="5">☆</span>
                            </div>
                            <input type="hidden" name="cleanliness" id="cleanliness" required>
                        </div>

                        <div class="category-rating">
                            <label>🛡️ Safety</label>
                            <div class="star-rating small" data-category="safety">
                                <span class="star" data-rating="1">☆</span>
                                <span class="star" data-rating="2">☆</span>
                                <span class="star" data-rating="3">☆</span>
                                <span class="star" data-rating="4">☆</span>
                                <span class="star" data-rating="5">☆</span>
                            </div>
                            <input type="hidden" name="safety" id="safety" required>
                        </div>

                        <div class="category-rating">
                            <label>🏢 Facilities</label>
                            <div class="star-rating small" data-category="facilities">
                                <span class="star" data-rating="1">☆</span>
                                <span class="star" data-rating="2">☆</span>
                                <span class="star" data-rating="3">☆</span>
                                <span class="star" data-rating="4">☆</span>
                                <span class="star" data-rating="5">☆</span>
                            </div>
                            <input type="hidden" name="facilities" id="facilities" required>
                        </div>

                        <div class="category-rating">
                            <label>👨‍🏫 Guide Quality</label>
                            <div class="star-rating small" data-category="guide_quality">
                                <span class="star" data-rating="1">☆</span>
                                <span class="star" data-rating="2">☆</span>
                                <span class="star" data-rating="3">☆</span>
                                <span class="star" data-rating="4">☆</span>
                                <span class="star" data-rating="5">☆</span>
                            </div>
                            <input type="hidden" name="guide_quality" id="guide_quality" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reviewText">Your Review *</label>
                        <textarea id="reviewText" name="review_text" rows="5"
                            placeholder="Share your experience... What did you like? Any tips for future visitors?"
                            required minlength="50"></textarea>
                        <small class="char-count">Minimum 50 characters</small>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span class="btn-text">Submit Review</span>
                        <span class="btn-loading" style="display: none;">Submitting...</span>
                    </button>
                </form>
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

    <script>
        let allReviews = [];

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Load reviews on page load
            loadReviews();

            // Overall rating
            const overallStars = document.querySelectorAll('#overallRating .star');
            overallStars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    document.getElementById('ratingValue').value = rating;
                    updateStars(overallStars, rating);
                });
            });

            // Category ratings
            document.querySelectorAll('.star-rating[data-category]').forEach(ratingGroup => {
                const category = ratingGroup.getAttribute('data-category');
                const stars = ratingGroup.querySelectorAll('.star');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        document.getElementById(category).value = rating;
                        updateStars(stars, rating);
                    });
                });
            });

            // Form submission
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitReview();
            });

            // Character count
            document.getElementById('reviewText').addEventListener('input', function() {
                const count = this.value.length;
                const charCounter = document.querySelector('.char-count');
                if (count < 50) {
                    charCounter.textContent = `${count}/50 characters (minimum)`;
                    charCounter.style.color = '#e74c3c';
                } else {
                    charCounter.textContent = `${count} characters ✓`;
                    charCounter.style.color = '#27ae60';
                }
            });
        });

        function updateStars(stars, rating) {
            stars.forEach(star => {
                const starRating = star.getAttribute('data-rating');
                if (starRating <= rating) {
                    star.textContent = '★';
                    star.classList.add('active');
                } else {
                    star.textContent = '☆';
                    star.classList.remove('active');
                }
            });
        }

        // Load reviews from database
        function loadReviews() {
            const params = new URLSearchParams({
                park: document.getElementById('filterPark').value,
                rating: document.getElementById('filterRating').value,
                sort: document.getElementById('sortDate').value,
                search: document.getElementById('searchBar').value
            });

            fetch(`get_reviews.php?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allReviews = data.reviews;
                        renderReviews(allReviews);
                    }
                })
                .catch(error => console.error('Error loading reviews:', error));
        }

        // Submit review
        function submitReview() {
            const form = document.getElementById('reviewForm');
            const formData = new FormData(form);
            const submitBtn = form.querySelector('.submit-btn');

            // Validation
            if (!formData.get('rating')) {
                showAlert('Please provide an overall rating', 'error');
                return;
            }

            const categories = ['cleanliness', 'safety', 'facilities', 'guide_quality'];
            for (let cat of categories) {
                if (!formData.get(cat)) {
                    showAlert('Please rate all categories', 'error');
                    return;
                }
            }

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-text').style.display = 'none';
            submitBtn.querySelector('.btn-loading').style.display = 'inline';

            fetch('submit_review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Review submitted successfully!', 'success');
                        form.reset();
                        // Reset all stars
                        document.querySelectorAll('.star').forEach(star => {
                            star.textContent = '☆';
                            star.classList.remove('active');
                        });
                        document.querySelector('.char-count').textContent = 'Minimum 50 characters';
                        document.querySelector('.char-count').style.color = '#888';

                        // Reload reviews
                        loadReviews();

                        // Scroll to top of reviews
                        document.getElementById('reviewsContainer').scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        showToast(data.message || 'Could not submit review', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error submitting review. Please try again.', 'error');
                    console.error('Error:', error);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.querySelector('.btn-text').style.display = 'inline';
                    submitBtn.querySelector('.btn-loading').style.display = 'none';
                });
        }

        // Render reviews
        function renderReviews(reviews) {
            const container = document.getElementById('reviewsContainer');

            if (reviews.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 40px; color: #666;">No reviews found. Be the first to share your experience!</div>';
                return;
            }

            container.innerHTML = reviews.map(r => `
                <div class="review-card">
                    <div class="review-header">
                        <img src="${r.avatar}" alt="${r.name}" onerror="this.src='https://i.pravatar.cc/40?img=1'">
                        <div>
                            <strong>${r.name}</strong><br>
                            <small>${new Date(r.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</small>
                            <br><small style="color: #27ae60; font-weight: 600;">📍 ${r.park}</small>
                        </div>
                    </div>
                    <div class="review-rating">${"★".repeat(r.rating)}${"☆".repeat(5 - r.rating)}</div>
                    <div class="rating-details">
                        <span>🧹 Cleanliness: ${"★".repeat(r.cleanliness)}</span>
                        <span>🛡️ Safety: ${"★".repeat(r.safety)}</span>
                        <span>🏢 Facilities: ${"★".repeat(r.facilities)}</span>
                        <span>👨‍🏫 Guide: ${"★".repeat(r.guide_quality)}</span>
                    </div>
                    <p>${r.text}</p>
                    <div class="review-footer">
                        <span onclick="reactToReview(${r.id}, 'like', this)">👍 ${r.likes}</span>
                        <span onclick="reactToReview(${r.id}, 'dislike', this)">👎 ${r.dislikes}</span>
                    </div>
                </div>
            `).join('');
        }

        // React to review (like/dislike)
        function reactToReview(reviewId, action, element) {
            fetch('update_reaction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `review_id=${reviewId}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const footer = element.parentElement;
                        const spans = footer.querySelectorAll('span');
                        spans[0].innerHTML = `👍 ${data.likes}`;
                        spans[1].innerHTML = `👎 ${data.dislikes}`;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Toast (popup) message utility
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type === 'error' ? 'error' : 'success'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            // Remove after animation completes
            setTimeout(() => {
                toast.remove();
            }, 3400);
        }

        // Show alert message
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;

            const form = document.getElementById('reviewForm');
            form.insertBefore(alertDiv, form.firstChild);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Filter and search functionality
        document.getElementById('searchBar').addEventListener('input', loadReviews);
        document.getElementById('filterPark').addEventListener('change', loadReviews);
        document.getElementById('filterRating').addEventListener('change', loadReviews);
        document.getElementById('sortDate').addEventListener('change', loadReviews);
    </script>

</html>