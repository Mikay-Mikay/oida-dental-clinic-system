<?php
$name = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?php echo $name; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center mb-6">
                <img src="assets/photo/logo.jpg" alt="Profile photo" class="w-24 h-24 rounded-full mr-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1"><?php echo $name; ?></h2>
                    <p class="text-gray-600">Specialty / Role: <span class="font-semibold">Professional Dentist / Dental Helper</span></p>
                    <p class="text-gray-600">Years of Experience: <span class="font-semibold">(sample)</span></p>
                </div>
            </div>
            <div class="mb-4">
                <span class="inline-block bg-green-200 text-green-800 text-xs px-3 py-1 rounded-full">Available</span>
            </div>
            <div class="mb-2">
                <strong>Languages Spoken:</strong> Filipino, English
            </div>
            <div class="mb-2">
                <strong>Clinic Branch Assignment:</strong> (sample branches)
            </div>
            <div class="mb-2">
                <strong>Working Hours / Availability:</strong> (sample schedule)
            </div>
            <a href="dashboard.php" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Back to List</a>
        </div>
    </div>
</body>
</html> 