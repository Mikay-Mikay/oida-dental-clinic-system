<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="assets/photo/logo.jpg" alt="Logo">
        <h2>M&A Oida Dental Clinic</h2>
        <i class="fa-solid fa-bars menu-toggle" id="menuToggle"></i>
    </div>
    <div class="sidebar-profile">
    <i class="fa-solid fa-user-circle" style="font-size: 80px; color: #ecf0f1;"></i>
    <h3>
    <?php 
    // Display the admin's name from the session
    echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';
    ?>
</h3>
    <p>Professional Dentist</p>
</div>
    <ul class="sidebar-menu">
    <li class="menu-item active" data-target="dashboard">
        <i class="fa-solid fa-house"></i> Dashboard
    </li>
    <li class="menu-item" data-target="appointments">
        <i class="fa-solid fa-calendar"></i> Appointments
    </li>
    <li class="menu-item" data-target="patient-records">
        <i class="fa-solid fa-folder"></i> Patient Records
    </li>
    <li class="menu-item" data-target="patient-feedback">
        <i class="fa-solid fa-comment"></i> Patient Feedback
    </li>
    <li class="menu-item" data-target="schedule">
        <i class="fa-solid fa-calendar-days"></i> Dentist & Staff Schedule
    </li>
    <li class="menu-item" data-target="staff-management">
        <i class="fa-solid fa-users"></i> Staff Management
    </li>
    <li class="menu-item" data-target="access-request">
        <i class="fa-solid fa-key"></i> Request for Access
    </li>
    <li class="menu-item" data-target="help">
        <i class="fa-solid fa-circle-question"></i> Help & Support
    </li>
    </ul>
    <ul class="sidebar-menu">
        <li>
            <i class="fa-solid fa-right-from-bracket"></i> <a href="logout.php" style="color: #e74c3c;">Logout</a>
        </li>
    </ul>
</div>

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
            <div class="dashboard-overview">
    <?php
    // Example data (replace with dynamic data from your database)
    $overviewData = [
        [
            'icon' => 'fa-users',
            'count' => 120,
            'label' => 'Patients',
            'increase' => '+40',
            'period' => 'This Month'
        ],
        [
            'icon' => 'fa-calendar-check',
            'count' => 40,
            'label' => 'Appointments',
            'increase' => '+20',
            'period' => 'This Month'
        ]
    ];
    
    // Loop through the data to generate cards
    foreach ($overviewData as $data) {
        echo '
        <div class="overview-card">
            <div class="overview-header">
                <div class="overview-icon">
                    <i class="fa-solid ' . $data['icon'] . '"></i>
                </div>
                <h2>' . $data['count'] . '</h2>
            </div>
            <p class="overview-label">' . $data['label'] . '</p>
            <div class="overview-footer">
                <span class="stat-increase">' . $data['increase'] . '</span>
                <span class="stat-period">' . $data['period'] . '</span>
            </div>
            <div class="overview-chart">
                <img src="assets/photo/growth.png" alt="Chart Icon">
            </div>
        </div>';
    }
    ?>
</div>
            </section>

            <div class="appointments-header">
            <button id="showAppointments" class="toggle-btn" style="display: none;">
        <i class="fa-solid fa-arrow-right"></i> Show
    </button>
</div>
<section class="appointments-section">
<button id="hideAppointments" class="toggle-btn">
        <i class="fa-solid fa-arrow-left"></i> Hide
    </button>
    <h2>Appointments</h2>
    <div class="appointments-container">
        <div class="calendar">
            <div class="calendar__header">
                <button class="prev-month">&lt;</button>
                <span class="calendar__month__year">
                    <span class="calendar__month">April</span>
                    <span class="calendar__year">2025</span>
                </span>
                <button class="next-month">&gt;</button>
            </div>
            <div class="calendar__body">
                <div class="calendar__days">
                    <!-- Days will be dynamically generated -->
                </div>
            </div>
        </div>
        <div class="appointments-list">
            <!-- Dynamic appointments will be added here -->
        </div>
    </div>
</section>

<section class="available-dentists">
    <h2>Available Dentists</h2>
    <div class="dentist-list">
    <div class="filter-buttons">
    <button data-filter="online">Show Online</button>
    <button data-filter="offline">Show Offline</button>
    <button data-filter="all">Show All</button>
