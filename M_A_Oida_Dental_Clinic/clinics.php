<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinics - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/clinics.css?v=1.1">
    <script src="assets/js/clinics.js" defer></script>
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
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
                   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\');'; ?>">
                    <div class="user-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
                   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
                    <button class="book-now">Book Now</button>
                </a>
            </div>
        </nav>
    </header>


    <main>
        <section class="clinics-info">
            <h2>Discover the Branches of<br><span>M&A Oida Dental Clinic</span></h2>
            <p>The <em>M&A Oida Dental Clinic</em> has <strong>7 branches in the Philippines</strong>,<br> 
            providing the best dental care services for over <strong>24 years</strong>.</p>
            
            
        </section>

        <div class="branches">
            <button class="branch-btn" data-branch="Commonwealth">Commonwealth Branch</button>
            <button class="branch-btn" data-branch="North Fairview">North Fairview Branch</button>
            <button class="branch-btn" data-branch="Maligaya Park">Maligaya Park Branch</button>
            <button class="branch-btn" data-branch="San Isidro">San Isidro Branch</button>
            <button class="branch-btn" data-branch="Quiapo">Quiapo Branch</button>
            <button class="branch-btn" data-branch="Kiko">Kiko Branch</button>
            <button class="branch-btn" data-branch="Naga">Naga Branch</button>
        </div>

        <section class="branch-details">
            <div class="details">
                <img src="assets/photos/dental.jpg" alt="Dental Clinic" style="width:100%;">
                <div class="text-block">
                    <h3 id="branch-name">Commonwealth Branch</h3>
                    <p id="branch-location">#3 Martan St, Brgy. Manggahan Commonwealth, Quezon City.</p>
                    <div class="operating-hours">
                        <h4>Operating Hours</h4>
                        <p id="branch-hours">Mon to Sat: 10:00 AM – 7:00 PM</p>
                    </div>
                </div>
                
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 M&A Oida Dental Clinic. All Rights Reserved.</p>
    </footer>
</body>
</html>