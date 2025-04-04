<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$sql = "SELECT * FROM patient_profiles WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Default values if wala pang profile
$first_name = $profile['first_name'] ?? 'N/A';
$middle_name = $profile['middle_name'] ?? 'N/A';
$last_name = $profile['last_name'] ?? 'N/A';
$region = $profile['region'] ?? 'N/A';
$province = $profile['province'] ?? 'N/A';
$city = $profile['city'] ?? 'N/A';
$barangay = $profile['barangay'] ?? 'N/A';
$zipcode = $profile['zipcode'] ?? 'N/A';
$phone_number = $profile['phone_number'] ?? 'N/A';
$birth_date = $profile['birth_date'] ?? 'N/A';
$gender = $profile['gender'] ?? 'N/A';
$email = $profile['email'] ?? 'N/A'; // Optional if included
$profile_img = $profile['profile_img'] ?? 'default_profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <a href="dashboard.php" class="back-button">&#8592; Back</a>
      <h1>My Profile</h1>
      <a href="my_bookings.php" class="my-bookings">MY BOOKINGS</a>
    </div>

    <div class="profile-picture">
      <img src="uploads/<?php echo htmlspecialchars($profile_img); ?>" alt="Profile Picture">
      <a href="edit_profile.php" class="button save-btn">Edit Profile</a>
    </div>

    <div class="profile-info">
      <div class="info-group"><span class="label">First Name:</span> <span class="value"><?php echo htmlspecialchars($first_name); ?></span></div>
      <div class="info-group"><span class="label">Region:</span> <span class="value"><?php echo htmlspecialchars($region); ?></span></div>
      <div class="info-group"><span class="label">Middle Name:</span> <span class="value"><?php echo htmlspecialchars($middle_name); ?></span></div>
      <div class="info-group"><span class="label">Province:</span> <span class="value"><?php echo htmlspecialchars($province); ?></span></div>
      <div class="info-group"><span class="label">Last Name:</span> <span class="value"><?php echo htmlspecialchars($last_name); ?></span></div>
      <div class="info-group"><span class="label">City/Municipality:</span> <span class="value"><?php echo htmlspecialchars($city); ?></span></div>
      <div class="info-group"><span class="label">Email Address:</span> <span class="value"><?php echo htmlspecialchars($email); ?></span></div>
      <div class="info-group"><span class="label">Barangay:</span> <span class="value"><?php echo htmlspecialchars($barangay); ?></span></div>
      <div class="info-group"><span class="label">Phone Number:</span> <span class="value"><?php echo htmlspecialchars($phone_number); ?></span></div>
      <div class="info-group"><span class="label">ZipCode:</span> <span class="value"><?php echo htmlspecialchars($zipcode); ?></span></div>
      <div class="info-group"><span class="label">Date of Birth:</span> <span class="value"><?php echo htmlspecialchars($birth_date); ?></span></div>
      <div class="info-group"><span class="label">Gender:</span> <span class="value"><?php echo htmlspecialchars($gender); ?></span></div>

      <p class="warning">*Always double check your new information before saving*</p>
    </div>

    <div class="button-group">
      <a href="dashboard.php" class="button cancel-btn">Back</a>
      <a href="edit_profile.php" class="button save-btn">Edit Profile</a>
    </div>
  </div>
</body>
</html>
