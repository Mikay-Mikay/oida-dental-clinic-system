<?php
require_once('session.php'); // Centralized session check
require 'db.php';

$user_id = $_SESSION['user_id']; // Now guaranteed by session.php

$sql = "
    SELECT 
        first_name, middle_name, last_name, email,
        profile_picture, phone_number, birth_date, gender, 
        region, province, city, barangay, zipcode
    FROM patient_profiles  
    WHERE patient_id = ?
";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error); // Debugging output
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="assets/css/profiles.css">
</head>
<body>
    <div class="profile-container">
        <div class="header">
          <a href="homepage.php" class="back-btn">Back</a>
            <h1 class="profile-title">My Profile</h1>
            <div class="bookings-btn">
                <button>MY BOOKINGS</button>
                <p class="booking-note">"Check your bookings here!"</p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-pic-section">
                <div class="profile-pic-container">
                    <img src="profile-placeholder.jpg" alt="Profile Picture" class="profile-pic">
                </div>
                <div class="profile-name-header">
                    <h2>VICTORIA ANNE GARCIA</h2>
                    <button class="edit-profile-btn">Edit Profile</button>
                </div>
            </div>

            <div class="profile-info">
                <div class="info-column">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" value="Victoria Anne" readonly>
                    </div>
                    <div class="form-group">
                        <label for="middleName">Middle Name:</label>
                        <input type="text" id="middleName" value="N/A" readonly>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" value="Garcia" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" value="vicanne26@gmail.com" readonly>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" value="09278680398" readonly>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="text" id="dob" value="06-11-97" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" value="Female" readonly>
                    </div>
                </div>
                <div class="info-column">
                    <div class="form-group">
                        <label for="region">Region:</label>
                        <input type="text" id="region" value="NCR" readonly>
                    </div>
                    <div class="form-group">
                        <label for="province">Province:</label>
                        <input type="text" id="province" value="Metro Manila" readonly>
                    </div>
                    <div class="form-group">
                        <label for="city">City/Municipality:</label>
                        <input type="text" id="city" value="Caloocan City" readonly>
                    </div>
                    <div class="form-group">
                        <label for="barangay">Barangay:</label>
                        <input type="text" id="barangay" value="Barangay 177" readonly>
                    </div>
                    <div class="form-group">
                        <label for="zipCode">ZipCode:</label>
                        <input type="text" id="zipCode" value="1400" readonly>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="edit-password-btn">Edit Password</button>
                <button class="logout-btn">Logout</button>
            </div>
        </div>
    </div>
</body>
</html>