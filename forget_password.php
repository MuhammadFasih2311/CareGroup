<?php
include('connect.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $doctor = $stmt->get_result()->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $conn->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");
        header("Location: change_password.php?token=$token");
        exit();
    } elseif ($doctor) {
        $token = bin2hex(random_bytes(16));
        $conn->query("UPDATE doctors SET reset_token = '$token' WHERE email = '$email'");
        header("Location: change_password.php?token=$token");
        exit();
    } elseif ($admin) {
        $token = bin2hex(random_bytes(16));
        $conn->query("UPDATE admin SET reset_token = '$token' WHERE email = '$email'");
        header("Location: change_password.php?token=$token");
        exit();
    } else {
        $message = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
  body {
    background: linear-gradient(to right, #2563eb, #ef4444);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Poppins', sans-serif;
  }
  .glass-box {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 420px;
    color: white;
  }
  .form-control {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
  }
  .btn-light {
      background: rgba(255,255,255,0.25);
      color: white;
      border: none;
      font-weight: 600;
      transition: all 0.4s ease;
      backdrop-filter: blur(5px);
    }

    .btn-light:hover {
      background: linear-gradient(to right, #2563eb, #ef4444);
      color: white;
      transform: scale(1.03);
      box-shadow: 0 0 10px rgba(255,255,255,0.3);
    }
</style>
</head>
<body>
<div class="glass-box" data-aos="zoom-in">
  <h3 class="text-center mb-4">Forgot Password</h3>

  <?php if ($message): ?>
    <div class="alert alert-danger text-center"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
    </div>
    <button type="submit" class="btn btn-light w-100 fw-bold">Next</button>
  </form>
</div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1100, 
    easing: 'ease-in-out',
    offset: 100  
  });
</script>

</body>
</html>
