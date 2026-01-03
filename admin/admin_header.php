<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
function activePage($page, $current_page) {
    return $page === $current_page ? "active-link" : "";
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CARE Group - Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<nav class="admin-navbar px-4 d-flex justify-content-between align-items-center">

    <div class="d-flex align-items-center" >
        <button id="sidebarToggle" class="btn custom-toggler me-3 d-lg-none" data-aos="fade-right" data-aos-delay="100">
            <i class="bi bi-list text-white fs-3"></i>
        </button>

        <h4 class="brand fw-bold text-white m-0 d-none d-lg-block brand-hover" data-aos="fade-right">
            CARE Gr<span class="text-warning">oup Admin</span>
        </h4>

        <h5 class="mobile-title text-white fw-bold d-lg-none m-0 brand-hover" data-aos="fade-right">
            CARE Gr<span class="text-warning">oup Admin</span>
        </h5>
    </div>
    <div class="d-flex align-items-center gap-3" data-aos="fade-left">
        <div class="welcome-text text-white d-none d-sm-block">
            <span class="">Wellcome,</span>
            <strong class="text-warning"><?php echo $_SESSION['admin_name']; ?></strong>
        </div>
        <div class="dropdown">
            <button id="profileDropdownBtn" class="btn admin-profile-btn d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="avatar-wrapper avatar-hover">
                    <img src="../images/admin-avatar.jpg" alt="Admin" class="admin-avatar">
                </div>
                <i id="dropdownIcon" class="bi bi-chevron-down text-white small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg admin-dropdown">
                <li>
                    <a class="dropdown-item" href="admin_profile.php">
                        <i class="bi bi-person-circle me-2"></i> Profile
                    </a>
                </li>
                <li><div class="dropdown-divider"></div></li>
                <li>
                    <a class="dropdown-item logout-drop" href="../logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
