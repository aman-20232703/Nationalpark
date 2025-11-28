<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
include '../dbconnect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in', 'debug' => 'No session']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$user_id = $_SESSION['user_id'];
$name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$inquiry = mysqli_real_escape_string($conn, $_POST['inquiry'] ?? '');
$message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$sql = "INSERT INTO contacts (user_id, name, email, inquiry, message) VALUES ('$user_id', '$name', '$email', '$inquiry', '$message')";
if (!mysqli_query($conn, $sql)) {
    echo json_encode(['success' => false, 'message' => 'Database error. Please check if table exists.', 'debug' => mysqli_error($conn), 'sql' => $sql]);
    exit;
}

$contact_id = mysqli_insert_id($conn);

// Send email notification to user (basic mail())
$subject = "We received your query - National Park";
$body = "Hello $name,\n\nThank you for contacting National Park. Your inquiry (" . ($inquiry ?: 'General Inquiry') . ") has been received. Our team will reply back soon.\n\nMessage you sent:\n$message\n\nRegards,\nNational Park Support";
$headers = "From: support@nationalpark.local\r\nReply-To: support@nationalpark.local\r\n";

$mailSent = @mail($email, $subject, $body, $headers);

$responseMessage = $mailSent ? 'Queries replied back soon.' : 'Query saved. Trying to send email notification soon.';

echo json_encode(['success' => true, 'message' => $responseMessage, 'contact_id' => $contact_id]);

mysqli_close($conn);
