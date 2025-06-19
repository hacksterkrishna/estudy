<?php
// File: dashboard.php (for student)

require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';


// Earnings
function getEarnings($conn, $user_id, $days = null) {
    $query = "SELECT SUM(amount) AS total FROM earnings WHERE user_id = ?";
    if ($days) $query .= " AND created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}
$today = getEarnings($conn, $user_id, 1);
$last7 = getEarnings($conn, $user_id, 7);
$last30 = getEarnings($conn, $user_id, 30);
$allTime = getEarnings($conn, $user_id);

// Wallet
$wallet = $conn->query("SELECT balance FROM wallets WHERE user_id = $user_id")->fetch_assoc();
$walletBalance = $wallet['balance'] ?? 0;

// Referrals
$referrals = $conn->query("SELECT COUNT(*) AS total FROM referrals WHERE referrer_id = $user_id")->fetch_assoc();
$referralCount = $referrals['total'] ?? 0;
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
        color: #ff8200;
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
    <?php include '../common/sidebar-student.php'; ?>

    <div class="dashboard-main">
        <h3>Welcome, <?php echo $_SESSION['first_name']; ?>!</h3>
        <p><strong>Package:</strong> SUPREME</p>

        <div class="card-box text-center">
            <h4>Today Earning</h4>
            <div class="amount">₹ <?php echo number_format($today, 2); ?>/-</div>
        </div>

        <div class="card-section">
            <div class="card-item">
                <p>Last 7 Days Earning</p>
                <h5>₹ <?php echo number_format($last7, 2); ?>/-</h5>
            </div>
            <div class="card-item">
                <p>Last 30 Days Earning</p>
                <h5>₹ <?php echo number_format($last30, 2); ?>/-</h5>
            </div>
            <div class="card-item">
                <p>All Time Earning</p>
                <h5>₹ <?php echo number_format($allTime, 2); ?>/-</h5>
            </div>
            <div class="card-item">
                <p>Wallet Balance</p>
                <h5>₹ <?php echo number_format($walletBalance, 2); ?>/-</h5>
            </div>
        </div>

        <div class="card-box">
            <div class="row">
                <div class="col-md-6 text-center">
                    <h5>Your Free Trip Achieved Target</h5>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo min(100, $referralCount * 4); ?>%;"></div>
                    </div>
                    <small>Achieved: <?php echo $referralCount; ?> / Target: 25</small>
                </div>
                <div class="col-md-6 text-center">
                    <h5>No Of Referral</h5>
                    <h3><?php echo $referralCount; ?>/-</h3>
                    <a href="student/referrals.php" class="btn btn-warning">Referral Details</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
