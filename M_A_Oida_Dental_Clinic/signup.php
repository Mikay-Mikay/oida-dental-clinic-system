<?php

session_start();
require 'db.php'; // Include database connection
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Helper functions
function isEmailRegistered($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function isEmailPending($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM pending_patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function validatePhoneNumber($phone) {
    return preg_match("/^(09|\\+639)\\d{9}$/", $phone);
}

function validateZipCode($zip) {
    return preg_match("/^\\d{4}$/", $zip); // Philippine ZIP code format
}

// Helper function to capitalize names
function capitalizeNames($name) {
    // Split the name by spaces
    $parts = explode(' ', trim($name));
    // Capitalize first letter of each part
    $parts = array_map(function($part) {
        return ucfirst(strtolower($part));
    }, $parts);
    // Join the parts back together
    return implode(' ', $parts);
}

// Temporarily disabled password validation
/*function validatePassword($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return strlen($password) >= 8 &&
           preg_match("/[A-Z]/", $password) &&
           preg_match("/[a-z]/", $password) &&
           preg_match("/[0-9]/", $password);
}*/

function validateAge($birthDate) {
    $today = new DateTime();
    $diff = $today->diff(new DateTime($birthDate));
    return $diff->y >= 18; // Must be at least 18 years old
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    
    try {
        // Validate required fields
        $required = ["first_name", "last_name", "email", "phone_number", "region", 
                    "province", "city", "barangay", "zip_code", "date_of_birth", 
                    "password", "confirm_password", "gender"];
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception(ucfirst(str_replace('_', ' ', $field)) . " is required.");
            }
        }

        // Sanitize and validate inputs
        $first_name = capitalizeNames(filter_var(trim($_POST["first_name"]), FILTER_SANITIZE_STRING));
        $middle_name = !empty($_POST["middle_name"]) ? capitalizeNames(filter_var(trim($_POST["middle_name"]), FILTER_SANITIZE_STRING)) : '';
        $last_name = capitalizeNames(filter_var(trim($_POST["last_name"]), FILTER_SANITIZE_STRING));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $phone_number = trim($_POST["phone_number"]);
        $region = filter_var(trim($_POST["region"]), FILTER_SANITIZE_STRING);
        $province = filter_var(trim($_POST["province"]), FILTER_SANITIZE_STRING);
        $city = filter_var(trim($_POST["city"]), FILTER_SANITIZE_STRING);
        $barangay = filter_var(trim($_POST["barangay"]), FILTER_SANITIZE_STRING);
        $zip_code = trim($_POST["zip_code"]);
        $date_of_birth = $_POST["date_of_birth"];
        $gender = filter_var(trim($_POST["gender"]), FILTER_SANITIZE_STRING);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if email is already registered
        if (isEmailRegistered($conn, $email)) {
            throw new Exception("Email is already registered");
        }
        if (isEmailPending($conn, $email)) {
            throw new Exception("A verification email has already been sent. Please check your inbox or spam folder.");
        }

        // Validate phone number
        if (!validatePhoneNumber($phone_number)) {
            throw new Exception("Invalid phone number format. Use 09XXXXXXXXX or +639XXXXXXXXX");
        }

        // Validate ZIP code
        if (!validateZipCode($zip_code)) {
            throw new Exception("Invalid ZIP code format. Must be 4 digits.");
        }

        // Validate age
        if (!validateAge($date_of_birth)) {
            throw new Exception("You must be at least 18 years old to register.");
        }

        // Temporarily disabled password strength validation
        /*if (!validatePassword($password)) {
            throw new Exception("Password must be at least 8 characters and contain uppercase, lowercase, and numbers");
        }*/

        // Check if passwords match
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Generate OTP and set expiration
        $otp = rand(100000, 999999);
        // Use MySQL NOW() + INTERVAL for consistent server time
        $stmt = $conn->prepare("SELECT DATE_ADD(NOW(), INTERVAL 10 MINUTE) as otp_expires");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $otp_expires = $row['otp_expires'];
        
        error_log("OTP Generation Time: " . date("Y-m-d H:i:s"));
        error_log("OTP: $otp, Expires: $otp_expires");

        // Always set role as 'user' for new registrations
        $role = 'user';

        // Store in pending_patients with debug
        $stmt = $conn->prepare("INSERT INTO pending_patients (first_name, middle_name, last_name, email, 
                              phone_number, region, province, city, barangay, zip_code, date_of_birth, 
                              password_hash, gender, role, otp, otp_expires) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssssssssssssss", 
            $first_name, $middle_name, $last_name, $email, $phone_number, $region, 
            $province, $city, $barangay, $zip_code, $date_of_birth, $password_hash, 
            $gender, $role, $otp, $otp_expires
        );

        if ($stmt->execute()) {
            error_log("Inserted into pending_patients successfully");
            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'oidaclinic1@gmail.com';
                $mail->Password = 'lkys fezt vzam bzof';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                $mail->setFrom('oidaclinic1@gmail.com', 'M&A Oida Dental Clinic');
                $mail->addAddress($email);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Dear $first_name,\n\nYour OTP code is: $otp\n\nThis code will expire in 10 minutes at: $otp_expires\n\nBest regards,\nM&A Oida Dental Clinic";
                
                $mail->send();
                error_log("OTP email sent successfully to: $email");
                echo json_encode([
                    "status" => "success",
                    "message" => "Registration initiated. Please check your email for the OTP code."
                ]);
            } catch (Exception $e) {
                error_log("Failed to send OTP email: " . $e->getMessage());
                // Delete the pending registration if email fails
                $conn->prepare("DELETE FROM pending_patients WHERE email = ?")->bind_param("s", $email)->execute();
                throw new Exception("Failed to send verification email. Please try again later.");
            }
        } else {
            error_log("Failed to insert into pending_patients: " . $stmt->error);
            throw new Exception("Database error. Please try again.");
        }
        
    } catch (Exception $e) {
        error_log("Error in signup process: " . $e->getMessage());
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
    exit();
}

