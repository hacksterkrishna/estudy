<?php
// File: admin/packages.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Handle delete action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM packages WHERE id = $delete_id");
    header("Location: packages.php?deleted=1");
    exit;
}

// Search/filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$level = isset($_GET['level']) ? $_GET['level'] : '';

$where = [];
if ($search) $where[] = "title LIKE '%" . $conn->real_escape_string($search) . "%'";
if ($level) $where[] = "level = '" . $conn->real_escape_string($level) . "'";
$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT * FROM packages $whereClause ORDER BY created_at DESC";
$packages = $conn->query($sql);
?>

<style>
.package-container { display: flex; min-height: 100vh; background: #f8f9fa; }
.package-main { flex: 1; padding: 2rem; overflow-x: auto; }
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>
    
  <div class="package-main">
    <h3>Manage Course Packages</h3>

    <?php if (isset($_GET['deleted'])) echo "<div class='alert alert-success'>Package deleted successfully.</div>"; ?>

    <form class="row g-3 mb-4" method="GET">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by title..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-4">
        <select name="level" class="form-select">
          <option value="">All Levels</option>
          <option value="beginner" <?= $level == 'beginner' ? 'selected' : '' ?>>Beginner</option>
          <option value="intermediate" <?= $level == 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
          <option value="advanced" <?= $level == 'advanced' ? 'selected' : '' ?>>Advanced</option>
        </select>
      </div>
      <div class="col-md-4">
        <button class="btn btn-primary w-100">Filter</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Level</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = $packages->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= $row['title'] ?></td>
            <td>₹ <?= number_format($row['price'], 2) ?></td>
            <td><?= $row['duration'] ?></td>
            <td><?= ucfirst($row['level']) ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td>
              <a href="edit-package.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../common/footer.php'; ?>
