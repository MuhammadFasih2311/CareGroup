<?php include('header.php'); ?>
<?php include('connect.php'); ?>
<link rel="stylesheet" href="assets/css/disease.css">

<!-- HERO SECTION -->
<section class="disease-hero">
  <div class="overlay"></div>
  <div class="container text-center content" data-aos="fade-up">
    <h1 class="fw-bold mb-3">Common Diseases & Conditions 🧬</h1>
    <p class="lead mb-0">Learn about causes, symptoms, and treatments from trusted experts.</p>
  </div>
</section>

<!-- FILTER SECTION -->
<section class="py-4 border-bottom">
  <div class="container text-center">
    <div class="row justify-content-center g-2">
      <div class="col-md-4 col-sm-6" data-aos="zoom-in">
        <select id="categoryFilter" class="form-select">
          <option value="">All Categories</option>
          <?php
            $catQuery = "SELECT DISTINCT category FROM diseases WHERE category IS NOT NULL AND category != '' ORDER BY category ASC";
            $catResult = $conn->query($catQuery);
            while ($catRow = $catResult->fetch_assoc()) {
              echo "<option value='{$catRow['category']}'>{$catRow['category']}</option>";
            }
          ?>
        </select>
      </div>
      <div class="col-md-4 col-sm-6" data-aos="zoom-in" data-aos-delay="100">
        <input type="text" id="searchBar" class="form-control" placeholder="Search by disease name or symptoms..." maxlength="40">
      </div>
    </div>
  </div>
</section>

<!-- DISEASES SECTION -->
<section class="diseases-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">Explore <span>Health Topics</span></h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
        Understand diseases, prevent complications, and get expert health insights verified by doctors.
      </p>
    </div>

    <div class="row g-4" id="diseaseContainer">
      <div class="text-center text-muted">Loading diseases...</div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="about-cta text-white text-center">
  <div class="cta-overlay"></div>
  <div class="container position-relative py-5" data-aos="fade-up">
    <h3 class="fw-bold mb-3 text-warning">Need Medical Advice?</h3>
    <p class="mb-4">Consult with specialists for treatment and prevention options.</p>
    <a href="doctors.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">Find a Doctor</a>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const categoryFilter = document.getElementById('categoryFilter');
  const searchBar = document.getElementById('searchBar');
  const container = document.getElementById('diseaseContainer');
  const perPage = 9;
  let currentPage = 1;
  let debounceTimer;

  function loadDiseases(page = 1, firstLoad = false) {
    const category = categoryFilter.value;
    const search = searchBar.value;
    currentPage = page;

    if (firstLoad) {
      container.innerHTML = `<div class="text-center text-muted py-5">⏳ Loading diseases...</div>`;
    }

    fetch(`fetch_diseases.php?page=${page}&limit=${perPage}&category=${encodeURIComponent(category)}&search=${encodeURIComponent(search)}`)
      .then(res => res.text())
      .then(data => {
        container.innerHTML = data.trim() || "<p class='text-center text-muted py-5'>No diseases found.</p>";
        attachPaginationEvents();
        
        if (!firstLoad) {
          const section = document.querySelector('.diseases-section');
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
        container.innerHTML = `<p class="text-center text-danger py-5">❌ Failed to load diseases.</p>`;
      });
  }

  function attachPaginationEvents() {
    document.querySelectorAll('.pagination-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const page = parseInt(this.dataset.page);
        loadDiseases(page);
      });
    });
  }

  categoryFilter.addEventListener('change', () => loadDiseases(1));
  searchBar.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadDiseases(1), 200);
  });

  loadDiseases(1, true);
});
</script>

<?php include('footer.php'); ?>
