<?php
session_start();
$pageTitle = "Packages";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';

$sql = "SELECT * FROM packages WHERE status = 'active' ORDER BY price DESC";
$result = $conn->query($sql);
?>

<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5">Our Packages</h2>
    <div class="row g-4">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <img src="assets/img/<?php echo $row['image']; ?>" class="card-img-top p-3" alt="<?php echo $row['title']; ?>">
            <div class="card-body">
              <h4 class="card-title text-center"><?php echo $row['title']; ?></h4>
              <h5 class="text-primary text-center mb-3">रु. <?php echo number_format($row['price']); ?></h5>
              <ul class="list-unstyled text-start small">
                <li><i class="bi bi-check2 text-success"></i> You get Access to X courses</li>
                <li><i class="bi bi-check2 text-success"></i> Course completion certificate</li>
                <li><i class="bi bi-check2 text-success"></i> Free Access of Training system</li>
                <li><i class="bi bi-check2 text-success"></i> Opportunity To Earn Y Range</li>
                <li><i class="bi bi-check2 text-success"></i> Dedicated Support System 24X7</li>
                <li><i class="bi bi-check2 text-success"></i> Special Training's Of certified coach</li>
              </ul>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
              <a href="package-details.php?package_id=<?php echo $row['id']; ?>" class="btn btn-outline-primary">Package Details</a>
              <a href="register.php?package_id=<?php echo $row['id']; ?>" class="btn btn-primary">Buy Package</a>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<?php
include 'common/footer.php';
?>
