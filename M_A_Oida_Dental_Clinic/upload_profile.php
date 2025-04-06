<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_img"])) {
    $patient_id = $_SESSION['user_id'];

    if (!$patient_id) {
        echo "Error: User ID is missing in session.";
        exit();
    }

    $file_name = basename($_FILES["profile_img"]["name"]);
    $target_dir = "uploads/";
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ["jpg", "jpeg", "png"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Only JPG, JPEG, and PNG files are allowed.";
        exit();
    }

    if ($_FILES["profile_img"]["size"] > 2 * 1024 * 1024) {
        echo "File size too large. Max: 2MB.";
        exit();
    }

    if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
        echo "File uploaded to: " . $target_file . "<br>";

        // Check if profile already exists
        $check_sql = "SELECT patient_id FROM patient_profiles WHERE patient_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $patient_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Update
            $update_sql = "UPDATE patient_profiles SET profile_img = ? WHERE patient_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $file_name, $patient_id);
            echo "Updating profile image for patient_id: $patient_id <br>";
        } else {
            // Insert
            $insert_sql = "INSERT INTO patient_profiles (patient_id, profile_img) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("is", $patient_id, $file_name);
            echo "Creating new profile for patient_id: $patient_id <br>";
        }

        if ($stmt->execute()) {
            echo "Profile image updated successfully! <br>";
            header("Refresh: 2; URL=profile.php");
            exit();
        } else {
            echo "Database error: " . $stmt->error;
        }
    } else {
        echo "File upload failed.";
    }
} else {
    echo "Invalid request.";
}
?>
