<?php
// Start session
session_start();

// Include PHPMailer (siguraduhin na tama ang path)
require 'vendor/autoload.php';

// Use PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = array();

if (isset($_POST["submit"])) {
    $First_Name       = $_POST["first_name"] ?? '';
    $Middle_Name      = $_POST["middle_name"] ?? '';
    $Last_Name        = $_POST["last_name"] ?? '';
    $Email            = $_POST["email"] ?? '';
    $Phone_Number     = $_POST["phone"] ?? '';
    $Address          = $_POST["address"] ?? '';
    $Region           = $_POST["region"] ?? '';
    $Province         = $_POST["province"] ?? '';
    $City             = $_POST["city"] ?? '';
    $Barangay         = $_POST["barangay"] ?? '';
    $Zip_Code         = $_POST["zip_code"] ?? '';
    $Date_of_Birth    = $_POST["dob"] ?? '';
    $Password         = $_POST["password"] ?? '';
    $Confirm_Password = $_POST["confirm_password"] ?? '';
    $Gender           = $_POST["gender"] ?? '';

    $passwordHash = password_hash($Password, PASSWORD_DEFAULT);

    if (empty($First_Name) || empty($Last_Name) || empty($Email) || empty($Phone_Number) ||
        empty($Address) || empty($Date_of_Birth) || empty($Password) || empty($Confirm_Password) || empty($Gender)) {
        array_push($errors, "All fields are required.");
    }

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid.");
    }

    if (strlen($Password) < 8) {
        array_push($errors, "Password must be at least 8 characters long.");
    }

    if ($Password !== $Confirm_Password) {
        array_push($errors, "Passwords do not match.");
    }

    if (count($errors) == 0) {
        require_once "database.php";

        $sql = "INSERT INTO test_registration 
        (First_Name, Middle_Name, Last_Name, Email, Phone_Number, Address, Region, Province, City, Barangay, Zip_Code, Date_of_Birth, Password, Gender) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssssssssssssss", 
            $First_Name, $Middle_Name, $Last_Name, $Email, $Phone_Number, $Address, 
            $Region, $Province, $City, $Barangay, $Zip_Code, $Date_of_Birth, $passwordHash, $Gender
        );

        if (mysqli_stmt_execute($stmt)) {
            // Generate OTP
            $otp_code = rand(100000, 999999);
            // Set OTP expiration (1 minute and 50 seconds from now)
            $otp_expiration = time() + (1 * 60) + 50;
            
            // Store OTP in session (o pwede sa DB)
            $_SESSION['otp'] = $otp_code;
            $_SESSION['otp_expiration'] = $otp_expiration;
            $_SESSION['email'] = $Email;

            // Send Email with OTP using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pmjireservify@gmail.com'; // Your Gmail Email
                $mail->Password = 'svoa zdpp dktf izld';   // Use App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email Content
                $mail->setFrom('pmjireservify@gmail.com', 'PM&JI Reservify');
                $mail->addAddress($Email); // Recipient
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Hello $First_Name,\n\nYour One-Time Password (OTP) is: $otp_code\n\nUse this code to complete your verification.\n\nBest Regards,\nPM&JI Reservify ";

                // Send Email
                $mail->send();
                echo "<script>alert('OTP has been sent to your email.');</script>";

                // Redirect to OTP verification page
                echo "<script>window.location.href = 'signup_otp.php';</script>";
                exit();
            } catch (Exception $e) {
                echo "<script>alert('Registration Successful but failed to send OTP.');</script>";
            }

        } else {
            die("Database error: Unable to execute query.");
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - PM&JI Reservify</title>
  <link rel="stylesheet" type="text/css" href="Sign up.css?v=1.1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Isama muna ang jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Isama ang signup.js kung nais mo itong ihiwalay; narito ay naka-inline na -->
  <script src="signup.js"></script>
</head>
<body>
  <div class="container">
    <div class="title">Registration</div>
    <div class="content">
      <form action="" method="POST">
        <div class="user-details">
          <!-- Personal Details -->
          <div class="input-box">
            <span class="details">First Name</span>
            <input type="text" name="first_name" placeholder="Enter your first name" required>
          </div>
          <div class="input-box">
            <span class="details">Middle Name</span>
            <input type="text" name="middle_name" placeholder="Enter your middle name">
          </div>
          <div class="input-box">
            <span class="details">Last Name</span>
            <input type="text" name="last_name" placeholder="Enter your last name" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="juandelacruz@gmail.com" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="phone" placeholder="e.g., 09123456789" required>
          </div>

          <!-- Address and Location -->
          <div class="input-box">
            <span class="details">Address</span>
            <input type="text" name="address" placeholder="Enter your full address" required>
          </div>
          <div class="input-box">
            <span class="details">Region</span>
            <select id="region" name="region" required>
              <option value="">Select Region</option>
            </select>
          </div>
          <div class="input-box">
            <span class="details">Province</span>
            <select id="province" name="province" required>
              <option value="">Select Province</option>
            </select>
          </div>
          <div class="input-box">
            <span class="details">City</span>
            <select id="city" name="city" required>
              <option value="">Select City</option>
            </select>
          </div>
          <div class="input-box">
            <span class="details">Barangay</span>
            <select id="barangay" name="barangay" required>
              <option value="">Select Barangay</option>
            </select>
          </div>
          <div class="input-box">
            <span class="details">Zip Code</span>
            <input type="text" name="zip_code" placeholder="Enter Zip Code" required>
          </div>

          <!-- Iba pang detalye -->
          <div class="input-box">
            <span class="details">Date of Birth</span>
            <input type="date" name="dob" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <span class="toggle-password" id="toggle-password">
              <i id="eye-icon" class="fa fa-eye"></i>
            </span>
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          </div>
        </div>

        <!-- Gender Section -->
        <div class="gender-details">
          <input type="radio" name="gender" id="dot-1" value="Male">
          <input type="radio" name="gender" id="dot-2" value="Female">
          <input type="radio" name="gender" id="dot-3" value="Prefer not to say">
          <span class="gender-title">Gender</span>
          <div class="category">
            <label for="dot-1">
              <span class="dot one"></span>
              <span class="gender">Male</span>
            </label>
            <label for="dot-2">
              <span class="dot two"></span>
              <span class="gender">Female</span>
            </label>
            <label for="dot-3">
              <span class="dot three"></span>
              <span class="gender">Prefer not to say</span>
            </label>
          </div>
        </div>

        <div class="g-recaptcha" data-sitekey="6Le6rr0qAAAAAHRn-AkSkpYlNlHsQCwg_xh_w32w"></div>

        <div class="checkbox-container">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms">
            I have read, understood, and agree to the <a href="TermsandConditions.php">Terms & Conditions</a>.
          </label>
        </div>

        <div class="button">
          <input type="submit" name="submit" value="Register" id="register-btn">
        </div>

        <div class="login-container">
          <p>Do you have an account? <a href="login.php">Log In</a></p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
