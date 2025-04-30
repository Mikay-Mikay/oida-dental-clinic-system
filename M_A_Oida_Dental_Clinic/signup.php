<?php
session_start();
require 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json'); // Ensure JSON response format

    // Get form inputs and sanitize them
    $first_name = trim($_POST["first_name"]);
    $middle_name = trim($_POST["middle_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $phone_number = trim($_POST["phone_number"]);
    $region = trim($_POST["region"]);
    $province = trim($_POST["province"]);
    $city = trim($_POST["city"]);
    $barangay = trim($_POST["barangay"]);
    $zip_code = trim($_POST["zip_code"]);
    $date_of_birth = $_POST["date_of_birth"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $gender = $_POST["gender"];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit();
    }

    // Validate phone number (basic Philippine mobile format check)
    if (!preg_match("/^(09|\+639)\d{9}$/", $phone_number)) {
        echo json_encode(["status" => "error", "message" => "Invalid phone number format"]);
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match"]);
        exit();
    }

    // Check if email is already registered
    $check_email = $conn->prepare("SELECT id FROM patients WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email is already registered"]);
        exit();
    }
    $check_email->close();

    // Hash password securely
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert into `patients` table
    $sql = "INSERT INTO patients (first_name, middle_name, last_name, email, phone_number, region, province, city, barangay, zip_code, date_of_birth, password_hash, gender) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $first_name, $middle_name, $last_name, $email, $phone_number, $region, $province, $city, $barangay, $zip_code, $date_of_birth, $password_hash, $gender);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registration successful! Redirecting to login..."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit(); // Stop further execution after sending JSON response
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ISched of M&A Oida Dental Clinic</title>
    <script src="assets/js/locations.js"></script>
    <script src="assets/js/signup.js"></script>
    <link rel="stylesheet" href="assets/css/signup.css?v=2.1">
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
                        <select id="region" name="region" onchange="updateProvinces()">
                            <option value="">Select a Region</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="province"><strong style="color: red;">*</strong>Province:</label>
                        <select id="province" name="province" onchange="updateCities()">
                            <option value="">Select a Province</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <label for="city"><strong style="color: red;">*</strong>City/Municipality:</label>
                        <select id="city" name="city" onchange="updateBarangays()">
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
                        <input type="password" name="password" placeholder="Enter your Password" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label><strong style="color: red;">*</strong>Confirm Password:</label>
                        <input type="password" name="confirm_password" placeholder="Re-type your Password" required>
                    </div>
                </div>

                <div class="terms">
                    <input type="checkbox" required>
                    <label>
                    I agree to the 
                    <a href="terms.php" target="_self">Terms &amp; Conditions</a>
                    </label>
                </div>


                <button type="submit">Sign Up</button>
                <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>
</div>
</body>
</html>
