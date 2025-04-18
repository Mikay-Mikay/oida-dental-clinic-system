<?php
// Start the session
session_start();

// Check if the admin is logged in
/*if (!isset($_SESSION['admin_id']) || !isset($_SESSION['email'])) {
    header("Location: admin_login.php");
    exit();
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="dashboard.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="assets/photo/logo.jpg" alt="Logo">
            <h2>M&A Oida Dental Clinic</h2>
            <i class="fa-solid fa-bars menu-toggle" id="menuToggle"></i>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><i class="fa-solid fa-house"></i> Dashboard</li>
            <li><i class="fa-solid fa-calendar-check"></i> Appointments</li>
            <li><i class="fa-solid fa-file-medical"></i> Patient Records</li>
            <li><i class="fa-solid fa-comments"></i> Patient Feedback</li>
            <li><i class="fa-solid fa-user-md"></i> Dentist & Staff Schedule</li>
            <li><i class="fa-solid fa-users"></i> Staff Management</li>
            <li><i class="fa-solid fa-key"></i> Request for Access</li>
            <li><i class="fa-solid fa-question-circle"></i> Help & Support</li>
            <li><a href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
        <i class="fa-solid fa-bars"></i>
    </button>

        <!-- Main Content -->
        <main class="main-content">
        <header class="main-header">
    <div class="header-left">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Admin ID: <?php echo $_SESSION['admin_id']; ?></p>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
        <p>Maligaya Park Branch</p>
    </div>
    <div class="header-right">
        <i class="fa-solid fa-calendar"></i>
        <i class="fa-solid fa-clipboard-list"></i>
        <i class="fa-regular fa-bell"></i>
        <i class="fa-regular fa-circle-user"></i>
    </div>
</header>

<div class="breadcrumb">
    <div class="breadcrumb-left">
        <i class="fa-solid fa-house"></i>
        <span>/ Dashboard</span>
    </div>
    <div class="date-picker">
        <i class="fa-solid fa-calendar"></i>
        <span>04/01/25 – 04/30/25</span>
    </div>
</div>

            <section class="dashboard-overview">
                <div class="overview-card">
                    <h2>120 Patients</h2>
                    <p>+40 This Month</p>
                </div>
                <div class="overview-card">
                    <h2>40 Appointments</h2>
                    <p>+20 This Month</p>
                </div>
            </section>

            <section class="available-dentists">
                <h2>Available Dentists</h2>
                <div class="dentist-list">
                    <div class="dentist-card">
                        <img src="assets/photo/dentist1.jpg" alt="Dr. Marcial Oida">
                        <p>Dr. Marcial Oida</p>
                        <span>Professional Dentist - 40 years</span>
                    </div>
                    <div class="dentist-card">
                        <img src="assets/photo/dentist2.jpg" alt="Dr. Ardeen Dofiles Oida">
                        <p>Dr. Ardeen Dofiles Oida</p>
                        <span>Professional Dentist - 24 years</span>
                    </div>
                    <div class="dentist-card">
                        <img src="assets/photo/dentist3.jpg" alt="Dr. Maribel Adajar">
                        <p>Dr. Maribel Adajar</p>
                        <span>Professional Dentist - 30 years</span>
                    </div>
                </div>
            </section>

            <section class="patients-overview">
                <h2>Patients</h2>
                <div class="patients-stats">
                    <div class="stat-card">
                        <h3>New Patients</h3>
                        <p>40 <span class="stat-increase">↑</span></p>
                    </div>
                    <div class="stat-card">
                        <h3>Male Patients</h3>
                        <p>57</p>
                    </div>
                    <div class="stat-card">
                        <h3>Female Patients</h3>
                        <p>48</p>
                    </div>
                    <div class="stat-card">
                        <h3>Other Patients</h3>
                        <p>20</p>
                    </div>
                </div>
                <div class="patients-chart">
                    <h3>In April 2025</h3>
                    <img src="assets/photo/piechart.png" alt="Patients Chart">
                </div>
            </section>

            <section class="appointments">
                <h2>Appointments</h2>
                <div class="appointments-list">
                    <div class="appointment-card">
                        <p>Victoria Anne Garcia</p>
                        <span>10:00 AM</span>
                    </div>
                    <div class="appointment-card">
                        <p>Mikaela Somera</p>
                        <span>2:00 PM</span>
                    </div>
                    <div class="appointment-card">
                        <p>William Marcus Lee</p>
                        <span>3:00 PM</span>
                    </div>
                    <div class="appointment-card">
                        <p>Adrielle Mendoza</p>
                        <span>4:00 PM</span>
                    </div>
                    <div class="appointment-card">
                        <p>Dr. Ardeen Dofiles Oida</p>
                        <span>Monday, April 14, 2025 (9:00 AM)</span>
                    </div>
                    <div class="appointment-card">
                        <p>Wilma Ayao</p>
                        <span>Monday, April 14, 2025 (10:00 AM)</span>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>