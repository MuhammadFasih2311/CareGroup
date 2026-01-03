<?php include('header.php'); ?>
<?php include('connect.php'); ?>
<link rel="stylesheet" href="assets/css/doctor.css">

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM doctors WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
  echo "<section class='py-5 text-center'><h3 class='text-danger'>Doctor not found.</h3></section>";
  include('footer.php');
  exit;
}

$doctor = $result->fetch_assoc();
?>

<!-- HERO SECTION -->
<section class="doc-detail-hero">
  <div class="overlay"></div>
  <div class="container text-center content" data-aos="fade-up">
    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($doctor['name']); ?></h1>
    <p class="lead mb-0">Expert in <?php echo htmlspecialchars($doctor['specialty']); ?></p>
  </div>
</section>

<!-- DOCTOR DETAIL SECTION -->
<section class="doctor-detail py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 text-center" data-aos="fade-right" data-aos-delay="100">
        <img src="<?php echo htmlspecialchars($doctor['image']); ?>" 
             alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
             class="doctor-detail-img rounded-circle shadow-lg">
      </div>

<div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
  <div class="doctor-info">
    <h2 class="fw-bold text-primary mb-2">
      <?php echo htmlspecialchars($doctor['name']); ?>
    </h2>
    <h5 class="text-danger fw-semibold mb-3">
      <?php echo htmlspecialchars($doctor['specialty']); ?>
    </h5>

    <ul class="list-unstyled mb-4 text-secondary">
      <?php if (!empty($doctor['experience'])): ?>
        <li><i class="bi bi-briefcase-fill text-primary me-2"></i>
          <strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years
        </li>
      <?php endif; ?>

      <?php if (!empty($doctor['qualification'])): ?>
        <li><i class="bi bi-mortarboard-fill text-primary me-2"></i>
          <strong>Qualification:</strong> <?php echo htmlspecialchars($doctor['qualification']); ?>
        </li>
      <?php endif; ?>

      <?php if (!empty($doctor['hospital'])): ?>
        <li><i class="bi bi-hospital-fill text-primary me-2"></i>
          <strong>Hospital:</strong> <?php echo htmlspecialchars($doctor['hospital']); ?>
        </li>
      <?php endif; ?>
    </ul>

    <p class="text-secondary mb-4" style="line-height:1.9;">
      <?php echo nl2br(htmlspecialchars($doctor['description'])); ?>
    </p>

    <div class="d-flex flex-wrap gap-3 mt-4">
      <a href="appointment.php?doctor_id=<?php echo $doctor['id']; ?>" 
         class="btn btn-primary rounded-pill px-4 py-2 shadow">
         <i class="bi bi-calendar-check me-2"></i>Book Appointment
      </a>
      <a href="contacts.php" 
         class="btn btn-outline-primary rounded-pill px-4 py-2">
         <i class="bi bi-telephone-forward me-2"></i>Contact Clinic
      </a>
      <a href="doctors.php" 
         class="btn btn-outline-secondary rounded-pill px-4 py-2">
         <i class="bi bi-arrow-left me-2"></i>Back to Doctors
      </a>
    </div>
  </div>
</div>
  </div>
</section>

<!-- RELATED DOCTORS SECTION -->
<section class="related-doctors py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">
        Related <span>Doctors</span>
      </h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
        Discover more of our experienced specialists.
      </p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php
      $related = "SELECT * FROM doctors WHERE id != $id ORDER BY RAND() LIMIT 3";
      $res = $conn->query($related);

      if ($res && $res->num_rows > 0) {
        $delay = 0;
        while ($row = $res->fetch_assoc()) {
          $rimg = !empty($row['image']) ? $row['image'] : 'assets/images/default-doctor.jpg';
          echo '
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="'.$delay.'">
            <a href="doctor-detail.php?id='.$row['id'].'" class="text-decoration-none text-dark">
              <div class="doctor-card text-center p-4 rounded-4 shadow-sm bg-white h-100 clickable-card">
                <div class="d-flex flex-column align-items-center">
                  <img src="'.$rimg.'" alt="'.$row['name'].'" class="doctor-img rounded-circle mb-3">
                  <h5 class="fw-bold text-primary mb-1">'.$row['name'].'</h5>
                  <p class="text-muted small mb-2">'.$row['specialty'].'</p>
                  <p class="text-secondary small mb-0">'.substr(htmlspecialchars($row['description']), 0, 90).'...</p>
                </div>
              </div>
            </a>
          </div>';
          $delay += 100;
        }
      } else {
        echo "<p class='text-center text-muted'>No related doctors found.</p>";
      }
      ?>
    </div>
  </div>
</section>

<script>
  window.addEventListener("load", () => {
    if (typeof AOS !== 'undefined') AOS.refresh();
  });
</script>

<?php include('footer.php'); ?>
