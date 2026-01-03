<?php
include('header.php');
include('connect.php');

if(!isset($_SESSION['user_id'])){
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $old = $_POST['old_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  $query = $conn->query("SELECT password FROM users WHERE id='$user_id'");
  $data = $query->fetch_assoc();

  if(!password_verify($old, $data['password'])){
    echo "<script>alert('Old password is incorrect!');</script>";
  } elseif($new === $old){
    echo "<script>alert('New password cannot be the same as the old password!');</script>";
  } elseif($new !== $confirm){
    echo "<script>alert('New passwords do not match!');</script>";
  } elseif(strlen($new) < 8 || strlen($new) > 40){
    echo "<script>alert('Password must be between 8 and 40 characters!');</script>";
  } else {
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$hash' WHERE id='$user_id'");
    echo "<script>alert('Password changed successfully!'); window.location='profile.php';</script>";
  }
}
?>

<section class="py-5 bg-light">
  <div class="container" data-aos="fade-up">
    <div class="card shadow-lg border-0 p-4 p-md-5 mx-auto rounded-4" style="max-width:700px;">
      <h3 class="fw-bold text-center text-danger mb-4">🔒 Change Password</h3>

      <form method="post" id="passwordForm" novalidate>

        <div class="mb-3 position-relative">
          <label class="form-label fw-bold text-muted">Old Password</label>
          <div class="input-group">
            <input type="password" name="old_password" minlength="8" maxlength="40" class="form-control rounded-start-pill" id="old_password" required>
            <button type="button" class="btn btn-outline-secondary rounded-end-pill toggle-password" data-target="old_password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
        </div>

        <div class="mb-3 position-relative">
          <label class="form-label fw-bold text-muted">New Password</label>
          <div class="input-group">
            <input type="password" name="new_password" minlength="8" maxlength="40" class="form-control rounded-start-pill" id="new_password" required>
            <button type="button" class="btn btn-outline-secondary rounded-end-pill toggle-password" data-target="new_password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <div id="passwordStrength" class="form-text text-danger mt-1"></div>
        </div>

        <div class="mb-3 position-relative">
          <label class="form-label fw-bold text-muted">Confirm New Password</label>
          <div class="input-group">
            <input type="password" name="confirm_password" minlength="8" maxlength="40" class="form-control rounded-start-pill" id="confirm_password" required>
            <button type="button" class="btn btn-outline-secondary rounded-end-pill toggle-password" data-target="confirm_password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <div id="matchMessage" class="form-text mt-1"></div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-danger rounded-pill px-5">Update Password</button>
          <a href="profile.php" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
document.querySelectorAll(".toggle-password").forEach(btn => {
  btn.addEventListener("click", () => {
    const target = document.getElementById(btn.dataset.target);
    const icon = btn.querySelector("i");
    if (target.type === "password") {
      target.type = "text";
      icon.classList.replace("bi-eye-slash", "bi-eye");
    } else {
      target.type = "password";
      icon.classList.replace("bi-eye", "bi-eye-slash");
    }
  });
});

const newPass = document.getElementById("new_password");
const confirmPass = document.getElementById("confirm_password");
const oldPass = document.getElementById("old_password");
const matchMsg = document.getElementById("matchMessage");
const strengthMsg = document.getElementById("passwordStrength");

function validatePasswords() {
  if (confirmPass.value === "") {
    matchMsg.textContent = "";
  } else if (confirmPass.value === newPass.value) {
    matchMsg.textContent = "✅ Passwords match";
    matchMsg.classList.remove("text-danger");
    matchMsg.classList.add("text-success");
  } else {
    matchMsg.textContent = "❌ Passwords do not match";
    matchMsg.classList.remove("text-success");
    matchMsg.classList.add("text-danger");
  }

  if (newPass.value && newPass.value === oldPass.value) {
    strengthMsg.textContent = "❌ New password cannot be the same as old password";
  } else if (newPass.value.length < 8) {
    strengthMsg.textContent = "⚠️ Minimum 8 characters required";
  } else {
    strengthMsg.textContent = "";
  }
}

newPass.addEventListener("input", validatePasswords);
confirmPass.addEventListener("input", validatePasswords);
oldPass.addEventListener("input", validatePasswords);
</script>

<?php include('footer.php'); ?>
