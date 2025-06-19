<?php
// File: admin/users.php
require '../session.php';
if ($_SESSION['user_type'] !== 'superadmin') {
    header("Location: dashboard.php");
    exit;
}
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id AND user_type != 'superadmin'");
    header("Location: users.php");
    exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="container mt-5">
    <h2>User Management</h2>
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['user_type']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if ($row['user_type'] !== 'superadmin'): ?>
                        <a href="users.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                        <?php else: ?>
                        <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../common/footer.php'; ?>