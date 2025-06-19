<?php
// File: student/save-bank.php
require 'session.php';
require '../includes/connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_name = $_POST['account_name'];
    $account_no = $_POST['account_no'];
    $ifsc_code = $_POST['ifsc_code'];
    $bank_name = $_POST['bank_name'];
    $branch_name = $_POST['branch_name'];
    $account_type = $_POST['account_type'];
    $relation_with_account = $_POST['relation_with_account'];

    $document = '';
    if (!empty($_FILES['document']['name'])) {
        $ext = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
        $document = 'bank_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['document']['tmp_name'], '../uploads/' . $document);
    }

    $stmt = $conn->prepare("INSERT INTO user_bank_details (user_id, account_name, account_no, ifsc_code, bank_name, branch_name, account_type, relation_with_account, document, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("issssssss", $user_id, $account_name, $account_no, $ifsc_code, $bank_name, $branch_name, $account_type, $relation_with_account, $document);
    $stmt->execute();

    header('Location: bank.php');
    exit;
}
?>