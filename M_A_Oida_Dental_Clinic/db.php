<?php

$servername = "localhost";
$username = "root"; // Baguhin kung may ibang username
$password = ""; // Baguhin kung may password
$dbname = "dental_clinic";

// Gumawa ng database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// I-check kung may error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
