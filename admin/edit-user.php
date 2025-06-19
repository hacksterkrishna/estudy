<?php
// File: admin/edit-user.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, user_type=? WHERE id=?");
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $user_type, $id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>User updated successfully.</div>";
        $user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Update failed.</div>";
    }
}
?>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
        <h3>Edit User</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">User Type</label>
                <select name="user_type" class="form-select">
                    <option value="student" <?= $user['user_type'] == 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="trainer" <?= $user['user_type'] == 'trainer' ? 'selected' : '' ?>>Trainer</option>
                    <option value="admin" <?= $user['user_type'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="superadmin" <?= $user['user_type'] == 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="users.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php include '../common/footer.php'; ?>
