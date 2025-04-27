<?php
require_once('db.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}
// Dynamic patient count
$patientCount = 0;
$patientThisMonth = 0;
// Total patients
$sql = "SELECT COUNT(*) as total FROM patients";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $patientCount = $row['total'];
}
// Patients registered this month
$sqlMonth = "SELECT COUNT(*) as month_total FROM patients WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
$resultMonth = $conn->query($sqlMonth);
if ($resultMonth && $rowMonth = $resultMonth->fetch_assoc()) {
    $patientThisMonth = $rowMonth['month_total'];
}
// Appointments count
$appointmentCount = 0;
$appointmentThisMonth = 0;
// Total appointments (exclude cancelled)
$sql = "SELECT COUNT(*) as total FROM appointments WHERE status != 'cancelled'";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $appointmentCount = $row['total'];
}
// Appointments this month (exclude cancelled)
$sqlMonth = "SELECT COUNT(*) as month_total FROM appointments WHERE status != 'cancelled' AND MONTH(appointment_date) = MONTH(CURRENT_DATE()) AND YEAR(appointment_date) = YEAR(CURRENT_DATE())";
$resultMonth = $conn->query($sqlMonth);
if ($resultMonth && $rowMonth = $resultMonth->fetch_assoc()) {
    $appointmentThisMonth = $rowMonth['month_total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <title>M&amp;A Oida Dental Clinic Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
</head>
<body class="bg-white text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="flex flex-col bg-white border-r border-gray-200 w-64 min-w-[256px] py-6 px-4">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center space-x-2">
                    <img alt="M&amp;A Oida Dental Clinic logo" class="w-8 h-8" src="assets/photo/logo.jpg"/>
                    <span class="text-sm font-semibold text-gray-900 whitespace-nowrap">
                        M&amp;A Oida Dental Clinic
                    </span>
                </div>
                <button aria-label="Toggle menu" class="text-blue-600 hover:text-blue-700 focus:outline-none">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>
            
            <!-- Profile Section -->
            <div class="flex flex-col items-center mb-8">
                <img alt="Profile photo" class="rounded-full w-24 h-24 object-cover mb-2" 
                     src="assets/photo/me.jpg"/>
                <h3 class="text-center text-sm font-semibold text-gray-900 leading-tight">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Dr. Ardeen Dofiles Oida'); ?>
                </h3>
                <p class="text-center text-xs text-gray-500 mt-1">
                    Professional Dentist
                </p>
            </div>

    <nav class="flex flex-col space-y-2 text-gray-700 text-sm font-medium">
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 text-blue-900" href="#dashboard-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-home">
       </i>
      </div>
      <span>
       Dashboard
      </span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#appointments-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-calendar-alt">
       </i>
      </div>
      <span>
       Appointments
      </span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#patient-records-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-user-injured">
       </i>
      </div>
      <span>
       Patient Records
      </span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#feedback-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-comment-alt">
       </i>
      </div>
      <span>
       Patient Feedback
      </span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#schedule-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-tooth">
       </i>
      </div>
      <span>
       Dentist &amp; Staff Schedule
      </span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#staff-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-users-cog">
       </i>
      </div>
      <span>
       Staff Management
      </span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#access-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-lock">
       </i>
      </div>
      <span>Request for Access</span>
     </a>
     <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#help-section">
      <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
       <i class="fas fa-question-circle">
       </i>
      </div>
      <span>
       Help &amp; Support
      </span>
     </a>
    </nav>
    <a href="admin_login.php" class="mt-auto flex items-center space-x-2 text-red-600 hover:text-red-700 font-semibold text-sm">
    <i class="fas fa-sign-out-alt fa-lg"></i>
    <span>Logout</span>
</a>
   </aside>
   <!-- Main content -->
   <main class="flex-1 flex flex-col overflow-hidden">
    <!-- Top bar -->
    <header class="flex items-center justify-between bg-blue-300 px-6 py-3 border-b border-gray-300">
    <!-- Welcome Message Section -->
    <div class="flex items-center space-x-3 text-gray-900 text-sm font-normal">
        <span>Welcome,</span>
        <span class="font-bold text-gray-900 text-base">
            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Dr. Ardeen Dofiles Oida'); ?>!
        </span>
        <span class="text-gray-900">
            | Maligaya Park Branch
        </span>
    </div>

    <!-- Action Buttons Section -->
    <div class="flex items-center space-x-4">
        <button class="bg-purple-700 text-white text-xs font-semibold rounded-md px-4 py-1 hover:bg-purple-800">
            Walk-in Appointment Form
        </button>
        <div class="flex items-center bg-blue-400 text-gray-900 text-xs font-semibold rounded-md px-3 py-1">
            <i class="fas fa-calendar-alt mr-1"></i>
            <span>
                <?php echo date('m/d/y') . ' - ' . date('m/d/y', strtotime('+1 month')); ?>
            </span>
        </div>
        <button aria-label="Notifications" class="text-gray-900 hover:text-gray-700 focus:outline-none">
            <i class="fas fa-bell fa-lg"></i>
        </button>
        <button aria-label="Clipboard" class="text-gray-900 hover:text-gray-700 focus:outline-none">
            <i class="fas fa-clipboard fa-lg"></i>
        </button>
        <button aria-label="Print" class="text-gray-900 hover:text-gray-700 focus:outline-none">
            <i class="fas fa-print fa-lg"></i>
        </button>
        <img alt="Profile photo of <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Dr. Ardeen Dofiles Oida'); ?>" 
             class="rounded-full w-10 h-10 object-cover" 
             src="assets/photo/me.jpg" />
    </div>
</header>

    <!-- Content area -->
    <div class="flex flex-1 overflow-hidden">
     <!-- Left content -->
     <section class="flex-1 p-6 overflow-y-auto space-y-6 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent">
      <!-- Cards row -->
      <div id="dashboard-section" class="flex flex-col sm:flex-row sm:space-x-6 space-y-4 sm:space-y-0">
       <!-- Patients card -->
       <div class="flex-1 border border-gray-300 rounded-lg p-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
         <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full text-blue-700">
          <i class="fas fa-users fa-lg">
          </i>
         </div>
         <div>
          <p class="text-lg font-semibold text-gray-900">
           <?php echo $patientCount; ?>
          </p>
          <p class="text-xs text-gray-700">
           Patients
          </p>
          <p class="text-xs text-gray-500 mt-1">
           +<?php echo $patientThisMonth; ?>
          </p>
          <p class="text-xs bg-blue-300 text-blue-900 rounded px-2 py-0.5 inline-block mt-0.5">
           This Month
          </p>
         </div>
        </div>
        <div>
         <svg aria-hidden="true" class="inline-block" fill="none" height="48" stroke="#1e40af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="48">
          <path d="M3 3v18h18">
          </path>
          <path d="M18 9l-3 3-2-2-3 6">
          </path>
          <path d="M15 6h3v3">
          </path>
         </svg>
        </div>
       </div>
       <!-- Appointments card -->
       <div class="flex-1 border border-gray-300 rounded-lg p-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
         <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full text-blue-700">
          <i class="fas fa-calendar-check fa-lg">
          </i>
         </div>
         <div>
          <p class="text-lg font-semibold text-gray-900">
           <?php echo $appointmentCount; ?>
          </p>
          <p class="text-xs text-gray-700">
           Appointments
          </p>
          <p class="text-xs text-gray-500 mt-1">
           +<?php echo $appointmentThisMonth; ?>
          </p>
          <p class="text-xs bg-blue-300 text-blue-900 rounded px-2 py-0.5 inline-block mt-0.5">
           This Month
          </p>
         </div>
        </div>
        <div>
         <svg aria-hidden="true" class="inline-block" fill="none" height="48" stroke="#1e40af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="48">
          <path d="M3 3v18h18">
          </path>
          <path d="M18 9l-3 3-2-2-3 6">
          </path>
          <path d="M15 6h3v3">
          </path>
         </svg>
        </div>
       </div>
      </div>
      <!-- Dentist & Dental Helpers -->
      <section class="border border-gray-300 rounded-lg p-3">
       <h2 class="text-sm font-semibold text-gray-900 mb-2">
        Dentist &amp; Dental Helpers
       </h2>
       <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
        <!-- Each person card -->
        <a onclick="showProfile('marcial_oida')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Dr. Marcial Oida, male dentist with short hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Marcial.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Marcial Oida
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('ardeen_oida')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Dr. Ardeen Dofiles Oida, female dentist with brown hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Ardeen.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Ardeen Dofiles Oida
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('maribel_adajar')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Dr. Maribel Adajar, female dentist with pink hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Maribel.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Maribel Adajar
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('joan_flores')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Dr. Joan Gajeto Flores, male dentist with short hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Joan.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Joan Gajeto Flores
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('geraldine_labasan')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Geraldine U. Labasan, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Geraldine.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Geraldine U. Labasan
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('lynneth_mutuan')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Lynneth Mutuan, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Lynneth.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Lynneth Mutuan
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('wilma_ayoo')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Wilma Ayoo, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Wilma.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Wilma Ayoo
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('gemma_velasquez')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Gemma A. Velasquez, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Gemma.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Gemma A. Velasquez
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
        <a onclick="showProfile('jocelyn_darang')" class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50 cursor-pointer">
         <img alt="Jocelyn Darang, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="assets/photo/Jocelyn.webp" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Jocelyn Darang
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400"></i>
        </a>
       </div>
      </section>
      <!-- Profile Modal -->
      <div id="profileModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full relative">
          <button onclick="hideProfileModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
          <div id="profileContent"></div>
        </div>
      </div>
      <script>
      function showProfile(profileId) {
          const profiles = {
              'marcial_oida': {
                  img: 'assets/photo/Marcial.webp',
                  name: 'Dr. Marcial Oida',
                  role: 'Professional Dentist',
                  experience: '24 years in practice',
                  languages: 'Filipino, English',
                  branches: 'Commonwealth Branch, North Fairview Branch, Montalban Branch, Quiapo Branch, Kiko Branch, Naga Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'ardeen_oida': {
                  img: 'assets/photo/Ardeen.webp',
                  name: 'Dr. Ardeen Dofiles Oida',
                  role: 'Professional Dentist',
                  experience: '12 years in practice',
                  languages: 'Filipino, English',
                  branches: 'Maligaya Park Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'maribel_adajar': {
                  img: 'assets/photo/Maribel.webp',
                  name: 'Dr. Maribel Adajar',
                  role: 'Professional Dentist',
                  experience: '10 years in practice',
                  languages: 'Filipino, English',
                  branches: 'Naga Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'joan_flores': {
                  img: 'assets/photo/Joan.webp',
                  name: 'Dr. Joan Gajeto Flores',
                  role: 'Professional Dentist',
                  experience: '8 years in practice',
                  languages: 'Filipino, English',
                  branches: 'Quiapo Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'geraldine_labasan': {
                  img: 'assets/photo/Geraldine.webp',
                  name: 'Geraldine U. Labasan',
                  role: 'Dental Helper',
                  experience: '5 years in practice',
                  languages: 'Filipino',
                  branches: 'Commonwealth Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'lynneth_mutuan': {
                  img: 'assets/photo/Lynneth.webp',
                  name: 'Lynneth Mutuan',
                  role: 'Dental Helper',
                  experience: '3 years in practice',
                  languages: 'Filipino',
                  branches: 'North Fairview Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'wilma_ayoo': {
                  img: 'assets/photo/Wilma.webp',
                  name: 'Wilma Ayoo',
                  role: 'Dental Helper',
                  experience: '6 years in practice',
                  languages: 'Filipino',
                  branches: 'Montalban Branch',
                  hours: 'Monday – Saturday [10:00 AM to 5:00 PM]',
                  status: 'Available'
              },
              'gemma_velasquez': {
                  img: 'assets/photo/Gemma.webp',
                  name: 'Gemma A. Velasquez',
                  role: 'Dental Helper',
                  experience: '4 years in practice',
                  languages: 'Filipino',
                  branches: 'Kiko Branch',
              }
              // Add more profiles as needed
          };
          const p = profiles[profileId];
          document.getElementById('profileContent').innerHTML = `
            <div class="flex items-center space-x-6">
              <img src="${p.img}" class="rounded-full w-28 h-28 object-cover border" />
              <div class="flex-1">
                <div class="flex items-center space-x-2 mb-2">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800" id="statusLabel">${p.status}</span>
                  <div class="flex space-x-1 ml-4">
                    <button onclick=\"setStatus('Available')\" class=\"bg-green-400 text-white px-2 py-1 rounded text-xs\">Available</button>
                    <button onclick=\"setStatus('Unavailable')\" class=\"bg-gray-400 text-white px-2 py-1 rounded text-xs\">Unavailable</button>
                    <button onclick=\"setStatus('In Surgery')\" class=\"bg-red-400 text-white px-2 py-1 rounded text-xs\">In Surgery</button>
                  </div>
                </div>
                <h2 class="text-2xl font-bold mb-1">${p.name}</h2>
                <div class="mb-1"><b>Specialty / Role:</b> ${p.role}</div>
                <div class="mb-1"><b>Years of Experience:</b> ${p.experience}</div>
                <div class="mb-1"><b>Languages Spoken:</b> ${p.languages}</div>
                <div class="mb-1"><b>Clinic Branch Assignment:</b> ${p.branches}</div>
                <div class="mb-1"><b>Working Hours / Availability:</b> ${p.hours}</div>
              </div>
            </div>
          `;
          document.getElementById('profileModal').classList.remove('hidden');
      }
      function hideProfileModal() {
          document.getElementById('profileModal').classList.add('hidden');
      }
      function setStatus(status) {
          document.getElementById('statusLabel').textContent = status;
          // Optionally, update color and send to backend via AJAX
      }
      </script>
      <!-- Patient Visits -->
      <section>
       <h2 class="text-sm font-semibold text-gray-900 mb-2">
        Patient Visits
       </h2>
       <div class="overflow-x-auto border border-gray-300 rounded-lg">
        <table class="w-full text-xs text-left border-collapse">
         <thead class="bg-gray-100 text-gray-700">
          <tr>
           <th class="border border-gray-300 px-2 py-1 w-8">
            #
           </th>
           <th class="border border-gray-300 px-2 py-1 min-w-[120px]">
            Patient Name
           </th>
           <th class="border border-gray-300 px-2 py-1 w-8">
            Age
           </th>
           <th class="border border-gray-300 px-2 py-1 min-w-[90px]">
            Date of Birth
           </th>
           <th class="border border-gray-300 px-2 py-1 min-w-[140px]">
            Services
           </th>
           <th class="border border-gray-300 px-2 py-1 w-16">
            Time
           </th>
          </tr>
         </thead>
         <tbody>
<?php
// Fetch the latest 10 patient visits (appointments that are not cancelled)
$sql = "SELECT a.*, p.first_name, p.middle_name, p.last_name, p.email, p.phone_number
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        WHERE a.status != 'cancelled'
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
        LIMIT 10";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    $rownum = 1;
    while ($row = $result->fetch_assoc()):
        $patientName = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);
        $services = $row['services'];
        $time = $row['appointment_time'];
        $row_class = ($rownum % 2 == 1) ? 'bg-gray-200' : '';
?>
<tr class="<?php echo $row_class; ?>">
    <td class="border border-gray-300 px-2 py-1 font-mono">
        <?php printf('%03d', $rownum); ?>
    </td>
    <td class="border border-gray-300 px-2 py-1 min-w-[120px]">
        <?php echo htmlspecialchars($patientName); ?>
    </td>
    <td class="border border-gray-300 px-2 py-1 font-mono">
        <?php echo htmlspecialchars($row['phone_number']); ?>
    </td>
    <td class="border border-gray-300 px-2 py-1 font-mono">
        <?php echo htmlspecialchars($row['email']); ?>
    </td>
    <td class="border border-gray-300 px-2 py-1 text-[9px]">
        <?php echo htmlspecialchars($services); ?>
    </td>
    <td class="border border-gray-300 px-2 py-1 font-mono">
        <?php echo htmlspecialchars($time); ?>
    </td>
</tr>
<?php
        $rownum++;
    endwhile;
else:
?>
<tr>
    <td colspan="6" class="text-center py-2">No patient visits found.</td>
</tr>
<?php endif; ?>
         </tbody>
        </table>
       </div>
       <div class="flex justify-between items-center mt-2 text-xs text-gray-600 font-mono">
        <span>
         Showing Page 1 of 1
        </span>
        <div class="flex items-center space-x-1">
         <button class="border border-gray-300 rounded px-2 py-0.5 hover:bg-gray-100" disabled="">
          Previous
         </button>
         <button class="border border-gray-300 rounded px-3 py-0.5 bg-purple-700 text-white">
          1
         </button>
         <button class="border border-gray-300 rounded px-2 py-0.5 hover:bg-gray-100" disabled="">
          Next
         </button>
        </div>
       </div>
      </section>
      <!-- Appointments Table Section -->
      <section id="appointments-section" class="bg-white rounded-lg border border-gray-300 shadow-md p-4">
          <div class="flex justify-between items-center mb-3">
              <h1 class="text-blue-900 font-bold text-lg select-none">
                  Appointments
              </h1>
              <div class="relative">
                  <input id="appointment-search" aria-label="Search" class="border border-gray-400 rounded text-sm pl-7 pr-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Search appointments..." type="text"/>
                  <i aria-hidden="true" class="fas fa-search absolute left-2 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
              </div>
          </div>
          
          <div class="flex space-x-3 mb-3 select-none">
              <button onclick="showSection('pending')" class="status-btn Pending bg-yellow-400 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer active" style="min-width: 90px;">
                  Pending
              </button>
              <button onclick="showSection('upcoming')" class="status-btn Upcoming bg-green-700 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer" style="min-width: 90px;">
                  Upcoming
              </button>
              <button onclick="showSection('rescheduled')" class="status-btn Rescheduled bg-blue-800 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer" style="min-width: 90px;">
                  Rescheduled
              </button>
              <button onclick="showSection('canceled')" class="status-btn Canceled bg-red-700 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer" style="min-width: 90px;">
                  Canceled
              </button>
          </div>
          
          <!-- Pending Section -->
          <div id="pending-section" class="appointment-section">
              <div class="overflow-x-auto">
                  <table class="w-full border border-gray-400 border-collapse text-xs">
                      <thead>
                          <tr class="bg-white border-b border-gray-400">
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Booking Ref No.
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Patient Name
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Service
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Date
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Time
                              </th>
                              <th class="font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Action
                              </th>
                          </tr>
                      </thead>
                      <tbody>
<?php
// Fetch pending appointments
$sql = "SELECT a.*, p.first_name, p.last_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.status = 'pending'
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        // Format reference number
        $ref = 'OIDA-' . date('Ymd', strtotime($row['appointment_date'])) . '-' . $row['id'];
        $patientName = trim($row['first_name'] . ' ' . $row['last_name']);
        $service = $row['services'];
        $date = date('F d, Y', strtotime($row['appointment_date']));
        $time = $row['appointment_time'];
?>
<tr class="border-t border-gray-400" data-booking-ref="<?= htmlspecialchars($ref) ?>">
    <td class="border-r border-gray-400 font-semibold px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($ref) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($patientName) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($service) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($date) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($time) ?></td>
    <td class="px-2 py-1 whitespace-nowrap flex items-center space-x-2">
        <button onclick="showConfirmModal('approve', '<?= htmlspecialchars($patientName) ?>', '<?= htmlspecialchars($date) ?>', '<?= htmlspecialchars($time) ?>', '<?= htmlspecialchars($ref) ?>', '<?= htmlspecialchars($service) ?>')" class="text-green-600 hover:text-green-700 transition-colors" title="Confirm appointment">
            <i class="fas fa-check-circle text-lg"></i>
        </button>
        <button onclick="showConfirmModal('decline', '<?= htmlspecialchars($patientName) ?>', '<?= htmlspecialchars($date) ?>', '<?= htmlspecialchars($time) ?>', '<?= htmlspecialchars($ref) ?>', '<?= htmlspecialchars($service) ?>')" class="text-red-600 hover:text-red-700 transition-colors" title="Cancel appointment">
            <i class="fas fa-times-circle text-lg"></i>
        </button>
        <button class="bg-blue-700 text-white text-xs font-semibold rounded px-3 py-1 hover:bg-blue-800 transition-colors" type="button">
            Details
        </button>
    </td>
</tr>
<?php
    endwhile;
else:
?>
<tr>
    <td colspan="6" class="text-center py-2">No pending appointments found.</td>
</tr>
<?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>

          <!-- Upcoming Section -->
          <div id="upcoming-section" class="appointment-section hidden">
              <div class="overflow-x-auto">
                  <table class="w-full border border-gray-400 border-collapse text-xs">
                      <thead>
                          <tr class="bg-white border-b border-gray-400">
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Booking Ref No.
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Patient Name
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Service
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Date
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Time
                              </th>
                              <th class="font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Status
                              </th>
                          </tr>
                      </thead>
                      <tbody>
<?php
// Fetch upcoming appointments (booked and date in the future)
$sql = "SELECT a.*, p.first_name, p.last_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.status = 'booked' AND a.appointment_date >= CURDATE()
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        // Format reference number
        $ref = 'OIDA-' . date('Ymd', strtotime($row['appointment_date'])) . '-' . $row['id'];
        $patientName = trim($row['first_name'] . ' ' . $row['last_name']);
        $service = $row['services'];
        $date = date('F d, Y', strtotime($row['appointment_date']));
        $time = $row['appointment_time'];
?>
<tr class="border-t border-gray-400" data-booking-ref="<?= htmlspecialchars($ref) ?>">
    <td class="border-r border-gray-400 font-semibold px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($ref) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($patientName) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($service) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($date) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($time) ?></td>
    <td class="px-2 py-1 whitespace-nowrap">
        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Confirmed</span>
    </td>
</tr>
<?php
    endwhile;
else:
?>
<tr>
    <td colspan="6" class="text-center py-2">No upcoming appointments found.</td>
</tr>
<?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>

          <!-- Rescheduled Section -->
          <div id="rescheduled-section" class="appointment-section hidden">
              <div class="overflow-x-auto">
                  <table class="w-full border border-gray-400 border-collapse text-xs">
                      <thead>
                          <tr class="bg-white border-b border-gray-400">
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Booking Ref No.
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Patient Name
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Service
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Date
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Time
                              </th>
                              <th class="font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Status
                              </th>
                          </tr>
                      </thead>
                      <tbody>
<?php
// Fetch rescheduled appointments
$sql = "SELECT a.*, p.first_name, p.last_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.parent_appointment_id IS NOT NULL
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        // Format reference number
        $ref = 'OIDA-' . date('Ymd', strtotime($row['appointment_date'])) . '-' . $row['id'];
        $patientName = trim($row['first_name'] . ' ' . $row['last_name']);
        $service = $row['services'];
        $date = date('F d, Y', strtotime($row['appointment_date']));
        $time = $row['appointment_time'];
?>
<tr class="border-t border-gray-400" data-booking-ref="<?= htmlspecialchars($ref) ?>">
    <td class="border-r border-gray-400 font-semibold px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($ref) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($patientName) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($service) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($date) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($time) ?></td>
    <td class="px-2 py-1 whitespace-nowrap">
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">Rescheduled</span>
    </td>
</tr>
<?php
    endwhile;
else:
?>
<tr>
    <td colspan="6" class="text-center py-2">No rescheduled appointments found.</td>
</tr>
<?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>

          <!-- Canceled Section -->
          <div id="canceled-section" class="appointment-section hidden">
              <div class="overflow-x-auto">
                  <table class="w-full border border-gray-400 border-collapse text-xs">
                      <thead>
                          <tr class="bg-white border-b border-gray-400">
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Booking Ref No.
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Patient Name
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Service
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Date
                              </th>
                              <th class="border-r border-gray-400 font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Time
                              </th>
                              <th class="font-bold text-left px-2 py-1 whitespace-nowrap">
                                  Status
                              </th>
                          </tr>
                      </thead>
                      <tbody>
<?php
// Fetch canceled appointments
$sql = "SELECT a.*, p.first_name, p.last_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.status = 'cancelled'
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        // Format reference number
        $ref = 'OIDA-' . date('Ymd', strtotime($row['appointment_date'])) . '-' . $row['id'];
        $patientName = trim($row['first_name'] . ' ' . $row['last_name']);
        $service = $row['services'];
        $date = date('F d, Y', strtotime($row['appointment_date']));
        $time = $row['appointment_time'];
?>
<tr class="border-t border-gray-400" data-booking-ref="<?= htmlspecialchars($ref) ?>">
    <td class="border-r border-gray-400 font-semibold px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($ref) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($patientName) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($service) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($date) ?></td>
    <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($time) ?></td>
    <td class="px-2 py-1 whitespace-nowrap">
        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Cancelled</span>
    </td>
</tr>
<?php
    endwhile;
else:
?>
<tr>
    <td colspan="6" class="text-center py-2">No canceled appointments found.</td>
</tr>
<?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </section>
     </section>
     <!-- Right sidebar -->
     <aside class="w-72 bg-white border-l border-gray-300 p-4 flex flex-col space-y-4 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent">
      <h3 class="text-sm font-semibold text-gray-900 mb-2">
       Appointments
      </h3>
      <?php
// Get current or requested month/year
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Validate and adjust month/year if needed
if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

// Calculate navigation dates
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

// Current date info
$today = date('j');
$currentMonth = date('n');
$currentYear = date('Y');

// Calendar calculations
$firstDayOfMonth = date('N', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01"));
$totalWeeks = ceil(($firstDayOfMonth + $daysInMonth) / 7);

// Clinic closure dates (from database or hardcoded)
$closureDates = [
    "$year-$month-14", // Example closure dates
    "$year-$month-21",
    "$year-$month-28",
];
?>

<div class="bg-yellow-200 rounded-lg p-3 text-center text-xs font-semibold text-gray-900 select-none">
    <!-- Calendar Header -->
    <div class="flex justify-between items-center mb-2">
        <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" 
           class="text-gray-900 hover:text-gray-700 px-2 py-1 rounded hover:bg-yellow-300 transition">
            <i class="fas fa-chevron-left"></i>
        </a>
        <span class="font-medium">
            <?= date('F Y', strtotime("$year-$month-01")) ?>
        </span>
        <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" 
           class="text-gray-900 hover:text-gray-700 px-2 py-1 rounded hover:bg-yellow-300 transition">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>

    <!-- Day Names -->
    <div class="grid grid-cols-7 gap-1 mb-1 text-[10px] font-semibold">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-1">
        <?php
        // Blank spaces before first day
        for ($i = 1; $i < $firstDayOfMonth; $i++) {
            echo '<div class="h-8"></div>';
        }

        // Days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $isToday = ($day == $today && $month == $currentMonth && $year == $currentYear);
            $isPast = (strtotime($date) < strtotime(date('Y-m-d')));
            $isClosed = in_array($date, $closureDates);

            $classes = [
                'h-8', 
                'rounded-full',
                'flex',
                'items-center',
                'justify-center',
                'mx-auto',
                'w-7',
                'text-[11px]'
            ];

            if ($isToday) {
                $classes[] = 'bg-blue-500 text-white font-bold';
            } elseif ($isPast) {
                $classes[] = 'text-gray-400';
            } elseif ($isClosed) {
                $classes[] = 'bg-red-500 text-white line-through';
            } else {
                $classes[] = 'hover:bg-yellow-300 cursor-pointer';
            }

            echo '<div class="' . implode(' ', $classes) . '">' . $day . '</div>';
        }

        // Blank spaces after last day
        $totalCells = $firstDayOfMonth + $daysInMonth - 1;
        $remainingCells = 42 - $totalCells; // 6 rows x 7 days
        for ($i = 0; $i < $remainingCells; $i++) {
            echo '<div class="h-8"></div>';
        }
        ?>
    </div>
      <!-- Appointment list -->
      <div class="space-y-2">
       <div class="flex items-center space-x-3 bg-white border border-gray-300 rounded px-3 py-2">
        <img alt="Victoria Anne Garcia, female patient with brown hair, smiling" class="rounded-full w-8 h-8 object-cover" height="32" src="https://storage.googleapis.com/a1aa/image/25c59d86-cf9d-41d3-42db-ff46489dbec8.jpg" width="32"/>
        <div class="flex-1 min-w-0">
         <p class="text-xs font-semibold text-gray-900 truncate">
          Victoria Anne Garcia
         </p>
         <p class="text-[9px] text-gray-500 truncate">
          Dental Cleaning &amp; Consultation, Tooth Filling, Panoramic X-ray
         </p>
        </div>
        <span class="text-xs font-semibold text-blue-900 whitespace-nowrap">
         10:00 AM
        </span>
       </div>
       <div class="flex items-center space-x-3 bg-white border border-gray-300 rounded px-3 py-2">
        <img alt="Mikaela Somera, female patient with dark hair, smiling" class="rounded-full w-8 h-8 object-cover" height="32" src="https://storage.googleapis.com/a1aa/image/ea16e691-4556-4e97-6607-f9ddcaa248f9.jpg" width="32"/>
        <div class="flex-1 min-w-0">
         <p class="text-xs font-semibold text-gray-900 truncate">
          Mikaela Somera
         </p>
         <p class="text-[9px] text-gray-500 truncate">
          Metal Braces
         </p>
        </div>
        <span class="text-xs font-semibold text-blue-900 whitespace-nowrap">
         2:00 PM
        </span>
       </div>
       <div class="flex items-center space-x-3 bg-white border border-gray-300 rounded px-3 py-2">
        <img alt="William Marcus Lee, male patient with short hair, smiling" class="rounded-full w-8 h-8 object-cover" height="32" src="https://storage.googleapis.com/a1aa/image/0320bbe8-e365-495f-80f5-4c18ab45fc60.jpg" width="32"/>
        <div class="flex-1 min-w-0">
         <p class="text-xs font-semibold text-gray-900 truncate">
          William Marcus Lee
         </p>
         <p class="text-[9px] text-gray-500 truncate">
          Dental Crown
         </p>
        </div>
        <span class="text-xs font-semibold text-blue-900 whitespace-nowrap">
         3:00 PM
        </span>
       </div>
       <div class="flex items-center space-x-3 bg-white border border-gray-300 rounded px-3 py-2">
        <img alt="Adrielle Mendoza, female patient with dark hair, smiling" class="rounded-full w-8 h-8 object-cover" height="32" src="https://storage.googleapis.com/a1aa/image/7c422bf7-305d-428a-f53a-295b6560461e.jpg" width="32"/>
        <div class="flex-1 min-w-0">
         <p class="text-xs font-semibold text-gray-900 truncate">
          Adrielle Mendoza
         </p>
         <p class="text-[9px] text-gray-500 truncate">
          Dental Check-up &amp; Consultation
         </p>
        </div>
        <span class="text-xs font-semibold text-blue-900 whitespace-nowrap">
         4:00 PM
        </span>
       </div>
      </div>
      <!-- Highlighted appointments -->
      <div class="space-y-3">
       <div class="bg-blue-400 rounded-lg p-3 flex items-center space-x-3 text-white">
        <img alt="Dr. Ardeen Dofiles Oida, female dentist with brown hair, smiling" class="rounded-full w-10 h-10 object-cover border-2 border-white" height="40" src="https://storage.googleapis.com/a1aa/image/181a42f6-d757-471b-0849-cf2a4bb34fdf.jpg" width="40"/>
        <div class="flex-1 min-w-0">
         <p class="text-xs font-semibold truncate">
          Dr. Ardeen Dofiles Oida
         </p>
         <p class="text-[9px] truncate">
          Professional Dentist
         </p>
         <p class="text-[9px] mt-1 font-mono">
          Monday, April 14 2025 (10:00 AM - 5:00 PM)
         </p>
        </div>
       </div>
       <div class="bg-blue-400 rounded-lg p-3 flex items-center space-x-3 text-white">
        <img alt="Wilma Ayoo, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover border-2 border-white" height="40" src="https://storage.googleapis.com/a1aa/image/eddd6b5f-a3dc-4591-50b9-35bd09f247a6.jpg" width="40"/>
        <div class="flex-1 min-w-0">
         <p class="text-xs font-semibold truncate">
          Wilma Ayoo
         </p>
         <p class="text-[9px] truncate">
          Dental Helper
         </p>
         <p class="text-[9px] mt-1 font-mono">
          Monday, April 14 2025 (10:00 AM - 5:00 PM)
         </p>
        </div>
       </div>
      </div>
     </aside>
    </div>
   </main>
  </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Confirm Approval of Appointment</h2>
            <p class="text-gray-600 mb-6">
                Are you sure you want to <span id="actionText" class="font-semibold text-blue-600"></span> this appointment for
                <span id="patientName" class="font-semibold"></span> on 
                <span id="appointmentDate" class="font-semibold"></span> at
                <span id="appointmentTime" class="font-semibold"></span>?
            </p>
            <div class="flex justify-end space-x-4">
                <button onclick="hideConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button id="submitButton" onclick="handleConfirm()" class="px-4 py-2 rounded transition-colors">
                    Submit
                </button>
            </div>
        </div>
    </div>

    <!-- Reason Modal -->
    <div id="reasonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Reason:</h2>
            <div class="mb-6">
                <textarea 
                    id="reasonText" 
                    class="w-full h-32 p-3 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="State your reason.."
                    maxlength="500"
                ></textarea>
                <div class="text-right text-sm text-gray-500">
                    <span id="charCount">0</span>/500
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <button onclick="hideReasonModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button onclick="handleReasonSubmit()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                    Submit
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="mb-4">
                    <i id="successIcon" class="fas fa-check-circle text-5xl"></i>
                </div>
                <h2 id="successTitle" class="text-xl font-bold mb-2">Approval Successful!</h2>
                <p class="text-gray-600 mb-6">
                    The appointment for <span id="successPatientName" class="font-semibold"></span> on 
                    <span id="successDate" class="font-semibold"></span> at 
                    <span id="successTime" class="font-semibold"></span> has been 
                    <span id="successAction" class="font-semibold"></span>. The patient 
                    will be notified via Email/SMS.
                </p>
                <button onclick="hideSuccessModal()" class="px-6 py-2 text-white rounded transition-colors" id="successButton">
                    Ok
                </button>
            </div>
        </div>
    </div>

    <!-- Cancel Details Modal -->
    <div id="cancelDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Cancellation Details</h3>
                <button onclick="closeCancelDetails()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Booking Reference</p>
                    <p class="font-medium" id="modalBookingRef"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Canceled By</p>
                    <p class="font-medium" id="modalCanceledBy"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Reason for Cancellation</p>
                    <p class="font-medium" id="modalCancelReason"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Additional Notes</p>
                    <p class="text-gray-700" id="modalNotes"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeCancelDetails()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Request for Access Section -->
    <section id="access-section" class="section-content hidden bg-white rounded-lg border border-gray-300 shadow-md p-4">
        <h2 class="text-lg font-semibold text-[#0B2E69] mb-4">Request for Access</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="px-4 py-2 text-sm font-semibold text-gray-900">Full Name</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-900">Email Address</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-900">Contact Number</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-900">Role Requested</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-900">Submitted On</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-900">Maricar Velasquez</td>
                        <td class="px-4 py-2 text-sm text-gray-900">maricar01@gmail.com</td>
                        <td class="px-4 py-2 text-sm text-gray-900">09456325874</td>
                        <td class="px-4 py-2 text-sm text-gray-900">Dental Helper</td>
                        <td class="px-4 py-2 text-sm text-gray-900">April 22, 2025</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                <button onclick="showAccessConfirmModal('approve', 'Maricar Velasquez', 'Dental Helper')"
                                    class="w-6 h-6 flex items-center justify-center rounded-full border border-green-600 text-green-600 hover:bg-green-600 hover:text-white transition-colors">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                                <button onclick="showAccessConfirmModal('reject', 'Maricar Velasquez', 'Dental Helper')"
                                    class="w-6 h-6 flex items-center justify-center rounded-full border border-red-600 text-red-600 hover:bg-red-600 hover:text-white transition-colors">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                                <button onclick="showAccessDetails('Maricar Velasquez')"
                                    class="px-3 py-1 text-xs font-semibold text-white bg-[#0B2E69] rounded hover:bg-[#0a2555] transition-colors">
                                    Details
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Access Request Confirmation Modal -->
    <div id="accessConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h2 id="accessModalTitle" class="text-lg font-semibold mb-4">Confirm Access Request</h2>
            <p class="text-gray-600 mb-4">
                Are you sure you want to <span id="accessActionText" class="font-semibold"></span> the access request for
                <span id="accessRequestName" class="font-semibold"></span> as
                <span id="accessRequestRole" class="font-semibold"></span>?
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="hideAccessConfirmModal()" 
                    class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button id="accessSubmitButton" onclick="handleAccessConfirm()" 
                    class="px-3 py-1.5 text-sm rounded transition-colors">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Access Details Modal -->
    <div id="accessDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Access Request Details</h3>
                <button onclick="hideAccessDetails()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Full Name</p>
                    <p class="font-medium" id="detailsName"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Additional Information</p>
                    <p class="text-sm text-gray-700" id="detailsInfo">
                        Experience: 5 years as Dental Assistant<br>
                        Education: BS in Dental Hygiene<br>
                        Current Workplace: ABC Dental Clinic<br>
                        Availability: Immediate
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Documents Submitted</p>
                    <ul class="mt-1 space-y-1">
                        <li>
                            <a href="#" class="text-sm text-blue-600 hover:underline flex items-center">
                                <i class="far fa-file-pdf mr-2"></i> Resume.pdf
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-blue-600 hover:underline flex items-center">
                                <i class="far fa-file-pdf mr-2"></i> Certificate.pdf
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-blue-600 hover:underline flex items-center">
                                <i class="far fa-file-pdf mr-2"></i> License.pdf
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="hideAccessDetails()" 
                    class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="assets/js/dashboard.js"></script>
    <script>
        // Function to filter appointments by search
        function filterAppointments() {
            const query = document.getElementById('appointment-search').value.toLowerCase();
            const sections = document.querySelectorAll('.appointment-section');
            sections.forEach(section => {
                // Only filter visible section
                if (!section.classList.contains('hidden')) {
                    const rows = section.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        // Skip 'no data' row
                        if (row.children.length < 2) return;
                        // Combine all cell text
                        const rowText = row.innerText.toLowerCase();
                        if (rowText.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            });
        }

        // Add event listener for search
        document.addEventListener('DOMContentLoaded', function() {
            showSection('pending');
            document.getElementById('appointment-search').addEventListener('input', filterAppointments);
        });

        // Function to toggle appointment sections
        function showSection(sectionName) {
            // Hide all sections first
            const sections = document.querySelectorAll('.appointment-section');
            sections.forEach(section => {
                section.classList.add('hidden');
            });
            // Show the selected section
            const selectedSection = document.getElementById(sectionName + '-section');
            if (selectedSection) {
                selectedSection.classList.remove('hidden');
            }
            // Update active button status
            const buttons = document.querySelectorAll('.status-btn');
            buttons.forEach(button => {
                button.classList.remove('active', 'bg-yellow-400', 'bg-green-700', 'bg-blue-800', 'bg-red-700');
                // Remove muted
                button.classList.remove('bg-yellow-200', 'bg-green-200', 'bg-blue-200', 'bg-red-200');
                // Highlight the current section with its color
                if (
                    (sectionName === 'pending' && button.classList.contains('Pending')) ||
                    (sectionName === 'upcoming' && button.classList.contains('Upcoming')) ||
                    (sectionName === 'rescheduled' && button.classList.contains('Rescheduled')) ||
                    (sectionName === 'canceled' && button.classList.contains('Canceled'))
                ) {
                    button.classList.add('active');
                    switch(sectionName) {
                        case 'pending':
                            button.classList.add('bg-yellow-400');
                            break;
                        case 'upcoming':
                            button.classList.add('bg-green-700');
                            break;
                        case 'rescheduled':
                            button.classList.add('bg-blue-800');
                            break;
                        case 'canceled':
                            button.classList.add('bg-red-700');
                            break;
                    }
                } else {
                    // Muted color for inactive buttons
                    if (button.classList.contains('Pending')) {
                        button.classList.add('bg-yellow-200');
                    } else if (button.classList.contains('Upcoming')) {
                        button.classList.add('bg-green-200');
                    } else if (button.classList.contains('Rescheduled')) {
                        button.classList.add('bg-blue-200');
                    } else if (button.classList.contains('Canceled')) {
                        button.classList.add('bg-red-200');
                    }
                }
            });
            // Reset search filter when switching section
            filterAppointments();
        }

        // Modal functions for appointment actions
        function showConfirmModal(action, patientName, date, time, ref, service) {
            let message = '';
            let appointmentId = ref.split('-')[2]; // Extract the ID from the reference number
            if (action === 'approve') {
                message = `Confirm appointment for ${patientName} on ${date} at ${time}?`;
            } else if (action === 'decline') {
                message = `Cancel appointment for ${patientName} on ${date} at ${time}?`;
            }
            if (confirm(message)) {
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('action', action === 'approve' ? 'approve' : 'decline');
                formData.append('appointment_id', appointmentId);
                xhr.open('POST', 'appointment_actions.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        } catch (e) {
                            alert('Error processing the response');
                            console.error(e);
                        }
                    } else {
                        alert('Request failed. Status: ' + xhr.status);
                    }
                };
                xhr.onerror = function() {
                    alert('Request failed. Network error.');
                };
                xhr.send(formData);
            }
        }
    </script>
</body>
</html>
