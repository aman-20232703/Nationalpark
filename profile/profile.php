<?php
session_start();
include '../dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// Default values
$name = 'User';
$email = '';
$phone = '';
$id_proof = '';
$address = '';
$profile_image = '../assets/default-user.png';

// Attempt to fetch user columns that may or may not exist in DB.
// We will query only columns we expect; if a column is missing the prepare will fail — handle gracefully.
$userColumns = ['full_name', 'email', 'phone', 'id_proof', 'address', 'profile_image'];
$selectCols = implode(",", $userColumns);

$sql = "SELECT $selectCols FROM users WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    // Fallback: try a minimal query (only full_name and email)
    $sql2 = "SELECT full_name, email FROM users WHERE id = ? LIMIT 1";
    $stmt2 = mysqli_prepare($conn, $sql2);
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, 'i', $user_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $tmp_name, $tmp_email);
        if (mysqli_stmt_fetch($stmt2)) {
            $name = $tmp_name ?: $name;
            $email = $tmp_email ?: $email;
        }
        mysqli_stmt_close($stmt2);
    }
} else {
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    // bind result variables for all columns (use null variables even if some columns are missing/NULL)
    mysqli_stmt_bind_result(
        $stmt,
        $r_full_name,
        $r_email,
        $r_phone,
        $r_id_proof,
        $r_address,
        $r_profile_image
    );
    if (mysqli_stmt_fetch($stmt)) {
        if (!empty($r_full_name)) $name = $r_full_name;
        if (!empty($r_email)) $email = $r_email;
        if (!empty($r_phone)) $phone = $r_phone;
        if (!empty($r_id_proof)) $id_proof = $r_id_proof;
        if (!empty($r_address)) $address = $r_address;
        if (!empty($r_profile_image)) {
            // if stored as filename, use uploads folder; if full path/url, use it directly
            if (strpos($r_profile_image, '/') === false && strpos($r_profile_image, '\\') === false) {
                $profile_image = '../uploads/' . $r_profile_image;
            } else {
                $profile_image = $r_profile_image;
            }
        }
    }
    mysqli_stmt_close($stmt);
}

// Fetch recent booking(s) for this user (if bookings.user_id exists). If user_id was not set on booking, we'll try fetching by id_proof.
$bookings = [];
$sqlBk = "SELECT ticket_id, park, visit_date, time_slot, entry_gate, tickets_count, visitor_names, created_at
           FROM bookings
           WHERE user_id = ?
           ORDER BY created_at DESC
           LIMIT 5";
