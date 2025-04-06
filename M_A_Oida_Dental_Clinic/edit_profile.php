<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$region = $_POST['region'];
$province = $_POST['province'];
$city = $_POST['city'];
$barangay = $_POST['barangay'];
$zipcode = $_POST['zipcode'];

$profile_picture = '';

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name']) {
    $target_dir = "uploads/";
    $file_name = basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $profile_picture = $target_file;
        $update_picture = ", profile_picture = '$profile_picture'";
    } else {
        $update_picture = "";
    }
} else {
    $update_picture = "";
}

$query = "UPDATE users SET
    first_name = '$first_name',
    middle_name = '$middle_name',
    last_name = '$last_name',
    email = '$email',
    phone = '$phone',
    dob = '$dob',
    gender = '$gender',
    region = '$region',
    province = '$province',
    city = '$city',
    barangay = '$barangay',
    zipcode = '$zipcode'
    $update_picture
    WHERE id = $user_id";

mysqli_query($conn, $query);

header("Location: profile.php");
exit();
?>
