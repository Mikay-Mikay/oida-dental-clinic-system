<?php
session_start();
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once "database.php";

// Function to generate OTP
function generateOTP() {
    return rand(100000, 999999);
}

// Error message variable
$error_message = "";

// If the user requests a new OTP (Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resend_otp'])) {
    $_SESSION['otp'] = generateOTP();
    $_SESSION['otp_expiration'] = time() + 120; // 2 minutes expiry

    $Email = $_SESSION['email'] ?? null;
    $First_Name = $_SESSION['first_name'] ?? 'User';

    if (!$Email) {
        $error_message = "Error: No email found. Please try again.";
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pmjireservify@gmail.com';
            $mail->Password = 'svoa zdpp dktf izld';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('pmjireservify@gmail.com', 'PM&JI Reservify');
            $mail->addAddress($Email);
            $mail->Subject = 'New OTP Code';
            $mail->Body = "Hello $First_Name,\n\nYour new OTP is: {$_SESSION['otp']}\n\nUse this code to complete your verification.\n\nBest Regards,\nPM&JI Reservify";

            $mail->send();

            echo "<script>
                localStorage.setItem('otp_message', 'A new OTP has been sent to your email.');
                window.location.href = 'signup_otp.php';
            </script>";
            exit();
        } catch (Exception $e) {
            $error_message = "Error sending OTP: " . $mail->ErrorInfo;
        }
    }
}

// OTP Verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["otp"])) {
    $entered_otp = $_POST["otp"] ?? '';

    if (!isset($_SESSION["otp"]) || time() > $_SESSION["otp_expiration"]) {
        $error_message = "OTP has expired. Please request a new one.";
    } elseif ($entered_otp == $_SESSION["otp"]) {
        $_SESSION['otp_message'] = "OTP Verified! Registration complete.";
        unset($_SESSION["otp"], $_SESSION["otp_expiration"]);
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Invalid OTP. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up OTP</title>
    <link rel="stylesheet" href="signup_otp.css?v=1.1">

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Display OTP message if exists
        let otpMessage = localStorage.getItem("otp_message");
        if (otpMessage) {
            document.getElementById("otpMessage").innerText = otpMessage;
            setTimeout(() => {
                document.getElementById("otpMessage").innerText = ""; // Remove message after 5s
                localStorage.removeItem("otp_message");
            }, 10000);
        }

        // OTP Countdown Timer
        let expirationTime = <?= isset($_SESSION["otp_expiration"]) ? $_SESSION["otp_expiration"] * 1000 : "null" ?>;
        let countdownElement = document.getElementById("countdown");
        let resendButton = document.getElementById("resendOtp");
        
        function updateCountdown() {
            let now = new Date().getTime();
            let timeLeft = expirationTime - now;

            if (timeLeft <= 0) {
                countdownElement.innerHTML = "OTP expired.";
                resendButton.style.display = "block"; // Show resend button
                return;
            }

            let seconds = Math.floor(timeLeft / 1000);
            countdownElement.innerHTML = "OTP expires in: " + seconds + "s";
        }

        if (expirationTime) {
            updateCountdown();
            let countdownInterval = setInterval(() => {
                updateCountdown();
                if (new Date().getTime() >= expirationTime) {
                    clearInterval(countdownInterval);
                }
            }, 1000);
        }
    });
    </script>
</head>
<body>

    <form action="" method="POST" id="signup_otp">
        <h1>Enter OTP</h1>
        <p id="countdown" style="color: red; font-weight: bold;"></p>
        
        <div class="input-box">
            <input type="number" name="otp" placeholder="Enter OTP" id="otpnumber" required>
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?= $error_message; ?></p>
            <?php endif; ?>
        </div>

        <div class="button-container">
            <button type="submit" class="btn" name="verify_otp">Verify</button>
            <button type="submit" class="btn" name="resend_otp" id="resendOtp" formnovalidate style="display: none;">
                Request New OTP
            </button>
        </div>

        <!-- OTP Message Displayed Here -->
        <p id="otpMessage" style="color: green; font-weight: bold; text-align: center;"></p>

    </form>

</body>
</html>