<?php
session_start();
$pageTitle = "Contact";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';
?>

<!-- Beautiful Contact Page -->
<section class="py-5" style="background: linear-gradient(to right, #fdfbfb, #ebedee);">
  <div class="container">
    <div class="text-center mb-5">
      <h1 class="fw-bold">Contact Us</h1>
      <p class="text-muted">Have questions, suggestions or just want to say hello? We'd love to hear from you!</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="row g-4 align-items-stretch">
          <!-- Contact Info -->
          <div class="col-md-5">
            <div class="h-100 p-4 rounded-4 shadow-sm bg-white border">
              <h5 class="mb-4">Reach Us</h5>
              <p><i class="bi bi-envelope-fill me-2 text-primary"></i><strong>Email:</strong> hello@skillzen.com</p>
              <p><i class="bi bi-telephone-fill me-2 text-success"></i><strong>Phone:</strong> +977 98540-00000</p>
              <p><i class="bi bi-geo-alt-fill me-2 text-danger"></i><strong>Address:</strong> Nepal</p>
              <hr>
              <p class="small text-muted">Available Mon–Fri, 10am to 5pm (UTC)</p>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="col-md-7">
            <div class="h-100 p-4 rounded-4 shadow-sm bg-white border">
              <form action="contact-handler.php" method="POST">
                <div class="mb-3">
                  <label for="name" class="form-label">Your Name</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required>
                </div>
                <div class="mb-3">
                  <label for="message" class="form-label">Message</label>
                  <textarea name="message" id="message" rows="5" class="form-control" placeholder="Write your message..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Message</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
