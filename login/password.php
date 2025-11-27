<?php
// change_password.php
session_start();
include '../dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$errors = [];
$success = false;

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid request. Please reload the page and try again.';
    }

    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($current === '') $errors[] = 'Current password is required.';
    if ($new === '') $errors[] = 'New password is required.';
    if ($confirm === '') $errors[] = 'Please confirm the new password.';

    if ($new !== '' && strlen($new) < 8) $errors[] = 'New password must be at least 8 characters.';
    if ($new !== '' && $new !== $confirm) $errors[] = 'New password and confirm password do not match.';

    if (empty($errors)) {
        // fetch existing hash
        $sql = "SELECT password_hash FROM users WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $errors[] = 'Database error (prepare).';
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $stored_hash);
            if (mysqli_stmt_fetch($stmt)) {
                // verify current password
                if (!password_verify($current, $stored_hash)) {
                    $errors[] = 'Current password is incorrect.';
                } else {
                    // prevent re-using the same password
                    if (password_verify($new, $stored_hash)) {
                        $errors[] = 'New password must be different from the current password.';
                    } else {
                        // create new hash and update
                        $new_hash = password_hash($new, PASSWORD_DEFAULT);
                        mysqli_stmt_close($stmt);

                        $upd = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE id = ?");
                        if (!$upd) {
                            $errors[] = 'Database error (prepare update).';
                        } else {
                            mysqli_stmt_bind_param($upd, 'si', $new_hash, $user_id);
                            $ok = mysqli_stmt_execute($upd);
                            mysqli_stmt_close($upd);
                            if ($ok) {
                                // success: set flash message and redirect to profile
                                $_SESSION['flash_success'] = 'Password changed successfully.';
                                // regenerate CSRF token
                                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                                header('Location: ../profile/profile.php');
                                exit;
                            } else {
                                $errors[] = 'Failed to update password. Please try again.';
                            }
                        }
                    }
                }
            } else {
                $errors[] = 'User account not found.';
            }
            if ($stmt) mysqli_stmt_close($stmt);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Change Password - National Park</title>
<link rel="stylesheet" href="profile.css">
<style>
/* small fallback styles */
.container { max-width:520px; margin:40px auto; background:#fff; padding:22px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.06); font-family:Arial, sans-serif; }
h2 { color:#145a32; margin-top:0; }
label { display:block; margin:10px 0 6px; font-weight:600; }
input[type="password"] { width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; }
.btn { background:#27ae60; color:#fff; padding:10px 14px; border-radius:8px; border:none; cursor:pointer; margin-top:12px; }
.alert { padding:10px; border-radius:6px; margin:10px 0; }
.alert.error { background:#ffe6e6; border:1px solid #ffb3b3; color:#8a1a1a; }
.alert.success { background:#e6ffe6; border:1px solid #b3ffb3; color:#155724; }
.small { font-size:13px; color:#666; margin-top:8px; }
</style>
</head>
<body>
<div class="container">
    <h2>Change Password</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul style="margin:0 0 0 18px; padding:0;">
                <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" class="btn">Change Password</button>
        <a href="../profile/profile.php" style="margin-left:8px; text-decoration:none; color:#145a32;">Cancel</a>
    </form>

    <p class="small">Password must be at least 8 characters. Use a mix of letters, numbers and symbols for better security.</p>
</div>
</body>
</html>
