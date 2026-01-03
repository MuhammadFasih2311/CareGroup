<?php include('header.php'); ?>
<?php include('connect.php'); ?>

<?php
$hospital = isset($_GET['hospital']) ? trim($_GET['hospital']) : '';
$hospital = $conn->real_escape_string($hospital);

$hospitalQuery = "SELECT * FROM hospitals WHERE name = '$hospital' LIMIT 1";
$hospitalResult = $conn->query($hospitalQuery);
$hospitalData = $hospitalResult && $hospitalResult->num_rows > 0 ? $hospitalResult->fetch_assoc() : null;
?>

<!-- HERO SECTION -->
<section class="hospital-detail position-relative">
  <div class="overlay"></div>
  <div class="container text-center content" data-aos="fade-up">
    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($hospital); ?></h1>
    <p class="lead mb-0">Providing exceptional healthcare with compassion and excellence</p>
  </div>
</section>

<!-- HOSPITAL DETAILS -->
<section class="py-5 bg-light">
  <div class="container" data-aos="flip-down">
    <?php if ($hospitalData): ?>
      <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
        <div class="row align-items-center">
          <div class="col-md-5 mb-4 mb-md-0">
            <img src="<?php echo htmlspecialchars($hospitalData['image']); ?>" alt="<?php echo htmlspecialchars($hospital); ?>" class="img-fluid rounded-4 shadow-sm">
          </div>
          <div class="col-md-7">
            <h2 class="fw-bold mb-3 text-primary"><?php echo htmlspecialchars($hospitalData['name']); ?></h2>
            <p class="text-muted mb-2"><i class="bi bi-geo-alt text-danger"></i> <?php echo htmlspecialchars($hospitalData['address']); ?></p>
            <p class="text-muted"><i class="bi bi-building text-success"></i> <?php echo htmlspecialchars($hospitalData['city']); ?></p>
            <p class="mt-3">
              <?php echo htmlspecialchars($hospitalData['name']); ?> is a trusted healthcare institution offering modern medical facilities and expert care for patients across all specialties.
            </p>
          </div>
        </div>
      </div>
    <?php else: ?>
      <p class="text-center text-muted">Hospital not found.</p>
    <?php endif; ?>
  </div>
</section>

<!-- FILTER SECTION -->
<section class="py-4 border-bottom bg-white">
  <div class="container text-center">
    <div class="row justify-content-center g-2">
      <div class="col-md-4 col-sm-6" data-aos="zoom-in">
        <select id="specialtyFilter" class="form-select rounded-pill">
          <option value="">All Specialties</option>
          <?php
          $specQuery = "SELECT DISTINCT specialty FROM doctors WHERE hospital='$hospital' ORDER BY specialty ASC";
          $specResult = $conn->query($specQuery);
          while ($row = $specResult->fetch_assoc()) {
            echo "<option value='{$row['specialty']}'>{$row['specialty']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-4 col-sm-6" data-aos="zoom-in" data-aos-delay="100">
        <input type="text" id="searchBar" class="form-control rounded-pill" placeholder="Search doctor...">
      </div>
    </div>
  </div>
</section>

<!-- RELATED DOCTORS SECTION -->
<section class="doctors-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">
        Our Doctors at <span><?php echo htmlspecialchars($hospital); ?></span>
      </h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
        Meet our specialized doctors providing dedicated care at <?php echo htmlspecialchars($hospital); ?>.
      </p>
    </div>

    <div class="row g-4" id="doctorsContainer">
      <div class="text-center text-muted">Loading doctors...</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="about-cta text-white text-center">
  <div class="cta-overlay"></div>
  <div class="container position-relative py-5" data-aos="fade-up">
    <h2 class="fw-bold mb-3 text-warning">Discover Our Medical Services</h2>
    <p class="mb-4">We offer a wide range of healthcare specialties for your well-being.</p>
    <a href="services.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">
      Explore Services <i class="bi bi-arrow-right-circle ms-2"></i>
    </a>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const hospital = "<?php echo addslashes($hospital); ?>";
  const specialtyFilter = document.getElementById('specialtyFilter');
  const searchBar = document.getElementById('searchBar');
  const container = document.getElementById('doctorsContainer');
  const perPage = 9;
  let currentPage = 1;
  let debounceTimer;

  function loadDoctors(page = 1, firstLoad = false) {
    const specialty = specialtyFilter.value;
    const search = searchBar.value;
    currentPage = page;

    if (firstLoad) {
      container.innerHTML = `<div class="text-center text-muted py-5">⏳ Loading doctors...</div>`;
    }

    fetch(`fetch_hospital_doctors.php?hospital=${encodeURIComponent(hospital)}&page=${page}&limit=${perPage}&specialty=${encodeURIComponent(specialty)}&search=${encodeURIComponent(search)}`)
      .then(res => res.text())
      .then(data => {
        container.innerHTML = data.trim() || "<p class='text-center text-muted py-5'>No doctors found.</p>";
        attachPaginationEvents();

        if (!firstLoad) {
          const section = document.querySelector('.doctors-section');
          if (section) {
            window.scrollTo({ top: section.offsetTop - 100, behavior: 'smooth' });
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
  searchBar.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadDoctors(1), 200);
  });

  loadDoctors(1, true);
});
</script>

<?php include('footer.php'); ?>
