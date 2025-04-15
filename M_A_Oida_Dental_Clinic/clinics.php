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
    <title>Clinics - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/clinics.css">
    <script src="assets/js/clinics.js" defer></script>
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
        <section class="branches-section">
            <div class="branches-info">
                <h1>Discover the Branches of <span class="clinic-name">M&A Oida Dental Clinic</span></h1>
                <p>The <span class="clinic-name-text">M&A Oida Dental Clinic</span> has 7 branches in Philippines, and has been providing the best dental care and services for the oral health of Filipino Families over 24 years.</p>
                
                <div class="branch-buttons">
                    <button class="branch-btn active" data-branch="commonwealth">Commonwealth Branch</button>
                    <button class="branch-btn" data-branch="north-fairview">North Fairview Branch</button>
                    <button class="branch-btn" data-branch="maligaya">Maligaya Park Branch</button>
                    <button class="branch-btn" data-branch="montalban">Montalban Branch</button>
                    <button class="branch-btn" data-branch="quiapo">Quiapo Branch</button>
                    <button class="branch-btn" data-branch="kiko">Kiko Branch</button>
                    <button class="branch-btn" data-branch="naga">Naga Branch</button>
                </div>
            </div>
            
            <div class="branch-details-container">
                <div class="branch-image-container">
                    <img id="branch-image" src="assets/photos/CWBranch_CL1.PNG" alt="M&A Oida Dental Clinic branch">
                </div>
            </div>
            
            <div class="map-container">
                <img id="branch-map" src="assets/photos/CWBranch_CL1.PNG" alt="Map location">
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 M&A Oida Dental Clinic. All Rights Reserved.</p>
    </footer>


</body>
</html>