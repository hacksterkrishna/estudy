<?php
// File: admin/edit-blog.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$editing = $id > 0;

// Load blog if editing
if ($editing) {
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $blog = $stmt->get_result()->fetch_assoc();
    if (!$blog) die("Blog not found");
} else {
    $blog = [
        'title' => '', 'slug' => '', 'content' => '', 'image' => '', 'status' => 'draft'
    ];
}

// Save blog
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $content = $_POST['content'];
    $status = $_POST['status'];
    $author_id = $_SESSION['user_id'];

    $image = $blog['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imgName = basename($_FILES['image']['name']);
        $targetPath = '../assets/img/' . $imgName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $image = $imgName;
    }

    if ($editing) {
        $stmt = $conn->prepare("UPDATE blogs SET title=?, slug=?, content=?, image=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $slug, $content, $image, $status, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO blogs (title, slug, content, image, status, author_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $slug, $content, $image, $status, $author_id);
    }
    $stmt->execute();
    header("Location: blogs.php?success=1");
    exit;
}
?>
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
    <h3><?= $editing ? 'Edit Blog' : 'Add New Blog' ?></h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" value="<?= htmlspecialchars($blog['slug']) ?>" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
            <option value="published" <?= $blog['status'] == 'published' ? 'selected' : '' ?>>Published</option>
            <option value="draft" <?= $blog['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
            <?php if ($blog['image']): ?>
            <img src="../assets/img/<?= $blog['image'] ?>" class="img-fluid mt-2" style="max-height: 100px;">
            <?php endif; ?>
        </div>
        </div>
        <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" id="editor" class="form-control" rows="8" required><?= htmlspecialchars($blog['content']) ?></textarea>
        </div>
        <button class="btn btn-success">Save Blog</button>
        <a href="blogs.php" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('editor');
</script>

<?php include '../common/footer.php'; ?>
