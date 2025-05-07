<?php 
require_once('session.php');
require_once('db.php');
require_once('includes/profile_functions.php');

// 1) INITIALIZE
$user = null;

// 2) FETCH LOGGED-IN USER
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
      SELECT first_name, profile_picture 
        FROM patients 
       WHERE id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc() ?: null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services - ISched of M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/services.css">
  <link rel="stylesheet" href="assets/css/homepage.css">
  <link rel="stylesheet" href="assets/css/profile-icon.css">
  <script src="assets/js/services.js" defer></script>
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<header>
  <nav class="navbar">
    <a href="index.php" class="logo-link">
      <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
    </a>

    <!-- only show welcome if logged in -->
    <?php if ($user !== null): ?>
      <div class="welcome-message">
        Welcome, <strong><?= htmlspecialchars($user['first_name']) ?>!</strong>
      </div>
    <?php endif; ?>

    <ul class="nav-links">
      <li><a href="index.php"
             <?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'class="active"'; ?>>
          Home</a></li>
      <li><a href="services.php"
             <?php if(basename($_SERVER['PHP_SELF'])=='services.php') echo 'class="active"'; ?>>
          Services</a></li>
      <li><a href="about.php"
             <?php if(basename($_SERVER['PHP_SELF'])=='about.php') echo 'class="active"'; ?>>
          About</a></li>
      <li><a href="reviews.php"
             <?php if(basename($_SERVER['PHP_SELF'])=='reviews.php') echo 'class="active"'; ?>>
          Reviews</a></li>
      <li><a href="contact.php"
             <?php if(basename($_SERVER['PHP_SELF'])=='contact.php') echo 'class="active"'; ?>>
          Contact Us</a></li>
    </ul>

    <div class="nav-right">
      <!-- BOOK NOW -->
      <a href="<?= $user !== null ? 'bookings.php' : 'login.php'; ?>"
         <?= $user === null 
              ? "onclick=\"alert('Please login to book an appointment.');\"" 
              : '' ?>>
        <button class="book-now">Book Now</button>
      </a>

       <!-- NOTIFICATIONS: only when logged in -->
       <?php if ($user !== null): ?>
        <div class="notification-wrapper">
          <div class="notification-toggle">
            <i class="fa-solid fa-bell"></i>
          </div>
          <div class="notification-dropdown">
            <p class="empty-message">No notifications yet</p>
          </div>
        </div>
      <?php endif; ?>

      <!-- PROFILE ICON -->
      <a href="<?= $user !== null ? 'profile.php' : 'login.php'; ?>"
         <?= $user === null 
              ? "onclick=\"alert('Please login to view profile.');\"" 
              : '' ?>>
        <div class="user-icon">
          <?php if ($user): ?>
            <img
              src="<?= get_profile_image_url($_SESSION['user_id'] ?? null) ?>?<?= time() ?>"
              alt="Profile Picture"
              class="profile-pic">
          <?php else: ?>
            <img src="assets/photos/default_avatar.png"
                 alt="Profile Icon"
                 class="profile-pic">
          <?php endif; ?>
        </div>
      </a>
    </div>
  </nav>
</header>

<main>
  <section class="services-section">
    <h2>Dental Services</h2>
    <div class="services-grid">
      <?php
        // list of services with image filename
        $services = [
          ['Dental Check-ups & Consultation','checkup.png'],
          ['Teeth Cleaning','cleaning.png'],
          ['Tooth Extraction','extraction.png'],
          ['Dental Fillings/Dental Bonding','fillings.png'],
          ['Gum Treatment and Gingivectomy','gum-treatment.png'],
          ['Teeth Whitening','whitening.png'],
          ['Dental Veneers','veneers.png'],
          ['Metal Braces/Ceramic','braces.png'],
          ['Clear Aligners/Retainers','retainer.png'],
          ['Dental Crown','crowns.png'],
          ['Dental Bridges','bridges.png'],
          ['Dentures (Partial & Full)','dentures.png'],
          ['Dental Implants','implants.png'],
          ['Fluoride Treatment','flouride.png'],
          ['Dental Sealants','sealants.png'],
          ['Kids’ Braces & Orthodontic Care','kidsbrace.png'],
          ['Wisdom Tooth Extraction (Odontectomy)','wisdomtooth.png'],
          ['Root Canal Treatment','rootcanal.png'],
          ['TMJ Treatment','tmjtreat.png'],
          ['Intraoral X-ray','intraoral.png'],
          ['Panoramic X-ray / Full Mouth X-ray','panoramic.png'],
          ['Lateral Cephalometric X-ray','cephalometric.png'],
          ['Periapical X-ray / Single Tooth X-ray','periapical.png'],
          ['TMJ Transcranial X-ray','tmjxray.png'],
        ];
        foreach ($services as $svc):
      ?>
        <div class="service-item">
          <p><?= htmlspecialchars($svc[0]) ?></p>
          <img src="assets/photos/clinics/<?= $svc[1] ?>"
               alt="<?= htmlspecialchars($svc[0]) ?>">
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<!-- Modal -->
<div id="serviceModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <img id="modalImage" src="" alt="Service Image" class="modal-image">
    <div class="modal-description">
      <h2 id="modalTitle"></h2>
      <p id="modalDescription"></p>
      <button class="modal-book-btn">Book Now</button>
    </div>
  </div>
</div>

<footer>
  <p>&copy; 2025 ISched of M&A Oida Dental Clinic. All Rights Reserved.</p>
</footer>

<script>
  // pass PHP login state
  const isLoggedIn = <?= $user ? 'true' : 'false' ?>;
</script>

<script>  document.addEventListener("DOMContentLoaded", function () {
    const bellToggle = document.querySelector(".notification-toggle");
    const wrapper = document.querySelector(".notification-wrapper");

    bellToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        wrapper.classList.toggle("show");
    });

    document.addEventListener("click", function (e) {
        if (!wrapper.contains(e.target)) {
            wrapper.classList.remove("show");
        }
    });
});
  </script>
</body>
</html>
