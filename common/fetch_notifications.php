<?php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];
$countResult = $conn->query("SELECT COUNT(*) as unread FROM notifications WHERE user_id=$user_id AND is_read=0");
$count = $countResult->fetch_assoc()['unread'];

$data = [];
$notifications = $conn->query("SELECT message, created_at FROM notifications WHERE user_id=$user_id ORDER BY created_at DESC LIMIT 5");
while ($row = $notifications->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['unread' => $count, 'notifications' => $data]);
