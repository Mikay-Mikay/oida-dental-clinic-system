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
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="assets/js/contact.js"></script>
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


    <div class="contact-container">
        <img src="clinic1.jpg" alt="Dental Chair" class="contact-image">
        
        <div class="contact-info">
            <h2>Contact Us</h2>
            <p>We would love to hear from you! Whether you have questions about our services or simply want to learn more about our clinic, our friendly team is here to help.</p>
            
            <div class="contact-details">
                <strong>Commonwealth Branch:</strong> 0938 195 7571<br>
                <strong>North Fairview Branch:</strong> 0918 578 2346<br>
                <strong>Maligaya Branch:</strong> 0908 637 2386<br>
                <strong>San Isidro Branch:</strong> 0920 600 6056<br>
                <strong>Quiapo Branch:</strong> 0928 422 3905<br>
                <strong>Kiko Branch:</strong> 0928 422 3905<br>
                <strong>Naga Branch:</strong> 0963 377 8137<br><br>
                
                <strong>Email:</strong> <a href="mailto:naioby_2007@yahoo.ph">naioby_2007@yahoo.ph</a>
            </div>
        </div>
    </div>
    

</body>
</html>