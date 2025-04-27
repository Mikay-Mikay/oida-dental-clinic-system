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
    <title>Appointments - M&amp;A Oida Dental Clinic</title>
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

            <!-- Navigation -->
            <nav class="flex flex-col space-y-2 text-gray-700 text-sm font-medium">
                <a class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="dashboard.php">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
                        <i class="fas fa-home"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 text-blue-900" href="appointments.php">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-lg text-gray-700">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span>Appointments</span>
                </a>
                <!-- Other navigation items -->
                <!-- Copy the rest of the navigation items from dashboard.php -->
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
                    <img alt="Profile photo" class="rounded-full w-10 h-10 object-cover" 
                         src="https://storage.googleapis.com/a1aa/image/41fed6ff-1f2a-487b-b938-0643eb35d989.jpg"/>
                </div>
            </header>

            <!-- Content area -->
            <div class="flex-1 p-6 overflow-y-auto space-y-6">
                <!-- Appointments Table Section -->
                <section class="bg-white rounded-lg border border-gray-300 shadow-md p-4">
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
                        <button class="bg-yellow-400 text-white text-xs font-semibold rounded px-4 py-1 cursor-default" style="min-width: 90px;">
                            Pending
                        </button>
                        <button class="bg-green-700 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer" style="min-width: 90px;">
                            Upcoming
                        </button>
                        <button class="bg-blue-800 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer" style="min-width: 90px;">
                            Rescheduled
                        </button>
                        <button class="bg-red-700 text-white text-xs font-semibold rounded px-4 py-1 cursor-pointer" style="min-width: 90px;">
                            Canceled
                        </button>
                    </div>
                    
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
                                <tr class="border-t border-gray-400">
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
                                        <img alt="Green check mark icon indicating confirmation" class="inline-block" height="16" src="https://storage.googleapis.com/a1aa/image/4d8d93f1-f70b-4f4a-ad2d-6c4f6658c070.jpg" width="16"/>
                                        <img alt="Red cross icon indicating cancellation" class="inline-block" height="16" src="https://storage.googleapis.com/a1aa/image/cb609f60-a305-4888-c647-f6b4c49d60cc.jpg" width="16"/>
                                        <button class="bg-blue-700 text-white text-xs font-semibold rounded px-3 py-1" type="button">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html> 