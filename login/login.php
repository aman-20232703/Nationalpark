<?php
session_start();
include '../dbconnect.php';

$errors = [];
$success = false;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "SELECT id, full_name, password_hash FROM users WHERE email = ? LIMIT 1");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $uid, $uname, $hashed_password);

            if (mysqli_stmt_fetch($stmt)) {
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $uid;
                    $_SESSION['user_name'] = $uname;
                    $success = true;
                } else {
                    $errors[] = 'Incorrect password.';
                }
            } else {
                $errors[] = 'Email not found.';
            }

            mysqli_stmt_close($stmt);
        } else {
            $errors[] = 'Database error.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wild Trails</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>

    <div class="login-box">
        <h2>Login</h2>

        <?php if (!empty($errors)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <script>
                setTimeout(() => {
                    document.getElementById("popup").style.display = "flex";
                    setTimeout(() => { 
                        window.location.href = "../home/home.php"; 
                    }, 2000);
                }, 300);
            </script>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="options">
                <label><input type="checkbox"> Remember Me</label>
                <a href="../forgotpassword/forgotpassword.php">Forgot Password?</a>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="signup">
            Don't have an account? <a href="../sinup/sinup.php">Sign Up</a>
        </div>
    </div>

    <div class="popup" id="popup">
        <div class="popup-content">
            <h3>✅ Login Successful!</h3>
            <p>Welcome back to National Park 🌿</p>
        </div>
    </div>

</body>

</html>
