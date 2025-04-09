<?php
require_once('db.php');

if (isset($_GET['date']) && isset($_GET['branch'])) {
    $date = $_GET['date'];
    $branch = $_GET['branch'];
    
    $bookedSlots = [];
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT appointment_time FROM appointments 
                           WHERE appointment_date = ? AND clinic_branch = ?");
    $stmt->bind_param("ss", $date, $branch);
    
    // Execute and store result
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $bookedSlots[] = $row['appointment_time'];
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($bookedSlots);
    
    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Date and branch parameters are required']);
}

$conn->close();
?>