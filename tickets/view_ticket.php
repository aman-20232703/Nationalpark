<?php
// view_ticket.php (debug-friendly replacement)
session_start();
include '../dbconnect.php'; // adjust path if necessary

// === CONFIG ===
$DEBUG = true; // set to false after debugging
$TICKETS_DIR = __DIR__ . '/tickets'; // adjust if your tickets folder is elsewhere

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Access denied. Please login.";
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$ticket = $_GET['ticket'] ?? '';
$mode = (($_GET['mode'] ?? 'view') === 'download') ? 'download' : 'view';

if ($ticket === '') {
    http_response_code(400);
    echo "Missing ticket id.";
    exit;
}

if (!preg_match('/^[A-Za-z0-9_-]+$/', $ticket)) {
    http_response_code(400);
    echo "Invalid ticket id.";
    exit;
}

// Lookup booking
$sql = "SELECT id, ticket_id, user_id, id_proof, payment_status FROM bookings WHERE ticket_id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    http_response_code(500);
    echo "Database error (prepare): " . htmlspecialchars(mysqli_error($conn));
    exit;
}
mysqli_stmt_bind_param($stmt, 's', $ticket);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $booking_id, $ticket_id, $booking_user_id, $booking_id_proof, $payment_status);
$found = mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$found) {
    http_response_code(404);
    echo "Ticket not found in bookings table (ticket id may be wrong).";
    if ($DEBUG) {
        echo "<hr><strong>Debug:</strong> Queried ticket id: " . htmlspecialchars($ticket);
        // show recent bookings for current user to help find ticket ids
        $q = mysqli_prepare($conn, "SELECT ticket_id, created_at FROM bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
        if ($q) {
            mysqli_stmt_bind_param($q, 'i', $user_id);
            mysqli_stmt_execute($q);
            mysqli_stmt_bind_result($q, $tkt, $c_at);
            echo "<p>Your recent bookings (from DB):</p><ul>";
            while (mysqli_stmt_fetch($q)) {
                echo "<li>" . htmlspecialchars($tkt) . " — " . htmlspecialchars($c_at) . "</li>";
            }
            echo "</ul>";
            mysqli_stmt_close($q);
        }
    }
    exit;
}

// Authorization check (owner or id_proof match)
$allowed = false;
if ($booking_user_id && (int)$booking_user_id === $user_id) {
    $allowed = true;
} else {
    $sqlu = "SELECT id_proof FROM users WHERE id = ? LIMIT 1";
    $st2 = mysqli_prepare($conn, $sqlu);
    if ($st2) {
        mysqli_stmt_bind_param($st2, 'i', $user_id);
        mysqli_stmt_execute($st2);
        mysqli_stmt_bind_result($st2, $user_id_proof);
        if (mysqli_stmt_fetch($st2)) {
            if (!empty($user_id_proof) && !empty($booking_id_proof) && $user_id_proof === $booking_id_proof) {
                $allowed = true;
            }
        }
        mysqli_stmt_close($st2);
    }
}

if (!$allowed) {
    http_response_code(403);
    echo "You are not authorized to view this ticket.";
    exit;
}

// === Find the ticket file ===
// We expect files named like: ticket_{ticket}.pdf or .txt
$variants = [
    $TICKETS_DIR . '/ticket_' . $ticket . '.pdf',
    $TICKETS_DIR . '/ticket_' . $ticket . '.PDF',
    $TICKETS_DIR . '/ticket_' . $ticket . '.txt',
    $TICKETS_DIR . '/ticket_' . $ticket . '.TXT',
];

// Also search for any file that contains the ticket id in its name (useful if naming differs)
$foundFile = null;
foreach ($variants as $p) {
    if (is_file($p) && is_readable($p)) {
        $foundFile = $p;
        break;
    }
}

// If not found, do a directory scan for files that include the ticket id
if (!$foundFile && is_dir($TICKETS_DIR)) {
    $pattern = '/ticket_.*' . preg_quote($ticket, '/') . '.*/i';
    $dh = opendir($TICKETS_DIR);
    if ($dh !== false) {
        while (($entry = readdir($dh)) !== false) {
            if ($entry === '.' || $entry === '..') continue;
            if (preg_match($pattern, $entry)) {
                $candidate = $TICKETS_DIR . '/' . $entry;
                if (is_file($candidate) && is_readable($candidate)) {
                    $foundFile = $candidate;
                    break;
                }
            }
        }
        closedir($dh);
    }
}

// Debugging / helpful info when file still not found
if (!$foundFile) {
    http_response_code(404);
    echo "Ticket file not found for ticket: " . htmlspecialchars($ticket) . "<br>";
    if ($DEBUG) {
        echo "<h4>Debug Info</h4>";
        echo "<p><strong>Expected tickets folder:</strong> " . htmlspecialchars($TICKETS_DIR) . "</p>";
        echo "<p><strong>Checked variants:</strong></p><ul>";
        foreach ($variants as $v) echo "<li>" . htmlspecialchars($v) . "</li>";
        echo "</ul>";

        // show matching files in directory
        if (is_dir($TICKETS_DIR)) {
            echo "<p><strong>Files in tickets/ matching 'ticket_':</strong></p><ul>";
            $files = glob($TICKETS_DIR . '/ticket_*');
            if ($files === false) {
                echo "<li>(glob failed or no files)</li>";
            } else {
                foreach ($files as $f) {
                    echo "<li>" . htmlspecialchars(basename($f)) . " — " . (is_readable($f) ? 'readable' : 'not readable') . "</li>";
                }
            }
            echo "</ul>";
        } else {
            echo "<p>Tickets directory does not exist or is not accessible by PHP.</p>";
        }

        // show booking DB row for verification
        echo "<h5>Booking DB row</h5>";
        echo "<pre>" . htmlspecialchars(json_encode([
            'booking_id' => $booking_id,
            'ticket_id' => $ticket_id,
            'booking_user_id' => $booking_user_id,
            'booking_id_proof' => $booking_id_proof,
            'payment_status' => $payment_status
        ], JSON_PRETTY_PRINT)) . "</pre>";
    }
    exit;
}

// Serve the file (same as before)
$mime = mime_content_type($foundFile) ?: 'application/octet-stream';
$basename = basename($foundFile);

if ($mode === 'download') {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $basename . '"');
    header('Content-Length: ' . filesize($foundFile));
    readfile($foundFile);
    exit;
}

$inlineMimes = ['application/pdf', 'text/plain', 'text/html', 'image/jpeg', 'image/png', 'image/webp'];
if (in_array($mime, $inlineMimes, true)) {
    header('Content-Type: ' . $mime);
    header('Content-Disposition: inline; filename="' . $basename . '"');
    header('Content-Length: ' . filesize($foundFile));
    readfile($foundFile);
    exit;
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $basename . '"');
header('Content-Length: ' . filesize($foundFile));
readfile($foundFile);
exit;
