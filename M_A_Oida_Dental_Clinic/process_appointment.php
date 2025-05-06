<?php
// Disable error display for API endpoints
ini_set('display_errors', 0);
error_reporting(E_ERROR);

// Start output buffering to capture any unexpected output
ob_start();

require_once('session.php');
require_once('db.php');

// Helper function to capitalize names
function capitalizeNames($name) {
    $parts = explode(' ', trim($name));
    $parts = array_map(function($part) {
        return ucfirst(strtolower($part));
    }, $parts);
    return implode(' ', $parts);
}

// Set content type to JSON
header('Content-Type: application/json');

// Process the appointment submission
$response = array();
$errors = array();

try {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        throw new Exception("User must be logged in to book an appointment");
    }
    
    // Get form data
    $patientId = (int)$_SESSION['user_id'];
    $clinicBranch = $_POST['clinic_branch'] ?? '';
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';
    $doctorId = !empty($_POST['doctor_id']) ? $_POST['doctor_id'] : null;

    // Get services
    $services = $_POST['services'] ?? [];
    if (!is_array($services)) {
        $services = [];
    }
    $services_list = implode(', ', $services);
    
    // Additional fields
    $consent = isset($_POST['consent']) ? 1 : 0;
    $blood_type = $_POST['blood_type'] ?? '';
    $status = 'booked'; // Default status

    // Validate based on current section
    $section = $_POST['section'] ?? '';
    switch ($section) {
        case 'services':
            // At least one service must be selected
            if (empty($_POST['services']) || !is_array($_POST['services'])) {
                $errors['services'] = 'Please select at least one service';
            }
            break;
            
        case 'appointment':
            // Clinic branch validation
            if (empty($_POST['clinic_branch'])) {
                $errors['clinic_branch'] = 'Please select a clinic branch';
            }
            
            // Appointment date validation
            if (empty($_POST['appointment_date'])) {
                $errors['appointment_date'] = 'Please select an appointment date';
            } elseif (strtotime($_POST['appointment_date']) < strtotime(date('Y-m-d'))) {
                $errors['appointment_date'] = 'Appointment date cannot be in the past';
            }
            
            // Appointment time validation
            if (empty($_POST['appointment_time'])) {
                $errors['appointment_time'] = 'Please select an appointment time';
            } else {
                // Check if the time slot is already booked
                $checkBookingQuery = $conn->prepare("
                    SELECT id FROM appointments 
                    WHERE appointment_date = ? 
                    AND appointment_time = ? 
                    AND clinic_branch = ? 
                    AND (status = 'booked' OR status = 'pending')
                ");
                
                $checkBookingQuery->bind_param("sss", 
                    $_POST['appointment_date'], 
                    $_POST['appointment_time'], 
                    $_POST['clinic_branch']
                );
                
                $checkBookingQuery->execute();
                $bookingResult = $checkBookingQuery->get_result();
                
                if ($bookingResult->num_rows > 0) {
                    $errors['appointment_time'] = 'This time slot is already booked. Please select another time.';
                }
            }
            break;
            
        case 'payment':
            // Payment section is informational only
            break;
            
        case 'summary':
            // Final validation before submission
            break;
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        throw new Exception("Validation failed");
    }
    
    // Insert appointment into database
    $sql = "INSERT INTO appointments (
        patient_id, doctor_id, clinic_branch, appointment_date, appointment_time,
        services, status, consent, blood_type
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param(
        "iissssiss",
        $patientId,
        $doctorId,
        $clinicBranch,
        $appointmentDate,
        $appointmentTime,
        $services_list,
        $status,
        $consent,
        $blood_type
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }
    
    // Get appointment ID and create reference number
    $appointmentId = $conn->insert_id;
    $referenceNumber = 'OIDA-' . str_pad($appointmentId, 8, '0', STR_PAD_LEFT);
    
    // Clear form data from session
    unset($_SESSION['form_data']);
    unset($_SESSION['current_section']);
    
    // Return success response
    $response['success'] = true;
    $response['reference_id'] = $referenceNumber;
    $response['message'] = "Appointment successfully booked";
    
} catch (Exception $e) {
    // Log the error
    error_log("Error in process_appointment.php: " . $e->getMessage());
    
    // Return error response
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    
    if (!empty($errors)) {
        $response['errors'] = $errors;
    }
}

// Clear any buffered output
$debug_output = ob_get_clean();
if (!empty($debug_output)) {
    error_log("Debug output from process_appointment.php: " . $debug_output);
}

// Send JSON response
echo json_encode($response);
exit;
