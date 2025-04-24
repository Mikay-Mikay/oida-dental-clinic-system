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
    <title>Services - ISched of M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/services.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<header>
        <nav class="navbar">
        <a href="index.php">
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
  <!-- 1) Book Now button -->
  <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
     onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
    <button class="book-now">Book Now</button>
  </a>

  <!-- 2) Profile icon -->
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
        <section class="services-section">
            <h2> Dental Services</h2>
            <div class="services-grid">
                <div class="service-item">
                    <p>Dental Check-ups & Consultation</p>
                    <img src="assets/photos/clinics/checkup.png" alt="Dental Check-ups">
                </div>
                <div class="service-item">
                    <p>Teeth Cleaning</p>
                    <img src="assets/photos/clinics/cleaning.jpg" alt="Teeth Cleaning">
                </div>
                <div class="service-item">
                    <p>Tooth Extraction</p>
                    <img src="assets/photos/clinics/extraction.png" alt="Tooth Extraction">
                </div>
                <div class="service-item">
                    <p>Dental Fillings/Dental Bonding</p>
                    <img src="assets/photos/clinics/fillings.png" alt="Dental Fillings">
                </div>
                <div class="service-item">
                    <p>Gum Treatment and Gingivectomy</p>
                    <img src="assets/photos/clinics/gum-treatment.png" alt="Gum Treatment">
                </div>
                <div class="service-item">
                    <p>Teeth Whitening</p>
                    <img src="assets/photos/clinics/whitening.png" alt="Teeth Whitening">
                </div>
                <div class="service-item">
                    <p>Dental Veneers</p>
                    <img src="assets/photos/clinics/veeners.png" alt="Dental Veneers">
                </div>
                <div class="service-item">
                    <p>Metal Braces/Ceramic</p>
                    <img src="assets/photos/clinics/braces.png" alt="Metal Braces">
                </div>
                <div class="service-item">
                    <p>Clear Alliginers/Retainers</p>
                    <img src="assets/photos/clinics/retainer.png" alt="Clear Alligners">
                </div>
                <div class="service-item">
                    <p>Dental Crown</p>
                    <img src="assets/photos/clinics/crowns.png" alt="Dental Crown">
                </div>
                <div class="service-item">
                    <p>Dental Bridges</p>
                    <img src="assets/photos/clinics/bridges.png" alt="Dental Bridges">
                </div>
                <div class="service-item">
                    <p>Dentures (Partial & Full)</p>
                    <img src="assets/photos/clinics/dentures.png" alt="Dentures">
                </div>
                <div class="service-item">
                    <p>Dental Implants</p>
                    <img src="assets/photos/clinics/implants.png" alt="Dental Implants">
                </div>
                <div class="service-item">
                    <p>Flouride Treatment</p>
                    <img src="assets/photos/clinics/flouride.png" alt="Flouride Treatment">
                </div>
                <div class="service-item">
                    <p>Dental Sealants</p>
                    <img src="assets/photos/clinics/sealants.png" alt="Dental Sealants">
                </div>
                <div class="service-item">
                    <p>Kids Braces & Orthodontic Care</p>
                    <img src="assets/photos/clinics/kidsbrace.png" alt="Kids Braces">
                </div>
                <div class="service-item">
                    <p>Wisdom Tooth Extraction (Odontectomy)</p>
                    <img src="assets/photos/clinics/wisdomtooth.png" alt="Wisdom Tooth Extraction">
                </div>
                <div class="service-item">
                    <p>Root Canal Treatment</p>
                    <img src="assets/photos/clinics/rootcanal.png" alt="Root Canal Treatmet">
                </div>
                <div class="service-item">
                    <p>TMJ Treatment</p>
                    <img src="assets/photos/clinics/tmjtreat.png" alt="TMJ Treatment">
                </div>
                <div class="service-item">
                    <p>Intraoral X-ray</p>
                    <img src="assets/photos/clinics/intraoral.png" alt="Intraoral X-ray">
                </div>
                <div class="service-item">
                    <p>Panoramic Xray/Full Mouth Xray</p>
                    <img src="assets/photos/clinics/flouride.png" alt="Panoramic X-rays">
                </div>
                <div class="service-item">
                    <p>Lateral Cephalometric X-ray</p>
                    <img src="assets/photos/clinics/cephalometric.png" alt="Cephalometric X-ray">
                </div>
                <div class="service-item">
                    <p>Periapical Xray/Single tooth Xray</p>
                    <img src="assets/photos/clinics/periapical.png" alt="Periapical Xray/Single tooth Xray">
                </div>
                <div class="service-item">
                    <p>TMJ Transcranial Xray</p>
                    <img src="assets/photos/clinics/tmjxray.png" alt="TMJ Transcranial Xray">
                </div>
            </div>
        </section>
    </main>

    <div id="serviceModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img id="modalImage" src="" alt="Service Image" class="modal-image">
        <div class="modal-description">
            <h2 id="modalTitle"></h2>
            <p id="modalDescription"></p>
            <button class="book-now">Book Now</button>
        </div>
    </div>
</div>
<script src="assets/js/services.js?v=1.1"></script> 
</body>
</html>
