<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - ISched of M&A Oida Dental Clinic</title>
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
        <a href="index.php">
            <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
        </a>
        <ul class="nav-links">
            <li><a href="index.php"    class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php')   echo 'active'; ?>">Home</a></li>
            <li><a href="clinics.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='clinics.php') echo 'active'; ?>">Clinics</a></li>
            <li><a href="services.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='services.php') echo 'active'; ?>">Services</a></li>
            <li><a href="about.php"    class="<?php if(basename($_SERVER['PHP_SELF'])=='about.php')    echo 'active'; ?>">About</a></li>
            <li><a href="reviews.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='reviews.php') echo 'active'; ?>">Reviews</a></li>
            <li><a href="contact.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='contact.php') echo 'active'; ?>">Contact Us</a></li>
        </ul>

            <div class="nav-right">
                <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
    <button class="book-now">Book Now</button>
</a>

<a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\');'; ?>">
    <div class="user-icon">
        <i class="fa-solid fa-user"></i>
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

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll(".nav-links a");

        navLinks.forEach(link => {
            if (link.getAttribute("href") === currentPath.split("/").pop()) {
                link.classList.add("active");
            }
        });
    });
</script>

</body>
</html>
