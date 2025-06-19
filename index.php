<?php
session_start();
$pageTitle = "Home";
include 'common/header_website.php';
include 'common/navbar.php';
?>

<header class="bg-light py-5">
  <div class="container text-center">
    <h1 class="display-4 fw-bold">We Help Businesses Build and Scale Technology</h1>
    <p class="lead mt-3">SkillzenNepal is your reliable technology partner in innovation, software development, and IT consulting.</p>
    <a href="contact.php" class="btn btn-primary btn-lg mt-4">Get In Touch</a>
  </div>
</header>

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

<section id="services" class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Our Services</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Software Development</h5>
            <p class="card-text">Custom web and mobile software solutions tailored to your business goals.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">IT Consulting</h5>
            <p class="card-text">Strategic technology advice and systems integration to streamline operations.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">UI/UX Design</h5>
            <p class="card-text">Beautiful and intuitive user experiences to engage your users and boost productivity.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section id="case-studies" class="py-5">
  <div class="container text-center">
    <h2 class="mb-4">Case Studies</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="p-4 border rounded mb-4">
          <h5>Case Study One</h5>
          <p>Helping a fintech company scale their operations through secure cloud solutions and optimized software architecture.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 border rounded mb-4">
          <h5>Case Study Two</h5>
          <p>Redesigning the mobile user experience for a health startup, increasing engagement by 40% within the first quarter.</p>
        </div>
      </div>
    </div>
  </div>
</section>



<section id="blog" class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Latest Blog Posts</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">The Future of AI in Software</h5>
            <p class="card-text">Exploring how artificial intelligence is reshaping the tech industry and what businesses can do to keep up.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Top 5 UI Trends</h5>
            <p class="card-text">Stay updated with the latest in user interface design trends for 2024 and beyond.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Scaling with Microservices</h5>
            <p class="card-text">A beginner’s guide to transitioning your monolith into scalable microservices.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="contact" class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h2>Contact Us</h2>
        <p>If you have any questions or need help with your project, feel free to reach out.</p>
        <a href="contact.php" class="btn btn-outline-primary">Go to Contact Page</a>
      </div>
      <div class="col-md-6">
        <img src="assets/img/contact.png" alt="Contact" class="img-fluid rounded">
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>
