<?php
// File: student/mark-watched.php
session_start();
require '../includes/connect.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit;
}

$user_id = $_SESSION['user_id'];
$video_id = (int) $_POST['video_id'];

if ($video_id > 0) {
    $stmt = $conn->prepare("INSERT IGNORE INTO video_progress (user_id, video_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $video_id);
    $stmt->execute();
}
?>