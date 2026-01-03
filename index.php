<?php include('header.php'); ?>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- HERO SECTION -->
<section class="hero-section">
  <div class="swiper hero-swiper w-100 h-100">
    <div class="swiper-wrapper">

      <div class="swiper-slide" style="background: url('images/hero1.jpg') center/cover no-repeat;"  data-aos="fade-in" data-aos-delay="100">
        <div class="overlay"></div>
        <div class="content">
          <h1>Expert Care You Can Trust 🩺</h1>
          <p>Book appointments with certified specialists anytime, anywhere.</p>
          <a href="appointment.php" class="btn btn-hero me-2">Book Appointment</a>
          <a href="about.php" class="btn btn-outline-hero">Learn More</a>
        </div>
      </div>

      <div class="swiper-slide" style="background: url('images/hero2.jpg') center/cover no-repeat;"  data-aos="fade-in" data-aos-delay="100">
        <div class="overlay"></div>
        <div class="content">
          <h1>Compassion Meets Innovation 💙</h1>
          <p>Delivering quality healthcare through modern medical technology.</p>
          <a href="services.php" class="btn btn-hero">Our Services</a>
        </div>
      </div>

      <div class="swiper-slide" style="background: url('images/hero3.jpg') center/cover no-repeat;"  data-aos="fade-in" data-aos-delay="100">
        <div class="overlay"></div>
        <div class="content">
          <h1>Healing Hands, Caring Hearts ❤️</h1>
          <p>Your health is our top priority. Together, we build healthier lives.</p>
          <a href="contacts.php" class="btn btn-hero me-2">Contact Us</a>
          <a href="diseases.php" class="btn btn-outline-hero">Diseases</a>
        </div>
      </div>

    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="about-section py-5">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="fw-bold section-title mb-3">About <span>CARE Group</span></h2>
      <p class="text-muted w-75 mx-auto">
        CARE Group is a trusted healthcare platform connecting patients with certified specialists across Pakistan. 
        From general consultations to advanced treatments, our mission is to make healthcare accessible, affordable, and reliable for everyone.
      </p>
    </div>

    <!-- Content -->
    <div class="row align-items-center gy-4">
      <div class="col-md-6" data-aos="fade-right">
        <div class="about-img-wrapper position-relative">
          <img src="images/home.jpg" class="img-fluid rounded-4 shadow-lg" alt="About CARE Group">
          <div class="about-badge bg-primary text-white shadow">
            <i class="bi bi-heart-pulse-fill fs-3 me-2"></i>
            10+ Years of Excellence
          </div>
        </div>
      </div>

      <div class="col-md-6 text-center" data-aos="fade-left">
        <h4 class="fw-bold small-section mb-3">Why Choose <span >CARE?</span></h4>
        <p class="text-muted mb-4">
          We blend compassion with innovation to provide the best medical support. Our platform ensures convenience, trust, and exceptional care at your fingertips.
        </p>

        <div class="row g-3">
          <div class="col-6">
            <div class="about-feature p-3 rounded-3 shadow-sm bg-white d-flex align-items-center">
              <i class="bi bi-headset text-primary fs-3 me-2"></i>
              <span class="fw-semibold text-muted">24/7 Support</span>
            </div>
          </div>
          <div class="col-6">
            <div class="about-feature p-3 rounded-3 shadow-sm bg-white d-flex align-items-center">
              <i class="bi bi-person-check text-success fs-3 me-2"></i>
              <span class="fw-semibold text-muted">Certified Doctors</span>
            </div>
          </div>
          <div class="col-6">
            <div class="about-feature p-3 rounded-3 shadow-sm bg-white d-flex align-items-center">
              <i class="bi bi-hospital text-danger fs-3 me-2"></i>
              <span class="fw-semibold text-muted">100+ Hospitals</span>
            </div>
          </div>
          <div class="col-6">
            <div class="about-feature p-3 rounded-3 shadow-sm bg-white d-flex align-items-center">
              <i class="bi bi-shield-check text-warning fs-3 me-2"></i>
              <span class="fw-semibold text-muted">Secure Platform</span>
            </div>
          </div>
        </div>

        <a href="about.php" class="btn btn-primary mt-4 px-4 py-2 rounded-pill shadow">
          Learn More <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES SECTION -->
