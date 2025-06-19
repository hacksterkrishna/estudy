<?php
// File: admin/blog-categories.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Handle add/update category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $editId = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;

  if ($editId > 0) {
    $stmt = $conn->prepare("UPDATE blog_categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $editId);
  } else {
    $stmt = $conn->prepare("INSERT INTO blog_categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
  }
  $stmt->execute();
  header("Location: blog-categories.php?success=1");
  exit;
}

// Handle delete
if (isset($_GET['delete'])) {
  $deleteId = intval($_GET['delete']);
  $conn->query("DELETE FROM blog_categories WHERE id = $deleteId");
  header("Location: blog-categories.php?deleted=1");
  exit;
}

// Get all categories with post count
$categories = $conn->query("SELECT bc.*, COUNT(bcm.blog_id) AS post_count FROM blog_categories bc LEFT JOIN blog_category_map bcm ON bc.id = bcm.category_id GROUP BY bc.id ORDER BY bc.name ASC");
$editingCategory = null;
if (isset($_GET['edit'])) {
  $editId = intval($_GET['edit']);
  $result = $conn->query("SELECT * FROM blog_categories WHERE id = $editId");
  $editingCategory = $result->fetch_assoc();
}
?>
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
    <h3>Manage Blog Categories</h3>

    <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Saved successfully</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="alert alert-warning">Deleted successfully</div><?php endif; ?>

    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="edit_id" value="<?= $editingCategory['id'] ?? '' ?>">
        <div class="col-md-8">
        <label class="form-label">Category Name</label>
        <input type="text" name="name" value="<?= $editingCategory['name'] ?? '' ?>" class="form-control" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-success me-2"><?= $editingCategory ? 'Update' : 'Add' ?> Category</button>
        <?php if ($editingCategory): ?><a href="blog-categories.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Post Count</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $categories->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['post_count'] ?></td>
            <td>
            <a href="blog-categories.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="blog-categories.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include '../common/footer.php'; ?>
