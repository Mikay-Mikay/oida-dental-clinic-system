<?php
require_once('session.php');
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate session
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("Authentication required");
        }

        // Validate form data
        $required = ['full_name', 'email', 'contact', 'services', 
                    'clinic_branch', 'appointment_date', 'appointment_time'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Prepare statement
        $stmt = $conn->prepare("INSERT INTO appointments 
            (patient_id, clinic_branch, appointment_date, appointment_time, services)
            VALUES (?, ?, ?, ?, ?)");
        
        $services = is_array($_POST['services']) 
            ? implode(', ', $_POST['services']) 
            : $conn->real_escape_string($_POST['services']);

        $stmt->bind_param("issss",
            $_SESSION['user_id'],
            $_POST['clinic_branch'],
            $_POST['appointment_date'],
            $_POST['appointment_time'],
            $services
        );

        if (!$stmt->execute()) {
            throw new Exception($conn->error);
        }

        // Success response
        echo json_encode(['status' => 'success']);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

$conn->close();
?>