<?php
// File: student/save-kyc.php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];

    $kyc_file = '';
    if (!empty($_FILES['kyc_file']['name'])) {
        $ext = pathinfo($_FILES['kyc_file']['name'], PATHINFO_EXTENSION);
        $kyc_file = 'kyc_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['kyc_file']['tmp_name'], '../uploads/' . $kyc_file);
    }

    $stmt = $conn->prepare("UPDATE user_bank_details SET full_name=?, dob=?, address=?, kyc_file=?, is_verified=0 WHERE user_id=?");
    $stmt->bind_param("ssssi", $full_name, $dob, $address, $kyc_file, $user_id);
    $stmt->execute();

    header('Location: bank.php');
    exit;
}
?>
