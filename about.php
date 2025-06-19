<?php
session_start();
$pageTitle = "About";
include 'common/header.php';
include 'common/navbar.php';
?>

<section class="py-5">
  <div class="container">
    <h1 class="text-center mb-5">About Us</h1>
    <div class="row align-items-center">
      <div class="col-md-6">
        <p>Spodenet is a technology consulting and software development company dedicated to helping businesses grow and innovate. Our team works with startups, enterprises, and global brands to build high-performance web and mobile applications, modernize legacy systems, and create seamless user experiences.</p>
        <p>We believe in a partnership approach to digital transformation—your success is our mission. With a blend of strategy, design, and engineering, we help organizations unlock new growth opportunities through technology.</p>
      </div>
      <div class="col-md-6">
        <img src="https://via.placeholder.com/500x300?text=About+Image" alt="About Spodenet" class="img-fluid rounded">
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
