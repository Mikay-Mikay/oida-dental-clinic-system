<?php
session_start();
?>
<?php
header("Content-Type: application/json"); // Set response type to JSON

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer (Install via Composer)

$host = "localhost"; // Change if needed
$user = "root"; // Your DB username
$pass = ""; // Your DB password
$dbname = "your_database"; // Replace with your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check database connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]); // Trim whitespace

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Invalid email format."]);
        exit();
    }

    // Debug: Check email input before query
    error_log("Checking email: " . $email);

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Debug: Log number of rows found
    error_log("Rows found: " . $stmt->num_rows);

    if ($stmt->num_rows > 0) {
        // Generate Reset Token
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store token in database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        // Generate Reset Link
        $reset_link = "http://yourwebsite.com/reset-password.php?token=" . $token;

        // Send Email via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.yourmail.com'; // Replace with SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@yourwebsite.com'; // SMTP email
            $mail->Password = 'your-email-password'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('noreply@yourwebsite.com', 'Your Website');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br><br>
                           <a href='$reset_link'>$reset_link</a><br><br>
                           This link will expire in 1 hour.";

            $mail->send();
            echo json_encode(["success" => "Reset link sent to: $email"]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Email sending failed: " . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(["error" => "Email not found. Please try again."]);
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/forgotpassword.css">
    <script src="assets/js/forgotpassword.js"></script>
</head>
<body>
    <div class="container">
        
        
        <div class="forgot-password-container">
            <h2>Forgot Password</h2>
            <p>Enter your Email Account for Verification.</p>
            
            <form>
                <div class="input-box">
                    <label>Email:</label>
                    <input type="email" placeholder="ex. Juandelacruz@gmail.com">
                </div>

                <button type="submit">Next</button>
            </form>
        </div>

        <!-- Logo Section -->
        <div class="logo-container">
            <img src="assets/photos/logo.jpg" alt="M&A Oida Dental Clinic">
            <h3>M&A Oida Dental Clinic</h3>
        </div>

    </div>
</body>
</html>
