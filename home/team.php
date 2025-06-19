<?php
// File: student/team.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$user_id = $_SESSION['user_id'];
$referrals = $conn->query("SELECT u.*, s.name AS state_name, p.title AS package_title FROM referrals r 
    JOIN users u ON u.id = r.referred_user_id 
    LEFT JOIN states s ON s.id = u.state_id
    LEFT JOIN packages p ON p.id = (SELECT package_id FROM enrollments WHERE user_id = u.id ORDER BY enrolled_on DESC LIMIT 1)
    WHERE r.referrer_id = $user_id ORDER BY u.created_at DESC");
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<style>
.card-box {
    background: linear-gradient(to right, #4e73df, #224abe);
    color: white;
    border-radius: 10px;
    padding: 1rem 2rem;
    margin-bottom: 1.5rem;
}
.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.table th, .table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
.table th {
    background: #f0f0f0;
    color: #333;
}
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="main-content">
        <div class="card-box">
            <h2>TEAM DETAILS</h2>
        </div>
        <table class="table" id="teamTable">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>State</th>
                    <th>Package</th>
                    <th>Referral</th>
                    <th>DOJ</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; while ($row = $referrals->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['state_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['package_title']); ?></td>
                        <td><?php echo $_SESSION['first_name']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#teamTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>

<?php include '../common/footer.php'; ?>