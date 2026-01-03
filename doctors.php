<?php include('header.php'); ?>
<?php include('connect.php'); ?>

<!-- HERO SECTION -->
<section class="doc-hero">
  <div class="overlay"></div>
  <div class="container text-center content" data-aos="fade-up">
    <h1 class="fw-bold mb-3">Meet Our Expert Doctors 🩺</h1>
    <p class="lead mb-0">Find and book top medical specialists instantly.</p>
  </div>
</section>

<!-- FILTER SECTION -->
<section class="py-4 border-bottom">
  <div class="container text-center">
    <div class="row justify-content-center g-2">
      <div class="col-md-3 col-sm-6" data-aos="zoom-in">
        <select id="specialtyFilter" class="form-select">
          <option value="">All Specialties</option>
          <?php
            $specQuery = "SELECT DISTINCT specialty FROM doctors ORDER BY specialty ASC";
            $specResult = $conn->query($specQuery);
            while($specRow = $specResult->fetch_assoc()) {
              echo "<option value='{$specRow['specialty']}'>{$specRow['specialty']}</option>";
            }
          ?>
        </select>
      </div>

      <div class="col-md-3 col-sm-6" data-aos="zoom-in" data-aos-delay="100">
        <select id="hospitalFilter" class="form-select">
          <option value="">All Hospitals</option>
          <?php
            $hosQuery = "SELECT DISTINCT hospital FROM doctors ORDER BY hospital ASC";
            $hosResult = $conn->query($hosQuery);
            while($hosRow = $hosResult->fetch_assoc()) {
              if (!empty($hosRow['hospital'])) {
                echo "<option value='{$hosRow['hospital']}'>{$hosRow['hospital']}</option>";
              }
            }
          ?>
        </select>
      </div>

      <div class="col-md-4 col-sm-6" data-aos="zoom-in" data-aos-delay="200">
        <input type="text" id="searchBar" class="form-control" placeholder="Search by name or specialty..." maxlength="40">
      </div>

    </div>
  </div>
</section>

<!-- DOCTORS SECTION -->
<section class="doctors-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">Our Medical <span>Specialists</span></h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
        Choose from our diverse team of doctors — each bringing years of experience and care.
      </p>
    </div>

    <div class="row g-4" id="doctorsContainer">
      <div class="text-center text-muted">Loading doctors...</div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="about-cta text-white text-center">
  <div class="cta-overlay"></div>
  <div class="container position-relative py-5" data-aos="fade-up">
    <h3 class="fw-bold mb-3 text-warning">Looking for Expert Medical Care?</h3>
    <p class="mb-4">Book an appointment with our trusted doctors and take the first step toward better health.</p>
    <a href="appointment.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">Book Appointment</a>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const specialtyFilter = document.getElementById('specialtyFilter');
  const hospitalFilter = document.getElementById('hospitalFilter');
  const searchBar = document.getElementById('searchBar');
  const container = document.getElementById('doctorsContainer');
  const perPage = 9;
  let currentPage = 1;
  let debounceTimer;

  function loadDoctors(page = 1, firstLoad = false) {
    const specialty = specialtyFilter.value;
    const hospital = hospitalFilter.value;
    const search = searchBar.value;
    currentPage = page;

    if (firstLoad) {
      container.innerHTML = `<div class="text-center text-muted py-5">⏳ Loading doctors...</div>`;
    }

    fetch(`fetch_doctors.php?page=${page}&limit=${perPage}&specialty=${encodeURIComponent(specialty)}&hospital=${encodeURIComponent(hospital)}&search=${encodeURIComponent(search)}`)
      .then(res => res.text())
      .then(data => {
        container.innerHTML = data.trim() || "<p class='text-center text-muted py-5'>No doctors found.</p>";
        attachPaginationEvents();

        if (!firstLoad) {
          const section = document.querySelector('.doctors-section');
          if (section) {
            window.scrollTo({
              top: section.offsetTop - 100,
              behavior: 'smooth'
            });
          }
        }

        requestAnimationFrame(() => {
          setTimeout(() => {
            if (typeof AOS !== 'undefined') AOS.refresh();
          }, 150);
        });
      })
      .catch(() => {
        container.innerHTML = `<p class="text-center text-danger py-5">❌ Failed to load doctors.</p>`;
      });
  }

  function attachPaginationEvents() {
    document.querySelectorAll('.pagination-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const page = parseInt(this.dataset.page);
        loadDoctors(page);
      });
    });
  }

  specialtyFilter.addEventListener('change', () => loadDoctors(1));
  hospitalFilter.addEventListener('change', () => loadDoctors(1));
  searchBar.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadDoctors(1), 200);
  });

  loadDoctors(1, true);
});
</script>

<?php include('footer.php'); ?>
