<?php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$user_id = $_SESSION['user_id'];
$payments = $conn->query("SELECT * FROM payments WHERE user_id = $user_id ORDER BY paid_at DESC");
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
.card-box { background: linear-gradient(to right, #0288d1, #26c6da); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
.invoice-card { background: #fff; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; }
.invoice-card:hover { background: #e3f2fd; }
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="main-content">
        <div class="card-box">
            <h2>Invoices</h2>
        </div>
        <div>
            <?php while($row = $payments->fetch_assoc()): ?>
                <div class="invoice-card" onclick="generateInvoice(<?= $row['id'] ?>)">
                    <h5>Invoice #<?= $row['id'] ?> - <?= $row['payment_method'] ?> - ₹<?= number_format($row['amount']) ?></h5>
                    <small><?= date('F d, Y', strtotime($row['paid_at'])) ?> | Transaction ID: <?= $row['transaction_id'] ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
function generateInvoice(paymentId) {
    window.location.href = 'generate-invoice.php?id=' + paymentId;
}
</script>

<?php include '../common/footer.php'; ?>