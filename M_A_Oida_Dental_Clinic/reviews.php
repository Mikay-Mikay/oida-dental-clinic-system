<?php 
require_once('session.php');
require_once('db.php');
require_once('includes/profile_functions.php');

// 1) INITIALIZE
$user = null;

// 2) FETCH LOGGED-IN USER
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

// POST: insert review
if ($_SERVER['REQUEST_METHOD']==='POST') {
  header('Content-Type: application/json');
  $d = json_decode(file_get_contents('php://input'), true);
  if (isset($d['rating'], $d['text'], $d['services'])) {
    $pid = $_SESSION['user_id'] ?? null;
    $name = !empty($d['anon'])
          ? 'Anonymous'
          : htmlspecialchars(trim($d['name'] ?? "{$user['first_name']} {$user['last_name']}"));
    $rating   = (int)$d['rating'];
    $text     = htmlspecialchars(trim($d['text']));
    $services = json_encode($d['services']);
    $ins = $conn->prepare("
      INSERT INTO reviews
        (patient_id, name, rating, text, services, date)
      VALUES (?,?,?,?,?,NOW())
    ");
    $ins->bind_param('isiss',$pid,$name,$rating,$text,$services);
    if ($ins->execute()) echo json_encode(['success'=>true]);
    else {
      http_response_code(500);
      echo json_encode(['success'=>false,'error'=>'DB insert failed']);
    }
    exit;
  }
  http_response_code(400);
  echo json_encode(['success'=>false,'error'=>'Rating, text, services required']);
  exit;
}

// GET: fetch reviews API
if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['api'])) {
  header('Content-Type: application/json');
  $res = $conn->query("
    SELECT r.*, p.profile_picture
      FROM reviews r
 LEFT JOIN patients p ON p.id = r.patient_id
   ORDER BY r.date DESC
  ");
  $out = [];
  while ($row = $res->fetch_assoc()) {
    $isAnonymous = strtolower(trim($row['name'])) === 'anonymous';
    $out[] = [
      'id'             => (int)$row['id'],
      'name'           => $row['name'],
      'rating'         => (int)$row['rating'],
      'text'           => $row['text'],
      'services'       => json_decode($row['services'], true),
      'date_display'   => date('M d, Y', strtotime($row['date'])),
      'date_raw'       => $row['date'],
      'profile_picture'=> $isAnonymous
                            ? 'assets/photos/default_avatar.png'
                            : get_profile_image_url($row['patient_id'] ?? null)
    ];
  }
  echo json_encode($out);
  exit;
}

// services list
$servicesList = [
  'Dental Check-ups & Consultation','Teeth Cleaning (Oral Prophylaxis)',
  'Tooth Extraction','Dental Fillings',
  'Gum Treatment and Gingivectomy (Periodontal Care)',
  'Teeth Whitening','Dental Veneers','Dental Bonding',
  'Metal Braces / Ceramic Braces','Clear Aligners / Retainers',
  'Dental Crown','Dental Bridges','Dentures (Partial & Full)',
  'Dental Implants','Fluoride Treatment','Dental Sealants',
  'Kids’ Braces & Orthodontic Care',
  'Wisdom Tooth Extraction (Odontectomy)',
  'Root Canal Treatment (Endodontics)','TMJ Treatment',
  'Intraoral X-ray','Panoramic X-ray / Full Mouth X-Ray',
  'Lateral Cephalometric X-Ray','Periapical X-Ray / Single Tooth X-Ray',
  'TMJ Transcranial X-ray'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reviews - ISched of M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/reviews.css">
  <link rel="stylesheet" href="assets/css/homepage.css">
  <link rel="stylesheet" href="assets/css/profile-icon.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <script>
    window.isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    window.currentUserName = <?= json_encode($user ? "{$user['first_name']} {$user['last_name']}" : "") ?>;
  </script>
  <script src="assets/js/reviews.js" defer></script>
</head>
<body>

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
      <li><a href="reviews.php"    <?= basename($_SERVER['PHP_SELF'])=='reviews.php' ? 'class="active"' : '' ?>>Reviews</a></li>
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

      <a href="<?= $user ? 'profile.php' : 'login.php' ?>"
         <?= $user ? '' : "onclick=\"alert('Please login to view profile.');\"" ?>>
        <div class="user-icon">
          <?php if ($user): ?>
            <img src="<?= get_profile_image_url($_SESSION['user_id'] ?? null) ?>?<?= time() ?>"
                 alt="Profile" class="profile-pic">
          <?php else: ?>
            <img src="assets/photos/default_avatar.png" alt="Profile" class="profile-pic">
          <?php endif; ?>
        </div>
      </a>
    </div>
  </nav>
</header>

<section class="reviews-section">
  <div class="reviews-page-header">
    <div class="header-left">
      <h1 class="header-title">Reviews</h1>
    </div>
    <div class="header-right">
      <a href="myreviews.php" class="my-reviews">My Reviews</a>
      <img id="filterToggle" class="filter-icon" src="assets/photos/filter.png" alt="Filter">
    </div>
  </div>

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

  <div id="reviewsContainer" class="reviews-container"></div>
  <div class="add-review-wrapper">
    <button id="addReviewBtn" class="cta-button">Add a Review</button>
  </div>
</section>

<!-- Review Modal -->
<div id="reviewModal" class="modal">
  <div class="modal-content review-modal">
    <span class="close">&times;</span>
    <div class="modal-header">
      <img src="<?= $user ? get_profile_image_url($_SESSION['user_id'] ?? null) : 'assets/photos/default_avatar.png' ?>" class="modal-avatar" id="modalAvatar">
      <div id="modalUsername" class="modal-username"></div>
    </div>
    <div class="modal-field">
      <label for="serviceType">Type of Service</label>
      <select id="serviceType" required>
        <option value="" disabled selected>Select a service…</option>
        <?php foreach($servicesList as $svc): ?>
          <option value="<?=htmlspecialchars($svc)?>"><?=htmlspecialchars($svc)?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="modal-field">
      <label>Your Rating</label>
      <div class="star-rating">
        <?php for($i=1;$i<=5;$i++): ?>
          <span class="star" data-value="<?=$i?>">★</span>
        <?php endfor; ?>
      </div>
      <div class="rating-text">Select your rating</div>
    </div>
    <div class="modal-field">
      <label for="reviewText">Your Feedback</label>
      <textarea id="reviewText" rows="6" maxlength="500"
        placeholder="Tell us about your experience…" required></textarea>
      <div id="wordCount" class="word-count">0 / 500 words</div>
    </div>
    <div class="modal-field anon-field">
      <input type="checkbox" id="anonToggle">
      <label for="anonToggle">Review anonymously</label>
    </div>
    <button id="submitReview" class="cta-button">Submit Review</button>
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
