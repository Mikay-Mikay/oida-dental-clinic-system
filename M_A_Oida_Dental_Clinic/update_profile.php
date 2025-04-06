<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "Unauthorized access.";
        exit();
    }

    $patient_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $region = $_POST['region'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $zipcode = $_POST['zipcode'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    // Check if profile already exists
    $check_sql = "SELECT patient_id FROM patient_profiles WHERE patient_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $patient_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // UPDATE
        $sql = "UPDATE patient_profiles SET first_name=?, middle_name=?, last_name=?, region=?, province=?, city=?, barangay=?, zipcode=?, phone_number=?, birth_date=?, gender=?, email=? WHERE patient_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssi", $first_name, $middle_name, $last_name, $region, $province, $city, $barangay, $zipcode, $phone_number, $birth_date, $gender, $email, $patient_id);
    } else {
        // INSERT
        $sql = "INSERT INTO patient_profiles (patient_id, first_name, middle_name, last_name, region, province, city, barangay, zipcode, phone_number, birth_date, gender, email)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssssssss", $patient_id, $first_name, $middle_name, $last_name, $region, $province, $city, $barangay, $zipcode, $phone_number, $birth_date, $gender, $email);
    }

    if ($stmt->execute()) {
        header("Location: profile.php?update=success");
        exit();
    } else {
        echo "Error saving profile: " . $stmt->error;
    }
}
?>
