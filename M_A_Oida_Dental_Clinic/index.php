<?php 
require_once('session.php');
require_once('db.php');

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
  <title>Home - ISched of M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/homepage.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="assets/js/homepage.js" defer></script>
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
          <?php if ($user && !empty($user['profile_picture'])): ?>
            <img
              src="uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>?<?= time() ?>"
              alt="Profile Picture"
              class="profile-pic">
          <?php elseif ($user): ?>
            <i class="fa-solid fa-user"></i>
          <?php else: ?>
            <img src="assets/photos/profile_icon.png"
                 alt="Profile Icon"
                 class="profile-pic">
          <?php endif; ?>
        </div>
      </a>
    </div>
  </nav>
</header>

<section id="home" class="hero">
  <div class="hero-text">
    <h1>Welcome to <span class="highlight">ISched of M&A Oida Dental Clinic</span></h1>
    <p><em>Where Every <span class="italic">Smile</span> Matters.</em></p>
    <p class="intro-text">
      With just a few clicks, you can organize your dental appointments using our
      advanced online booking system. iSched will assist you with check-ups,
      treatments, and follow-up appointments with high-level organization specific
      to your needs.
          </p>
    <ul class="features">
      <li>Clean &amp; Safe Environment</li>
      <li>Friendly Staff</li>
      <li>Professional Dentists</li>
    </ul>
  </div>
  <div class="image-container">
    <img src="assets/photos/clinic.jpg"  alt="Clinic Front">
    <img src="assets/photos/clinic1.jpg" alt="Dental Chair">
    <img src="assets/photos/regalado_branch.png" alt="Dentists at Work">
    <img src="assets/photos/clinics/veneers.png" alt="Dental Veneers">
  </div>
</section>

<footer>
  <p>&copy; 2025 ISched of M&A Oida Dental Clinic. All Rights Reserved.</p>
</footer>
</body>
</html>
