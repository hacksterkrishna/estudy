<?php
session_start();
$pageTitle = "Case Studies";
include 'common/header.php';
include 'common/navbar.php';
?>

<section class="py-5">
  <div class="container">
    <h1 class="text-center mb-5">Case Studies</h1>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="p-4 border rounded">
          <h5>Fintech Cloud Transformation</h5>
          <p>We helped a fast-growing fintech company move to a scalable cloud-based system. Our solution improved uptime by 99.9% and enhanced data security while cutting costs by 30%.</p>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="p-4 border rounded">
          <h5>Healthcare Mobile Redesign</h5>
          <p>A health startup partnered with us to revamp their mobile UX. We created an intuitive interface that increased daily engagement by 40% within three months of launch.</p>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="p-4 border rounded">
          <h5>E-Commerce Platform Migration</h5>
          <p>We migrated a large e-commerce site to a modern headless CMS, reducing page load times by 60% and improving SEO scores across all products.</p>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="p-4 border rounded">
          <h5>Public Sector Data Dashboard</h5>
          <p>Designed and developed a real-time dashboard for a provincial government agency, enabling data-driven decisions with greater transparency and accountability.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
