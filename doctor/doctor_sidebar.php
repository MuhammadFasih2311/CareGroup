<aside class="sidebar admin-sidebar d-none d-lg-block">
    <ul class="nav flex-column">

        <li class="nav-item" data-aos="zoom-in">
            <a class="nav-link <?= activePage('dashboard.php',$current_page) ?>" href="dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item" data-aos="zoom-in" data-aos-delay="100">
            <a class="nav-link <?= activePage('appointments.php',$current_page) ?>" href="appointments.php">
                <i class="bi bi-calendar2-check me-2"></i> My Appointments
            </a>
        </li>

        <div class="logout-line"></div>

        <li class="nav-item mt-2" data-aos="zoom-in" data-aos-delay="200">
            <a class="nav-link logout-link" href="../logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>

    </ul>
</aside>

<aside id="mobileSidebar" class="mobile-sidebar d-lg-none">

    <div class="mobile-header">
        <span class="left">Doctor <span class="text-warning">Panel</span></span>
        <button id="mobileClose" class="close-btn">✕</button>
    </div>

    <ul class="nav flex-column p-3">

        <li class="nav-item">
            <a class="nav-link <?= activePage('dashboard.php',$current_page) ?>" href="dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= activePage('appointments.php',$current_page) ?>" href="appointments.php">
                <i class="bi bi-calendar2-check me-2"></i> My Appointments
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
