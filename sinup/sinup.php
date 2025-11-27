<?php
include '../dbconnect.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = false;
$full_name = '';
$email = '';
//$park = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid form submission.';
    }

    $full_name = trim($_POST['name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';
    //$park      = trim($_POST['park'] ?? '');

    if ($full_name === '') {
        $errors[] = 'Full name is required.';
    } elseif (mb_strlen($full_name) > 150) {
        $errors[] = 'Full name is too long.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($confirm === '') {
        $errors[] = 'Confirm password is required.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // $allowed_parks = [
    //     'Jim Corbett National Park',
    //     'Ranthambore National Park',
    //     'Kaziranga National Park',
    //     'Gir National Park',
    //     'Sundarbans National Park'
    // ];

    // if ($park !== '' && !in_array($park, $allowed_parks, true)) {
    //     $park = '';
    // }

if (empty($errors)) {
    try {
        // check if email already exists
        $stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE email = ? LIMIT 1');
        if ($stmt === false) {
            $errors[] = 'Database error (prepare failed).';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = 'Email already exists.';
                mysqli_stmt_close($stmt);
            } else {
                mysqli_stmt_close($stmt);

                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $ins = mysqli_prepare($conn, 'INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
                if ($ins === false) {
                    $errors[] = 'Database error (prepare failed).';
                } else {
                    mysqli_stmt_bind_param($ins, 'sss', $full_name, $email, $password_hash);
                    $exec = mysqli_stmt_execute($ins);

                    if ($exec) {
                        $success = true;
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    } else {
                        $errors[] = 'Account creation failed.';
                    }

                    mysqli_stmt_close($ins);
                }
            }
        }
    } catch (Exception $e) {
        $errors[] = 'Account creation failed.';
    }
}

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - National Park</title>
    <link rel="stylesheet" href="sinup.css">
</head>

<body>

<div class="signup-box">
    <h2>Create Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- <?php if ($success): ?>
        <div class="success-box">Account created! <a href="../login/login.php">Login</a></div>
    <?php endif; ?> -->

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="name" required value="<?php echo htmlspecialchars($full_name); ?>">
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" class="signup-btn">Sign Up</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="../login/login.php">Login</a>
    </div>
</div>

<div class="popup" id="popup">
    <div class="popup-content">
        <h3>Registration Successful!</h3>
        <p>Welcome to our National Park Community 🌿</p>
    </div>
</div>

<script>
    <?php if ($success): ?>
    setTimeout(() => {
        const pop = document.getElementById('popup');
        pop.style.display = 'flex';
        setTimeout(() => { pop.style.display = 'none'; }, 2500);
    }, 300);
    <?php endif; ?>
</script>

</body>
</html>