<section class="py-5">
  <div class="container text-center">
    <h2 class="fw-bold mb-3 section-title" data-aos="fade-up">Our <span>Services</span></h2>
    <p class="section-subtitle text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
      We offer comprehensive medical solutions — from specialist consultations to nationwide hospital access.
    </p>

    <div class="row g-4">
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
        <div class="service-card">
          <div class="card-inner">
            <div class="card-front shadow">
              <i class="bi bi-heart-pulse-fill text-danger fs-1 mb-3"></i>
              <h5 class="fw-bold">Specialist Consultations</h5>
              <p class="text-muted">Book appointments with certified doctors anytime, anywhere.</p>
            </div>
            <div class="card-back bg-gradient-1 text-white d-flex flex-column justify-content-center align-items-center shadow">
              <p class="px-3 text-light">Get access to trusted specialists across Pakistan in just a few clicks.</p>
              <a href="doctors.php" class="btn btn-light mt-2 px-4 rounded-pill">
                Learn More <i class="bi bi-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="service-card">
          <div class="card-inner">
            <div class="card-front shadow">
              <i class="bi bi-clipboard2-pulse-fill text-warning fs-1 mb-3"></i>
              <h5 class="fw-bold">Health Check Packages</h5>
              <p class="text-muted">Preventive diagnostics and health screening packages for all ages.</p>
            </div>
            <div class="card-back bg-gradient-1 text-white d-flex flex-column justify-content-center align-items-center shadow">
              <p class="px-3 text-light">Stay ahead of illness with comprehensive health checkups and reports.</p>
              <a href="services.php" class="btn btn-light mt-2 px-4 rounded-pill">
                Explore <i class="bi bi-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="service-card">
          <div class="card-inner">
            <div class="card-front shadow">
              <i class="bi bi-hospital-fill text-success fs-1 mb-3"></i>
              <h5 class="fw-bold">Hospital Network</h5>
              <p class="text-muted">Nationwide network of top hospitals and healthcare centers.</p>
            </div>
            <div class="card-back bg-gradient-1 text-white d-flex flex-column justify-content-center align-items-center shadow">
              <p class="px-3 text-light">Get treated at premium partner hospitals with expert doctors and care.</p>
              <a href="hospitals.php" class="btn btn-light mt-2 px-4 rounded-pill">
                View Network <i class="bi bi-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES SECTION -->
<section class="services-section py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold section-title mb-3" data-aos="fade-up">
      Why Patients <span>Love Us ❤️</span>
    </h2>
    <p class="text-muted mb-5" style="max-width:700px;margin:auto;" data-aos="fade-up" data-aos-delay="100">
      At CARE Group, we blend technology and compassion to make healthcare accessible, reliable, and comfortable for everyone across Pakistan.
    </p>

    <div class="row g-4">
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-box p-4 rounded-4 shadow-sm bg-white hover-glow">
          <i class="bi bi-shield-check fs-1 text-success mb-3"></i>
          <h6 class="fw-bold text-dark">Trusted & Verified Doctors</h6>
        </div>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-box p-4 rounded-4 shadow-sm bg-white hover-glow">
          <i class="bi bi-clock-history fs-1 text-primary mb-3"></i>
          <h6 class="fw-bold text-dark">24/7 Appointment Booking</h6>
        </div>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-box p-4 rounded-4 shadow-sm bg-white hover-glow">
          <i class="bi bi-cash-stack fs-1 text-warning mb-3"></i>
          <h6 class="fw-bold text-dark">Affordable Packages</h6>
        </div>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
        <div class="feature-box p-4 rounded-4 shadow-sm bg-white hover-glow">
          <i class="bi bi-geo-alt fs-1 text-danger mb-3"></i>
          <h6 class="fw-bold text-dark">Nationwide Coverage</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TEAM SECTION -->
<section class="about-team py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="fw-bold section-title">Meet Our <span>Specialists</span></h2>
      <p class="text-muted" data-aos-delay="100">
        Trusted experts dedicated to your health and care.
      </p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php
      include('connect.php');
      $team_sql = "SELECT * FROM doctors ORDER BY RAND() LIMIT 3";
      $team_res = $conn->query($team_sql);

      if ($team_res->num_rows > 0) {
        $delay = 0;
        while ($team = $team_res->fetch_assoc()) {
          echo '
          <div class="col-md-4" data-aos="zoom-in" data-aos-delay="'.$delay.'">
            <a href="doctor-detail.php?id='.$team['id'].'" class="text-decoration-none text-dark w-100 h-100 d-block">
              <div class="team-card text-center p-4 shadow-sm bg-white rounded-4 h-100">
                <img src="'.$team['image'].'" class="team-img mb-3 rounded-circle" alt="'.$team['name'].'">
                <h5 class="fw-bold">'.$team['name'].'</h5>
                <p class="text-primary">'.$team['specialty'].'</p>
                <p class="text-muted small">'.$team['description'].'</p>
              </div>
            </a>
          </div>';
          $delay += 100;
        }
      } else {
        echo "<p class='text-center text-muted'>No doctors found.</p>";
      }
      ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="about-cta  text-white text-center">
  <div class="cta-overlay">
    <div class="container position-relative py-5" data-aos="fade-up">
      <h2 class="fw-bold mb-3 text-warning">Ready to Take the First Step?</h2>
      <p class="lead mb-4 text-light" data-aos-delay="100">
        Your health journey begins here. Book your appointment with CARE Group specialists today.
      </p>
      <a href="appointment.php" class="btn btn-cta rounded-pill px-5 py-3">
        Get Started <i class="bi bi-arrow-right-circle ms-2"></i>
      </a>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  const swiper = new Swiper('.hero-swiper', {
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    grabCursor: true,
    pagination: { el: '.swiper-pagination', clickable: true }
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const cards = document.querySelectorAll(".service-card");

  cards.forEach(card => {
    card.addEventListener("click", function (e) {
      e.stopPropagation(); 
      const isFlipped = this.classList.contains("flipped");

      cards.forEach(c => c.classList.remove("flipped"));

      if (!isFlipped) {
        this.classList.add("flipped");
      }
    });
  });

  document.addEventListener("click", function () {
    cards.forEach(c => c.classList.remove("flipped"));
  });
});
</script>

<?php include('footer.php'); ?>
