<?php
// File: admin/blogs.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Pagination and search
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSQL = $search ? "WHERE b.title LIKE '%$search%' OR u.first_name LIKE '%$search%'" : '';

$totalResult = $conn->query("SELECT COUNT(*) as total FROM blogs b LEFT JOIN users u ON b.author_id = u.id $searchSQL")->fetch_assoc()['total'];
$totalPages = ceil($totalResult / $limit);

$sql = "SELECT b.*, u.first_name FROM blogs b
        LEFT JOIN users u ON b.author_id = u.id
        $searchSQL ORDER BY b.created_at DESC
        LIMIT $limit OFFSET $offset";
$blogs = $conn->query($sql);
?>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Blogs</h3>
        <a href="edit-blog.php" class="btn btn-primary">+ Add New Blog</a>
    </div>

    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-4">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search by title or author">
        </div>
        <div class="col-auto">
        <button class="btn btn-outline-primary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
            <th>#</th>
            <th>Title</th>
            <th>Author</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($blogs->num_rows): $i = $offset + 1; ?>
            <?php while ($row = $blogs->fetch_assoc()): ?>
                <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['first_name']) ?></td>
                <td><span class="badge bg-<?= $row['status'] === 'published' ? 'success' : 'secondary' ?>"><?= ucfirst($row['status']) ?></span></td>
                <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="edit-blog.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete-blog.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr><td colspan="6" class="text-center">No blogs found.</td></tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav>
        <ul class="pagination">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
        </nav>
    <?php endif; ?>
    </div>
</div>

<?php include '../common/footer.php'; ?>
