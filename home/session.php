<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (!($_SESSION['user_type'] == 'student' || $_SESSION['user_type'] == 'trainer')) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];

?>