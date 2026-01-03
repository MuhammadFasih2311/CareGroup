<?php
session_start();
include('connect.php');
include('auto_login.php');

$message = "";

if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
  $message = "<div class='alert alert-success text-center'>Signup successful! Please login.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie("remember_token", $token, time() + 86400*30, "/");
            $conn->query("UPDATE admin SET remember_token='$token' WHERE id={$admin['id']}");
        }

        header("Location: admin/dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $doctor = $stmt->get_result()->fetch_assoc();

    if ($doctor && password_verify($password, $doctor['password'])) {

        $_SESSION['doctor_id'] = $doctor['id'];
        $_SESSION['doctor_name'] = $doctor['name'];

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie("remember_token", $token, time() + 86400*30, "/");
            $conn->query("UPDATE doctors SET remember_token='$token' WHERE id={$doctor['id']}");
        }

        header("Location: doctor/dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];  
    $_SESSION['user_phone'] = $user['phone'];  

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie("remember_token", $token, time() + 86400*30, "/");
        $conn->query("UPDATE users SET remember_token='$token' WHERE id={$user['id']}");
    }

    header("Location: index.php");
    exit();
}


    $message = "<div class='alert alert-danger text-center'>Invalid email or password.</div>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 420px;
      color: white;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
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
  <div class="glass-box"data-aos="zoom-in" >
    <h3 class="text-center mb-4">Login</h3>

    <?php if ($message) echo $message; ?>

    <form method="POST">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3 position-relative">
        <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Password" required>
        <span id="eyeToggle" class="eye-icon">🙈</span>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-light w-100 fw-bold">Login</button>
      <p class="text-center mt-3">
        <a href="forget_password.php" class="text-warning text-decoration-none">Forgot Password?</a>
      </p>
      <p class="text-center">Don’t have an account? 
        <a href="signup.php" class="text-warning text-decoration-none">Sign Up</a>
      </p>
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
 <script>
    const pass = document.getElementById('loginPassword');
    const eye = document.getElementById('eyeToggle');
    eye.onclick = () => {
      if (pass.type === 'password') {
        pass.type = 'text';
        eye.textContent = '👁️';
      } else {
        pass.type = 'password';
        eye.textContent = '🙈';
      }
    };

    setTimeout(() => {
      const alert = document.querySelector('.alert');
      if (alert) alert.remove();
    }, 5000);
  </script>
</body>
</html>
