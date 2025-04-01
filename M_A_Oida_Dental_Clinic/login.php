<?php
header('Content-Type: application/json');
session_start();
require 'db.php'; // Ensure correct database connection

// Clear any previous output
ob_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit();
    }

    // Check if email exists in the `patient` table
    $query = "SELECT id, password_hash FROM patient WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) { 
            $_SESSION['user_id'] = $user['id'];
            
            ob_end_clean(); // Clear all previous output before JSON response
            
            echo json_encode(["status" => "success"]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email not found"]);
        exit();
    }

    // Close resources
    $stmt->close();
    $conn->close();
}
?>
