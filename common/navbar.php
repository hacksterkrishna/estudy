<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/index.php">      
      <img src="/<?= $site['logo'] ?>" alt="<?= $site['site_name'] ?> Logo" class="img-fluid" style="max-height: 60px; width: auto;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/index.php">HOME</a></li>
        <li class="nav-item"><a class="nav-link" href="/packages.php">PACKAGE</a></li>
        <li class="nav-item"><a class="nav-link" href="/about.php">ABOUT US</a></li>
        <li class="nav-item"><a class="nav-link" href="/contact.php">CONTACT US</a></li>
        <!-- <li class="nav-item"><a class="nav-link" href="/gallery.php">EVENT GALLERY</a></li> -->
        <?php if (isset($_SESSION['user_id'])) { ?>

          <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'superadmin') { ?>
            <li class="nav-item"><a class="nav-link" href="/admin/dashboard.php">ADMIN PANEL</a></li>  
          <?php } elseif ($_SESSION['user_type'] == 'student' || $_SESSION['user_type'] == 'trainer') { ?>
            <li class="nav-item"><a class="nav-link" href="/home/dashboard.php">AFFILIATE PANEL</a></li>
          <?php } ?>  

          <li class="nav-item"><a class="nav-link" href="/logout.php">LOGOUT</a></li>
        <?php } else { ?>
          <li class="nav-item"><a class="nav-link" href="/login.php">LOGIN</a></li> 
        <?php } ?>

      </ul>
    </div>
  </div>
</nav>