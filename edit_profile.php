<?php
include('header.php');
include('connect.php');

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$query = $conn->query("SELECT * FROM users WHERE id='$user_id' LIMIT 1");
$user = $query->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone']);
  $email = trim($_POST['email']);

  if (strlen($name) < 3) {
    echo "<script>alert('❌ Name must be at least 3 characters long!');</script>";
  } elseif (strlen($phone) != 11 || !ctype_digit($phone)) {
    echo "<script>alert('❌ Phone number must be exactly 11 digits!');</script>";
  } elseif (strlen($email) < 4 || strlen($email) > 40 || strpos($email, '@') === false) {
    echo "<script>alert('❌ Please enter a valid email with @ symbol and length between 4–40!');</script>";
  } else {
    $update = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
    $update->bind_param("sssi", $name, $email, $phone, $user_id);

    if ($update->execute()) {
      echo "<script>alert('✅ Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
      echo "<script>alert('❌ Error updating profile. Try again.');</script>";
    }
  }
}
?>

<section class="py-5 bg-light">
  <div class="container" data-aos="fade-up">
    <div class="card shadow-lg border-0 p-4 p-md-5 mx-auto rounded-4" style="max-width:700px;">
      <h3 class="fw-bold text-center text-primary mb-4">✏️ Edit Profile</h3>
      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label fw-bold text-muted">Full Name</label>
          <input type="text" name="name" minlength="3" maxlength="40"
                 oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')"
                 class="form-control rounded-pill"
                 value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold text-muted">Email</label>
          <input type="email" name="email" minlength="4" maxlength="40"
                 class="form-control rounded-pill"
                 value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold text-muted">Phone</label>
          <input type="text" name="phone" minlength="11" maxlength="11"
                 oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                 class="form-control rounded-pill"
                 value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary rounded-pill px-5">Save Changes</button>
          <a href="profile.php" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include('footer.php'); ?>
