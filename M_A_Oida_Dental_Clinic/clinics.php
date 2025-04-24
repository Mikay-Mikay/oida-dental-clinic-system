<?php 
// clinics.php
require_once 'session.php';
require_once 'db.php';

// fetch user if logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT first_name, profile_picture FROM patients WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Clinics – ISched of M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/clinics.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<header>
        <nav class="navbar">
        <a href="homepage.php">
            <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
        </a>
            <?php if(isset($_SESSION['user_id']) && isset($user)): ?>
                <div class="welcome-message">
                    Welcome, <strong><?= htmlspecialchars($user['first_name']) ?>!</strong>
                </div>
            <?php endif; ?>

            <ul class="nav-links">
            <li><a href="index.php"    class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php')   echo 'active'; ?>">Home</a></li>
            <li><a href="clinics.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='clinics.php') echo 'active'; ?>">Clinics</a></li>
            <li><a href="services.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='services.php') echo 'active'; ?>">Services</a></li>
            <li><a href="about.php"    class="<?php if(basename($_SERVER['PHP_SELF'])=='about.php')    echo 'active'; ?>">About</a></li>
            <li><a href="reviews.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='reviews.php') echo 'active'; ?>">Reviews</a></li>
            <li><a href="contact.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='contact.php') echo 'active'; ?>">Contact Us</a></li>
        </ul>

<div class="nav-right">
                <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
    <button class="book-now">Book Now</button>
</a>

<a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\');'; ?>">
    <div class="user-icon">
        <i class="fa-solid fa-user"></i>
    </div>
</a>
            </div>
        </nav>
    </header>

<main>
  <section class="branches-section">
    <div class="branches-info">
      <h1>Discover the Branches of <span class="clinic-name">M&A Oida Dental Clinic</span></h1>
      <p>
        The <strong style="color: #124085">M&amp;A Oida Dental Clinic</strong> has <strong style="color: #124085">7 branches</strong> in the
        Philippines and has provided excellent dental care for over <strong style="color: #124085;">24 years</strong>.
      </p>
      <div class="branch-buttons">
        <button class="branch-btn active" data-branch="commonwealth">Commonwealth</button>
        <button class="branch-btn" data-branch="north-fairview">North Fairview</button>
        <button class="branch-btn" data-branch="maligaya">Maligaya Park</button>
        <button class="branch-btn" data-branch="montalban">Montalban</button>
        <button class="branch-btn" data-branch="quiapo">Quiapo</button>
        <button class="branch-btn" data-branch="kiko">Kiko</button>
        <button class="branch-btn" data-branch="naga">Naga</button>
      </div>
    </div>

    <div class="branch-display">
      <div class="branch-image-container">
        <img id="branch-image"
             src="assets/photos/CWBranch_CL1.png"
             alt="Branch Photo">
      </div>

      <div class="map-container">
        <iframe
          id="branch-map"
          width="100%" height="100%" style="border:0;"
          allowfullscreen="" loading="lazy">
        </iframe>
        <div id="map-notice" class="map-notice" style="display:none;">
          <p><em>Map for this branch is not yet available.</em></p>
        </div>
      </div>
    </div>
  </section>
</main>

<footer>
  <p>© 2025 M&A Oida Dental Clinic. All Rights Reserved.</p>
</footer>

<script>
const data = {
  "commonwealth": {
    img: "CWBranch_CL1.png",
    map: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.123456789012!2d121.0700000!3d14.6500000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b9f4dc2abcd5%3A0xabcdef1234567890!2s3%20Martan%20St%2C%20Commonwealth%2C%20Quezon%20City!5e0!3m2!1sen!2sph!4v1700000000000"
  },
  "north-fairview": {
    img: "NFBranch_CL1.png",
    map: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.987654321098!2d121.0370000!3d14.6660000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b8ef1234abcd%3A0xd1f2a3b4c5d6e7f8!2sRegalado%20Hiway%2C%20North%20Fairview%2C%20Quezon%20City!5e0!3m2!1sen!2sph!4v1700000001000"
  },
  "maligaya": {
    img: "MPBranch_CL1.png",
    map: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.543210987654!2d121.0000000!3d14.6900000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7cde123abcd%3A0xabcdef9876543210!2sPeralta%20St%2C%20Maligaya%20Park%2C%20Caloocan!5e0!3m2!1sen!2sph!4v1700000000002"
  },
  "montalban": {
    img: "MontalbanBranch_CL1.png",
    map: ""
  },
  "quiapo": {
    img: "QuiapoBranch_CL1.png",
    map: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.123450000000!2d120.9780000!3d14.6000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397aebc1234abcd%3A0xabcdef1234009876!2sEvangelista%20St%2C%20Quiapo%2C%20Manila!5e0!3m2!1sen!2sph!4v1700000000003"
  },
  "kiko": {
    img: "KikoBranch_CL1.png",
    map: ""
  },
  "naga": {
    img: "NagaBranch_CL1.png",
    map: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3800.123450000000!2d123.1700000!3d13.6160000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b1abcd1234efgh%3A0xabcdef9876123456!2sPanganiban%20Drive%2C%20Naga%20City!5e0!3m2!1sen!2sph!4v1700000000004"
  }
};

document.querySelectorAll('.branch-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelector('.branch-btn.active').classList.remove('active');
    btn.classList.add('active');

    const key = btn.dataset.branch;
    // swap image
    document.getElementById('branch-image').src = `assets/photos/${data[key].img}`;

    // map vs notice
    const iframe = document.getElementById('branch-map'),
          notice = document.getElementById('map-notice');

    if (data[key].map) {
      iframe.src = data[key].map;
      iframe.style.display = 'block';
      notice.style.display = 'none';
    } else {
      iframe.style.display = 'none';
      notice.style.display = 'flex';
    }
  });
});

// trigger the initial “click” on whichever button already has .active
document.querySelector('.branch-btn.active').click();
</script>


</body>
</html>
