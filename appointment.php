<?php
include 'header.php';
include 'connect.php';

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$user_phone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '';

$prefill = [];
if (!empty($_GET)) {
  foreach ($_GET as $key => $value) {
    $prefill[$key] = htmlspecialchars($value);
  }
}

$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
$doctor = null;

if ($doctor_id > 0) {
  $query = "SELECT * FROM doctors WHERE id = $doctor_id";
  $result = $conn->query($query);
  if ($result && $result->num_rows > 0) {
    $doctor = $result->fetch_assoc();
  }
}
?>

<!-- HERO SECTION -->
<section class="appointment-hero">
  <div class="overlay"></div>
  <div class="content text-center" data-aos="fade-up">
    <h1>Book Your Appointment 📖</h1>
    <p>Schedule a visit with our trusted healthcare specialists.</p>
  </div>
</section>

<!-- INTRO SECTION -->
<section class="about-section py-5 bg-light">
  <div class="container text-center" data-aos="fade-up">
    <h2 class="fw-bold section-title mb-3">
      Our <span>Appointment Process</span>
    </h2>
    <p class="text-muted mb-4">
      Booking your appointment is simple and fast. Just fill in your details, select your preferred hospital and doctor,
      and we’ll confirm your slot instantly.
    </p>

    <div class="row justify-content-center g-4 mt-4">
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="fa-solid fa-hospital-user fs-1 text-primary mb-3"></i>
          <h5 class="fw-semibold mb-2">Choose Hospital & Doctor</h5>
          <p class="text-muted small">Select your preferred hospital and a specialist suited to your needs.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="fa-solid fa-calendar-check fs-1 text-success mb-3"></i>
          <h5 class="fw-semibold mb-2">Pick Date & Time</h5>
          <p class="text-muted small">Choose an available date and time that fits your schedule best.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="fa-solid fa-user-shield fs-1 text-warning mb-3"></i>
          <h5 class="fw-semibold mb-2">Confirm & Relax</h5>
          <p class="text-muted small">Once submitted, your appointment is confirmed instantly. We’ll take care of the rest!</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- APPOINTMENT FORM SECTION -->
<section class="appointment-section py-5">
  <div class="container" data-aos="fade-out">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card appointment-card shadow-lg border-0 p-4">
          <h3 class="text-center mb-4 section-title">Appointment <span>Form</span></h3>

          <form class="require-login" id="appointmentForm" action="save_appointment.php" method="POST" novalidate>
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control"
                       minlength="3" maxlength="35"
                       value="<?= htmlspecialchars($prefill['name'] ?? $user_name) ?>" required
                       oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');">
                <div class="invalid-feedback">Please enter your full name (3–40 characters).</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($prefill['email'] ?? $user_email) ?>" readonly>
                <div class="invalid-feedback">Valid email required.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="text" name="phone" class="form-control"
                       minlength="11" maxlength="11" pattern="[0-9]{11}"
                     value="<?= htmlspecialchars($prefill['phone'] ?? $user_phone) ?>" required oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                <div class="invalid-feedback">Phone number must be 11 digits.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Select Hospital</label>
              <select name="hospital" id="hospital" class="form-select" required>
                <option value="">Choose...</option>
                <?php
                  $hospitals = $conn->query("SELECT name FROM hospitals ORDER BY name ASC");
                  while($row = $hospitals->fetch_assoc()) {
                    $selected = (
                      ($prefill['hospital'] ?? '') == $row['name'] ||
                      ($doctor && $doctor['hospital'] == $row['name'])
                    ) ? 'selected' : '';
                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                  }
                ?>
              </select>
                <div class="invalid-feedback">Please select a hospital.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Select Specialty</label>
                <select name="specialty" id="specialty" class="form-select" required>
                <option value="">Choose...</option>
                <?php
                  $specs = $conn->query("SELECT DISTINCT specialty FROM doctors ORDER BY specialty ASC");
                  while($row = $specs->fetch_assoc()) {
                    $selected = (
                      ($prefill['specialty'] ?? '') == $row['specialty'] ||
                      ($doctor && $doctor['specialty'] == $row['specialty'])
                    ) ? 'selected' : '';
                    echo "<option value='{$row['specialty']}' $selected>{$row['specialty']}</option>";
                  }
                ?>
              </select>
                <div class="invalid-feedback">Please select a specialty.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Select Doctor</label>
                <select name="doctor_id" id="doctor" class="form-select" required>
                  <option value="">Choose...</option>
                  <?php
                    if ($doctor) {
                      echo "<option value='{$doctor['id']}' selected>{$doctor['name']}</option>";
                    }
                  ?>
                </select>
                <div class="invalid-feedback">Please select a doctor.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Preferred Date</label>
                <input type="date" name="date" id="dateInput" class="form-control" required
                value="<?= htmlspecialchars($prefill['date'] ?? '') ?>">
                <div class="invalid-feedback">Please select a valid date (today or later).</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Preferred Time</label>
                <input type="time" name="time" class="form-control" required
                value="<?= htmlspecialchars($prefill['time'] ?? '') ?>">
                <div class="invalid-feedback">Please select a valid time.</div>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Message (Optional)</label>
                <textarea name="message" rows="4" class="form-control"
                          minlength="5" maxlength="300"
                          placeholder="Any additional details (optional)"><?= htmlspecialchars($prefill['message'] ?? '') ?></textarea>
                <div class="invalid-feedback">Message must be 5–300 characters (if entered).</div>
              </div>

              <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Book Appointment</button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="about-cta text-white text-center mt-5">
  <div class="cta-overlay"></div>
  <div class="container position-relative py-5" data-aos="fade-up">
    <h2 class="fw-bold mb-3 text-warning">Want to Explore More Doctors?</h2>
    <p class="mb-4">Browse our expert medical team and find your perfect specialist.</p>
    <a href="doctors.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">View Doctors</a>
  </div>
