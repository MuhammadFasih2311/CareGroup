<?php include('header.php'); ?>
<?php include('connect.php'); ?>
<link rel="stylesheet" href="assets/css/disease.css">

<?php
$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM diseases WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$disease = $result->fetch_assoc();
$stmt->close();

if (!$disease) {
  echo "<div class='container py-5 text-center'><h3 class='text-danger'>Disease not found.</h3></div>";
  include('footer.php');
  exit;
}
?>

<!-- HERO SECTION -->
<section class="disease-detail-hero position-relative">
  <div class="overlay"></div>
  <div class="container text-center content" data-aos="fade-up">
    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($disease['name']); ?></h1>
    <p class="lead mb-0 text-light">Detailed overview, symptoms, and treatments.</p>
  </div>
</section>

<!-- DISEASE DETAIL SECTION -->
<section class="disease-detail-section py-5">
  <div class="container">
    <div class="row align-items-center g-5">

      <div class="col-lg-5 text-center" data-aos="fade-right" data-aos-delay="100">
        <div class="disease-detail-card shadow-sm p-4 rounded-4 bg-white">
          <img src="<?php echo htmlspecialchars($disease['image']); ?>" alt="<?php echo htmlspecialchars($disease['name']); ?>" class="img-fluid rounded-3 mb-3">
          <h4 class="fw-bold text-primary mb-2"><?php echo htmlspecialchars($disease['name']); ?></h4>
          <p class="text-muted small">
            <?php echo htmlspecialchars($disease['short_desc'] ?? ''); ?>
          </p>
        </div>
      </div>

      <div class="col-lg-7" data-aos="fade-left" data-aos-delay="100">
        <div class="disease-info">
          <h5 class="fw-bold text-danger">Description</h5>
          <p class="text-secondary"><?php echo nl2br($disease['description']); ?></p>

          <h5 class="fw-bold text-primary mt-4">Symptoms</h5>
          <p class="text-secondary"><?php echo nl2br($disease['symptoms']); ?></p>

          <h5 class="fw-bold text-success mt-4">Treatment</h5>
          <p class="text-secondary"><?php echo nl2br($disease['treatment']); ?></p>

          <div class="mt-4">
            <button id="showDoctorsBtn" class="btn btn-outline-danger rounded-pill px-4">
              🩺 Find Doctors for This Disease
            </button>
            <a href="diseases.php" class="btn btn-outline-primary rounded-pill px-4 ms-2">
              ← Back to Diseases
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="relatedDoctorsSection" class="py-5 bg-light mt-5" style="display:none;">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">Doctors for <span><?php echo htmlspecialchars($disease['name']); ?></span></h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Meet specialists who treat this condition.</p>
    </div>
    <div class="row g-4" id="relatedDoctorsContainer">
      <div class="text-center text-muted" data-aos="zoom-in">Loading doctors...</div>
    </div>
  </div>
</section>

<!-- RELATED DISEASES SECTION -->
<section class="related-diseases py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">Related <span>Diseases</span></h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Explore more health conditions.</p>
    </div>

    <div class="row g-4">
      <?php
      $related = "SELECT * FROM diseases WHERE id != ? ORDER BY RAND() LIMIT 3";
      $stmt = $conn->prepare($related);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $res = $stmt->get_result();

      if ($res && $res->num_rows > 0) {
        $delay = 0;
        while ($row = $res->fetch_assoc()) {
          $rid = intval($row['id']);
          $rname = htmlspecialchars($row['name']);
          $rimg = !empty($row['image']) ? $row['image'] : 'assets/images/default-disease.jpg';
          $rdesc = htmlspecialchars(substr($row['short_desc'] ?? $row['description'], 0, 120));

          echo '
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="'.$delay.'">
            <a href="disease-detail.php?id='.$rid.'" class="text-decoration-none text-dark">
              <div class="disease-card bg-white rounded-4 shadow-sm p-4 h-100 text-center">
                <img src="'.$rimg.'" alt="'.$rname.'" class="disease-img rounded-circle mb-3">
                <h5 class="fw-bold">'.$rname.'</h5>
                <p class="text-muted small mb-3">'.$rdesc.'...</p>
              </div>
            </a>
          </div>';
          $delay += 100;
        }
      }
      $stmt->close();
      ?>
    </div>
  </div>
</section>

<script>
document.getElementById("showDoctorsBtn").addEventListener("click", function() {
  const section = document.getElementById("relatedDoctorsSection");
  section.style.display = "block";
  section.classList.add("fade-in");

  fetch("fetch_related_doctors.php?disease=<?php echo urlencode($disease['name']); ?>")
    .then(res => res.text())
    .then(data => {
      document.getElementById("relatedDoctorsContainer").innerHTML = data;
      AOS.refresh();
    })
    .catch(() => {
      document.getElementById("relatedDoctorsContainer").innerHTML = 
        "<p class='text-center text-danger' data-aos='fade-up'>Failed to load doctors.</p>";
    });
});
</script>

<?php include('footer.php'); ?>
