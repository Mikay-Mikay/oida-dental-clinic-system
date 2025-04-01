<?php
header('Content-Type: application/json');
session_start();
require 'db.php'; // Gamitin lang ang db.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $middle_name = trim($_POST["middle_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $phone_number = trim($_POST["phone_number"]);
    $region = trim($_POST["region"]);
    $province = trim($_POST["province"]);
    $city = trim($_POST["city"]);
    $barangay = trim($_POST["barangay"]);
    $zip_code = trim($_POST["zip_code"]);
    $date_of_birth = $_POST["date_of_birth"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $gender = $_POST["gender"];

    // Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match"]);
        exit();
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM patients WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email is already registered"]);
        exit();
    }
    $check_email->close();

    // Hash password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert into `patients` table
    $sql = "INSERT INTO patients (first_name, middle_name, last_name, email, phone_number, region, province, city, barangay, zip_code, date_of_birth, password_hash, gender) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $first_name, $middle_name, $last_name, $email, $phone_number, $region, $province, $city, $barangay, $zip_code, $date_of_birth, $password_hash, $gender);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registration successful! Redirecting to login..."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
