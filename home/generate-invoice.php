<?php
require '../includes/connect.php';
require 'session.php';
// 
// require_once '../vendor/dompdf/autoload.inc.php'; // Adjust path if needed
use Dompdf\Dompdf;
require '../vendor/autoload.php';

$payment_id = intval($_GET['id']);
$payment = $conn->query("SELECT p.*, u.first_name, u.last_name, u.email, u.phone, s.name AS state, c.name AS country FROM payments p JOIN users u ON u.id = p.user_id LEFT JOIN states s ON u.state_id = s.id LEFT JOIN countries c ON u.country_id = c.id WHERE p.id = $payment_id AND p.user_id = {$_SESSION['user_id']}")->fetch_assoc();

if (!$payment) {
    die('Invalid invoice');
}

$invoiceHTML = "
<style>
body { font-family: Arial, sans-serif; }
.header { text-align: center; margin-bottom: 20px; }
.header img { height: 50px; }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
.right { text-align: right; }
</style>
<div class='header'>
    <h2>SPODENET PRIVATE LIMITED</h2>
    <p>Madhyapur Thimi Nagarpalika -06,<br>Bhaktapur, Bagmati Nepal</p>
    <hr>
    <h3>INVOICE</h3>
</div>
<table width='100%'>
    <tr>
        <td>
            <strong>Billed To:</strong><br>
            {$payment['first_name']} {$payment['last_name']}<br>
            {$payment['email']}<br>
            {$payment['phone']}<br>
            {$payment['state']}, {$payment['country']}
        </td>
        <td class='right'>
            <strong>Invoice #:</strong> {$payment['id']}<br>
            <strong>Date:</strong> " . date('M d, Y', strtotime($payment['paid_at'])) . "<br>
            <strong>Amount:</strong> NPR " . number_format($payment['amount'], 2) . "<br>
            <strong>Transaction ID:</strong> {$payment['transaction_id']}
        </td>
    </tr>
</table>
<table class='table'>
    <thead>
        <tr>
            <th>Description</th>
            <th>Unit Price</th>
            <th>VAT</th>
            <th>Total (NPR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{$payment['package_id']} Subscription</td>
            <td>" . ($payment['amount'] - ($payment['amount'] * 0.13)) . "</td>
            <td>" . ($payment['amount'] * 0.13) . " (13%)</td>
            <td><strong>NPR {$payment['amount']}</strong></td>
        </tr>
    </tbody>
</table>
<p style='text-align:center; margin-top:30px;'>www.spodenet.com</p>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($invoiceHTML);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice-{$payment['id']}.pdf", ["Attachment" => 1]);
?>
