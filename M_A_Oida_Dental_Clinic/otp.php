<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="assets/css/otp.css">
    <script src="assets/js/otp.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="otp-box">
            <h2>Forgot Password</h2>
            <p>Go to your ‘Gmail’ and enter the OTP Code here to proceed.</p>
            
            <div class="input-box">
                <label>OTP:</label>
                <input type="text" id="otp-input" placeholder="Enter the Code" maxlength="6">
            </div>
            
            <p class="timer">The code will expire in: <span id="countdown">100s</span></p>
            
            <button id="verify-btn">Verify</button>
        </div>

        <div class="logo-container">
            <img src="assets/photos/logo.jpg" alt="M&A Oida Dental Clinic">
            <h3>M&A Oida Dental Clinic</h3>
        </div>
    </div>
</body>
</html>
