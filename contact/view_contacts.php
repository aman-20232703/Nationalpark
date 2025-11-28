<?php
session_start();
include '../dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "❌Please login first";
    header("Location: ../main/index.php");
    exit;
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_submit'])) {
    $contact_id = intval($_POST['contact_id']);
    $reply_text = mysqli_real_escape_string($conn, $_POST['reply_text']);
    $admin_id = $_SESSION['user_id'];

    // Get contact details
    $contactQuery = mysqli_query($conn, "SELECT * FROM contacts WHERE id = $contact_id");
    $contact = mysqli_fetch_assoc($contactQuery);

    if ($contact) {
        // Save reply to database
        $replySql = "INSERT INTO contact_replies (contact_id, admin_id, reply_text) VALUES ($contact_id, $admin_id, '$reply_text')";

        if (mysqli_query($conn, $replySql)) {
            // Update contact status
            mysqli_query($conn, "UPDATE contacts SET status='in-progress' WHERE id=$contact_id");

            // Send email notification
            $to = $contact['email'];
            $subject = "Reply to your query - National Park";
            $message = "Hello {$contact['name']},\n\n";
            $message .= "Thank you for contacting National Park. Here is our response to your query:\n\n";
            $message .= "Your Inquiry: {$contact['inquiry']}\n";
            $message .= "Your Message: {$contact['message']}\n\n";
            $message .= "Our Reply:\n{$reply_text}\n\n";
            $message .= "If you have any further questions, please don't hesitate to contact us.\n\n";
            $message .= "Best regards,\nNational Park Support Team";

            $headers = "From: support@nationalpark.com\r\n";
            $headers .= "Reply-To: support@nationalpark.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            $mailSent = @mail($to, $subject, $message, $headers);

            $successMsg = $mailSent ?
                "✅ Reply sent successfully and email notification delivered!" :
                "✅ Reply saved (email notification may not be configured on server)";
        } else {
            $errorMsg = "❌ Failed to save reply: " . mysqli_error($conn);
        }
    }
}

// Mark as closed
if (isset($_GET['close']) && is_numeric($_GET['close'])) {
    $id = intval($_GET['close']);
    mysqli_query($conn, "UPDATE contacts SET status='closed' WHERE id=$id");
    header("Location: view_contacts.php");
    exit;
}

// Get all contacts
$filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$sql = "SELECT c.*, u.full_name as user_name FROM contacts c 
        LEFT JOIN users u ON c.user_id = u.id";

if ($filter !== 'all') {
    $sql .= " WHERE c.status = '$filter'";
}

