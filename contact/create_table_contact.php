<?php
include '../dbconnect.php';

// Create contacts table
$sql1 = "CREATE TABLE IF NOT EXISTS contacts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL,
  inquiry VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('new','in-progress','closed') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user (user_id),
  INDEX idx_email (email),
  INDEX idx_status (status),
  INDEX idx_created (created_at)
)";

// Create contact_replies table
$sql2 = "CREATE TABLE IF NOT EXISTS contact_replies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  contact_id INT UNSIGNED NOT NULL,
  admin_id INT UNSIGNED NULL,
  reply_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_contact (contact_id),
  INDEX idx_created (created_at)
)";

$ok1 = mysqli_query($conn, $sql1);
$ok2 = mysqli_query($conn, $sql2);

if ($ok1 && $ok2) {
    echo "Contacts tables ready.\n";
    echo '<a href="contact.php">Go to Contact Page</a>';
} else {
    echo "Error creating tables: " . mysqli_error($conn);
}

mysqli_close($conn);
