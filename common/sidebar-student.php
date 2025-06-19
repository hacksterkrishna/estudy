<style>
.dashboard-container {
    display: flex;
    flex-wrap: nowrap;
    min-height: 100vh;
    background-color: #f2f9ff;
}
.dashboard-main {
    flex: 1;
    padding: 2rem;
    overflow-x: hidden;
}
.main-content {
    flex: 1;
    padding: 1.5rem;
}
.sidebar {
    width: 220px;
    background: #fff;
    padding: 1rem;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
}
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 24px;
    right: 75px;
    z-index: 1022;
    background: #007bff;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
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
    .sidebar-toggle {
        display: block;
    }
    .sidebar {
        position: fixed;
        top: 0;
        left: -240px;
        width: 220px;
        height: 100vh;
        background: #fff;
        transition: all 0.3s ease-in-out;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    .sidebar.active {
        left: 0;
        margin-top: 85px;
    }
    .main-content {
        padding-top: 3.5rem;
    }
}

@media (max-width: 768px) {
    
    /* .menu-toggle {
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
    } */
}
</style>
<div class="sidebar">
    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">🏠 Earning</a>
    <a href="courses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">📚 Course</a>
    <a href="team.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : ''; ?>">👥 Team</a>
    <a href="bank.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bank.php' ? 'active' : ''; ?>">🏦 Add Bank Acc</a>
    <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">📝 Edit Profile</a>
    <a href="referrals.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'referrals.php' ? 'active' : ''; ?>">📨 Referral Detail</a>
    <a href="leaderboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'leaderboard.php' ? 'active' : ''; ?>">🏆 Leaderboard</a>
    <a href="payout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'payout.php' ? 'active' : ''; ?>">💰 My Payout</a>
    <a href="invoice.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'invoice.php' ? 'active' : ''; ?>">🧾 Invoice</a>
</div>