</section>

<script>
const today = new Date().toISOString().split('T')[0];
document.getElementById('dateInput').setAttribute('min', today);

document.addEventListener('DOMContentLoaded', () => {
  const hospitalSelect = document.getElementById('hospital');
  const specialtySelect = document.getElementById('specialty');
  const doctorSelect = document.getElementById('doctor');

  function loadDoctors() {
    const hospital = hospitalSelect.value;
    const specialty = specialtySelect.value;
    if (!hospital || !specialty) return;

    fetch(`fetch_doctors_dropdown.php?hospital=${encodeURIComponent(hospital)}&specialty=${encodeURIComponent(specialty)}`)
      .then(res => res.json())
      .then(data => {
        doctorSelect.innerHTML = '<option value="">Choose...</option>';
        data.forEach(doc => {
          const opt = document.createElement('option');
          opt.value = doc.id;
          opt.textContent = doc.name;
          doctorSelect.appendChild(opt);
        });
      });
  }

  hospitalSelect.addEventListener('change', loadDoctors);
  specialtySelect.addEventListener('change', loadDoctors);
});

document.getElementById('appointmentForm').addEventListener('submit', function(e) {
  const form = this;
  if (!form.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  form.classList.add('was-validated');
});

document.addEventListener("DOMContentLoaded", function() {
  const forms = document.querySelectorAll("form.require-login");
  const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

  forms.forEach(form => {
    form.addEventListener("submit", function(e) {
      if (!isLoggedIn) {
        e.preventDefault();

        const params = new URLSearchParams(new FormData(form)).toString();
        const redirectURL = window.location.pathname + '?' + params;

        const modalLogin = document.querySelector('#loginPromptModal a[href="login.php"]');
        const modalSignup = document.querySelector('#loginPromptModal a[href="signup.php"]');
        if (modalLogin) modalLogin.href = 'login.php?redirect=' + encodeURIComponent(redirectURL);
        if (modalSignup) modalSignup.href = 'signup.php?redirect=' + encodeURIComponent(redirectURL);

        const modal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
        modal.show();
      }
    });
  });
});

  document.addEventListener("DOMContentLoaded", () => {
    if (window.location.hash === "#appointmentForm") {
      const el = document.querySelector("#appointmentForm");
      if (el) el.scrollIntoView({ behavior: "smooth" });
    }
  });
</script>


<div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="loginPromptModalLabel">Login Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p class="text-muted mb-3">You need to log in or register to submit this form.</p>
        <a href="login.php" class="btn btn-log px-4 me-2">
          <i class="fa-solid fa-right-to-bracket me-1"></i> Login
        </a>
        <a href="signup.php" class="btn btn-register px-4">
          <i class="fa-solid fa-user-plus me-1"></i> Register
        </a>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
