<?php
// common/footer.php
?>
<footer class="text-white pt-5" style="background: linear-gradient(120deg, #1a1a2e, #16213e);">
  <div class="container">
    <div class="row gy-5">
      <!-- Logo & Intro -->
      <div class="col-md-3">
        <img src="/<?= $site['logo'] ?>" alt="<?= $site['site_name'] ?> Logo" class="img-fluid mb-3" style="max-width: 150px;">
        <p class="small text-white-50">
          SkillzenNepal provides a platform for learners of all ages to acquire and apply future-proof skills, enabling them to stand out from the crowd and grow.
        </p>
      </div>

      <!-- Quick Links -->
      <div class="col-6 col-md-2">
        <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled small">
          <li><a href="index.php" class="text-white-50 text-decoration-none">Home</a></li>
          <li><a href="login.php" class="text-white-50 text-decoration-none">Login</a></li>
          <li><a href="blog.php" class="text-white-50 text-decoration-none">Blogs</a></li>
          <li><a href="packages.php" class="text-white-50 text-decoration-none">Packages</a></li>
          <li><a href="about.php" class="text-white-50 text-decoration-none">About Us</a></li>
          <li><a href="contact.php" class="text-white-50 text-decoration-none">Contact Us</a></li>
          <li><a href="#" class="text-white-50 text-decoration-none">Disclaimer</a></li>
        </ul>
      </div>

      <!-- Legal & Certificates -->
      <div class="col-6 col-md-3">
        <h6 class="text-uppercase fw-bold mb-3">Legal</h6>
        <ul class="list-unstyled small">
          <li><a href="privacy-policy.php" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
          <li><a href="#" class="text-white-50 text-decoration-none">Refund Policy</a></li>
          <li><a href="#" class="text-white-50 text-decoration-none">Terms & Conditions</a></li>
        </ul>
        <h6 class="text-uppercase fw-bold mt-4 mb-3">Certificates</h6>
        <div class="d-flex flex-wrap gap-1">
          <span class="badge bg-secondary small">ISO 9001</span>
          <span class="badge bg-secondary small">GST</span>
          <span class="badge bg-secondary small">MCA</span>
          <span class="badge bg-secondary small">Startup India</span>
          <span class="badge bg-secondary small">Udyam</span>
        </div>
      </div>

      <!-- Contact & Download -->
      <div class="col-md-4">
        <h6 class="text-uppercase fw-bold mb-3">Get in Touch</h6>
        <p class="small mb-1"><i class="bi bi-envelope me-2 text-primary"></i> support@skillzennepal.com</p>
        <p class="small mb-1"><i class="bi bi-telephone me-2 text-success"></i> +977-9800000000</p>
        <p class="small mb-3"><i class="bi bi-geo-alt me-2 text-warning"></i> Kathmandu, Nepal</p>

        <h6 class="text-uppercase fw-bold mb-2">Download Our App</h6>
        <p class="small text-white-50">Get your hands on the ultimate learning companion</p>
        <a href="#"><img src="/assets/img/google-play-badge.png" alt="Get it on Google Play" class="img-fluid" style="max-width: 150px;"></a>
      </div>
    </div>

    <hr class="my-4 border-secondary">

    <!-- Bottom Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
      <p class="small text-white-50 mb-2 mb-md-0">&copy; <?= date("Y") ?> SkillzenNepal Pvt. Ltd. All rights reserved.</p>
      <div class="d-flex gap-3">
        <a href="#" class="text-white-50"><i class="bi bi-facebook fs-5"></i></a>
        <a href="#" class="text-white-50"><i class="bi bi-youtube fs-5"></i></a>
        <a href="#" class="text-white-50"><i class="bi bi-linkedin fs-5"></i></a>
        <a href="#" class="text-white-50"><i class="bi bi-instagram fs-5"></i></a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
