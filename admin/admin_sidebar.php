<aside class="sidebar d-none d-lg-block">
    
    <ul class="nav flex-column">

        <li class="nav-item" data-aos="zoom-in">
            <a class="nav-link <?= activePage('dashboard.php', $current_page) ?>" href="dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item" data-aos="zoom-in" data-aos-delay="100">
            <a class="nav-link <?= activePage('appointments.php', $current_page) ?>" href="appointments.php">
                <i class="bi bi-calendar2-check me-2"></i> Appointments
            </a>
        </li>

        <li class="nav-item" data-aos="zoom-in" data-aos-delay="200">
            <a class="nav-link <?= activePage('doctors.php', $current_page) ?>" href="doctors.php">
                <i class="bi bi-person-badge me-2"></i> Doctors
            </a>
        </li>

        <li class="nav-item" data-aos="zoom-in" data-aos-delay="300">
            <a class="nav-link <?= activePage('diseases.php', $current_page) ?>" href="diseases.php">
                <i class="bi bi-virus me-2"></i> Diseases
            </a>
        </li>

        <li class="nav-item" data-aos="zoom-in" data-aos-delay="400">
            <a class="nav-link <?= activePage('hospitals.php', $current_page) ?>" href="hospitals.php">
                <i class="bi bi-hospital me-2"></i> Hospitals
            </a>
        </li>

        <li class="nav-item" data-aos="zoom-in" data-aos-delay="500">
            <a class="nav-link <?= activePage('users.php', $current_page) ?>" href="users.php">
                <i class="bi bi-people me-2"></i> Patients / Users
            </a>
        </li>
        <li class="nav-item" data-aos="zoom-in" data-aos-delay="600">
            <a class="nav-link <?= activePage('admin_messages_control.php', $current_page) ?>" href="admin_messages_control.php">
                <i class="bi bi-people me-2"></i> View Messages
            </a>
        </li>

        <div class="logout-line"></div>

        <li class="nav-item mt-2" data-aos="zoom-in" data-aos-delay="700"> 
            <a class="nav-link logout-link" href="../logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>

    </ul>

</aside>


<!--  MOBILE SIDEBAR -->
<aside id="mobileSidebar" class="mobile-sidebar d-lg-none">

    <div class="mobile-header">
       <span class="left">CARE Gr<span class="text-warning">oup Admin</span></span>
        <button id="mobileClose" class="close-btn">✕</button>
    </div>

    <ul class="nav flex-column p-3">

        <li class="nav-item">
            <a class="nav-link <?= activePage('dashboard.php', $current_page) ?>" href="dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= activePage('appointments.php', $current_page) ?>" href="appointments.php">
                <i class="bi bi-calendar2-check me-2"></i> Appointments
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= activePage('doctors.php', $current_page) ?>" href="doctors.php">
                <i class="bi bi-person-badge me-2"></i> Doctors
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= activePage('diseases.php', $current_page) ?>" href="diseases.php">
                <i class="bi bi-virus me-2"></i> Diseases
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= activePage('hospitals.php', $current_page) ?>" href="hospitals.php">
                <i class="bi bi-hospital me-2"></i> Hospitals
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= activePage('users.php', $current_page) ?>" href="users.php">
                <i class="bi bi-people me-2"></i> Patients / Users
            </a>
        </li>

        <div class="logout-line"></div>

        <li class="nav-item">
            <a class="nav-link text-warning" href="../logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>

    </ul>

</aside>

<main class="main-content">
