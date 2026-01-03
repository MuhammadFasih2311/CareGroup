<?php
include('header.php');
include('connect.php');

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM appointments WHERE user_id = ? AND status='active' ORDER BY date DESC, time DESC");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$today = date('Y-m-d H:i:s');
$upcoming = [];
$past = [];

while ($row = $result->fetch_assoc()) {
  $appointment_datetime = $row['date'] . ' ' . $row['time'];
  if ($appointment_datetime >= $today) {
    $upcoming[] = $row;
  } else {
    $past[] = $row;
  }
}
?>

<style>
.appointments-hero {
  position: relative;
  height: 80vh;
  background: url("images/appointment-bg.jpg") center center / cover no-repeat fixed;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}

.appointments-hero .overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, rgba(0, 102, 255, 0.4), rgba(255, 59, 59, 0.35));
  z-index: 1;
}

.appointments-hero .content {
  position: relative;
  z-index: 2;
  text-align: center;
  max-width: 700px;
}

.appointments-hero h1 {
  font-size: 3rem;
  font-weight: 700;
  letter-spacing: 1px;
}

.appointments-hero p {
  font-size: 1.1rem;
  color: #f8f9fa;
  margin-top: 0.5rem;
}

.nav-pills .nav-link {
  background: #fff;
  color: #007bff;
  border: 1px solid #007bff;
  transition: all 0.3s;
}
.nav-pills .nav-link.active {
  background: linear-gradient(90deg, #0066ff, #ff3b3b);
  color: #fff;
  border: none;
}
.card {
  transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
<!--  HERO SECTION -->
<section class="appointments-hero">
  <div class="overlay"></div>
  <div class="content" data-aos="fade-up">
    <h1>My Appointments</h1>
    <p>Manage your scheduled visits and track your medical history with ease 🩺</p>
  </div>
</section>


<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold section-title" data-aos="fade-up">
        Manage <span>Your Appointments</span>
      </h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
        Stay organized with your upcoming and past appointments. Review details, edit, or cancel anytime — all from one place.
      </p>
    </div>

    <ul class="nav nav-pills justify-content-center mb-4" id="appointmentTabs">
      <li class="nav-item" data-aos="zoom-in">
        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#upcoming">Upcoming</button>
      </li>
      <li class="nav-item ms-2" data-aos="zoom-in" data-aos-delay="100">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#past">Past</button>
      </li>
    </ul>

    <div class="tab-content">

      <!-- UPCOMING -->
      <div class="tab-pane fade show active" id="upcoming">
        <?php if (count($upcoming) > 0): ?>
          <div class="row g-4">
            <?php foreach ($upcoming as $row): ?>
              <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                  <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-2"><i class="bi bi-hospital"></i> <?= htmlspecialchars($row['hospital']) ?></h5>
                    <p class="text-muted mb-1">
                      <i class="bi bi-person-badge"></i>
                      <a href="doctor-detail.php?id=<?= urlencode($row['doctor_id']) ?>" class="text-decoration-none fw-semibold">
                        <?= htmlspecialchars($row['doctor']) ?>
                      </a>
                    </p>
                    <p class="text-muted mb-1"><i class="bi bi-bookmark-heart"></i> <?= htmlspecialchars($row['specialty']) ?></p>
                    <hr>
                    <p class="mb-1"><i class="bi bi-calendar-event text-danger"></i> <strong><?= date("F d, Y", strtotime($row['date'])) ?></strong></p>
                    <p class="mb-1"><i class="bi bi-clock text-success"></i> <?= date("h:i A", strtotime($row['time'])) ?></p>
                    <p class="small text-muted mt-2"><i class="bi bi-chat-left-text"></i> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
                  </div>
                  <div class="card-footer bg-white border-0 text-center pb-3">
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 cancel-btn" data-id="<?= $row['id'] ?>"><i class="bi bi-x-circle"></i> Cancel</button>
                    <div class="mt-2 small text-secondary"><i class="bi bi-calendar-check"></i> Booked on <?= date("M d, Y", strtotime($row['created_at'])) ?></div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <img src="assets/images/empty.svg" style="max-width:180px;" class="mb-3" data-aos="fade-up">
            <h5 class="text-muted" data-aos="fade-up" data-aos-delay="100">No upcoming appointments.</h5>
            <a href="appointment.php" class="btn btn-primary rounded-pill mt-3 px-4" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-plus-circle"></i> Book New
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- PAST -->
      <div class="tab-pane fade" id="past">
        <?php if (count($past) > 0): ?>
          <div class="row g-4">
            <?php foreach ($past as $row): ?>
              <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100 bg-light-subtle">
                  <div class="card-body p-4">
                    <h5 class="fw-bold text-secondary mb-2"><i class="bi bi-hospital"></i> <?= htmlspecialchars($row['hospital']) ?></h5>
                    <p class="text-muted mb-1"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($row['doctor']) ?></p>
                    <p class="text-muted mb-1"><i class="bi bi-bookmark-heart"></i> <?= htmlspecialchars($row['specialty']) ?></p>
                    <hr>
                    <p class="mb-1"><i class="bi bi-calendar-event text-danger"></i> <strong><?= date("F d, Y", strtotime($row['date'])) ?></strong></p>
                    <p class="mb-1"><i class="bi bi-clock text-success"></i> <?= date("h:i A", strtotime($row['time'])) ?></p>
                    <p class="small text-muted mt-2"><i class="bi bi-chat-left-text"></i> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
                  </div>
                  <div class="card-footer bg-white border-0 text-center pb-3">
                    <div class="small text-muted"><i class="bi bi-clock-history"></i> Completed</div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <img src="assets/images/empty.svg" style="max-width:180px;" class="mb-3" data-aos="fade-up">
            <h5 class="text-muted" data-aos="fade-up" data-aos-delay="100">No past appointments.</h5>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="about-cta text-white text-center">
  <div class="cta-overlay"></div>
  <div class="container position-relative py-5" data-aos="fade-up">
    <h2 class="fw-bold mb-3 text-warning">Need Help or Have Questions?</h2>
    <p class="mb-4">We’re here to assist you anytime. Get in touch with our support team.</p>
    <a href="contact.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">
      Contact Us <i class="bi bi-arrow-right-circle ms-2"></i>
    </a>
  </div>
</section>

<script>
document.querySelectorAll('.cancel-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    if (confirm("Are you sure you want to cancel this appointment?")) {
      fetch('cancel_appointment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
      })
      .then(res => res.text())
      .then(data => {
        alert('Appointment canceled successfully.');
        location.reload();
      })
      .catch(() => alert('Failed to cancel appointment.'));
    }
  });
});
</script>

<?php include('footer.php'); ?>
