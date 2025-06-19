<?php
// File: student/bank.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

$user_id = $_SESSION['user_id'];
$data = $conn->query("SELECT * FROM user_bank_details WHERE user_id = $user_id LIMIT 1")->fetch_assoc();
$is_verified = $data && $data['is_verified'] == 1;
?>

<style>
.card-box {
    background: linear-gradient(to right, #06b6d4, #3b82f6);
    color: #fff;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}
.tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #ccc;
}
.tabs button {
    padding: 0.5rem 1rem;
    background: none;
    border: none;
    font-weight: bold;
    cursor: pointer;
    border-bottom: 3px solid transparent;
}
.tabs button.active {
    border-color: #3b82f6;
    color: #3b82f6;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
    background: #fff;
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
label {
    font-weight: 500;
    display: block;
    margin: 1rem 0 0.2rem;
}
input, select, textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
}
input[type="submit"] {
    background: #3b82f6;
    color: #fff;
    font-weight: bold;
    margin-top: 1rem;
    border: none;
    cursor: pointer;
    padding: 0.7rem 1.5rem;
}
</style>

<div class="dashboard-container">
    <?php include '../common/sidebar-student.php'; ?>
    
    <div class="main-content">
        <div class="card-box">
            <h2>Your Bank & KYC Details</h2>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('kyc')">KYC Details</button>
            <button class="tab-btn" onclick="switchTab('bank')">Bank Details</button>
            <button class="tab-btn" onclick="switchTab('pan')">Pan Card</button>
        </div>

        <div id="kyc" class="tab-content active">
            <?php if ($is_verified): ?>
                <p style="color:green">Your document is approved and account is active</p>
            <?php else: ?>
                <form method="post" enctype="multipart/form-data" action="save-kyc.php">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                    <label>Date of Birth</label>
                    <input type="date" name="dob" required>
                    <label>Address</label>
                    <textarea name="address" required></textarea>
                    <label>Upload Citizenship Document</label>
                    <input type="file" name="kyc_file" required>
                    <input type="submit" value="Submit KYC">
                </form>
            <?php endif; ?>
        </div>

        <div id="bank" class="tab-content">
            <?php if ($is_verified): ?>
                <p><strong>Name:</strong> <?php echo $data['account_name']; ?></p>
                <p><strong>Account No:</strong> <?php echo $data['account_no']; ?></p>
                <p><strong>IFSC Code:</strong> <?php echo $data['ifsc_code']; ?></p>
                <p><strong>Bank Name:</strong> <?php echo $data['bank_name']; ?></p>
                <p><strong>Branch Name:</strong> <?php echo $data['branch_name']; ?></p>
                <p><strong>Account Type:</strong> <?php echo $data['account_type']; ?></p>
                <p><strong>Relation With Account:</strong> <?php echo $data['relation_with_account']; ?></p>
                <p><strong>Account Verify Document:</strong> <a href="../uploads/<?php echo $data['document']; ?>" target="_blank">View</a></p>
                <p style="color:green">Congratulations! Your document is approved and account is active</p>
            <?php else: ?>
                <form method="post" enctype="multipart/form-data" action="save-bank.php">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_name" required>
                    <label>Account Number</label>
                    <input type="text" name="account_no" required>
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" required>
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" required>
                    <label>Branch Name</label>
                    <input type="text" name="branch_name" required>
                    <label>Account Type</label>
                    <select name="account_type">
                        <option value="Saving">Saving</option>
                        <option value="Current">Current</option>
                    </select>
                    <label>Relation With Account</label>
                    <input type="text" name="relation_with_account" required>
                    <label>Upload Bank Document</label>
                    <input type="file" name="document" required>
                    <input type="submit" value="Submit Bank Details">
                </form>
            <?php endif; ?>
        </div>

        <div id="pan" class="tab-content">
            <?php if ($is_verified): ?>
                <p style="color:green">Your pan details is successfully verified</p>
                <img src="../uploads/<?php echo $data['pan_image']; ?>" width="300">
            <?php else: ?>
                <form method="post" enctype="multipart/form-data" action="save-pan.php">
                    <label>Upload PAN Card</label>
                    <input type="file" name="pan_image" required>
                    <input type="submit" value="Submit PAN Details">
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}
</script>

<?php include '../common/footer.php'; ?>
