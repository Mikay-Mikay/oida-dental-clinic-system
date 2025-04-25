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
                     src="https://storage.googleapis.com/a1aa/image/41fed6ff-1f2a-487b-b938-0643eb35d989.jpg"/>
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
             src="https://storage.googleapis.com/a1aa/image/41fed6ff-1f2a-487b-b938-0643eb35d989.jpg" />
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
           120
          </p>
          <p class="text-xs text-gray-700">
           Patients
          </p>
          <p class="text-xs text-gray-500 mt-1">
           +40
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
           40
          </p>
          <p class="text-xs text-gray-700">
           Appointments
          </p>
          <p class="text-xs text-gray-500 mt-1">
           +20
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
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Dr. Marcial Oida, male dentist with short hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/dc18abd0-f2fb-4b75-b6fe-f5fdff8ce38c.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Marcial Oida
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Dr. Ardeen Dofiles Oida, female dentist with brown hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/98744531-e436-4c11-3d96-43e587e09d9d.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Ardeen Dofiles Oida
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Dr. Maribel Adajar, female dentist with pink hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/de61221c-89bf-4482-e319-af0910fcf581.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Maribel Adajar
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Dr. Joan Gajeto Flores, male dentist with short hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/6ff22061-8da2-4bf3-7194-ee676ea91070.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Dr. Joan Gajeto Flores
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Professional Dentist
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Geraldine U. Labasan, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/1408e578-f309-4c63-b810-edcba1d2e21d.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Geraldine U. Labasan
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Lynneth Mutuan, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/a8a0db4b-802e-45c2-847c-787b57c55ca0.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Lynneth Mutuan
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Wilma Ayoo, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/eddd6b5f-a3dc-4591-50b9-35bd09f247a6.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Wilma Ayoo
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Gemma A. Velasquez, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/4de90e46-0d6f-4329-15e1-2d62cee247ba.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Gemma A. Velasquez
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
        <a class="flex items-center space-x-3 border border-gray-300 rounded px-3 py-2 hover:bg-gray-50" href="#">
         <img alt="Jocelyn Darang, female dental helper with dark hair, professional portrait" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/73e9d24a-6058-4adb-55f6-8c913b66f493.jpg" width="40"/>
         <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-blue-900 truncate">
           Jocelyn Darang
          </p>
          <p class="text-[9px] text-gray-500 truncate">
           Dental Helper
          </p>
         </div>
         <i class="fas fa-chevron-right text-gray-400">
         </i>
        </a>
       </div>
      </section>
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
          <tr class="bg-gray-200">
           <td class="border border-gray-300 px-2 py-1 font-mono">
            001
           </td>
           <td class="border border-gray-300 px-2 py-1 flex items-center space-x-2 min-w-[120px]">
            <img alt="Victoria Anne Garcia, female patient with brown hair, smiling" class="rounded-full w-6 h-6 object-cover" height="24" src="https://storage.googleapis.com/a1aa/image/25c59d86-cf9d-41d3-42db-ff46489dbec8.jpg" width="24"/>
            <span>
             Victoria Anne Garcia
            </span>
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            27
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            06/11/97
           </td>
           <td class="border border-gray-300 px-2 py-1 text-[9px]">
            Dental Cleaning, Oral Consultation, Panoramic X-ray, Tooth Filling
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            10:00 AM
           </td>
          </tr>
          <tr>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            002
           </td>
           <td class="border border-gray-300 px-2 py-1 flex items-center space-x-2 min-w-[120px]">
            <img alt="Mikaela Somera, female patient with dark hair, smiling" class="rounded-full w-6 h-6 object-cover" height="24" src="https://storage.googleapis.com/a1aa/image/ea16e691-4556-4e97-6607-f9ddcaa248f9.jpg" width="24"/>
            <span>
             Mikaela Somera
            </span>
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            21
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            12/26/03
           </td>
           <td class="border border-gray-300 px-2 py-1 text-[9px]">
            Metal Braces
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            2:00 PM
           </td>
          </tr>
          <tr class="bg-gray-200">
           <td class="border border-gray-300 px-2 py-1 font-mono">
            003
           </td>
           <td class="border border-gray-300 px-2 py-1 flex items-center space-x-2 min-w-[120px]">
            <img alt="William Marcus Lee, male patient with short hair, smiling" class="rounded-full w-6 h-6 object-cover" height="24" src="https://storage.googleapis.com/a1aa/image/0320bbe8-e365-495f-80f5-4c18ab45fc60.jpg" width="24"/>
            <span>
             William Marcus Lee
            </span>
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            24
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            02/14/01
           </td>
           <td class="border border-gray-300 px-2 py-1 text-[9px]">
            Dental Crown
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            3:00 PM
           </td>
          </tr>
          <tr>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            003
           </td>
           <td class="border border-gray-300 px-2 py-1 flex items-center space-x-2 min-w-[120px]">
            <img alt="Adrielle Mendoza, female patient with dark hair, smiling" class="rounded-full w-6 h-6 object-cover" height="24" src="https://storage.googleapis.com/a1aa/image/7c422bf7-305d-428a-f53a-295b6560461e.jpg" width="24"/>
            <span>
             Adrielle Mendoza
            </span>
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            20
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            11/22/04
           </td>
           <td class="border border-gray-300 px-2 py-1 text-[9px]">
            Dental Crown
           </td>
           <td class="border border-gray-300 px-2 py-1 font-mono">
            3:00 PM
           </td>
          </tr>
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
                  <input aria-label="Search" class="border border-gray-400 rounded text-sm pl-7 pr-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Search appointments..." type="text"/>
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
                          <tr class="border-t border-gray-400" data-booking-ref="MDC-20250407-289">
                              <td class="border-r border-gray-400 font-semibold px-2 py-1 whitespace-nowrap">
                                  MDC-20250407-289
                              </td>
                              <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
                                  Mikaela Somera
                              </td>
                              <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
                                  Metal Braces
                              </td>
                              <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
                                  April 22, 2025
                              </td>
                              <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
                                  2:00 PM
                              </td>
                              <td class="px-2 py-1 whitespace-nowrap flex items-center space-x-2">
                                  <button onclick="showConfirmModal('approve', 'Mikaela Somera', 'April 22, 2025', '2:00 PM', 'MDC-20250407-289', 'Metal Braces')" class="text-green-600 hover:text-green-700 transition-colors" title="Confirm appointment">
                                      <i class="fas fa-check-circle text-lg"></i>
                                  </button>
                                  <button onclick="showConfirmModal('decline', 'Mikaela Somera', 'April 22, 2025', '2:00 PM', 'MDC-20250407-289', 'Metal Braces')" class="text-red-600 hover:text-red-700 transition-colors" title="Cancel appointment">
                                      <i class="fas fa-times-circle text-lg"></i>
                                  </button>
                                  <button class="bg-blue-700 text-white text-xs font-semibold rounded px-3 py-1 hover:bg-blue-800 transition-colors" type="button">
                                      Details
                                  </button>
                              </td>
                          </tr>
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
                          <!-- Upcoming appointments will be added here dynamically -->
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
                          <!-- Rescheduled appointments will be added here dynamically -->
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
                          <!-- Canceled appointments will be added here dynamically -->
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
                <h3 class="text-xl font-semibold">Cancellation Details</h3>
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
</body>
</html>
