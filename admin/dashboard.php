<?php
// File: admin/dashboard.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Earnings summary
function getAdminEarnings($conn, $days = null) {
    $query = "SELECT SUM(amount) AS total FROM earnings";
    if ($days) $query .= " WHERE created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)";
    $result = $conn->query($query)->fetch_assoc();
    return $result['total'] ?? 0;
}
$today = getAdminEarnings($conn, 1);
$last7 = getAdminEarnings($conn, 7);
$last30 = getAdminEarnings($conn, 30);
$allTime = getAdminEarnings($conn);

$userCount = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$packageCount = $conn->query("SELECT COUNT(*) as total FROM packages")->fetch_assoc()['total'];
$referralCount = $conn->query("SELECT COUNT(*) as total FROM referrals")->fetch_assoc()['total'];
?>

<style>
.card-box {
    background: #fff;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.card-box h4 {
    font-weight: bold;
    color: #333;
}
.amount {
    color: #28a745;
    font-size: 2rem;
    font-weight: bold;
}
.card-section {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}
.card-item {
    flex: 1 1 250px;
    background: #fff;
    border-left: 4px solid #007bff;
    padding: 1rem;
    border-radius: 5px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.05);
}
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
        <h3>Admin Dashboard</h3>
        <p>Welcome, <?php echo $_SESSION['first_name']; ?> (<?php echo $_SESSION['user_type']; ?>)</p>

        <div class="card-box text-center">
            <h4>Today Total Earnings</h4>
            <div class="amount"> <?php echo number_format($today, 2); ?>/-</div>
        </div>

        <div class="card-section">
            <div class="card-item">
                <p>Last 7 Days Earning</p>
                <h5> <?php echo number_format($last7, 2); ?>/-</h5>
            </div>
            <div class="card-item">
                <p>Last 30 Days Earning</p>
                <h5> <?php echo number_format($last30, 2); ?>/-</h5>
            </div>
            <div class="card-item">
                <p>All Time Earning</p>
                <h5> <?php echo number_format($allTime, 2); ?>/-</h5>
            </div>
            <div class="card-item">
                <p>Total Users</p>
                <h5><?php echo $userCount; ?></h5>
            </div>
            <div class="card-item">
                <p>Total Packages</p>
                <h5><?php echo $packageCount; ?></h5>
            </div>
            <div class="card-item">
                <p>Total Referrals</p>
                <h5><?php echo $referralCount; ?></h5>
            </div>
        </div>
    </div>
</div>


<?php include '../common/footer.php'; ?>
