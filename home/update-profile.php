<?php
// File: student/update-profile.php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $occupation = trim($_POST['occupation']);
    $dob = $_POST['dob'];
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $address = trim($_POST['address']);
    $country_id = (int) $_POST['country_id'];
    $state_id = (int) $_POST['state_id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    // Log the update
    $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, detail, ip_address, user_agent) VALUES (?, 'update_profile', ?, ?, ?)");
    $detail = "Updated profile details";
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $log_stmt->bind_param("isss", $user_id, $detail, $ip, $agent);
    $log_stmt->execute();

    // Update the user table
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, occupation=?, dob=?, father_name=?, mother_name=?, address=?, country_id=?, state_id=? WHERE id=?");
    $stmt->bind_param("ssssssssssii", $first_name, $last_name, $email, $phone, $occupation, $dob, $father_name, $mother_name, $address, $country_id, $state_id, $user_id);
    $stmt->execute();

    header("Location: profile.php");
    exit;
}
?>
