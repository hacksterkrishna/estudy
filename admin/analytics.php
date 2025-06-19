<?php
// File: admin/analytics.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Filter handling
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');
$userType = $_GET['user_type'] ?? 'all';

$whereClause = "WHERE created_at BETWEEN '$startDate' AND '$endDate'";
if ($userType !== 'all') {
  $whereClause .= " AND user_type = '$userType'";
}

// Earnings per month
$earningsQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(amount) AS total FROM earnings $whereClause GROUP BY month ORDER BY month ASC";
$earningsResult = $conn->query($earningsQuery);
$earningsLabels = [];
$earningsData = [];
while ($row = $earningsResult->fetch_assoc()) {
  $earningsLabels[] = $row['month'];
  $earningsData[] = $row['total'];
}

// User registrations per month
$usersQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total FROM users $whereClause GROUP BY month ORDER BY month ASC";
$usersResult = $conn->query($usersQuery);
$userLabels = [];
$userData = [];
while ($row = $usersResult->fetch_assoc()) {
  $userLabels[] = $row['month'];
  $userData[] = $row['total'];
}

// User type breakdown for pie chart
$typeBreakdown = $conn->query("SELECT user_type, COUNT(*) as total FROM users $whereClause GROUP BY user_type");
$typeLabels = [];
$typeCounts = [];
while ($row = $typeBreakdown->fetch_assoc()) {
  $typeLabels[] = ucfirst($row['user_type']);
  $typeCounts[] = $row['total'];
}
?>
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
    <h3>Analytics Dashboard</h3>

    <form class="row g-3 mb-4">
        <div class="col-md-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
        </div>
        <div class="col-md-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
        </div>
        <div class="col-md-3">
        <label class="form-label">User Type</label>
        <select name="user_type" class="form-select">
            <option value="all" <?= $userType == 'all' ? 'selected' : '' ?>>All</option>
            <option value="student" <?= $userType == 'student' ? 'selected' : '' ?>>Student</option>
            <option value="trainer" <?= $userType == 'trainer' ? 'selected' : '' ?>>Trainer</option>
            <option value="admin" <?= $userType == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="superadmin" <?= $userType == 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
        </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-6">
        <div class="card p-3">
            <h5>Earnings (Monthly)</h5>
            <canvas id="earningsChart"></canvas>
        </div>
        </div>
        <div class="col-md-6">
        <div class="card p-3">
            <h5>User Registrations (Monthly)</h5>
            <canvas id="usersChart"></canvas>
        </div>
        </div>
        <div class="col-md-6 mt-4">
        <div class="card p-3">
            <h5>User Type Distribution</h5>
            <canvas id="userTypeChart"></canvas>
        </div>
        </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const earningsCtx = document.getElementById('earningsChart').getContext('2d');
new Chart(earningsCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode($earningsLabels) ?>,
    datasets: [{
      label: 'Earnings',
      data: <?= json_encode($earningsData) ?>,
      borderColor: 'green',
      backgroundColor: 'rgba(0,255,0,0.1)',
      fill: true,
      tension: 0.4
    }]
  },
  options: { responsive: true, plugins: { tooltip: { enabled: true } } }
});

const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($userLabels) ?>,
    datasets: [{
      label: 'Users',
      data: <?= json_encode($userData) ?>,
      backgroundColor: 'rgba(0,123,255,0.6)'
    }]
  },
  options: { responsive: true, plugins: { tooltip: { enabled: true } } }
});

const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
new Chart(userTypeCtx, {
  type: 'pie',
  data: {
    labels: <?= json_encode($typeLabels) ?>,
    datasets: [{
      data: <?= json_encode($typeCounts) ?>,
      backgroundColor: ['#007bff','#ffc107','#28a745','#dc3545','#6610f2']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      tooltip: { enabled: true },
      legend: { position: 'bottom' }
    }
  }
});
</script>

<?php include '../common/footer.php'; ?>
