<?php
session_start();
$pageTitle = "Contact";
include 'common/header.php';
include 'common/navbar.php';
?>

<section class="py-5">
  <div class="container">
    <h1 class="text-center mb-5">Get in Touch</h1>
    <div class="row">
      <div class="col-md-6">
        <h5>Contact Information</h5>
        <p><strong>Email:</strong> hello@spodenet.com</p>
        <p><strong>Phone:</strong> +1 (555) 123-4567</p>
        <p><strong>Address:</strong> 123 Innovation Drive, Tech City, USA</p>
      </div>
      <div class="col-md-6">
        <form>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
