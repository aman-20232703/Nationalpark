<?php
session_start();
include '../dbconnect.php'; // must set $conn (procedural mysqli)

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$errors = [];
$success = false;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch current user values
$name = $email = $phone = $address = $id_proof = '';
$profile_image = '../assets/default-user.png';
$id_proof_file_web = null; // web path for existing id proof file

$sql = "SELECT full_name, email, phone, address, profile_image, id_proof, id_proof_file FROM users WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $r_name, $r_email, $r_phone, $r_address, $r_profile_image, $r_id_proof, $r_id_proof_file);
    if (mysqli_stmt_fetch($stmt)) {
        $name = $r_name ?? '';
        $email = $r_email ?? '';
        $phone = $r_phone ?? '';
        $address = $r_address ?? '';
        $id_proof = $r_id_proof ?? '';
        if (!empty($r_profile_image)) {
            if (strpos($r_profile_image, '/') === false && strpos($r_profile_image, '\\') === false) {
                $profile_image = '../uploads/' . $r_profile_image;
            } else {
                $profile_image = $r_profile_image;
            }
        }
        if (!empty($r_id_proof_file)) {
            // stored as filename -> use uploads/idproofs folder
            if (strpos($r_id_proof_file, '/') === false && strpos($r_id_proof_file, '\\') === false) {
                $id_proof_file_web = '../uploads/idproofs/' . $r_id_proof_file;
            } else {
                $id_proof_file_web = $r_id_proof_file;
            }
        }
    }
    mysqli_stmt_close($stmt);
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid request. Please reload and try again.';
    }

    $fullname = trim($_POST['full_name'] ?? '');
    $email_in = trim($_POST['email'] ?? '');
    $phone_in = trim($_POST['phone'] ?? '');
    $address_in = trim($_POST['address'] ?? '');
    $id_proof_in = trim($_POST['id_proof'] ?? '');

    // Validate basic fields
    if ($fullname === '') $errors[] = 'Full name is required.';
    if ($email_in === '') $errors[] = 'Email is required.';
    elseif (!filter_var($email_in, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if ($phone_in !== '' && !preg_match('/^[0-9+\-\s]{7,30}$/', $phone_in)) $errors[] = 'Invalid phone number format.';
    if ($id_proof_in !== '' && strlen($id_proof_in) > 100) $errors[] = 'ID proof number is too long.';

    // Check email uniqueness
    if (empty($errors)) {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
        if ($check) {
            mysqli_stmt_bind_param($check, 'si', $email_in, $user_id);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);
            if (mysqli_stmt_num_rows($check) > 0) {
                $errors[] = 'Email is already used by another account.';
            }
            mysqli_stmt_close($check);
        } else {
            $errors[] = 'Database error (email check).';
        }
    }

    // Profile image handling (optional) - same as before
    $uploaded_profile_filename = null;
    if (empty($errors) && !empty($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['profile_image'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Failed to upload profile image (error ' . $f['error'] . ').';
        } else {
            if ($f['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Profile image must be 2 MB or smaller.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $f['tmp_name']);
                finfo_close($finfo);
                $allowed_img = [
                    'image/jpeg' => 'jpg',
                    'image/pjpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp'
                ];
                if (!array_key_exists($mime, $allowed_img)) {
                    $errors[] = 'Allowed profile image types: JPG, PNG, WEBP.';
                } else {
                    $ext = $allowed_img[$mime];
                    $upload_dir = __DIR__ . '/../uploads';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    $basename = 'user_' . $user_id . '_' . time() . '_' . bin2hex(random_bytes(4));
                    $filename = $basename . '.' . $ext;
                    $destination = $upload_dir . '/' . $filename;
                    if (!move_uploaded_file($f['tmp_name'], $destination)) {
                        $errors[] = 'Failed to move uploaded profile image.';
                    } else {
                        $uploaded_profile_filename = $filename;
                        @chmod($destination, 0644);
                    }
                }
            }
        }
    }

    // ID proof file handling (optional). Allowed: PDF, JPG, PNG, WEBP. Max 5 MB.
    $uploaded_idproof_filename = null;
    if (empty($errors) && !empty($_FILES['id_proof_file']) && $_FILES['id_proof_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $g = $_FILES['id_proof_file'];
        if ($g['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Failed to upload ID proof file (error ' . $g['error'] . ').';
        } else {
            if ($g['size'] > 5 * 1024 * 1024) {
                $errors[] = 'ID proof file must be 5 MB or smaller.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $g['tmp_name']);
                finfo_close($finfo);
                $allowed_id = [
                    'application/pdf' => 'pdf',
                    'image/jpeg' => 'jpg',
                    'image/pjpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp'
                ];
                if (!array_key_exists($mime, $allowed_id)) {
                    $errors[] = 'Allowed ID proof file types: PDF, JPG, PNG, WEBP.';
                } else {
                    $ext = $allowed_id[$mime];
                    $upload_dir = __DIR__ . '/../uploads/idproofs';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    $basename = 'idproof_' . $user_id . '_' . time() . '_' . bin2hex(random_bytes(4));
                    $filename = $basename . '.' . $ext;
                    $destination = $upload_dir . '/' . $filename;
                    if (!move_uploaded_file($g['tmp_name'], $destination)) {
                        $errors[] = 'Failed to move uploaded ID proof file.';
                    } else {
                        $uploaded_idproof_filename = $filename;
                        @chmod($destination, 0644);
                    }
                }
            }
        }
    }

    // If no errors -> update DB
    if (empty($errors)) {
        // choose update query depending on whether files were uploaded
        $fields = [];
        $types = '';
        $params = [];

        $fields[] = 'full_name = ?';
        $types .= 's';
        $params[] = $fullname;
        $fields[] = 'email = ?';
        $types .= 's';
        $params[] = $email_in;
        $fields[] = 'phone = ?';
        $types .= 's';
        $params[] = $phone_in;
        $fields[] = 'address = ?';
        $types .= 's';
        $params[] = $address_in;
        $fields[] = 'id_proof = ?';
        $types .= 's';
        $params[] = $id_proof_in;

        if ($uploaded_profile_filename !== null) {
            $fields[] = 'profile_image = ?';
            $types .= 's';
            $params[] = $uploaded_profile_filename;
        }
        if ($uploaded_idproof_filename !== null) {
            $fields[] = 'id_proof_file = ?';
            $types .= 's';
            $params[] = $uploaded_idproof_filename;
        }

        $params[] = $user_id;
        $types .= 'i';

        $sqlSet = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $upd = mysqli_prepare($conn, $sqlSet);
        if (!$upd) {
            $errors[] = 'Database error (prepare update).';
        } else {
            // bind dynamically
            // mysqli_stmt_bind_param requires variables by reference; build array
            $bind_names[] = $types;
            for ($i = 0; $i < count($params); $i++) {
                $bind_names[] = &$params[$i];
            }
            call_user_func_array('mysqli_stmt_bind_param', array_merge([$upd], $bind_names));
            $ok = mysqli_stmt_execute($upd);
            mysqli_stmt_close($upd);

            if ($ok) {
                // delete old files if replaced
                if (
                    $uploaded_profile_filename !== null && !empty($r_profile_image) &&
                    strpos($r_profile_image, '/') === false && is_file(__DIR__ . '/../uploads/' . $r_profile_image)
                ) {
                    @unlink(__DIR__ . '/../uploads/' . $r_profile_image);
                }
                if (
                    $uploaded_idproof_filename !== null && !empty($r_id_proof_file) &&
                    strpos($r_id_proof_file, '/') === false && is_file(__DIR__ . '/../uploads/idproofs/' . $r_id_proof_file)
                ) {
                    @unlink(__DIR__ . '/../uploads/idproofs/' . $r_id_proof_file);
                }

                // refresh displayed values
                $name = $fullname;
                $email = $email_in;
                $phone = $phone_in;
                $address = $address_in;
                $id_proof = $id_proof_in;
                if ($uploaded_profile_filename !== null) $profile_image = '../uploads/' . $uploaded_profile_filename;
                if ($uploaded_idproof_filename !== null) $id_proof_file_web = '../uploads/idproofs/' . $uploaded_idproof_filename;

                $success = true;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header('Location: profile.php');
                exit;
            } else {
                $errors[] = 'Failed to update profile.';
                // cleanup newly uploaded files on failure
                if ($uploaded_profile_filename) @unlink(__DIR__ . '/../uploads/' . $uploaded_profile_filename);
                if ($uploaded_idproof_filename) @unlink(__DIR__ . '/../uploads/idproofs/' . $uploaded_idproof_filename);
                header('Location: profile.php');
                exit;
            }
        }
    } // end empty errors
} // POST end
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Profile - National Park</title>
    <link rel="stylesheet" href="profile.css">
    <style>
        .container {
            max-width: 780px;
            margin: 28px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            font-family: Arial, sans-serif;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #27ae60;
        }

        .form-row {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .form-col {
            flex: 1;
        }

        label {
            display: block;
            margin: 8px 0 6px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="file"] {
            margin-top: 6px;
        }

        .btn {
            background: #27ae60;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .alert {
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
        }

        .alert.error {
            background: #ffe6e6;
            border: 1px solid #ffb3b3;
            color: #8a1a1a;
        }

        .alert.success {
            background: #e6ffe6;
            border: 1px solid #b3ffb3;
            color: #155724;
        }

        .small {
            font-size: 13px;
            color: #666;
            margin-top: 6px;
        }

        .link {
            display: inline-block;
            margin-top: 8px;
            color: #145a32;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container" role="main">
        <h2>Edit Profile</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <ul style="margin:0 0 0 18px; padding:0;"><?php foreach ($errors as $err) echo '<li>' . htmlspecialchars($err) . '</li>'; ?></ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success">Profile updated successfully.</div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-row">
                <div style="width:140px; text-align:center;">
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile photo" class="profile-img" id="previewImg">
                    <div class="small">Current photo</div>
                </div>

                <div class="form-col">
                    <label for="full_name">Full name</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($name); ?>" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" maxlength="10" value="<?php echo htmlspecialchars($phone); ?>">

                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                </div>
            </div>

            <div style="margin-top:12px;">
                <label for="profile_image">Upload new profile image (JPG, PNG, WEBP) — max 2 MB</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*">
                <div class="small">If you don't upload a new image, the existing one will remain.</div>
            </div>

            <hr style="margin:16px 0;">

            <h3>ID Proof</h3>
            <div>
                <label for="id_proof">ID Proof Number (Aadhaar / Passport)</label>
                <input type="text" id="id_proof" name="id_proof" value="<?php echo htmlspecialchars($id_proof); ?>">
            </div>
            <div style="margin-top:8px;">
                <label for="id_proof_file">Upload ID Proof file (PDF, JPG, PNG, WEBP) — max 5 MB</label>
                <input type="file" name="id_proof_file" id="id_proof_file" accept=".pdf,image/*">
                <?php if ($id_proof_file_web): ?>
                    <div class="small">Existing file: <a class="link" href="<?php echo htmlspecialchars($id_proof_file_web); ?>" target="_blank" rel="noopener">View / Download</a></div>
                <?php else: ?>
                    <div class="small">No ID proof file uploaded yet.</div>
                <?php endif; ?>
            </div>

            <div style="margin-top:18px;">
                <button type="submit" class="btn">Save Changes</button>
                <a class="btn" style="background:#ccc;color:#000;text-decoration:none;margin-left:8px;" href="profile.php">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // client-side preview for profile image
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const f = this.files[0];
            if (!f) return;
            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!allowed.includes(f.type)) {
                alert('Allowed profile image types: JPG, PNG, WEBP');
                this.value = '';
                return;
            }
            if (f.size > 2 * 1024 * 1024) {
                alert('Profile image must be 2 MB or smaller.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('previewImg').src = ev.target.result;
            };
            reader.readAsDataURL(f);
        });

        // client-side validation for ID proof file
        document.getElementById('id_proof_file').addEventListener('change', function(e) {
            const f = this.files[0];
            if (!f) return;
            const allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
            if (!allowed.includes(f.type)) {
                alert('Allowed ID proof file types: PDF, JPG, PNG, WEBP');
                this.value = '';
                return;
            }
            if (f.size > 5 * 1024 * 1024) {
                alert('ID proof file must be 5 MB or smaller.');
                this.value = '';
                return;
            }
        });
    </script>
</body>

</html>