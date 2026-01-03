<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CARE Group - Medical Services</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

  <style>
    .navbar-custom .dropdown-menu {
      background: linear-gradient(145deg, #ff4d4d, #ff8080);
      border: none;
      border-radius: 12px;
      padding: 8px 0;
      box-shadow: 0 8px 20px rgba(255, 0, 0, 0.15);
      transition: all 0.3s ease-in-out;
    }

    .navbar-custom .dropdown-item {
      color: #fff;
      font-weight: 500;
      padding: 10px 18px;
      transition: all 0.3s ease;
      border-radius: 8px;
    }

    .navbar-custom .dropdown-item i {
      color: #fff !important;
    }

    .navbar-custom .dropdown-item:hover {
      background: linear-gradient(90deg, #ff1a1a, #e60000);
      color: #fff !important;
      transform: translateX(5px);
      box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
    }

    .dropdown-divider {
      border-top: 1px solid rgba(255, 255, 255, 0.3);
    }

    .navbar-custom .dropdown-item.text-danger {
      color: #fff !important;
      font-weight: 600;
      transition: all 0.3s ease-in-out;
    }

    .navbar-custom .dropdown-item.text-danger:hover {
      color: #fff !important;
      background: linear-gradient(90deg, #ff4d4d, #e60000);
      box-shadow: 0 0 12px rgba(255, 0, 0, 0.4);
      transform: scale(1.05);
    }

    .navbar-custom .nav-link.text-warning:hover {
      text-shadow: 0 0 8px rgba(255, 193, 7, 0.8);
    }
    @media (max-width: 992px) {
  .navbar-collapse {
    max-height: 80vh !important;
    overflow-y: auto !important;
  }

  body.off-scroll {
    overflow: hidden !important;
  }
} 
[data-aos] {
  overflow: visible !important;
}

  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php" data-aos="fade-right">
      <i class="fa-solid fa-heart-pulse me-2 text-danger"></i>
      <span class="brand-text">CARE <span class="text-highlight">Group</span></span>
    </a>

    <button class="custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" data-aos="fade-down">
      <div class="toggler-icon">
        <span></span><span></span><span></span>
      </div>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <li class="nav-item" data-aos="fade-down" data-aos-delay="100">
          <a class="nav-link <?= ($currentPage == 'index.php') ? 'active' : '' ?>" href="index.php">Home</a>
        </li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="200">
          <a class="nav-link <?= ($currentPage == 'about.php') ? 'active' : '' ?>" href="about.php">About</a>
        </li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="300">
          <a class="nav-link <?= ($currentPage == 'appointment.php') ? 'active' : '' ?>" href="appointment.php">Appointment</a>
        </li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="400">
          <a class="nav-link <?= ($currentPage == 'contacts.php') ? 'active' : '' ?>" href="contacts.php">Contact</a>
        </li>

        <li class="nav-item dropdown" data-aos="fade-down" data-aos-delay="500">
          <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['doctors.php', 'diseases.php', 'locations.php']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            More
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 border-0">
            <li><a class="dropdown-item <?= ($currentPage == 'doctors.php') ? 'active' : '' ?>" href="doctors.php"><i class="fa-solid fa-user-doctor me-2"></i>Doctors</a></li>
            <li><a class="dropdown-item <?= ($currentPage == 'diseases.php') ? 'active' : '' ?>" href="diseases.php"><i class="fa-solid fa-virus-covid me-2"></i>Diseases</a></li>
            <li><a class="dropdown-item <?= ($currentPage == 'locations.php') ? 'active' : '' ?>" href="locations.php"><i class="fa-solid fa-hospital me-2"></i>Hospitals</a></li>
            <li><a class="dropdown-item <?= ($currentPage == 'services.php') ? 'active' : '' ?>" href="services.php"><i class="fa-solid fa-stethoscope me-2"></i>Services</a></li>
          </ul>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item dropdown ms-lg-3" data-aos="fade-down" data-aos-delay="600">
            <a class="nav-link dropdown-toggle text-warning fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-circle-user me-1"></i> <?= htmlspecialchars($_SESSION['user_name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 border-0">
              <li>
                <a class="dropdown-item" href="profile.php">
                  <i class="fa-solid fa-user-circle me-2 text-primary"></i>Profile
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="my_appointments.php">
                  <i class="fa-solid fa-user-circle me-2 text-primary"></i>My Appointemnts
                </a>
              </li>
              <li><hr class="dropdown-divider bg-light"></li>
              <li>
                <a class="dropdown-item text-danger fw-semibold" href="logout.php">
                  <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                </a>
              </li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item" data-aos="fade-down" data-aos-delay="600">
            <a class="btn btn-login ms-lg-3" href="login.php">
              <i class="fa-solid fa-right-to-bracket me-1"></i>Login
            </a>
          </li>
          <li class="nav-item" data-aos="fade-down" data-aos-delay="700">
            <a class="btn btn-register ms-2" href="signup.php">
              <i class="fa-solid fa-user-plus me-1"></i>Register
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {

  const navbar = document.querySelector(".navbar-custom");
  const navbarCollapse = document.querySelector("#navbarNav");
  const toggler = document.querySelector(".custom-toggler");

  function handleScroll() {
    let currentY = window.scrollY;

    if (currentY > 50) {
      navbar.classList.add("scrolled-force");
    } else {
      if (!navbarCollapse.classList.contains("show")) {
        navbar.classList.remove("scrolled-force");
      }
    }
  }

  window.addEventListener("scroll", handleScroll);


  navbarCollapse.addEventListener("show.bs.collapse", function () {
    toggler.classList.add("active"); 

  navbarCollapse.addEventListener("hide.bs.collapse", function () {
    toggler.classList.remove("active");  
  });

});
})
</script>