</div>

        <?php
        // Connect to the database
        require 'db.php'; // Ensure this file contains your database connection

        // Query to fetch dentists
        $query = "SELECT name, photo, specialty, status FROM dentists";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Loop through the results and display dentists
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="dentist-card" data-status="' . $row['status'] . '">
                    <img src="' . $row['photo'] . '" alt="' . $row['name'] . '">
                    <p>' . $row['name'] . '</p>
                    <span>' . $row['specialty'] . '</span>
                    <span class="dentist-status ' . $row['status'] . '">' . ucfirst($row['status']) . '</span>
                </div>';
            }
        } else {
            echo '<p>No dentists available at the moment.</p>';
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</section>

<section class="patients-overview">
    <h2>Patients</h2>
    <div class="patients-stats">
    <div class="stat-card">
            <h3>New Patients</h3>
            <p id="newPatientsCount">0</p>
            <span id="newPatient">%</span>
        </div>
        <div class="stat-card">
            <h3>Male Patients</h3>
            <p id="maleCount">0</p>
            <span id="malePercent">0%</span>
        </div>
        <div class="stat-card">
            <h3>Female Patients</h3>
            <p id="femaleCount">0</p>
            <span id="femalePercent">0%</span>
        </div>
        <div class="stat-card">
            <h3>Other Patients</h3>
            <p id="otherCount">0</p>
            <span id="otherPercent">0%</span>
        </div>
    </div>
    <div class="patients-chart">
        <h3>Today's Appointments</h3>
        <canvas id="patientsPieChart"></canvas>
    </div>
</section>


<h2>Appointments</h2>
<div class="year-selector">
  <button onclick="updateChart(2024)">Year 2024</button>
  <button class="active" onclick="updateChart(2025)">Year 2025</button>
  <button onclick="updateChart(2026)">Year 2026</button>
</div>
<canvas id="appointmentsChart" height="100"></canvas>

    <section class="patient-visits-section">
    <div class="visits-card">
        <div class="visits-header">
            <h2>Patient Visits</h2>
            <span class="date">April 2025</span>
        </div>
        <div class="visits-content">
            <div class="table-responsive">
                <table class="visits-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient Name</th>
                            <th>Age</th>
                            <th>Date of Birth</th>
                            <th>Services</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td class="patient-info">
                                <img src="assets/photo/patients/victoria.jpg" alt="Victoria">
                                Victoria Anne Garcia
                            </td>
                            <td>27</td>
                            <td>06/11/97</td>
                            <td>Dental Check-up & Consultation,<br>Panoramic X-Ray/Full Mouth X-Ray</td>
                            <td>10:00 AM</td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td class="patient-info">
                                <img src="assets/photo/patients/mikaela.jpg" alt="Mikaela">
                                Mikaela Somera
                            </td>
                            <td>21</td>
                            <td>12/26/03</td>
                            <td>Metal Braces</td>
                            <td>2:00 PM</td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td class="patient-info">
                                <img src="assets/photo/patients/william.jpg" alt="William">
                                William Marcus Lee
                            </td>
                            <td>24</td>
                            <td>02/14/01</td>
                            <td>Dental Crown</td>
                            <td>3:00 PM</td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td class="patient-info">
                                <img src="assets/photo/patients/adrielle.jpg" alt="Adrielle">
                                Adrielle Mendoza
                            </td>
                            <td>20</td>
                            <td>11/22/04</td>
                            <td>Dental Crown</td>
                            <td>3:00 PM</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <div class="showing-info">Showing Page 1 of 1</div>
                <div class="pagination">
                    <button class="prev-btn" disabled>Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="next-btn" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="appointments-overview content-section" id="appointments">
        <div class="appointments-card">
            <h2>Appointments</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search..." class="search-input">
                <button class="search-btn"><i class="fa-solid fa-search"></i></button>
            </div>
            
            <div class="status-filters">
                <button class="filter-btn pending active">Pending</button>
                <button class="filter-btn upcoming">Upcoming</button>
                <button class="filter-btn rescheduled">Rescheduled</button>
                <button class="filter-btn cancelled">Cancelled</button>
            </div>

            <div class="appointments-table">
                <table>
                    <thead>
                        <tr>
                            <th>Booking Ref No.</th>
                            <th>Patient Name</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        

                    <tr class="pending">
        <td>MDC-20250407-289</td>
        <td>Mikaela Somera</td>
        <td>Metal Braces</td>
        <td>April 22, 2025</td>
        <td>2:00 PM</td>
        <td class="action-buttons">
            <button class="approve-btn" title="Approve"><i class="fa-solid fa-check"></i></button>
            <button class="cancel-btn" title="Cancel"><i class="fa-solid fa-times"></i></button>
            <button class="details-btn">Details</button>
        </td>
    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal for Appointment Details -->
<div id="approvalModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Approval of Appointment</h2>
            <p>Are you sure you want to approve this appointment for <span id="patientName">Mikaela Somera</span> on <span id="appointmentDate">April 22, 2025 at 2:00 PM</span>?</p>
            <div class="modal-actions">
                <button id="cancelBtn" class="cancel-btn">Cancel</button>
                <button id="submitBtn" class="submit-btn">Submit</button>
            </div>
        </div>
    </div>


    <!-- Modal for Approval Success -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <h2>Approval Successful!</h2>
        <p>The appointment for <span id="successPatientName">Mikaela Somera</span> on <span id="successAppointmentDate">April 22, 2025 at 2:00 PM</span> has been approved. The patient will be notified via Email/SMS.</p>
        <button id="okBtn" class="submit-btn">OK</button>
        </div>

        </main>
    </div>

    <script src="assets/js/dashboard.js?v=1.0"></script>
</body>
</html>