<?php
session_start();
$pageTitle = "Pay with QR";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';
?>

<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">Pay via QR Code</h2>
    <div class="row justify-content-center">
      <div class="col-lg-10 p-4 border rounded bg-white shadow-sm">
        <div class="row align-items-center">
          
          <!-- QR Code Section -->
          <div class="col-md-6 text-center mb-4 mb-md-0">
            <h4 class="mb-4">Take screenshot</h4>
            <img src="assets/img/qr.png" alt="QR Code" class="img-fluid" style="max-width: 300px;">
          </div>

          <!-- Payment Form Section -->
          <div class="col-md-6">
            <h5 class="fw-bold">Please Pay ₹2500 /- On given QR</h5>
            <p class="small text-muted">
              Note: Don't pay amount which is lower or higher than package price. Please pay exact amount otherwise your ID will not be activated and we will not accept any refund.
            </p>
            <form action="success_registration.php" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="payment_screenshot" class="form-label">Payment Screenshot</label>
                <input type="file" name="payment_screenshot" class="form-control" accept="image/*" required>
              </div>
              <div class="mb-3 text-center">
                <img src="assets/img/upload_img.jpg" class="img-thumbnail" style="max-height: 180px;" alt="Preview">
              </div>
              <div class="mb-3">
                <label for="transaction_id" class="form-label">Transaction ID</label>
                <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter Transaction ID" required>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">Submit Details</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
