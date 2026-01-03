<?php include('header.php'); ?>
<?php include('connect.php'); ?>
<link rel="stylesheet" href="assets/css/locations.css">

<!-- HERO SECTION -->
<section class="location-hero">
  <div class="overlay"></div>
  <div class="container text-center content" data-aos="fade-up">
    <h1 class="fw-bold mb-3">Our Hospitals</h1>
    <p class="lead mb-0">Find the best hospitals and healthcare centers near you</p>
  </div>
</section>

<section class="py-3">
  <div class="container">
    <div class="row justify-content-center g-3 align-items-center">

      <div class="col-md-4" data-aos="zoom-in">
        <select id="locationFilter" class="form-select rounded-pill">
          <option value="">All Hospitals</option>
          <?php
          $query = "SELECT DISTINCT name FROM hospitals WHERE name IS NOT NULL ORDER BY name";
          $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
          }
          ?>
        </select>
      </div>

      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
        <input type="text" id="searchBar" class="form-control rounded-pill" placeholder="Search hospital...">
      </div>

    </div>
  </div>
</section>

<!-- HOSPITALS SECTION -->
<section class="doctors-section py-5">
  <div class="container">
    <div class="text-center mb-5">
  <h2 class="fw-bold section-title" data-aos="fade-up">
    Our Partner <span>Hospitals</span>
  </h2>
  <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
    Explore top hospitals affiliated with <strong>MedicoCare</strong> — delivering excellence in healthcare and patient care.
  </p>
</div>

    <div class="row g-4" id="hospitalContainer">
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="about-cta text-white text-center">
  <div class="cta-overlay"></div>
  <div class="container position-relative py-5" data-aos="fade-up">
    <h3 class="fw-bold mb-3 text-warning">Partner With Us</h3>
    <p class="mb-4">Join our network of trusted hospitals to bring quality healthcare to more people.</p>
    <a href="contacts.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">Contact Us</a>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchBar = document.getElementById('searchBar');
  const locationFilter = document.getElementById('locationFilter');
  const container = document.getElementById('hospitalContainer');
  const perPage = 9;
  let currentPage = 1;
  let debounceTimer;

  function loadHospitals(page = 1, firstLoad = false) {
    const search = searchBar.value;
    const location = locationFilter.value;
    currentPage = page;

    if (firstLoad) {
      container.innerHTML = `<div class="text-center text-muted py-5">⏳ Loading hospitals...</div>`;
    }

    $.ajax({
      url: "fetch_locations.php",
      method: "GET",
      data: { search, location, page, limit: perPage },
      success: function(data) {
        container.innerHTML = data.trim() || "<p class='text-center text-muted py-5'>No hospitals found.</p>";
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
      },
      error: function() {
        container.innerHTML = `<p class="text-center text-danger py-5">❌ Failed to load hospitals.</p>`;
      }
    });
  }

  function attachPaginationEvents() {
    document.querySelectorAll('.pagination-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const page = parseInt(this.dataset.page);
        loadHospitals(page);
      });
    });
  }

  searchBar.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadHospitals(1), 200);
  });

  locationFilter.addEventListener('change', () => loadHospitals(1));

  loadHospitals(1, true);
});
</script>


<?php include('footer.php'); ?>
