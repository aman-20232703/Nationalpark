<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'park';

$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn){
    die(mysqli_connect_error());
}

// $sql = "CREATE TABLE IF NOT EXISTS users (
//   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//   full_name VARCHAR(150) NOT NULL,
//   email VARCHAR(255) NOT NULL UNIQUE,
//   password_hash VARCHAR(255) NOT NULL,
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// )";

// $sql = "
// CREATE TABLE IF NOT EXISTS bookings (
//   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//   ticket_id VARCHAR(64) NOT NULL UNIQUE,
//   user_id INT NULL,                    
//   park VARCHAR(100) NOT NULL,
//   visit_date DATE NOT NULL,
//   time_slot VARCHAR(64) NOT NULL,
//   entry_gate VARCHAR(64) NOT NULL,
//   tickets_count TINYINT UNSIGNED NOT NULL,
//   visitor_names TEXT NOT NULL,
//   id_proof VARCHAR(64) NOT NULL,
//   payment_method VARCHAR(50) NOT NULL,
//   payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// ) ";
// $sql = "ALTER TABLE users
//   ADD COLUMN IF NOT EXISTS phone VARCHAR(30) NULL,
//   ADD COLUMN IF NOT EXISTS address TEXT NULL,
//   ADD COLUMN IF NOT EXISTS id_proof VARCHAR(100) NULL;
// ";
// if (mysqli_query($conn,$sql)){
//     echo ('table created successfully.');
// }
// else{
//     echo 'There is an error'.mysqli_error($conn);
// }
//mysqli_close($conn);
?>