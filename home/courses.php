<?php
// File: student/courses.php

require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$filter = $_GET['skill'] ?? '';

$sqlCount = "SELECT COUNT(DISTINCT p.id) as total FROM packages p LEFT JOIN package_features f ON p.id = f.package_id WHERE p.status='active'";
sqlFilter($sqlCount, $filter);
$total = $conn->query($sqlCount)->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

$sql = "SELECT DISTINCT p.* FROM packages p LEFT JOIN package_features f ON p.id = f.package_id WHERE p.status='active'";
sqlFilter($sql, $filter);
$sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
if ($filter) {
    $stmt->bind_param("sii", $filter, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$packages = $stmt->get_result();

function sqlFilter(&$sql, $filter) {
    if ($filter) $sql .= " AND f.feature_text = ?";
}
?>

<style>
.course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    background: #f7faff;
}
.course-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transition: 0.3s;
    text-decoration: none;
    color: inherit;
}
.course-card:hover {
    transform: translateY(-5px);
}
.course-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.course-card-body {
    padding: 1rem;
}
.course-card h5 {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
    font-weight: bold;
    color: #1c2c7c;
}
.tag {
    display: inline-block;
    background: #dbeafe;
    color: #2563eb;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 12px;
    margin: 3px 4px 0 0;
    text-decoration: none;
}
.tag:hover {
    background: #bfdbfe;
    color: #1d4ed8;
}
.pagination {
    display: flex;
    justify-content: center;
    padding: 1rem;
}
.pagination a {
    margin: 0 5px;
    padding: 8px 12px;
    background: #fff;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #007bff;
    border-radius: 5px;
}
.pagination a.active {
    background: #007bff;
    color: #fff;
}
</style>

<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>

    <div class="main-content">
        <div class="course-grid">
        <?php while ($row = $packages->fetch_assoc()): ?>
            <a href="course-details.php?slug=<?php echo urlencode($row['slug']); ?>" class="course-card">
                <img src="../assets/img/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                <div class="course-card-body">
                    <h5><?php echo $row['title']; ?></h5>
                    <?php
                    $id = $row['id'];
                    $tags = $conn->query("SELECT feature_text FROM package_features WHERE package_id = $id");
                    while ($t = $tags->fetch_assoc()): ?>
                        <a class="tag" href="?skill=<?php echo urlencode($t['feature_text']); ?>"><?php echo htmlspecialchars($t['feature_text']); ?></a>
                    <?php endwhile; ?>
                </div>
            </a>
        <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?><?php if ($filter) echo '&skill=' . urlencode($filter); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
