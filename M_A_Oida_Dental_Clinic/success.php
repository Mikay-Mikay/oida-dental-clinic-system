<?php
require_once('session.php');
require_once('db.php');

// Redirect if no success flag or reference number
if (!isset($_SESSION['appointment_success']) || !isset($_SESSION['reference_number'])) {
    header('Location: bookings.php');
    exit;
}

$referenceNumber = $_SESSION['reference_number'];

// Clear session variables
unset($_SESSION['appointment_success']);
unset($_SESSION['reference_number']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/bookings.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-header">
                <h2>Appointment Successfully Booked!</h2>
                <div class="success-icon">✓</div>
            </div>
            
            <div class="success-message">
                <p>Thank you for booking with M&A Oida Dental Clinic.</p>
                <div class="reference-number">
                    <strong>Your Booking Reference Number:</strong>
                    <span><?php echo htmlspecialchars($referenceNumber); ?></span>
                </div>
                <p class="important-note">Please save this reference number. You will need it when you visit the clinic.</p>
            </div>
            
            <div class="next-steps">
                <h3>What's Next?</h3>
                <ul>
                    <li>You will receive a confirmation email shortly.</li>
                    <li>Visit our clinic at your scheduled appointment time.</li>
                    <li>Bring a valid ID for verification.</li>
                </ul>
            </div>
            
            <div class="button-group">
                <a href="mybookings.php" class="view-bookings-btn">View My Bookings</a>
                <a href="index.php" class="home-btn">Return to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>