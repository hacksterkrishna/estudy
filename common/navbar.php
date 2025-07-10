<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background: linear-gradient(to right, #ffffff, #f0f0f0);">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="/index.php">
      <img src="/<?= $site['logo'] ?>" alt="<?= $site['site_name'] ?> Logo" class="img-fluid" style="max-height: 55px; width: auto;">
   
    </a>

    <!-- Mobile Toggle -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link px-3 fw-semibold text-dark position-relative" href="/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-3 fw-semibold text-dark" href="/packages.php">Package</a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-3 fw-semibold text-dark" href="/about.php">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-3 fw-semibold text-dark" href="/contact.php">Contact Us</a>
        </li>

        <?php if (isset($_SESSION['user_id'])) { ?>
          <?php if ($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'superadmin') { ?>
            <li class="nav-item">
              <a class="nav-link px-3 text-dark fw-semibold" href="/admin/dashboard.php">Admin Panel</a>
            </li>
          <?php } elseif (($_SESSION['user_type'] == 'student' || $_SESSION['user_type'] == 'trainer') && $_SESSION['user_valid'] == 'valid') { ?>
            <li class="nav-item">
              <a class="nav-link px-3 text-dark fw-semibold" href="/home/dashboard.php">Affiliate Panel</a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a class="btn btn-outline-danger ms-2" href="/logout.php">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="btn btn-primary ms-2" href="/login.php">Login</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
