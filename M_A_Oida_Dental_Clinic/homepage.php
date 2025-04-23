<?php 
require_once('session.php');
require_once('db.php'); // Add this if missing

// Move user data fetching to the top
if(isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT first_name, profile_picture FROM patients WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Add error handling
    if(!$user) {
        die("User not found in database");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="assets/js/homepage.js"></script>
    <script>
        function redirectToProfile() {
    window.location.href = 'profile.php'; // Palitan ito ng tamang URL para sa profile page
}
    </script>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
            <?php if(isset($_SESSION['user_id']) && isset($user)): ?>
                <div class="welcome-message">
                    Welcome, <strong><?= htmlspecialchars($user['first_name']) ?>!</strong>
                </div>
            <?php endif; ?>

            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="clinics.php">Clinics</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>

            <div class="nav-right">
    <!-- 1. Book Now button -->
    <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'javascript:void(0);' ?>"
       onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\'); window.location.href=\'login.php\';' ?>">
        <button class="book-now">Book Now</button>
    </a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- 2. Profile icon -->
        <a href="profile.php"
           onclick="/* same login check if you need it */">
            <div class="user-icon">
                <?php if (!empty($user['profile_picture'])): ?>
                  <img src="uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>?<?= time() ?>"
                       alt="Profile Picture" class="profile-pic">
                <?php else: ?>
                  <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>
        </a>

        <!-- 3. Notification bell -->
        <div class="notification-wrapper">
            <div class="notification-toggle">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="notification-dropdown">
                <p class="empty-message">No notifications yet</p>
            </div>
        </div>
    <?php endif; ?>
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

        <div class="stats">
            <div class="stat">
                <img src="assets/photos/location icon.png" 
                    alt="Location icon" class="stat-icon">
                <span class="stat-text">
                <strong>7 Clinics</strong> in the Philippines
                </span>
            </div>

            <div class="stat">
                <img src="assets/photos/customers icon.png" 
                    alt="Customers icon" class="stat-icon">
                <span class="stat-text">
                Over <strong>50,000</strong> Customers
                </span>
            </div>
        </div>

        <ul class="features">
            <li>Clean &amp; Safe Environment</li>
            <li>Friendly Staff</li>
            <li>Professional Dentists</li>
        </ul>

        </div>
        <div class="image-container">
            <img src="assets/photos/clinic.jpg"  alt="Clinic Front">
            <img src="assets/photos/clinic1.jpg" alt="Dental Chair">
            <img src="assets/photos/clinic2.jpg" alt="Dentists at Work">
            <img src="assets/photos/clinics/veeners.png" alt="Dental Veneers">
        </div>
    </section>
    <footer>
        <p>&copy; 2025 ISched of M&A Oida Dental Clinic. All Rights Reserved.</p>
    </footer>
</body>
</html>
