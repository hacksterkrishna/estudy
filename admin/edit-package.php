<?php
// File: admin/edit-package.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Handle edit or add mode
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$editing = $id > 0;

// Fetch existing data if editing
if ($editing) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $package = $stmt->get_result()->fetch_assoc();
    if (!$package) die("Package not found");
} else {
    $package = [
        'title' => '', 'price' => '', 'duration' => '', 'level' => '', 'status' => 'active', 'description' => '', 'image' => ''
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $duration = trim($_POST['duration']);
    $level = $_POST['level'];
    $status = $_POST['status'];
    $description = trim($_POST['description']);

    $image = $package['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imgName = basename($_FILES['image']['name']);
        $targetPath = '../assets/img/' . $imgName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $image = $imgName;
    }

    if ($editing) {
        $stmt = $conn->prepare("UPDATE packages SET title=?, price=?, duration=?, level=?, status=?, description=?, image=? WHERE id=?");
        $stmt->bind_param("sdsssssi", $title, $price, $duration, $level, $status, $description, $image, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO packages (title, price, duration, level, status, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsssss", $title, $price, $duration, $level, $status, $description, $image);
    }
    $stmt->execute();
    header("Location: packages.php?success=1");
    exit;
}
?>
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
    <h3><?= $editing ? 'Edit' : 'Add' ?> Package</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($package['title']) ?>" class="form-control" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Price (NPR)</label>
            <input type="number" step="0.01" name="price" value="<?= $package['price'] ?>" class="form-control" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Duration</label>
            <input type="text" name="duration" value="<?= $package['duration'] ?>" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-select" required>
            <option value="beginner" <?= $package['level'] == 'beginner' ? 'selected' : '' ?>>Beginner</option>
            <option value="intermediate" <?= $package['level'] == 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
            <option value="advanced" <?= $package['level'] == 'advanced' ? 'selected' : '' ?>>Advanced</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
            <option value="active" <?= $package['status'] == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $package['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control">
            <?php if ($package['image']): ?>
            <img src="../assets/img/<?= $package['image'] ?>" class="img-fluid mt-2" style="max-height: 100px;">
            <?php endif; ?>
        </div>
        </div>
        <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($package['description']) ?></textarea>
        </div>
        <button class="btn btn-success"><?= $editing ? 'Update' : 'Add' ?> Package</button>
        <a href="packages.php" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
</div>


<?php include '../common/footer.php'; ?>
