<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $admin_id = trim($_POST['admin_id']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        $sql = "SELECT id, admin_id, email, password_hash, name 
                FROM admin_logins 
                WHERE email = ? AND admin_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param('ss', $email, $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_name'] = $user['name']; // Add this line
$_SESSION['admin_id'] = $user['admin_id'];
$_SESSION['email'] = $user['email'];
                    
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid password';
                }
            } else {
                $error = 'Account not found';
            }
            $stmt->close();
        } else {
            $error = 'Database error';
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/admin_login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <img src="assets/photo/logo.jpg" alt="Logo" class="logo">
            <h1>Welcome Admin!</h1>
            <p>Please Login to Continue</p>
            <?php if ($error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form action="admin_login.php" method="POST">
                <div class="form-group">
                    <label for="email"><i class="fa-solid fa-envelope"></i> Email:</label>
                    <input type="email" name="email" id="email"
                           placeholder="ex. Juandelacruz@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="admin_id"><i class="fa-solid fa-id-card"></i> Admin ID:</label>
                    <input type="text" name="admin_id" id="admin_id"
                           placeholder="ex. ADM-001" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fa-solid fa-lock"></i> Password:</label>
                    <input type="password" name="password" id="password"
                           placeholder="Enter your Password" required>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe">Remember me</label>
                    </div>
                    <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <p class="request-access">
                Not Admin Yet? <a href="admin_request.php">Request Access</a>
            </p>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const loginButton = document.querySelector(".login-button");
        loginButton.addEventListener("click", (e) => {
            // Basic front-end validation
            const email   = document.querySelector("#email").value.trim();
            const adminId = document.querySelector("#admin_id").value.trim();
            const password= document.querySelector("#password").value.trim();

            if (!email || !adminId || !password) {
                e.preventDefault();
                alert("All fields are required!");
                return;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert("Please enter a valid email address!");
            }
        });
    });
    </script>
</body>
</html>
