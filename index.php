<?php
session_start();
$pageTitle = "Home";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';

$sql = "SELECT * FROM packages WHERE status = 'active' ORDER BY price DESC";
$result = $conn->query($sql);
?>

<!-- Hero Video Section -->
<section class="py-5" style="background: linear-gradient(135deg, #f5f7fa, #c3cfe2);">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">See How SkillzenNepal is Changing Lives</h2>
      <p class="lead text-muted">Watch real success stories and explore our powerful learning platform</p>
    </div>
    <div class="row">
      <!-- Success Story Video -->
      <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100">
          <div class="card-body p-0">
            <div class="ratio ratio-16x9 rounded">
              <iframe src="https://www.youtube.com/embed/VIDEO_ID_1" title="Success Story" allowfullscreen></iframe>
            </div>
          </div>
          <div class="card-footer bg-white text-center">
            <h5 class="mb-1">Success Story</h5>
            <p class="small text-muted mb-0">Hear from those who transformed their lives with us.</p>
          </div>
        </div>
      </div>

      <!-- Tutorial Video -->
      <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100">
          <div class="card-body p-0">
            <div class="ratio ratio-16x9 rounded">
              <iframe src="https://www.youtube.com/embed/VIDEO_ID_2" title="Tutorial" allowfullscreen></iframe>
            </div>
          </div>
          <div class="card-footer bg-white text-center">
            <h5 class="mb-1">Platform Tutorial</h5>
            <p class="small text-muted mb-0">Learn how to use our platform effectively.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- About Section -->
<section id="about" class="py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h2>About SkillzenNepal</h2>
        <p>We are a technology consulting company that delivers custom software, helps businesses innovate, and brings ideas to life using cutting-edge technologies. Our team of experts ensures top-tier services across the globe.</p>
      </div>
      <div class="col-md-6">
        <img src="assets/img/about.png" alt="About Us" class="img-fluid rounded">
      </div>
    </div>
  </div>
</section>

<!-- Package Section -->
<section id="packages" class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-5">Choose Your Plan</h2>
    <div class="row">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="col-md-4 mb-4">
          <div class="p-4 h-100 text-white" style="background: linear-gradient(135deg, #7b2ff7, #f107a3); border-radius: 20px;">
            <h4><?php echo $row['title']; ?></h4>
            <h2 class="mb-3">रु. <?php echo number_format($row['price']); ?></h2>
            <ul class="list-unstyled text-start">
              <?php
              $pkg_id = $row['id'];
              $features_sql = "SELECT feature_text FROM package_features WHERE package_id = $pkg_id ORDER BY order_by ASC";
              $features_result = $conn->query($features_sql);
              while ($frow = $features_result->fetch_assoc()) {
                echo '<li>✓ ' . htmlspecialchars($frow['feature_text']) . '</li>';
              }
              ?>
            </ul>
            <a href="package-details.php?package_id=<?php echo $row['id']; ?>" class="btn btn-light text-dark mt-3">Package Details</a>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- Blog Section -->
<section id="blog" class="py-5">
  <div class="container text-center">
    <h2 class="mb-4">Latest Blog Posts</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="assets/img/blog1.jpg" class="card-img-top" alt="Blog 1">
          <div class="card-body">
            <h5 class="card-title">The Future of AI in Software</h5>
            <p class="card-text">Exploring how AI is reshaping the tech industry and what businesses can do to keep up.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="assets/img/blog2.jpg" class="card-img-top" alt="Blog 2">
          <div class="card-body">
            <h5 class="card-title">Top 5 UI Trends</h5>
            <p class="card-text">Stay updated with the latest user interface design trends for 2024 and beyond.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="assets/img/blog3.jpg" class="card-img-top" alt="Blog 3">
          <div class="card-body">
            <h5 class="card-title">Scaling with Microservices</h5>
            <p class="card-text">A beginner’s guide to transitioning your monolith into scalable microservices.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5" style="background: linear-gradient(135deg, #e0f7fa, #fff);">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Get in Touch</h2>
      <p class="text-muted">Have questions or need assistance? We're here to help!</p>
    </div>
    <div class="row align-items-center">
      <!-- Contact Form -->
      <div class="col-md-6 mb-4">
        <div class="p-4 shadow rounded bg-white">
          <form action="contact-handler.php" method="POST">
            <div class="mb-3">
              <label for="name" class="form-label">Your Name</label>
              <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Your Message</label>
              <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Message</button>
          </form>
        </div>
      </div>

      <!-- Contact Image -->
      <div class="col-md-6 text-center">
        <img src="assets/img/contact.png" alt="Contact Us" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
        <p class="mt-3 text-muted small">Or email us directly at <a href="mailto:info@skillzennepal.com">info@skillzennepal.com</a></p>
      </div>
    </div>
  </div>
</section>


<?php include 'common/footer.php'; ?>
