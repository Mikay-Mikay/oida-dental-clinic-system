<?php
require_once('session.php');
require_once('db.php');

header('Content-Type: application/json');

$response = array();
$errors = array();

try {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User must be logged in to book an appointment");
    }

    // Get essential form data
    $patientId = (int)$_SESSION['user_id'];
    $clinicBranch = $_POST['clinic_branch'] ?? '';
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';
    $doctorId = !empty($_POST['doctor_id']) ? $_POST['doctor_id'] : null;
    $services = $_POST['services'] ?? [];
    
    // Validate required fields
    if (empty($clinicBranch)) {
        $errors['clinic_branch'] = 'Clinic branch is required';
    }
    if (empty($appointmentDate)) {
        $errors['appointment_date'] = 'Appointment date is required';
    }
    if (empty($appointmentTime)) {
        $errors['appointment_time'] = 'Appointment time is required';
    }
    if (empty($services) || !is_array($services)) {
        $errors['services'] = 'At least one service must be selected';
    }

    if (!empty($errors)) {
        throw new Exception("Validation failed");
    }

    // Convert services array to string
    $services_list = implode(', ', $services);

    // Insert appointment
    $sql = "INSERT INTO appointments (
        patient_id, doctor_id, clinic_branch, appointment_date, appointment_time,
        services, status
    ) VALUES (?, ?, ?, ?, ?, ?, 'pending')";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param(
        "iissss",
        $patientId,
        $doctorId,
        $clinicBranch,
        $appointmentDate,
        $appointmentTime,
        $services_list
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to create appointment: " . $stmt->error);
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
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    if (!empty($errors)) {
        $response['errors'] = $errors;
    }
}

echo json_encode($response);
?>