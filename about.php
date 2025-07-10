<?php
session_start();
$pageTitle = "About";
require 'includes/connect.php';
include 'common/header.php';
include 'common/navbar.php';
?>

<section class="py-5">
  <div class="container">
    <h1 class="text-center mb-5">About Us</h1>
    <div class="row align-items-center">
      <div class="col-md-6">
        <p>SkillzenNepal is a dynamic digital learning and career empowerment platform dedicated to equipping individuals with the skills, tools, and confidence needed to thrive in today’s competitive world. We bridge the gap between education and real-world employability by offering high-quality courses, practical training, and mentorship programs tailored to Nepal's growing digital ecosystem.</p>
        <p>At SkillzenNepal, we believe in learning with purpose. Our platform is built to support students, job seekers, freelancers, and entrepreneurs by providing access to industry-relevant courses in areas such as digital marketing, communication skills, freelancing, affiliate marketing, and more. With a focus on skill-building, career development, and earning opportunities, we empower learners to unlock their potential and become self-reliant.</p>
        <p>We don’t just teach—we mentor, guide, and help you earn. Through our structured packages, live training sessions, certification programs, and affiliate systems, SkillzenNepal ensures that every learner gets not only knowledge but a pathway to real income and impact.</p>
        
      </div>
      <div class="col-md-6">
        <img src="assets/img/about.png" alt="About Spodenet" class="img-fluid rounded">
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-md-12">
        <h4>🎯 Our Vision</h4>
          <p>To become Nepal’s leading platform for skill development, digital learning, and income generation—shaping a future where learning leads directly to opportunity.</p>

          <h3 class="fw-bold mb-4"><span class="me-2">🧩</span> What Makes Us Different</h3>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Real-world, income-focused courses</li>
            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Personalized mentorship and career support</li>
            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Certification + affiliate income opportunities</li>
            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Transparent pricing and lifetime access</li>
            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Community support and regular Q&A sessions</li>
          </ul>

      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
