<style>
.sidebar {
    width: 220px;
    background: #fff;
    padding: 1rem;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
}
.sidebar a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1rem;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    margin-bottom: 10px;
}
.sidebar a:hover, .sidebar a.active {
    background-color: #e6f0ff;
    color: #1c2c7c;
}
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        height: 100%;
        z-index: 999;
        transition: left 0.3s ease;
    }
    .sidebar.active {
        left: 0;
    }
    .menu-toggle {
        position: fixed;
        left: 10px;
        top: 10px;
        font-size: 1.5rem;
        background: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        z-index: 1000;
        border-radius: 5px;
    }
}
</style>
<div class="sidebar">
    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">🏠 Earning</a>
    <a href="courses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">📚 Course</a>
    <a href="team.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : ''; ?>">👥 Team</a>
    <a href="bank.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bank.php' ? 'active' : ''; ?>">🏦 Add Bank Account</a>
    <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">📝 Edit Profile</a>
    <a href="referrals.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'referrals.php' ? 'active' : ''; ?>">📨 Referral Detail</a>
    <a href="leaderboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'leaderboard.php' ? 'active' : ''; ?>">🏆 Leaderboard</a>
    <a href="payout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'payout.php' ? 'active' : ''; ?>">💰 My Payout</a>
    <a href="invoice.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'invoice.php' ? 'active' : ''; ?>">🧾 Invoice</a>
</div>
