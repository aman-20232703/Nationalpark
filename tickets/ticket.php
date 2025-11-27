<?php
// process_booking_pdf.php
session_start();
include '../dbconnect.php'; // procedural mysqli $conn
require('../fpdf186/fpdf.php');


function clean($v) {
    return trim($v);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed.";
    exit;
}

$park         = clean($_POST['park'] ?? '');
$visit_date   = clean($_POST['date'] ?? '');
$time_slot    = clean($_POST['time'] ?? '');
$entry_gate   = clean($_POST['gate'] ?? '');
$tickets_count = (int)($_POST['tickets'] ?? 0);
$visitor_names = clean($_POST['visitor'] ?? '');
$id_proof      = clean($_POST['id_proof'] ?? $_POST['id'] ?? '');
$payment_method = clean($_POST['payment'] ?? '');
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

if ($park === '') $errors[] = 'Park is required.';
if ($visit_date === '') $errors[] = 'Date is required.';
if ($time_slot === '') $errors[] = 'Time slot is required.';
if ($entry_gate === '') $errors[] = 'Entry gate is required.';
if ($tickets_count < 1 || $tickets_count > 10) $errors[] = 'Tickets count must be between 1 and 10.';
if ($visitor_names === '') $errors[] = 'Visitor name(s) is required.';
if ($id_proof === '') $errors[] = 'ID proof number is required.';
if ($payment_method === '') $errors[] = 'Payment method is required.';

$today = new DateTime('today');
$max = (new DateTime('today'))->modify('+2 months');

try {
    $d = new DateTime($visit_date);
    if ($d < $today) $errors[] = 'Visit date cannot be in the past.';
    if ($d > $max) $errors[] = 'Visit date must be within two months from today.';
} catch (Exception $e) {
    $errors[] = 'Invalid visit date.';
}

if (!empty($errors)) {
    echo "<h3>Errors:</h3><ul>";
    foreach ($errors as $er) echo "<li>" . htmlspecialchars($er) . "</li>";
    echo "</ul>";
    echo '<p><a href="javascript:history.back()">Go back</a></p>';
    exit;
}

$ticket_id = 'NP' . (new DateTime())->format('Ymd') . strtoupper(bin2hex(random_bytes(4)));
$payment_status = 'paid';

$sql = "INSERT INTO bookings (ticket_id, user_id, park, visit_date, time_slot, entry_gate, tickets_count, visitor_names, id_proof, payment_method, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

$visit_date_sql = $visit_date;
mysqli_stmt_bind_param($stmt, 'sisssisssss',
    $ticket_id,
    $user_id,
    $park,
    $visit_date_sql,
    $time_slot,
    $entry_gate,
    $tickets_count,
    $visitor_names,
    $id_proof,
    $payment_method,
    $payment_status
);

$exec = mysqli_stmt_execute($stmt);

if (!$exec) {
    mysqli_stmt_close($stmt);
    echo "<p>Booking failed: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    exit;
}

$booking_id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

$ticket_dir = __DIR__ . '/tickets';
if (!is_dir($ticket_dir)) {
    mkdir($ticket_dir, 0755, true);
}

$ticket_filename = $ticket_dir . '/ticket_' . $ticket_id . '.pdf';

// --- Generate PDF with FPDF ---
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// Header
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'National Park - Ticket',0,1,'C');
$pdf->Ln(4);

$pdf->SetFont('Arial','',11);
$pdf->Cell(40,8,'Ticket ID:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$ticket_id,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(40,8,'Booking ID:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$booking_id,0,1);

$pdf->Ln(4);

$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'Park:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$park,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'Visit Date:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$visit_date_sql,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'Time Slot:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$time_slot,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'Entry Gate:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$entry_gate,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'Tickets:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,(string)$tickets_count,0,1);

$pdf->Ln(4);

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,7,'Visitor(s):',0,1);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,7,$visitor_names);

$pdf->Ln(2);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'ID Proof:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$id_proof,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(50,8,'Payment Method:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$payment_method . ' (' . $payment_status . ')',0,1);

$pdf->Ln(8);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,6,'Please carry a valid ID proof at the time of entry. This ticket is non-transferable.',0,1);

$pdf->Ln(6);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Booked At: ' . (new DateTime())->format('Y-m-d H:i:s'),0,1);

// Optionally add a QR code image here if you generate one (requires extra library).
$pdf->Output('F', $ticket_filename);

// --- Serve response: embed PDF and provide download link ---
$web_path = 'tickets/ticket_' . rawurlencode($ticket_id) . '.pdf';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Your Ticket - National Park</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 24px; background:#f6f8fa; }
    .card { background:#fff; padding:20px; border-radius:8px; max-width:900px; margin:auto; box-shadow:0 6px 18px rgba(0,0,0,0.06);}
    h1 { color:#145a32; }
    .controls { margin-top:12px; }
    .btn { display:inline-block; background:#27ae60;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none; margin-right:8px;}
    iframe { width:100%; height:600px; border:1px solid #ddd; border-radius:6px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Booking Confirmed ✅</h1>
    <p><strong>Ticket ID:</strong> <?php echo htmlspecialchars($ticket_id); ?></p>
    <p><strong>Park:</strong> <?php echo htmlspecialchars($park); ?></p>
    <p><strong>Visit Date:</strong> <?php echo htmlspecialchars($visit_date_sql); ?></p>
    <p><strong>Time Slot:</strong> <?php echo htmlspecialchars($time_slot); ?></p>

    <div class="controls">
      <a class="btn" href="<?php echo $web_path; ?>" download>Download PDF</a>
      <a class="btn" href="../home/home.php">Back to Home</a>
    </div>

    <hr style="margin:18px 0;">

    <iframe src="<?php echo $web_path; ?>"></iframe>
  </div>
</body>
</html>
