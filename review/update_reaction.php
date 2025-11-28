<?php
include '../dbconnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = intval($_POST['review_id']);
    $action = $_POST['action']; // 'like' or 'dislike'

    if ($action === 'like') {
        $sql = "UPDATE reviews SET likes = likes + 1 WHERE id = $review_id";
    } elseif ($action === 'dislike') {
        $sql = "UPDATE reviews SET dislikes = dislikes + 1 WHERE id = $review_id";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    if (mysqli_query($conn, $sql)) {
        // Get updated counts
        $result = mysqli_query($conn, "SELECT likes, dislikes FROM reviews WHERE id = $review_id");
        $data = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'likes' => $data['likes'],
            'dislikes' => $data['dislikes']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating reaction']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
