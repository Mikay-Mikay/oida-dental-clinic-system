<?php
// Strict error handling for development
declare(strict_types=1);
error_reporting(0);
ini_set('display_errors', '0');

// Clean output buffer before starting
while (ob_get_level() > 0) ob_end_clean();

session_start();
require 'db.php';

// Check for remember me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = filter_var($_COOKIE['remember_me'], FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare("SELECT user_id FROM remember_me_tokens WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        session_regenerate_id(true);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    
    try {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) ? true : false;

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check in patients table
        $stmt = $conn->prepare("SELECT id, first_name, last_name, password_hash, role FROM patients WHERE email = ?");
        if (!$stmt) throw new Exception("Database error: " . $conn->error);

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            throw new Exception("Invalid email or password");
        }

        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['password_hash'])) {
            throw new Exception("Invalid email or password");
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        session_regenerate_id(true);

        // Handle remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Delete any existing tokens for this user
            $stmt = $conn->prepare("DELETE FROM remember_me_tokens WHERE user_id = ?");
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();
            
            // Insert new token
            $stmt = $conn->prepare("INSERT INTO remember_me_tokens (user_id, token, expires) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user['id'], $token, $expires);
            $stmt->execute();
            
            // Set cookie
            setcookie('remember_me', $token, strtotime('+30 days'), '/', '', true, true);
        }

        // Redirect based on role
        $redirect = 'index.php';
        if ($user['role'] === 'admin' || $user['role'] === 'dentist' || $user['role'] === 'dental_helper') {
            $redirect = '../M_A_Oida_Dental_Clinic_Admin/dashboard.php';
        }

        echo json_encode([
            "status" => "success",
            "message" => "Login successful!",
            "redirect" => $redirect
        ]);
        exit();
        
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
        exit();
    }
}

// Non-POST requests show login page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ISched of M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="assets/js/script.js"></script>
</head>
<body>
    <div class="login-container">
        <!-- Back Arrow -->
        <a href="index.php" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <div class="login-box">
            <!-- HEADER: logo + title -->
            <div class="login-header">
                <img src="assets/photos/logo-2.png" alt="Clinic Logo" class="login-logo">
                <h2>Login</h2>
            </div>

            <form id="login-form" action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="ex. Juandelacruz@gmail.com" required>
            
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span class="toggle-password" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </span>
                </div>

                <!-- REMEMBER ME -->
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                
                <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
                
                <button type="submit" class="login-btn">Login</button>
    
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>