$sql .= " ORDER BY c.created_at DESC";
$contacts = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contact Queries - National Park</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #1a472a;
            margin-bottom: 10px;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
        }

        .filter-tabs a {
            padding: 8px 16px;
            background: #e8f5e9;
            color: #1a472a;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .filter-tabs a:hover,
        .filter-tabs a.active {
            background: #1a472a;
            color: white;
        }

        .back-link {
            padding: 8px 16px;
            background: #1a472a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-card.new {
            background: #e3f2fd;
        }

        .stat-card.progress {
            background: #fff3e0;
        }

        .stat-card.closed {
            background: #e8f5e9;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #1a472a;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }

        .contact-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fafafa;
        }

        .contact-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .contact-info h3 {
            color: #1a472a;
            margin-bottom: 5px;
        }

        .contact-meta {
            font-size: 14px;
            color: #666;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-badge.new {
            background: #2196f3;
            color: white;
        }

        .status-badge.in-progress {
            background: #ff9800;
            color: white;
        }

        .status-badge.closed {
            background: #4caf50;
            color: white;
        }

        .inquiry-type {
            background: #e8f5e9;
            color: #1a472a;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 13px;
            display: inline-block;
            margin-top: 5px;
        }

        .message-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #1a472a;
        }

        .reply-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
        }

        .reply-form {
            display: none;
        }

        .reply-form.active {
            display: block;
        }

        .reply-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            min-height: 120px;
            font-family: Arial, sans-serif;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-reply {
            background: #2196f3;
            color: white;
        }

        .btn-reply:hover {
            background: #1976d2;
        }

        .btn-submit {
            background: #4caf50;
            color: white;
        }

        .btn-submit:hover {
            background: #45a049;
        }

        .btn-cancel {
            background: #9e9e9e;
            color: white;
        }

        .btn-cancel:hover {
            background: #757575;
        }

        .btn-close {
            background: #ff9800;
            color: white;
            font-size: 12px;
            padding: 6px 12px;
        }

        .btn-close:hover {
            background: #f57c00;
        }

        .previous-replies {
            margin-top: 15px;
        }

        .previous-reply {
            background: #f0f8ff;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 3px solid #2196f3;
        }

        .reply-meta {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .no-contacts {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-actions">
            <div>
                <h1>📧 Contact Queries Management</h1>
                <p style="color: #666; margin-top: 5px;">View and respond to visitor inquiries</p>
            </div>
            <a href="../home/home.php" class="back-link">← Back to Home</a>
        </div>

        <?php if (isset($successMsg)): ?>
            <div class="success-msg"><?= $successMsg ?></div>
        <?php endif; ?>

        <?php if (isset($errorMsg)): ?>
            <div class="error-msg"><?= $errorMsg ?></div>
        <?php endif; ?>

        <!-- Statistics -->
        <?php
        $newCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status='new'"))['count'];
        $progressCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status='in-progress'"))['count'];
        $closedCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status='closed'"))['count'];
        ?>
        <div class="stats">
            <div class="stat-card new">
                <div class="stat-number"><?= $newCount ?></div>
                <div class="stat-label">New Queries</div>
            </div>
            <div class="stat-card progress">
                <div class="stat-number"><?= $progressCount ?></div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-card closed">
                <div class="stat-number"><?= $closedCount ?></div>
                <div class="stat-label">Closed</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="?status=all" class="<?= $filter === 'all' ? 'active' : '' ?>">All</a>
            <a href="?status=new" class="<?= $filter === 'new' ? 'active' : '' ?>">New</a>
            <a href="?status=in-progress" class="<?= $filter === 'in-progress' ? 'active' : '' ?>">In Progress</a>
            <a href="?status=closed" class="<?= $filter === 'closed' ? 'active' : '' ?>">Closed</a>
        </div>

        <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

        <!-- Contact Cards -->
        <?php if (mysqli_num_rows($contacts) > 0): ?>
            <?php while ($contact = mysqli_fetch_assoc($contacts)): ?>
                <div class="contact-card">
                    <div class="contact-header">
                        <div class="contact-info">
                            <h3><?= htmlspecialchars($contact['name']) ?></h3>
                            <div class="contact-meta">
                                📧 <?= htmlspecialchars($contact['email']) ?> |
                                👤 User: <?= htmlspecialchars($contact['user_name'] ?? 'Unknown') ?> |
                                📅 <?= date('M d, Y h:i A', strtotime($contact['created_at'])) ?>
                            </div>
                            <span class="inquiry-type">📝 <?= htmlspecialchars($contact['inquiry'] ?: 'General') ?></span>
                        </div>
                        <div>
                            <span class="status-badge <?= $contact['status'] ?>"><?= strtoupper($contact['status']) ?></span>
                            <?php if ($contact['status'] !== 'closed'): ?>
                                <a href="?close=<?= $contact['id'] ?>" class="btn btn-close" onclick="return confirm('Mark this query as closed?')">Mark Closed</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="message-box">
                        <strong>Message:</strong><br>
                        <?= nl2br(htmlspecialchars($contact['message'])) ?>
                    </div>

                    <!-- Previous Replies -->
                    <?php
                    $repliesQuery = mysqli_query($conn, "SELECT r.*, u.full_name FROM contact_replies r LEFT JOIN users u ON r.admin_id = u.id WHERE r.contact_id = {$contact['id']} ORDER BY r.created_at ASC");
                    if (mysqli_num_rows($repliesQuery) > 0):
                    ?>
                        <div class="previous-replies">
                            <strong>📨 Previous Replies:</strong>
                            <?php while ($reply = mysqli_fetch_assoc($repliesQuery)): ?>
                                <div class="previous-reply">
                                    <?= nl2br(htmlspecialchars($reply['reply_text'])) ?>
                                    <div class="reply-meta">
                                        Replied by: <?= htmlspecialchars($reply['full_name'] ?? 'Admin') ?> |
                                        <?= date('M d, Y h:i A', strtotime($reply['created_at'])) ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Reply Section -->
                    <div class="reply-section">
                        <button class="btn btn-reply" onclick="toggleReplyForm(<?= $contact['id'] ?>)">
                            ✉️ Reply to this Query
                        </button>

                        <form method="POST" class="reply-form" id="replyForm<?= $contact['id'] ?>">
                            <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                            <textarea name="reply_text" placeholder="Type your reply here... This will be sent to <?= htmlspecialchars($contact['email']) ?>" required></textarea>
                            <button type="submit" name="reply_submit" class="btn btn-submit">📤 Send Reply</button>
                            <button type="button" class="btn btn-cancel" onclick="toggleReplyForm(<?= $contact['id'] ?>)">Cancel</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-contacts">
                <h3>No contact queries found</h3>
                <p>There are no queries matching the selected filter.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleReplyForm(contactId) {
            const form = document.getElementById('replyForm' + contactId);
            form.classList.toggle('active');
            if (form.classList.contains('active')) {
                form.querySelector('textarea').focus();
            }
        }

        // Auto-hide success/error messages after 5 seconds
        setTimeout(() => {
            const msgs = document.querySelectorAll('.success-msg, .error-msg');
            msgs.forEach(msg => msg.style.display = 'none');
        }, 5000);
    </script>
</body>

</html>

<?php mysqli_close($conn); ?>