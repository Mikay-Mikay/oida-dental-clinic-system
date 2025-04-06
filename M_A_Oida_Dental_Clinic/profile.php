<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Kunin ang profile info ng user
$sql = "SELECT * FROM patient_profiles WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Default profile pic kung wala pa
$profile_img = $profile['profile_img'] ?? 'default_profile.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <a href="homepage.html" class="back-button">&#8592; Back</a>
      <h1>My Profile</h1>
      <a href="#" class="my-bookings">MY BOOKINGS</a>
    </div>

    <div class="profile-picture">
      <img src="uploads/<?php echo htmlspecialchars($profile_img); ?>" alt="Profile Picture">
      <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_img" required>
        <button type="submit">Change Profile Picture</button>
      </form>
    </div>

    <form action="update_profile.php" method="POST" class="profile-info">
      <div class="info-group">
        <span class="label">First Name:</span>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" required>
      </div>

      <div class="info-group">
        <span class="label">Middle Name:</span>
        <input type="text" name="middle_name" value="<?php echo htmlspecialchars($profile['middle_name'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Last Name:</span>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" required>
      </div>

      <div class="info-group">
        <span class="label">Region:</span>
        <input type="text" name="region" value="<?php echo htmlspecialchars($profile['region'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Province:</span>
        <input type="text" name="province" value="<?php echo htmlspecialchars($profile['province'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">City:</span>
        <input type="text" name="city" value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Barangay:</span>
        <input type="text" name="barangay" value="<?php echo htmlspecialchars($profile['barangay'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Zip Code:</span>
        <input type="text" name="zipcode" value="<?php echo htmlspecialchars($profile['zipcode'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Phone Number:</span>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($profile['phone_number'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Date of Birth:</span>
        <input type="date" name="birth_date" value="<?php echo htmlspecialchars($profile['birth_date'] ?? ''); ?>">
      </div>

      <div class="info-group">
        <span class="label">Gender:</span>
        <select name="gender">
          <option value="">Select</option>
          <option value="Male" <?php echo ($profile['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
          <option value="Female" <?php echo ($profile['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
        </select>
      </div>

      <div class="info-group">
        <span class="label">Email:</span>
        <input type="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" required>
      </div>

      <button type="submit">Save Changes</button>
    </form>
  </div>
</body>
</html>
