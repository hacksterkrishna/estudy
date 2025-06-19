<?php
// File: student/update-picture.php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_img'])) {
    $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $target = '../uploads/' . $filename;

    if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $target)) {
        $stmt = $conn->prepare("UPDATE users SET profile_img = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $user_id);
        $stmt->execute();
    }

    header("Location: profile.php");
    exit;
}
?>
