<?php
include '../dbconnect.php';

// Create reviews table
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    park_name VARCHAR(100) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating >= 1 AND rating <= 5),
    cleanliness TINYINT UNSIGNED NOT NULL CHECK (cleanliness >= 1 AND cleanliness <= 5),
    safety TINYINT UNSIGNED NOT NULL CHECK (safety >= 1 AND safety <= 5),
    facilities TINYINT UNSIGNED NOT NULL CHECK (facilities >= 1 AND facilities <= 5),
    guide_quality TINYINT UNSIGNED NOT NULL CHECK (guide_quality >= 1 AND guide_quality <= 5),
    review_text TEXT NOT NULL,
    likes INT UNSIGNED DEFAULT 0,
    dislikes INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_park (park_name),
    INDEX idx_user (user_id),
    INDEX idx_rating (rating),
    INDEX idx_created (created_at)
)";

if (mysqli_query($conn, $sql)) {
    echo "Reviews table created successfully!<br>";
    echo '<a href="review.php">Go to Review Page</a>';
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
