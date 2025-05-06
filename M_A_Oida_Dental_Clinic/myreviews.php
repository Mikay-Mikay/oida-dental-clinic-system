<?php 
require_once('session.php');
require_once('db.php');

// 1) FETCH LOGGED‑IN USER
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
      SELECT first_name, last_name, profile_picture 
        FROM patients 
       WHERE id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc() ?: null;
}

// 2) FETCH ONLY THIS USER’S REVIEWS
$reviews = [];
if ($user) {
    $stmt = $conn->prepare("
      SELECT 
        r.id,
        r.name,
        r.rating,
        r.text,
        r.services,
        r.date,
        p.profile_picture
      FROM reviews r
      LEFT JOIN patients p
        ON p.id = r.patient_id
      WHERE r.patient_id = ?
      ORDER BY r.date DESC
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Reviews – M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/homepage.css">
  <link rel="stylesheet" href="assets/css/myreviews.css">
  <link rel="stylesheet" href="assets/css/reviews.css">

  <script>
    window.isLoggedIn      = <?= $user ? 'true' : 'false' ?>;
    window.currentUserName = <?= $user 
      ? json_encode($user['first_name'] . ' ' . $user['last_name']) 
      : '""' 
    ?>;
    window.currentUserId   = <?= $currentUserId ?: 'null' ?>;
  </script>
</head>
<body>

  <!-- HEADER / NAV (copy exactly from reviews.php header) -->
  <header>
  <nav class="navbar">
    <a href="index.php" class="logo-link">
      <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
    </a>

    <?php if ($user !== null): ?>
      <div class="welcome-message">
        Welcome, <strong><?= htmlspecialchars($user['first_name']) ?>!</strong>
      </div>
    <?php endif; ?>

    <ul class="nav-links">
      <li><a href="index.php"      <?= basename($_SERVER['PHP_SELF'])=='index.php' ? 'class="active"' : '' ?>>Home</a></li>
      <li><a href="services.php"   <?= basename($_SERVER['PHP_SELF'])=='services.php' ? 'class="active"' : '' ?>>Services</a></li>
      <li><a href="about.php"      <?= basename($_SERVER['PHP_SELF'])=='about.php' ? 'class="active"' : '' ?>>About</a></li>
      <li><a href="reviews.php" <?= in_array(basename($_SERVER['PHP_SELF']), ['reviews.php', 'myreviews.php']) ? 'class="active"' : '' ?>>Reviews</a></li>
      <li><a href="contact.php"    <?= basename($_SERVER['PHP_SELF'])=='contact.php' ? 'class="active"' : '' ?>>Contact Us</a></li>
    </ul>

    <div class="nav-right">
      <a href="<?= $user ? 'bookings.php' : 'login.php' ?>"
         <?= $user ? '' : "onclick=\"alert('Please login to book an appointment.');\"" ?>>
        <button class="book-now">Book Now</button>
      </a>

      <?php if ($user): ?>
        <div class="notification-wrapper">
          <div class="notification-toggle"><i class="fa-solid fa-bell"></i></div>
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
    <?php else: ?>
        <img
        src="assets/photos/profile_icon.png"
        alt="Default Profile Icon"
        class="profile-pic">
        
    <?php endif; ?>
  </div>
</a>
    </div>
  </nav>
</header>
</header>

  <!-- MAIN CONTENT -->
  <main class="reviews-section">
    <div class="reviews-page-header">
      <a href="reviews.php" class="back-button">Back</a>
      <h1 class="header-title">My Reviews</h1>
      <img id="filterToggle" class="filter-icon" src="assets/photos/filter.png" alt="Filter">
    </div>

    <!-- FILTER DROPDOWN -->
    <div id="filterDropdown" class="filter-dropdown">
      <h3>Sort By Time:</h3>
      <div class="filter-group">
        <label><input type="radio" name="time" value="all-time" checked> All Time</label>
        <label><input type="radio" name="time" value="past-few-days"> Past Few Days</label>
        <label><input type="radio" name="time" value="past-few-weeks"> Past Few Weeks</label>
        <label><input type="radio" name="time" value="past-few-months"> Past Few Months</label>
        <label><input type="radio" name="time" value="past-few-years"> Past Few Years</label>
      </div>
      <hr>
      <h3>Sort By Ratings:</h3>
      <div class="filter-group">
        <label><input type="radio" name="rating" value="all" checked> All Ratings</label>
        <label><input type="radio" name="rating" value="5"> ★★★★★ 5 Star</label>
        <label><input type="radio" name="rating" value="4"> ★★★★☆ 4 Star</label>
        <label><input type="radio" name="rating" value="3"> ★★★☆☆ 3 Star</label>
        <label><input type="radio" name="rating" value="2"> ★★☆☆☆ 2 Star</label>
        <label><input type="radio" name="rating" value="1"> ★☆☆☆☆ 1 Star</label>
      </div>
      <button id="applyFilter" class="apply-filters">Filter</button>
    </div>

    <!-- REVIEWS CONTAINER: rendered server‑side -->
    <div id="reviewsContainer" class="reviews-container">
    <?php if (empty($reviews)): ?>
  <p class="no-reviews">No reviews found.</p>
<?php else: ?>
  <?php foreach ($reviews as $r): 
    $services = json_decode($r['services'], true) ?: [];
    $stars    = str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']);
    $date     = date('M d, Y', strtotime($r['date']));
    $isAnon   = strtolower(trim($r['name'])) === 'anonymous';
    $avatar   = $isAnon 
                ? 'assets/photos/profile_icon.png' 
                : ('uploads/profiles/' . ($r['profile_picture'] ?: 'profile_icon.png'));
  ?>
    <div class="review-item">
      <div class="review-header">
        <img src="<?= htmlspecialchars($avatar) ?>" alt="" class="review-avatar">
        <div class="reviewer-name"><?= htmlspecialchars($r['name']) ?></div>
        <div class="review-rating"><?= $stars ?></div>
      </div>
      <div class="review-content"><?= nl2br(htmlspecialchars($r['text'])) ?></div>
      <?php if ($services): ?>
        <div class="review-service"><?= htmlspecialchars(implode(', ', $services)) ?></div>
      <?php endif; ?>
      <div class="review-date"><?= $date ?></div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
    </div>
  <footer>© 2025 M&amp;A Oida Dental Clinic. All Rights Reserved.</footer>

  <!-- load your existing reviews.js -->
  <script src="assets/js/reviews.js"></script>
</body>
</html>
