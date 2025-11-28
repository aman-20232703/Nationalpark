<?php
include '../dbconnect.php';

header('Content-Type: application/json');

$park_filter = isset($_GET['park']) ? mysqli_real_escape_string($conn, $_GET['park']) : '';
$rating_filter = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT r.*, u.full_name, u.profile_image 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE 1=1";

if ($park_filter) {
    $sql .= " AND r.park_name = '$park_filter'";
}

if ($rating_filter > 0) {
    $sql .= " AND r.rating = $rating_filter";
}

if ($search) {
    $sql .= " AND (r.review_text LIKE '%$search%' OR u.full_name LIKE '%$search%' OR r.park_name LIKE '%$search%')";
}

if ($sort === 'newest') {
    $sql .= " ORDER BY r.created_at DESC";
} elseif ($sort === 'oldest') {
    $sql .= " ORDER BY r.created_at ASC";
} else {
    $sql .= " ORDER BY r.rating DESC";
}

$result = mysqli_query($conn, $sql);
$reviews = [];

while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = [
        'id' => $row['id'],
        'name' => $row['full_name'],
        'avatar' => $row['profile_image'] ? '../uploads/' . $row['profile_image'] : 'https://i.pravatar.cc/40?img=' . rand(1, 70),
        'date' => $row['created_at'],
        'rating' => intval($row['rating']),
        'park' => $row['park_name'],
        'text' => $row['review_text'],
        'cleanliness' => intval($row['cleanliness']),
        'safety' => intval($row['safety']),
        'facilities' => intval($row['facilities']),
        'guide_quality' => intval($row['guide_quality']),
        'likes' => intval($row['likes']),
        'dislikes' => intval($row['dislikes'])
    ];
}

echo json_encode(['success' => true, 'reviews' => $reviews]);

mysqli_close($conn);
