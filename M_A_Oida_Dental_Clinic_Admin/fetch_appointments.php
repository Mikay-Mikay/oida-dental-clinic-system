<?php
require 'db.php'; // Include your database connection

header('Content-Type: application/json');

// Query to fetch approved appointments
$sql = "SELECT full_name, appointment_time AS time, image 
        FROM appointments 
        WHERE status = 'approved' 
        ORDER BY appointment_date, appointment_time";

$result = $conn->query($sql);

$appointments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

echo json_encode($appointments);

$conn->close();
?>