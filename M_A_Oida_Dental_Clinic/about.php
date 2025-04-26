<?php 
require_once('session.php');
require_once('db.php');

if(isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT first_name, profile_picture FROM patients WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>About Us - M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/about.css">
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
            <li><a href="clinics.php"  class="<?php if(basename(  $_SERVER['PHP_SELF'])=='clinics.php') echo 'active'; ?>">Clinics</a></li>
            <li><a href="services.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='services.php') echo 'active'; ?>">Services</a></li>
            <li><a href="about.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='about.php') echo 'active'; ?>">About</a></li>
            <li><a href="reviews.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='reviews.php') echo 'active'; ?>">Reviews</a></li>
            <li><a href="contact.php"  class="<?php if(basename($_SERVER['PHP_SELF'])=='contact.php') echo 'active'; ?>">Contact Us</a></li>
        </ul>

        <div class="nav-right">
  <!-- BOOK NOW -->
  <a
    href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
    onclick="<?php if (!isset($_SESSION['user_id'])) echo "alert('Please login to book an appointment.');"; ?>"
  >
    <button class="book-now">Book Now</button>
  </a>

  <!-- PROFILE ICON -->
  <a
    href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
    onclick="<?php if (!isset($_SESSION['user_id'])) echo "alert('Please login to access your profile.');"; ?>"
  >
    <div class="user-icon">
      <i class="fa-solid fa-user"></i>
    </div>
  </a>
</div>


        </nav>
    </header>

<main>
  <!-- Lead Dentists -->
  <section class="about-section">
    <div class="experts-container">
      <div class="expert">
        <div class="blob blob-left"></div>
        <img src="assets/photos/Dr._Marcial_Oida.png" alt="Dr. Marcial Oida" class="expert-img">
        <div class="expert-label">Dr. Marcial Oida<br><em>Professional Dentist</em></div>
      </div>
      <div class="expert">
        <div class="blob blob-right"></div>
        <img src="assets/photos/Dr._Ardeen_Dofiles_Oida.png" alt="Dr. Ardeen Oida" class="expert-img">
        <div class="expert-label">Dr. Ardeen D. Oida<br><em>Professional Dentist</em></div>
      </div>
    </div>
    <div class="about-content">
        <h2><span class="highlight">Meet Our</span> <strong>Dental Experts</strong></h2>
        <p>
            Welcome to <strong style="color: #124085">M&A Oida Dental Clinic</strong>, where excellence in dental care has been a tradition for 
            <strong style="color: #124085">over 24 years</strong>. At the heart of our clinic are 
            <strong style="color: #124085">Dr. Marcial Oida</strong> and <strong style="color: #124085">Dr. Ardeen Dofiles Oida</strong>, a dedicated married couple who 
            lead with both expertise and compassion.
        </p>
        <p>
            Their combined experience and commitment to staying at the forefront of dental innovation ensure that every 
            patient receives personalized, high-quality treatment. Together, they create a warm and welcoming environment 
            that has made our clinic a trusted name in the community.
        </p>
    </div>
  </section>

  <!-- Other Dental Experts -->
  <section class="other-experts">
    <h2 class="other-heading">
      <span class="highlight">Meet Our Other</span> <strong style="color: #124085">Dental Experts</strong>
    </h2>
    <div class="experts-container">
      <div class="expert">
        <div class="blob blob-left"></div>
        <img src="assets/photos/Dr._Maribel_Adajar.png" alt="Dr. Maribel Adajar" class="expert-img">
        <div class="expert-label">Dr. Maribel Adajar<br><em>Professional Dentist</em></div>
      </div>
      <div class="expert">
        <div class="blob blob-right"></div>
        <img src="assets/photos/Dr._Joan_Gajeto_Flores.png" alt="Dr. Joan Flores" class="expert-img">
        <div class="expert-label">Dr. Joan G. Flores<br><em>Professional Dentist</em></div>
      </div>
    </div>
  </section>

  <!-- Dental Helpers -->
  <section class="dental-helpers">
    <h2 class="helpers-heading">
      <span class="highlight">Meet Our</span> <strong style="color: #124085">Dental Helpers</strong>
    </h2>
    <div class="experts-container">
      <div class="expert">
        <div class="blob blob-left"></div>
        <img src="assets/photos/Manggahan Branch.png" alt="Geraldine Labasan" class="expert-img">
        <div class="expert-label">Geraldine U. Labasan<br><em>Commonwealth Branch</em></div>
      </div>
      <div class="expert">
        <div class="blob blob-right"></div>
        <img src="assets/photos/Kiko Branch.png" alt="Jocelyn Darang" class="expert-img">
        <div class="expert-label">Jocelyn Darang<br><em>Kiko Branch</em></div>
      </div>
      <div class="expert">
        <div class="blob blob-left"></div>
        <img src="assets/photos/Montalban Branch.png" alt="Gemma Velasquez" class="expert-img">
        <div class="expert-label">Gemma A. Velasquez<br><em>Montalban Branch</em></div>
      </div>
      <div class="expert">
        <div class="blob blob-right"></div>
        <img src="assets/photos/Maligaya Branch.png" alt="Wilma Ayao" class="expert-img">
        <div class="expert-label">Wilma Ayao<br><em>Maligaya Park</em></div>
      </div>
      <div class="expert">
        <div class="blob blob-left"></div>
        <img src="assets/photos/Regalado Branch.png" alt="Lynneth Matuan" class="expert-img">
        <div class="expert-label">Lynneth Matuan<br><em>North Fairview</em></div>
      </div>
    </div>
  </section>
</main>

<footer>
  <p>© 2025 M&A Oida Dental Clinic. All Rights Reserved.</p>
</footer>

</body>
</html>

