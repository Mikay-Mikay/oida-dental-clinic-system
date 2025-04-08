<?php
session_start();

// Redirect to homepage if not logged in and trying to access a protected page
$allowed_pages = [
    'index.php', 'homepage.php', 'login.php', 'signup.php',
    'clinics.php', 'about.php', 'services.php', 'reviews.php', 'contact.php'
];
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user_id']) && !in_array($current_page, $allowed_pages)) {
    header("Location: index.php");
    exit();
}
?>
