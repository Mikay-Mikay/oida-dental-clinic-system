<?php
require 'db.php'; // Your DB connection

$query = "SELECT name, photo, specialty, status FROM dentists";
$result = $conn->query($query);

$dentists = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dentists[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($dentists);
$conn->close();
?>
