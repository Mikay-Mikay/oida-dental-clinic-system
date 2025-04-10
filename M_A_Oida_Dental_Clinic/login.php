<?php
session_start();
ob_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        ob_end_clean(); // Clean buffer
        echo json_encode(["status" => "error", "message" => "Invalid email."]);
        exit();
    }

    // Fixed table name
    $query = "SELECT id, password_hash FROM patients WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        ob_end_clean(); // Clean buffer
        echo json_encode(["status" => "error", "message" => "DB error: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user exists with the provided email
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            
            // Close statement and connection before sending the response
            $stmt->close();
            $conn->close();
            ob_end_clean(); // Clear any previous output

            echo json_encode([
                "status" => "success"
            ]);
            exit();
        } else {
            $stmt->close();
            $conn->close();

            echo json_encode([
                "status" => "error",
                "message" => "Incorrect password."
            ]);
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();

        echo json_encode([
            "status" => "error",
            "message" => "Email not found."
        ]);
        exit();
    }
} else {
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Login - M&A Oida Dental Clinic</title>
      <link rel="stylesheet" href="assets/css/style.css">
      <script src="assets/js/script.js"></script>
    </head>
    <body>
      <div class="login-container">
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
          <div class="logo-container">
              <img src="assets/photos/logo.jpg" alt="Clinic Logo">
              <h1>M&A Oida Dental Clinic</h1>
          </div>
      </div>
    </body>
    </html>
    <?php
}
?>