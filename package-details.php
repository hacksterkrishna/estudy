<?php
session_start();
$pageTitle = "Package Details";
include 'common/header.php';
include 'common/navbar.php';
include 'includes/connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
  echo "<div class='container py-5'><div class='alert alert-danger'>Invalid Package ID.</div></div>";
  include 'common/footer.php';
  exit;
}

$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ? AND status = 'active'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "<div class='container py-5'><div class='alert alert-warning'>Package not found.</div></div>";
  include 'common/footer.php';
  exit;
}

$package = $result->fetch_assoc();
?>

<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <div class="col-md-6">
        <img src="assets/img/packages/<?php echo $package['image']; ?>" alt="<?php echo $package['title']; ?>" class="img-fluid rounded shadow">
      </div>
      <div class="col-md-6">
        <h2><?php echo $package['title']; ?></h2>
        <h4 class="text-primary">र <?php echo number_format($package['price']); ?></h4>
        <p><?php echo nl2br($package['description']); ?></p>
        <ul class="list-unstyled small">
          <li><i class="bi bi-check2 text-success"></i> Access to courses: X</li>
          <li><i class="bi bi-check2 text-success"></i> Includes certificate</li>
          <li><i class="bi bi-check2 text-success"></i> Lifetime access to training platform</li>
          <li><i class="bi bi-check2 text-success"></i> Earnings up to Y range</li>
        </ul>
        <a href="buy-package.php?id=<?php echo $package['id']; ?>" class="btn btn-primary mt-3">Buy This Package</a>
      </div>
    </div>
  </div>
</section>

<?php
include 'common/footer.php';
?>