// Non-POST requests show signup page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ISched of M&A Oida Dental Clinic</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/signup.js"></script>
    <link rel="stylesheet" href="assets/css/signup.css?v=2.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">   
        <div class="signup-box">
            <!-- HEADER: logo + title -->
            <div class="signup-header">
                <img src="assets/photos/logo-2.png" alt="Clinic Logo" class="signup-logo">
                <h2>Sign Up</h2>
            </div>

            <form id="signupForm" action="signup.php" method="POST">
                <div class="input-group">
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>First Name:</label>
                        <input type="text" name="first_name" placeholder="ex. Juan" required>
                    </div>
                    <div class="input-box">
                        <label>Middle Name:</label>
                        <input type="text" name="middle_name" placeholder="ex. Medrano">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Last Name:</label>
                        <input type="text" name="last_name" placeholder="ex. Dela Cruz" required>
                    </div>
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Email:</label>
                        <input type="email" name="email" placeholder="ex. Juandelacruz@gmail.com" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Phone Number:</label>
                        <input type="text" name="phone_number" placeholder="ex. 09123456789" required>
                    </div>
                    <div class="input-box">
                        <label for="region"><strong style="color: red;">*</strong>Region:</label>
                        <select id="region" name="region">
                            <option value="">Select a Region</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="province"><strong style="color: red;">*</strong>Province:</label>
                        <select id="province" name="province">
                            <option value="">Select a Province</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <label for="city"><strong style="color: red;">*</strong>City/Municipality:</label>
                        <select id="city" name="city">
                            <option value="">Select a City/Municipality</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="barangay"><strong style="color: red;">*</strong>Barangay:</label>
                        <select id="barangay" name="barangay">
                            <option value="">Select a Barangay</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Zip Code:</label>
                        <input type="text" name="zip_code" placeholder="Enter a Zip Code" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Date of Birth:</label>
                        <input type="date" name="date_of_birth" required>
                    </div>

                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Gender:</label>
                        <select name="gender" required>
                            <option value="">Select a Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Female">Prefer not to say</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Password:</label>
                        <div class="password-container">
                            <input type="password" id="signup-password" name="password" placeholder="Enter your Password" required>
                            <span class="toggle-password" onclick="togglePassword('signup-password')">
                                <i class="fas fa-eye" id="signup-password-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Confirm Password:</label>
                        <div class="password-container">
                            <input type="password" id="confirm-password" name="confirm_password" placeholder="Re-type your Password" required>
                            <span class="toggle-password" onclick="togglePassword('confirm-password')">
                                <i class="fas fa-eye" id="confirm-password-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="terms">
                    <input type="checkbox" required>
                    <label>
                        I agree to the 
                        <a href="#" onclick="openTermsModal(); return false;">Terms &amp; Conditions</a>
                    </label>
                </div>

                <button type="submit">Sign Up</button>
                <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>
    </div>

    <!-- Terms Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content terms-modal">
            <span class="close" onclick="closeTermsModal()">&times;</span>
            <h2>Terms and Conditions</h2>
            <div class="terms-content">
                <p>Welcome to <strong>ISched of M&A Oida Dental Clinic</strong>. By accessing our services, you agree to comply with the following terms and conditions. Please read them carefully.</p>

                <h3>1. General Terms</h3>
                <p>1.1 These terms govern the use of M&A Oida Dental Clinic's services, including appointments and treatments.</p>
                <p>1.2 The clinic reserves the right to update these terms without prior notice.</p>

                <h3>2. Appointments and Scheduling</h3>
                <p>2.1 Patients must book appointments in advance through our web-based system, phone, or walk-in process.</p>
                <p>2.2 Late arrivals of more than 20 minutes may result in rescheduling or cancellation.</p>
                <p>2.3 Cancellations should be made at least 24 hours before the scheduled appointment.</p>

                <h3>3. Patient Records and Privacy</h3>
                <p>3.1 Patient records are confidential and stored securely within our system.</p>
                <p>3.2 Personal information will only be used for medical and administrative purposes.</p>

                <h3>4. Payments and Fees</h3>
                <p>4.1 Payment is required upon completion of services unless prior arrangement has been made.</p>
                <p>4.2 The clinic accepts cash, credit/debit cards, and e-wallets (e.g., GCash, Maya).</p>

                <h3>5. Treatment Plans and Responsibility</h3>
                <p>5.1 Treatment recommendations are based on professional assessments.</p>
                <p>5.2 Patients are responsible for following post-treatment care instructions.</p>

                <h3>6. Consent to Treatment</h3>
                <p>6.1 By agreeing to these terms, you acknowledge that dental procedures carry inherent risks.</p>
                <p>6.2 The clinic will explain all procedures and potential risks before treatment.</p>

                <p><strong>By using our services, you acknowledge that you have read, understood, and agreed to these terms and conditions.</strong></p>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div id="otpModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" id="closeOtpModal">&times;</span>
            <h2>Verify Your Email</h2>
            <div id="otpMessage"></div>
            <form id="otpForm">
                <input type="hidden" name="email" id="otpEmail">
                <label for="otpInput">OTP:</label>
                <input type="text" name="otp" id="otpInput" required placeholder="Enter the code sent to your email">
                <button type="submit">Verify</button>
            </form>
            <a href="#" id="resendOtpLink"><i class="fas fa-sync-alt"></i> Resend OTP</a>
            <p class="login-link" style="text-align:center;">Already verified? <a href="login.php">Log In</a></p>
        </div>
    </div>
    <style>
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(0,0,0,0.4); }
        .modal-content { background: #fff; margin: 10% auto; padding: 30px 20px; border-radius: 8px; max-width: 400px; box-shadow: 0 2px 12px rgba(0,0,0,0.12); position: relative; }
        .modal-content h2 { text-align: center; }
        .modal-content form { display: flex; flex-direction: column; gap: 15px; }
        .modal-content input[type=text] { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .modal-content button { padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .modal-content button:hover { background: #0056b3; }
        .modal-content .close { position: absolute; right: 15px; top: 10px; font-size: 1.5em; color: #888; cursor: pointer; }
        .modal-content .close:hover { color: #333; }
        #otpMessage { margin-bottom: 10px; text-align: center; }
        #resendOtpLink { display: block; margin-top: 10px; text-align: center; }

        /* Terms modal specific styles */
        .terms-modal {
            max-width: 800px;
            max-height: 80vh;
            margin: 5vh auto;
        }
        .terms-content {
            overflow-y: auto;
            max-height: calc(80vh - 100px);
            padding: 20px;
            font-size: 14px;
            line-height: 1.6;
        }
        .terms-content h3 {
            color: #6a5acd;
            margin: 20px 0 10px;
        }
        .terms-content p {
            margin-bottom: 10px;
            text-align: justify;
        }
        .terms-content strong {
            font-weight: bold;
        }
    </style>

    <script>
        // Add these functions to handle the terms modal
        function openTermsModal() {
            document.getElementById('termsModal').style.display = 'block';
        }

        function closeTermsModal() {
            document.getElementById('termsModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.id === 'termsModal') {
                closeTermsModal();
            }
        }

        // Function to toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>