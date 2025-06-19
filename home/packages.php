<?php
// File: admin/packages.php
require '../session.php';
if (!in_array($_SESSION['user_type'], ['superadmin', 'admin'])) {
    header("Location: dashboard.php");
    exit;
}
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $conn->query("DELETE FROM packages WHERE id = $deleteId");
    header("Location: packages.php");
    exit;
}

// Handle Add or Edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $level = $_POST['level'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    if ($id) {
        // Update existing
        $stmt = $conn->prepare("UPDATE packages SET title=?, price=?, duration=?, level=?, status=? WHERE id=?");
        $stmt->bind_param("sdsssi", $title, $price, $duration, $level, $status, $id);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO packages (title, price, duration, level, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $title, $price, $duration, $level, $status);
    }
    $stmt->execute();
    header("Location: packages.php");
    exit;
}

// Load existing for edit
$editPackage = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $result = $conn->query("SELECT * FROM packages WHERE id = $editId");
    $editPackage = $result->fetch_assoc();
}

// Search/filter logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = $search ? "WHERE title LIKE '%$search%'" : '';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$totalRes = $conn->query("SELECT COUNT(*) as total FROM packages $whereClause");
$totalRows = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$packages = $conn->query("SELECT * FROM packages $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>

<div class="container mt-5">
    <h2>Course Package Management</h2>

    <!-- Add/Edit Form -->
    <form method="POST" class="border p-4 mb-4 bg-light">
        <input type="hidden" name="id" value="<?php echo $editPackage['id'] ?? ''; ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $editPackage['title'] ?? ''; ?>" required>
            </div>
            <div class="col-md-2">
                <label>Price (NPR)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $editPackage['price'] ?? ''; ?>" required>
            </div>
            <div class="col-md-3">
                <label>Duration</label>
                <input type="text" name="duration" class="form-control" value="<?php echo $editPackage['duration'] ?? ''; ?>" required>
            </div>
            <div class="col-md-2">
                <label>Level</label>
                <select name="level" class="form-control" required>
                    <?php foreach (["beginner", "intermediate", "advanced"] as $level): ?>
                        <option value="<?php echo $level; ?>" <?php if (($editPackage['level'] ?? '') === $level) echo 'selected'; ?>><?php echo ucfirst($level); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="active" <?php if (($editPackage['status'] ?? '') === 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if (($editPackage['status'] ?? '') === 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <button class="btn btn-success">Save Package</button>
        <?php if ($editPackage): ?>
            <a href="packages.php" class="btn btn-secondary">Cancel Edit</a>
        <?php endif; ?>
    </form>

    <!-- Search bar -->
    <form method="GET" class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by title">
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary">Search</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Level</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $packages->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td>Rs <?php echo number_format($row['price']); ?></td>
                    <td><?php echo $row['duration']; ?></td>
                    <td><?php echo ucfirst($row['level']); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <a href="packages.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this package?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.href = 'packages.php?delete=' + id;
  });
</script>

<?php include '../common/footer.php'; ?>