$stmtBk = mysqli_prepare($conn, $sqlBk);
if ($stmtBk) {
    mysqli_stmt_bind_param($stmtBk, 'i', $user_id);
    mysqli_stmt_execute($stmtBk);
    mysqli_stmt_bind_result($stmtBk, $b_ticket, $b_park, $b_date, $b_time, $b_gate, $b_count, $b_visitors, $b_created);
    while (mysqli_stmt_fetch($stmtBk)) {
        $bookings[] = [
            'ticket' => $b_ticket,
            'park' => $b_park,
            'date' => $b_date,
            'time' => $b_time,
            'gate' => $b_gate,
            'count' => $b_count,
            'visitors' => $b_visitors,
            'created' => $b_created
        ];
    }
    mysqli_stmt_close($stmtBk);
} elseif (!empty($id_proof)) {
    // fallback: fetch bookings by id_proof if user_id wasn't used
    $sqlBk2 = "SELECT ticket_id, park, visit_date, time_slot, entry_gate, tickets_count, visitor_names, created_at
               FROM bookings
               WHERE id_proof = ?
               ORDER BY created_at DESC
               LIMIT 5";
    $stmtBk2 = mysqli_prepare($conn, $sqlBk2);
    if ($stmtBk2) {
        mysqli_stmt_bind_param($stmtBk2, 's', $id_proof);
        mysqli_stmt_execute($stmtBk2);
        mysqli_stmt_bind_result($stmtBk2, $b_ticket, $b_park, $b_date, $b_time, $b_gate, $b_count, $b_visitors, $b_created);
        while (mysqli_stmt_fetch($stmtBk2)) {
            $bookings[] = [
                'ticket' => $b_ticket,
                'park' => $b_park,
                'date' => $b_date,
                'time' => $b_time,
                'gate' => $b_gate,
                'count' => $b_count,
                'visitors' => $b_visitors,
                'created' => $b_created
            ];
        }
        mysqli_stmt_close($stmtBk2);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User Profile - National Park</title>
    <link rel="stylesheet" href="profile.css">
    <style>
        /* minimal fallback styling if profile.css missing */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ecf9f0, #e6f7ea);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .profile-header {
            display: flex;
            gap: 18px;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 16px;
        }

        .profile-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #27ae60;
        }

        .info h2 {
            margin: 0;
            color: #145a32;
        }

        .info p {
            margin: 6px 0;
            color: #333;
        }

        .actions {
            margin-left: auto;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn {
            background: #27ae60;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn.secondary {
            background: #e6f2ea;
            color: #145a32;
            border: 1px solid #d0e6d3;
        }

        .section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .booking {
            background: #f9fff7;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid #e2f4df;
        }

        .small {
            font-size: 13px;
            color: #666;
        }

        a.logout {
            color: #d9534f;
            text-decoration: none;
            margin-left: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-header" role="region" aria-label="User profile header">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile picture" class="profile-img">
            <div class="info">
                <h2 id="username"><?php echo htmlspecialchars($name); ?></h2>
                <p><strong>Email:</strong> <span id="email"><?php echo htmlspecialchars($email); ?></span></p>
                <p><strong>Contact:</strong> <span id="phone"><?php echo htmlspecialchars($phone ?: 'Not provided'); ?></span></p>
                <p class="small"><strong>User ID:</strong> <?php echo $user_id; ?></p>
            </div>
            <div class="actions">
                <a class="btn" href="edit_profile.php">Edit Profile</a>
                <a class="btn secondary" href="../login/password.php">Change Password</a>
                <a class="logout" href="logout.php" title="Logout">Logout</a>
            </div>
        </div>

        <div class="section" aria-labelledby="personal-details-heading">
            <h3 id="personal-details-heading">Personal Details</h3>
            <table>
                <tr>
                    <td style="width:200px;"><strong>ID Proof:</strong></td>
                    <td id="idproof"><?php echo htmlspecialchars($id_proof ?: 'Not provided'); ?></td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td id="address"><?php echo htmlspecialchars($address ?: 'Not provided'); ?></td>
                </tr>
            </table>
        </div>

        <div class="section" aria-labelledby="booking-details-heading">
            <h3 id="booking-details-heading">Recent Bookings</h3>

            <?php if (empty($bookings)): ?>
                <p class="small">No recent bookings found.</p>
            <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                    <div class="booking" role="article" aria-label="Booking <?php echo htmlspecialchars($b['ticket']); ?>">
                        <p><strong>Park:</strong> <?php echo htmlspecialchars($b['park']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($b['date']); ?> &nbsp; <strong>Time:</strong> <?php echo htmlspecialchars($b['time']); ?></p>
                        <p><strong>Entry Gate:</strong> <?php echo htmlspecialchars($b['gate']); ?> &nbsp; <strong>Tickets:</strong> <?php echo (int)$b['count']; ?></p>
                        <p><strong>Visitors:</strong> <?php echo htmlspecialchars($b['visitors']); ?></p>
                        <p class="small">Ticket ID: <?php echo htmlspecialchars($b['ticket']); ?> — Booked on <?php echo htmlspecialchars($b['created']); ?></p>

                        <p>
                            <a class="btn secondary" href="../tickets/view_ticket.php?ticket=<?php echo rawurlencode($b['ticket']); ?>&mode=view" target="_blank" rel="noopener">View Ticket</a>
                            <a class="btn" href="../tickets/view_ticket.php?ticket=<?php echo rawurlencode($b['ticket']); ?>&mode=download">Download Ticket</a>
                        </p>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <div class="section" aria-labelledby="account-settings-heading">
            <h3 id="account-settings-heading">Account Settings</h3>
            <p><button class="btn" onclick="location.href='../login/password.php'">Change Password</button></p>
        </div>
    </div>

</body>

</html>