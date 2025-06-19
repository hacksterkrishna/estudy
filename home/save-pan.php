<?php
// File: student/save-pan.php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pan_image = '';
    if (!empty($_FILES['pan_image']['name'])) {
        $ext = pathinfo($_FILES['pan_image']['name'], PATHINFO_EXTENSION);
        $pan_image = 'pan_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['pan_image']['tmp_name'], '../uploads/' . $pan_image);
    }

    $stmt = $conn->prepare("UPDATE user_bank_details SET pan_image=?, is_verified=0 WHERE user_id=?");
    $stmt->bind_param("si", $pan_image, $user_id);
    $stmt->execute();

    header('Location: bank.php');
    exit;
}
?>