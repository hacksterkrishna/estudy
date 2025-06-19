<?php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

function getTopEarners($conn, $period = 'all') {
    $where = '';
    if ($period == 'weekly') {
        $where = "WHERE DATE(r.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } elseif ($period == 'monthly') {
        $where = "WHERE MONTH(r.created_at) = MONTH(CURDATE()) AND YEAR(r.created_at) = YEAR(CURDATE())";
    }

    return $conn->query("SELECT u.id, u.first_name, u.last_name, u.profile_img, SUM(amount) as total FROM referrals r 
        JOIN users u ON u.id = r.referrer_id 
        $where GROUP BY r.referrer_id ORDER BY total DESC LIMIT 10");
}

$active_tab = $_GET['tab'] ?? 'all';
$leaders = getTopEarners($conn, $active_tab);
$top = [];
$others = [];
$count = 0;
while ($row = $leaders->fetch_assoc()) {
    if ($count < 3) $top[] = $row;
    else $others[] = $row;
    $count++;
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.tabs { display: flex; justify-content: center; gap: 1rem; margin-bottom: 1.5rem; }
.tabs a { padding: 0.5rem 1rem; border: 2px solid #ccc; border-radius: 20px; text-decoration: none; font-weight: bold; color: #000; }
.tabs a.active { background: #007bff; color: #fff; border-color: #007bff; }
.top-three { display: flex; justify-content: center; gap: 2rem; margin-bottom: 2rem; }
.card { background: #1e1e2f; color: white; padding: 1rem; border-radius: 10px; width: 200px; position: relative; text-align: center; }
.card img { width: 60px; height: 60px; border-radius: 50%; border: 2px solid #fff; }
.card .medal { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); font-size: 1.5rem; }
.leader-table { background: #1e1e2f; color: white; border-radius: 10px; padding: 1rem; }
.leader-table table { width: 100%; border-collapse: collapse; }
.leader-table th, .leader-table td { padding: 0.75rem 1rem; text-align: left; }
.leader-table th { border-bottom: 1px solid #555; }
.leader-table td img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; vertical-align: middle; }
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="main-content">
        <div class="card-box">
            <h2>Leaderboard</h2>
        </div>
        <div class="tabs">
            <a href="?tab=weekly" class="<?= $active_tab == 'weekly' ? 'active' : '' ?>">Weekly</a>
            <a href="?tab=monthly" class="<?= $active_tab == 'monthly' ? 'active' : '' ?>">Monthly</a>
            <a href="?tab=all" class="<?= $active_tab == 'all' ? 'active' : '' ?>">All Time</a>
        </div>
        <div class="top-three">
            <?php foreach ($top as $i => $user): ?>
                <div class="card">
                    <div class="medal">
                        <?php if ($i == 0) echo '🥇'; elseif ($i == 1) echo '🥈'; else echo '🥉'; ?>
                    </div>
                    <img src="../uploads/<?= $user['profile_img'] ?>" alt="profile">
                    <h4><?= htmlspecialchars($user['first_name']) ?></h4>
                    <div>₹<?= number_format($user['total']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <canvas id="earningChart" style="max-height:300px;margin-bottom:2rem;"></canvas>

        <div class="leader-table">
            <table id="leaderboardTable">
                <thead>
                    <tr><th>Rank</th><th>Profile</th><th>Earning</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 4; 
                    foreach ($others as $user): ?>
                        <tr>
                            <td><?= $rank++ ?></td>
                            <td><img src="../uploads/<?= $user['profile_img'] ?>"> <?= htmlspecialchars($user['first_name']) ?></td>
                            <td>₹<?= number_format($user['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#leaderboardTable').DataTable();
});

const chartLabels = [
<?php foreach ($top as $u) echo "'" . $u['first_name'] . "',"; ?>
];
const chartData = [
<?php foreach ($top as $u) echo $u['total'] . ","; ?>
];

const ctx = document.getElementById('earningChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Top Earners (₹)',
            data: chartData,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `₹${context.parsed.y.toLocaleString()}`;
                    }
                }
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php include '../common/footer.php'; ?>
