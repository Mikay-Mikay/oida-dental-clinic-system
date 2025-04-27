<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp'])) {
    header("Location: forgotpassword.php");
    exit();
}

$email = $_SESSION['reset_email'];
$token = $_SESSION['otp'];
$input_otp = $_POST['otp'] ?? '';

$conn = new mysqli('localhost', 'root', '', 'dental_clinic');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT otp, otp_expiry FROM admin_users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $db_token = $row['otp'];
    $expiry = $row['otp_expiry'];
    $now = date('Y-m-d H:i:s');
    if ($input_otp === $db_token && $expiry > $now) {
        // OTP is valid
        header("Location: reset_password.php?token=$db_token");
        exit();
    } else {
        // OTP is invalid or expired
        $_SESSION['otp_error'] = 'Invalid or expired OTP code.';
        header("Location: verify_otp.php");
        exit();
    }
} else {
    $_SESSION['otp_error'] = 'User not found.';
    header("Location: verify_otp.php");
    exit();
} 