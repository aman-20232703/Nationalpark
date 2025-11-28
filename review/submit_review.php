<?php
include '../dbconnect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to submit a review']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $park_name = mysqli_real_escape_string($conn, $_POST['park_name']);
    $rating = intval($_POST['rating']);
    $cleanliness = intval($_POST['cleanliness']);
    $safety = intval($_POST['safety']);
    $facilities = intval($_POST['facilities']);
    $guide_quality = intval($_POST['guide_quality']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    // Get user's name for display
    $user_query = "SELECT full_name, profile_image FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);

    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, park_name, rating, cleanliness, safety, facilities, guide_quality, review_text, created_at) 
            VALUES ('$user_id', '$park_name', '$rating', '$cleanliness', '$safety', '$facilities', '$guide_quality', '$review_text', NOW())";

    if (mysqli_query($conn, $sql)) {
        $review_id = mysqli_insert_id($conn);
        echo json_encode([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => [
                'id' => $review_id,
                'name' => $user_data['full_name'],
                'avatar' => $user_data['profile_image'] ? '../uploads/' . $user_data['profile_image'] : 'https://i.pravatar.cc/40?img=1',
                'date' => date('Y-m-d'),
                'rating' => $rating,
                'park' => $park_name,
                'text' => $review_text,
                'cleanliness' => $cleanliness,
                'safety' => $safety,
                'facilities' => $facilities,
                'guide_quality' => $guide_quality,
                'likes' => 0,
                'dislikes' => 0
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
