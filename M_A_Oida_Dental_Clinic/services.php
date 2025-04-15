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
    <title>Our Services - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/services.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
            <?php if(isset($_SESSION['user_id']) && isset($user)): ?>
                <div class="welcome-message">Welcome, <?= htmlspecialchars($user['first_name']) ?></div>
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
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'javascript:void(0);' ?>"
                onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\'); window.location.href=\'login.php\';' ?>">
                <div class="user-icon">
                    <?php if(isset($user) && !empty($user['profile_picture'])): ?>
                        <img src="uploads/profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>?<?php echo time() ?>" 
                        alt="Profile Picture" class="profile-pic">
                    <?php else: ?>
                        <i class="fa-solid fa-user"></i>
                    <?php endif; ?>
                </div>
            </a>
            <?php if(isset($_SESSION['user_id'])): ?><div class="notification-wrapper">
                <div class="notification-toggle">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="notification-dropdown">
                    <p class="empty-message">No notifications yet</p>
                </div>
    </div>
    <?php endif; ?>

            <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'javascript:void(0);' ?>"
            onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\'); window.location.href=\'login.php\';' ?>">
            <button class="book-now">Book Now</button>
        </a>
    </div>
        </nav>
    </header>


    <main>
        <section class="services-section">
            <h2> Dental Services</h2>
            <div class="services-grid">
                <div class="service-item">
                    <p>Dental Check-ups & Consultation</p>
                    <img src="assets/photos/checkup.jpg" alt="Dental Check-ups">
                </div>
                <div class="service-item">
                    <p>Teeth Cleaning (Oral Prophylaxis)</p>
                    <img src="assets/photos/cleaning.jpg" alt="Teeth Cleaning">
                </div>
                <div class="service-item">
                    <p>Tooth Extraction</p>
                    <img src="assets/photos/extraction.jpg" alt="Tooth Extraction">
                </div>
                <div class="service-item">
                    <p>Dental Fillings</p>
                    <img src="assets/photos/fillings.jpg" alt="Dental Fillings">
                </div>
                <div class="service-item">
                    <p>Gum Treatment (Periodontal Care)</p>
                    <img src="assets/photos/gum-treatment.jpg" alt="Gum Treatment">
                </div>
                <div class="service-item">
                    <p>Teeth Whitening</p>
                    <img src="assets/photos/whitening.jpg" alt="Teeth Whitening">
                </div>
                <div class="service-item">
                    <p>Dental Veneers</p>
                    <img src="assets/photos/veeners.jpg" alt="Dental Veneers">
                </div>
                <div class="service-item">
                    <p>Dental Bonding</p>
                    <img src="assets/photos/bonding.jpg" alt="Dental Bonding">
                </div>
                <div class="service-item">
                    <p>Metal Braces</p>
                    <img src="assets/photos/braces.jpg" alt="Metal Braces">
                </div>
                <div class="service-item">
                    <p>Ceramic Braces</p>
                    <img src="assets/photos/ceramic.jpg" alt="Ceramic Braces">
                </div>
                <div class="service-item">
                    <p>Clear Alliginers (Invisalign, ClearCorrect, etc.)</p>
                    <img src="assets/photos/alligner.jpg" alt="Clear Alligners">
                </div>
                <div class="service-item">
                    <p>Retainers</p>
                    <img src="assets/photos/retainer.jpg" alt="Retainers">
                </div>
                <div class="service-item">
                    <p>Dental Crown</p>
                    <img src="assets/photos/crown.jpg" alt="Dental Crown">
                </div>
                <div class="service-item">
                    <p>Dental Bridges</p>
                    <img src="assets/photos/bridges.jpg" alt="Dental Bridges">
                </div>
                <div class="service-item">
                    <p>Dentures (Partial & Full)</p>
                    <img src="assets/photos/denture.jpg" alt="Dentures">
                </div>
                <div class="service-item">
                    <p>Dental Implants</p>
                    <img src="assets/photos/implants.jpg" alt="Dental Implants">
                </div>
                <div class="service-item">
                    <p>Flouride Treatment</p>
                    <img src="assets/photos/flouride.jpg" alt="Flouride Treatment">
                </div>
                <div class="service-item">
                    <p>Dental Sealants</p>
                    <img src="assets/photos/sealant.jpg" alt="Dental Sealants">
                </div>
                <div class="service-item">
                    <p>Kids Braces & Orthodontic Care</p>
                    <img src="assets/photos/kids.jpg" alt="Kids Braces">
                </div>
                <div class="service-item">
                    <p>Wisdom Tooth Extraction (Odontectomy)</p>
                    <img src="assets/photos/wisdom.jpg" alt="Wisdom Tooth Extraction">
                </div>
                <div class="service-item">
                    <p>Root Canal Treatment</p>
                    <img src="assets/photos/root.jpg" alt="Root Canal Treatmet">
                </div>
                <div class="service-item">
                    <p>TMJ Treatment</p>
                    <img src="assets/photos/tmj.jpg" alt="TMJ Treatment">
                </div>
                <div class="service-item">
                    <p>Intraoral X-ray</p>
                    <img src="assets/photos/intraoral.jpg" alt="Intraoral X-ray">
                </div>
                <div class="service-item">
                    <p>Panoramic X-rays</p>
                    <img src="assets/photos/panoramic.jpg" alt="Panoramic X-rays">
                </div>
                <div class="service-item">
                    <p>Cephalometric X-ray</p>
                    <img src="assets/photos/cephalometric.jpg" alt="Cephalometric X-ray">
                </div>
            </div>
        </section>
    </main>

    <script src="assets/js/services.js"></script> 
</body>
</html>
