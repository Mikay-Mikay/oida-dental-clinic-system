<?php
require_once('session.php');
require 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        first_name, middle_name, last_name, email,
        phone_number, date_of_birth, gender, 
        region, province, city, barangay, zip_code,
        profile_picture
    FROM patients  
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Format date of birth to DD-MM-YYYY
$formatted_dob = date('d-m-Y', strtotime($user['date_of_birth']));
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
                <img src="uploads/profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                alt="Profile Picture" class="profile-pic">
            </div>
            <div class="profile-name-header">
                <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name']); ?></h2>
                <a href="edit_profile.php" class="edit-profile-btn">Edit Profile</a>
            </div>
        </div>

            <div class="profile-info">
                <div class="info-column">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="middleName">Middle Name:</label>
                        <input type="text" id="middleName" value="<?php echo htmlspecialchars($user['middle_name'] ?? 'N/A'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="text" id="dob" value="<?php echo htmlspecialchars($formatted_dob); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                    </div>
                </div>
                <div class="info-column">
                    <div class="form-group">
                        <label for="region">Region:</label>
                        <input type="text" id="region" value="<?php echo htmlspecialchars($user['region']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="province">Province:</label>
                        <input type="text" id="province" value="<?php echo htmlspecialchars($user['province']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="city">City/Municipality:</label>
                        <input type="text" id="city" value="<?php echo htmlspecialchars($user['city']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="barangay">Barangay:</label>
                        <input type="text" id="barangay" value="<?php echo htmlspecialchars($user['barangay']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="zipCode">ZipCode:</label>
                        <input type="text" id="zipCode" value="<?php echo htmlspecialchars($user['zip_code']); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="edit-password-btn">Edit Password</button>
                <form action="logout.php" method="POST">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>