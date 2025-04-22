<!-- filepath: c:\xampp\htdocs\M_A_Oida_Dental_Clinic_Admin\forgotpassword.php -->
<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        // Handle email submission
        $email = $_POST['email'];

        // Check if the email exists in the database
        $sql = "SELECT * FROM admin_logins WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);

            // Save the OTP and its expiry time in the database
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
            $sql = "UPDATE admin_logins SET otp = '$otp', otp_expiry = '$expiry' WHERE email = '$email'";

            if ($conn->query($sql) === TRUE) {
                // Send the OTP to the user's email
                $subject = "Your OTP for Password Reset";
                $message = "Your OTP is: $otp. It will expire in 10 minutes.";
                $headers = "From: no-reply@dentalclinic.com";

                if (mail($email, $subject, $message, $headers)) {
                    $_SESSION['email'] = $email; // Store email in session for OTP verification
                    $_SESSION['otp_sent'] = true; // Flag to show OTP form
                } else {
                    $error = "Failed to send OTP. Please try again.";
                }
            } else {
                $error = "Failed to save OTP. Please try again.";
            }
        } else {
            $error = "Email not found in our records.";
        }
    } elseif (isset($_POST['otp'])) {
        // Handle OTP verification
        $otp = $_POST['otp'];
        $email = $_SESSION['email'];

        // Check if the OTP is valid
        $sql = "SELECT * FROM admin_logins WHERE email = '$email' AND otp = '$otp' AND otp_expiry > NOW()";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // OTP is valid
            $success = "OTP verified successfully! You can now reset your password.";
            unset($_SESSION['otp_sent']); // Clear OTP session flag
        } else {
            $error = "Invalid or expired OTP. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/admin_login.css">
</head>
<body>
    <div class="container">
        <img src="assets/photo/logo.jpg" alt="Logo">
        <h1>Forgot Password</h1>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['otp_sent'])): ?>
            <!-- OTP Verification Form -->
            <p>Go to your Gmail and enter the OTP Code here to proceed.</p>
            <form action="forgotpassword.php" method="POST">
                <input type="text" name="otp" placeholder="Enter the Code" required>
                <p style="color: red;">The code will expire in 10 minutes.</p>
                <button type="submit">Verify</button>
            </form>
        <?php else: ?>
            <!-- Email Submission Form -->
            <p>Enter your Email Account for Verification.</p>
            <form action="forgotpassword.php" method="POST">
                <input type="email" name="email" placeholder="ex. Juandelacruz@gmail.com" required>
                <button type="submit">Next</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>