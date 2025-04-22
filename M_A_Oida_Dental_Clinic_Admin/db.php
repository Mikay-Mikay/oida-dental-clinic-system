<?php
// filepath: c:\xampp\htdocs\M_A_Oida_Dental_Clinic\db.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dental_clinic";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>