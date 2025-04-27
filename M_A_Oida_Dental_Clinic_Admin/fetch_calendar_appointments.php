<?php
require 'db.php'; // Include your database connection

header('Content-Type: application/json'); // Set the response type to JSON

// Query to fetch appointments for the current month
$sql = "SELECT id, full_name, appointment_date, appointment_time 
        FROM appointments 
        WHERE MONTH(appointment_date) = MONTH(CURDATE()) 
        AND YEAR(appointment_date) = YEAR(CURDATE())";

$result = $conn->query($sql);

$appointments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = [
            'id' => $row['id'],
            'full_name' => $row['full_name'],
            'appointment_date' => $row['appointment_date'],
            'appointment_time' => $row['appointment_time']
        ];
    }
}

echo json_encode($appointments);

$conn->close();
?>