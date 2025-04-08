<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="clinics.php">Clinics</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <div class="nav-right">
            <div class="user-icon">
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
                   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\');'; ?>">
                    <i class='fa-solid fa-user' style='font-size: 24px;'></i>
                </a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
    <button class="book-now">Book Now</button>
</a>
        </nav>
    </header>

    <section class="about-section">
        <div class="about-content">
            <h2><span class="highlight">Meet Our</span> <strong>Dental Experts</strong></h2>
            <p>
                Welcome to <a href="#">M&A Oida Dental Clinic</a>, where excellence in dental care has been a tradition for 
                <strong>over 24 years</strong>. At the heart of our clinic are 
                <a href="#">Dr. Marcial Oida</a> and <a href="#">Dr. Ardeen Dofiles Oida</a>, a dedicated married couple who 
                lead with both expertise and compassion.
            </p>
            <p>
                Their combined experience and commitment to staying at the forefront of dental innovation ensure that every 
                patient receives personalized, high-quality treatment. Together, they create a warm and welcoming environment 
                that has made our clinic a trusted name in the community.
            </p>
        </div>

        <div class="experts-container">
            <div class="expert">
                <img src="assets/photos/dr-ardeen.png" alt="Dr. Ardeen Dofiles Oida" class="expert-img">
                <div class="expert-label">Dr. Ardeen Dofiles Oida</div>
            </div>
            <div class="expert">
                <img src="assets/photos/dr-marcial.png" alt="Dr. Marcial Oida" class="expert-img">
                <div class="expert-label">Dr. Marcial Oida</div>
            </div>
        </div>
    </section>

    <script src="assets/js/about.js"></script>
</body>
</html>
