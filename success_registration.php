<?php
session_start();
$pageTitle = "Registration Success";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';
?>

<style>
.success-box {
    max-width: 500px;
    margin: 80px auto;
    background: #fff;
    border-radius: 8px;
    padding: 40px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    text-align: center;
}
.success-box img {
    width: 100px;
    margin-bottom: 30px;
}
.success-box h2 {
    color: #28a745;
    font-weight: 700;
}
.success-box p {
    color: #333;
    font-size: 16px;
}
.success-box .btn {
    margin-top: 20px;
    padding: 10px 30px;
    font-weight: 600;
}
body {
    background-color: #f1f7fc;
}
</style>

<div class="success-box">
    <img src="assets/img/success.png" alt="Success Icon">
    <h2>Request Successful Send</h2>
    <p>We received your purchase request<br>Please wait for Confirmation</p>
    <a href="index.php" class="btn btn-success">Back To Home</a>
</div>

<?php include 'common/footer.php'; ?>
