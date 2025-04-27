<?php
session_start();
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_token'])) {
    header("Location: forgotpassword.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - M&A Oida Dental Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let timeLeft = 100;
        function countdown() {
            if (timeLeft <= 0) {
                document.getElementById("timer").innerHTML = "Code expired!";
                document.getElementById("verifyBtn").disabled = true;
            } else {
                document.getElementById("timer").innerHTML = "The code will expire in: " + timeLeft + "s";
                timeLeft--;
                setTimeout(countdown, 1000);
            }
        }
        window.onload = countdown;
    </script>
</head>
<body class="bg-blue-400 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <div class="flex flex-col items-center mb-6">
            <img src="assets/photo/logo.jpg" class="w-20 h-20 mb-4 rounded-full" alt="Logo">
            <h2 class="text-2xl font-bold mb-2">Forgot Password</h2>
            <p class="text-center text-gray-600">Go to your 'Gmail' and enter the OTP Code here to proceed.</p>
        </div>
        <form method="POST" action="process_otp.php">
            <?php if (isset($_SESSION['otp_error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-2">
                    <?php echo $_SESSION['otp_error']; unset($_SESSION['otp_error']); ?>
                </div>
            <?php endif; ?>
            <label class="block mb-2">OTP:</label>
            <input type="text" name="otp" class="w-full mb-4 p-2 border rounded" placeholder="Enter the Code" required>
            <div id="timer" class="text-red-500 mb-4"></div>
            <button id="verifyBtn" type="submit" class="w-full bg-blue-900 text-white py-2 rounded">Verify</button>
        </form>
    </div>
</body>
</html> 