<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (!($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'superadmin')) {
    header("Location: ../student/dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];

?>