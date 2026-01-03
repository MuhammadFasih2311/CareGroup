<?php
include('header.php');
include('connect.php');

if(!isset($_SESSION['user_id'])){
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$query = $conn->query("SELECT * FROM users WHERE id='$user_id' LIMIT 1");
$user = $query->fetch_assoc();
?>

<!-- PROFILE HERO -->
<section class="profile-hero text-center text-white d-flex align-items-center justify-content-center">
  <div class="overlay"></div>
  <div class="container position-relative">
    <div class="profile-icon mx-auto mb-3" data-aos="flip-left">
      <i class="bi bi-person-circle"></i>
    </div>
    <h1 class="fw-bold" data-aos="fade-up"><?php echo htmlspecialchars($user['name']); ?></h1>
    <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">Welcome to your CARE Group Profile</p>
  </div>
</section>

<!-- PROFILE DETAILS -->
<section class="py-5 profile-section">
  <div class="container" data-aos="zoom-in">
    <div class="card profile-card mx-auto shadow-lg border-0 p-4 p-md-5 rounded-4" style="max-width:700px;">
      <h3 class="fw-bold text-center text-primary mb-4">👤 Your Profile</h3>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-bold text-muted">Full Name</label>
          <input type="text" class="form-control rounded-pill" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold text-muted">Email</label>
          <input type="email" class="form-control rounded-pill" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold text-muted">Phone</label>
          <input type="text" class="form-control rounded-pill" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold text-muted">Account Created</label>
          <input type="text" class="form-control rounded-pill" value="<?php echo date('F d, Y', strtotime($user['created_at'])); ?>" readonly>
        </div>
      </div>

      <div class="text-center mt-4">
        <a href="edit_profile.php" class="btn btn-primary rounded-pill px-4 me-2"><i class="bi bi-pencil-square me-1"></i>Edit Profile</a>
        <a href="cpassword.php" class="btn btn-danger rounded-pill px-4"><i class="bi bi-lock-fill me-1"></i>Change Password</a>
      </div>
    </div>
  </div>
</section>

<style>
.profile-hero {
  position: relative;
  height: 50vh;
  background: linear-gradient(120deg, #0066ff, #ff3b3b);
  overflow: hidden;
}
.profile-hero .overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.2);
}
.profile-icon {
  font-size: 6rem;
  color: #fff;
  background: rgba(255,255,255,0.2);
  width: 140px;
  height: 140px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.profile-card {
  background: #fff;
  border-radius: 20px;
  transition: all 0.3s ease;
}
.profile-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
</style>

<?php include('footer.php'); ?>
