<?php
// File: admin/users.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Handle delete action
if (isset($_GET['delete']) && $_SESSION['user_type'] === 'superadmin') {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header("Location: users.php?deleted=1");
    exit;
}

// Filters and pagination
$filter = isset($_GET['role']) ? $_GET['role'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where = [];
if ($filter) $where[] = "user_type = '" . $conn->real_escape_string($filter) . "'";
if ($search) {
    $search_safe = $conn->real_escape_string($search);
    $where[] = "(first_name LIKE '%$search_safe%' OR last_name LIKE '%$search_safe%' OR email LIKE '%$search_safe%')";
}
$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM users $whereClause");
$totalRows = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT * FROM users $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$users = $conn->query($sql);
?>

<style>
.user-container {
    display: flex;
    min-height: 100vh;
    background-color: #f2f9ff;
}
.user-main {
    flex: 1;
    padding: 2rem;
    overflow-x: auto;
}
.table th, .table td {
    vertical-align: middle;
}
.pagination {
    margin-top: 1rem;
}
</style>


<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="user-main">
        <h3>Manage Users</h3>

        <form class="mb-3" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="role" class="form-label">Filter by Role:</label>
                    <select name="role" id="role" class="form-select" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="superadmin" <?= $filter == 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                        <option value="admin" <?= $filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="trainer" <?= $filter == 'trainer' ? 'selected' : '' ?>>Trainer</option>
                        <option value="student" <?= $filter == 'student' ? 'selected' : '' ?>>Student</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Name or Email:</label>
                    <input type="text" name="search" id="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Enter name or email">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </div>
        </form>

        <?php if (isset($_GET['deleted'])) echo "<div class='alert alert-success'>User deleted successfully.</div>"; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = $offset + 1; while ($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td><?= ucfirst($row['user_type']) ?></td>
                        <td><?= isset($row['status']) ? ucfirst($row['status']) : 'Active' ?></td>
                        <td>
                            <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <?php if ($_SESSION['user_type'] == 'superadmin'): ?>
                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $p ?><?= $filter ? '&role=' . urlencode($filter) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
