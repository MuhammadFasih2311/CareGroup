<?php
include('connect.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  if (strlen($password) < 8) {
    $message = "Password must be at least 8 characters long.";
  } elseif ($password !== $confirm) {
    $message = "Passwords do not match.";
  } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
    $message = "Phone number must be exactly 11 digits.";
  } else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $hashed);

    if ($stmt->execute()) {
      header("Location: login.php?registered=true");
      exit();
    } else {
      $message = "Email already exists or registration failed.";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
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
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .form-control {
      background: rgba(255,255,255,0.2);
      border: none;
      color: white;
    }

    .form-control:focus {
      background: rgba(255,255,255,0.3);
      box-shadow: none;
      color: white;
    }

    .eye-icon {
      cursor: pointer;
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: white;
      font-size: 1.2rem;
      user-select: none;
    }

    .alert {
      position: relative;
      border-radius: 12px;
    }

    .alert button.close {
      position: absolute;
      right: 10px;
      top: 5px;
      background: transparent;
      border: none;
      font-size: 1.6rem; 
      color: inherit;
      line-height: 1;
      opacity: 0.9;
      transition: 0.2s;
    }

    .alert button.close:hover {
      opacity: 1;
      transform: scale(1.2);
    }

    .password-hint {
      font-size: 0.9rem;
      margin-top: 5px;
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

    .text-success-light {
      color: #4ade80 !important; 
    }
  </style>
</head>
<body>
  <div class="glass-box" data-aos="zoom-in">
    <h3 class="text-center mb-4">Create Account</h3>

    <?php if ($message): ?>
      <div class="alert alert-warning text-center alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">&times;</button>
      </div>
    <?php endif; ?>

    <form method="POST" id="signupForm">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required minlength="3" maxlength="40" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');">
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required minlength="4" maxlength="40">
      </div>
      <div class="mb-3">
      <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone (11 digits)" required maxlength="11" minlength="11" pattern="[0-9]{11}" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
    </div>
      <div class="mb-3 position-relative">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password (min 8 chars)" required>
        <span id="eye1" class="eye-icon" onclick="togglePass('password','eye1')">🙈</span>
        <div id="passHint" class="password-hint text-warning"></div>
      </div>

      <div class="mb-3 position-relative">
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
        <span id="eye2" class="eye-icon" onclick="togglePass('confirm_password','eye2')">🙈</span>
        <div id="confirmHint" class="password-hint text-warning"></div>
      </div>

      <button type="submit" class="btn btn-light w-100 fw-bold">Sign Up</button>
      <p class="text-center mt-3">Already have an account? <a href="login.php" class="text-warning text-decoration-none">Login</a></p>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1100, 
    easing: 'ease-in-out',
    offset: 100  
  });
</script>

  <script>
    function togglePass(id, eyeId) {
      const input = document.getElementById(id);
      const eye = document.getElementById(eyeId);
      if (input.type === 'password') {
        input.type = 'text';
        eye.textContent = '👁️';
      } else {
        input.type = 'password';
        eye.textContent = '🙈';
      }
    }

    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const passHint = document.getElementById('passHint');
    const confirmHint = document.getElementById('confirmHint');

    password.addEventListener('input', () => {
      if (password.value.length < 8) {
        passHint.textContent = "Password must be at least 8 characters.";
        passHint.classList.add("text-warning");
        passHint.classList.remove("text-success-light");
      } else {
        passHint.textContent = "Password looks good ✅";
        passHint.classList.remove("text-warning");
        passHint.classList.add("text-success-light");
      }
    });

    confirmPassword.addEventListener('input', () => {
      if (confirmPassword.value !== password.value) {
        confirmHint.textContent = "Passwords do not match.";
        confirmHint.classList.add("text-warning");
        confirmHint.classList.remove("text-success-light");
      } else {
        confirmHint.textContent = "Passwords match ✅";
        confirmHint.classList.remove("text-warning");
        confirmHint.classList.add("text-success-light");
      }
    });

    setTimeout(() => {
      const alert = document.querySelector('.alert');
      if (alert) {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
      }
    }, 5000);
  </script>
</body>
</html>
