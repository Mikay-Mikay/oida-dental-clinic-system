<?php
require 'db.php';

header('Content-Type: application/json');

$response = [];

try {
    // Fetch appointments
    $query = "SELECT * FROM appointments ORDER BY appointment_date DESC, appointment_time DESC";
    $result = $conn->query($query);
    
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    
    $response['success'] = true;
    $response['appointments'] = $appointments;
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>