<?php
// Strict error handling for development
declare(strict_types=1);
error_reporting(0); // Disable in production
ini_set('display_errors', '0');

// Clean output buffer before starting
while (ob_get_level() > 0) ob_end_clean();

session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure clean JSON output
    header('Content-Type: application/json');
    
    try {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Database query
        $stmt = $conn->prepare("SELECT id, password_hash FROM patients WHERE email = ?");
        if (!$stmt) throw new Exception("Database error: " . $conn->error);

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            throw new Exception("Email not found");
        }

        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['password_hash'])) {
            throw new Exception("Incorrect password");
        }

        // Successful login
        $_SESSION['user_id'] = $user['id'];
        session_regenerate_id(true);

        echo json_encode([
            "status" => "success",
            "redirect" => "homepage.php"
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
      <title>Login - M&A Oida Dental Clinic</title>
      <link rel="stylesheet" href="assets/css/style.css?v=2.1">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
      <script src="assets/js/script.js"></script>
      
    </head>
    <body>
      <div class="login-container">
        <!-- Back Arrow -->
        <a href="homepage.php" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
        </a>
          <div class="login-box">
              <h2>Login</h2>
              <form id="login-form" action="login.php" method="POST">
                  <label for="email">Email:</label>
                  <input type="email" id="email" name="email" placeholder="ex. Juandelacruz@gmail.com" required>
              
                  <label for="password">Password:</label>
                  <input type="password" id="password" name="password" placeholder="Enter your Password" required>
                  <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
                  
                  <button type="submit" class="login-btn">Login</button>
      
                  <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
              </form>
          </div>
</div>
          <div class="logo-container">
              <img src="assets/photos/logo.jpg" alt="Clinic Logo">
              <h1>M&A Oida Dental Clinic</h1>
          </div>
    </body>
    </html>

