<?php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$user_id = $_SESSION['user_id'];
$where = "WHERE user_id = $user_id";

if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where .= " AND DATE(created_at) BETWEEN '$from' AND '$to'";
}
if (!empty($_GET['status'])) {
    $status = $_GET['status'];
    $where .= " AND status = '$status'";
}

$payouts = $conn->query("SELECT * FROM payouts $where ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<style>
.card-box { background: linear-gradient(to right, #0288d1, #26c6da); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
.payout-table { background: #fff; border-radius: 8px; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.filter-form { margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
.filter-form input, .filter-form select { padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px; }
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="main-content">
        <div class="card-box">
            <h2>Payout Details</h2>
        </div>

        <form class="filter-form" method="get">
            <label>From <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>"></label>
            <label>To <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>"></label>
            <label>Status
                <select name="status">
                    <option value="">All</option>
                    <option value="Success" <?= ($_GET['status'] ?? '') == 'Success' ? 'selected' : '' ?>>Success</option>
                    <option value="Pending" <?= ($_GET['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Failed" <?= ($_GET['status'] ?? '') == 'Failed' ? 'selected' : '' ?>>Failed</option>
                </select>
            </label>
            <button type="submit">Filter</button>
        </form>

        <div class="payout-table">
            <table id="payoutTable" class="display">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Transaction Id</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = $payouts->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($_SESSION['user_name']) ?></td>
                            <td><?= htmlspecialchars($_SESSION['user_email']) ?></td>
                            <td>₹<?= number_format($row['amount']) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                            <td><span style="color:<?= $row['status'] == 'Success' ? 'green' : ($row['status'] == 'Failed' ? 'red' : 'orange') ?>;font-weight:600;"><?= htmlspecialchars($row['status']) ?></span></td>
                            <td><?= date('Y-m-d H:i:s', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#payoutTable').DataTable();
});
</script>

<?php include '../common/footer.php'; ?>
