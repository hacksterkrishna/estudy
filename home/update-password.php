<?php
// File: student/update-password.php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        die('Passwords do not match.');
    }

    $check = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $check->bind_result($hash);
    $check->fetch();
    $check->close();

    if (!password_verify($current, $hash)) {
        die('Incorrect current password.');
    }

    $new_hash = password_hash($new, PASSWORD_BCRYPT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $new_hash, $user_id);
    $update->execute();

    header("Location: profile.php");
    exit;
}
?>
