<?php
session_start();
$pageTitle = "Blog";
include 'common/header.php';
include 'common/navbar.php';
?>

<section class="py-5">
  <div class="container">
    <h1 class="text-center mb-5">Latest Blog Posts</h1>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">The Future of AI in Software</h5>
            <p class="card-text">Exploring how artificial intelligence is reshaping the tech industry and what businesses can do to keep up.</p>
            <a href="#" class="btn btn-outline-primary">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Top 5 UI/UX Design Trends</h5>
            <p class="card-text">Stay updated with the latest in user interface and user experience design trends in 2024 and beyond.</p>
            <a href="#" class="btn btn-outline-primary">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Why Businesses Need Cloud Solutions</h5>
            <p class="card-text">Learn how cloud computing improves flexibility, security, and scalability in modern business environments.</p>
            <a href="#" class="btn btn-outline-primary">Read More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
