<?php
// File: student/course-details.php

require 'session.php';
require '../includes/connect.php';
include '../common/header.php';

if ($_SESSION['user_type'] !== 'student') {
    header("Location: ../admin/dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$slug = $_GET['slug'] ?? '';
$stmt = $conn->prepare("SELECT * FROM packages WHERE slug = ? AND status = 'active' LIMIT 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    echo "<div style='padding:2rem'>Course not found!</div>";
    include '../common/footer.php';
    exit;
}

$videos = $conn->query("SELECT * FROM course_videos WHERE package_id = {$package['id']} ORDER BY sort_order ASC, id ASC");
$first_video = $videos->fetch_assoc();
$videos->data_seek(0);
$watched = $conn->query("SELECT video_id FROM video_progress WHERE user_id = $user_id");
$watched_ids = [];
while ($row = $watched->fetch_assoc()) {
    $watched_ids[] = $row['video_id'];
}
$watched_count = count($watched_ids);
$total_videos = $videos->num_rows;
$progress = $total_videos ? round(($watched_count / $total_videos) * 100) : 0;
$videos->data_seek(0);
?>

<style>
.page-layout {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    min-height: 100vh;
}
.sidebar {
    flex: 0 0 220px;
}
.video-layout {
    display: flex;
    flex: 1;
    flex-direction: row;
    flex-wrap: nowrap;
}
.video-player {
    flex: 3;
    padding: 2rem;
    background: #f9fafb;
}
.video-sidebar {
    flex: 1;
    background: #fff;
    border-left: 1px solid #eee;
    padding: 1rem;
    overflow-y: auto;
    max-height: 100vh;
}
.video-sidebar h4 {
    color: #1c2c7c;
    margin-bottom: 1rem;
}
.video-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}
.video-item span {
    font-weight: 500;
    color: #333;
    flex: 1;
}
.label {
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 5px;
    margin-left: 5px;
}
.label.new { background: #e0f2fe; color: #0284c7; }
.label.watched { background: #dcfce7; color: #16a34a; }
.progress-bar-container {
    margin: 1rem 0;
    background: #eee;
    border-radius: 20px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 10px;
    background: #f97316;
    width: <?php echo $progress; ?>%;
    transition: width 0.3s;
}
@media (max-width: 768px) {
    .page-layout {
        flex-direction: column;
    }
    .video-layout {
        flex-direction: column;
    }
    .video-sidebar {
        border-left: none;
        border-top: 1px solid #eee;
        max-height: 300px;
    }
}
</style>

<div class="page-layout">
    <?php include '../common/sidebar-student.php'; ?>
    <div class="video-layout">
        <div class="video-player">
            <h2><?php echo $package['title']; ?></h2>
            <video id="courseVideo" width="100%" height="500" controls>
                <source src="<?php echo $first_video['video_url']; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="video-sidebar" id="videoList">
            <h4><?php echo $package['title']; ?></h4>
            <div class="progress-bar-container">
                <div class="progress-bar-fill"></div>
            </div>
            <small>Watched Video: <?php echo $watched_count; ?> / <?php echo $total_videos; ?></small>
            <?php $count = 1; $index = 0; $videoList = []; while ($video = $videos->fetch_assoc()): ?>
                <?php $videoList[] = $video; ?>
                <div class="video-item" data-index="<?php echo $index++; ?>" onclick="loadVideo('<?php echo $video['video_url']; ?>', <?php echo $video['id']; ?>)">
                    <span><?php echo $count++ . '. ' . $video['title']; ?></span>
                    <?php if (in_array($video['id'], $watched_ids)): ?><span class="label watched">Watched</span><?php endif; ?>
                    <?php if ($video['is_new']): ?><span class="label new">New</span><?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
const videos = <?php echo json_encode($videoList); ?>;
let currentIndex = 0;

function loadVideo(url, videoId) {
    const player = document.getElementById('courseVideo');
    player.querySelector('source').src = url;
    player.load();
    player.play();

    fetch('mark-watched.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'video_id=' + videoId
    });

    currentIndex = videos.findIndex(v => v.video_url === url);
}

document.getElementById('courseVideo').addEventListener('ended', () => {
    if (currentIndex + 1 < videos.length) {
        const next = videos[currentIndex + 1];
        loadVideo(next.video_url, next.id);
        const item = document.querySelector(`.video-item[data-index='${currentIndex + 1}']`);
        item?.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>

<?php include '../common/footer.php'; ?>