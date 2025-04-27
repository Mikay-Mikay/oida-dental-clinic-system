<?php
require_once('db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $appointment_id = $_POST['appointment_id'] ?? '';
    if ($action && $appointment_id) {
        if ($action === 'approve') {
            $sql = "UPDATE appointments SET status = 'booked' WHERE id = ?";
        } elseif ($action === 'decline') {
            $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            exit;
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $appointment_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Appointment updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error.']);
        }
        exit;
    }
}
echo json_encode(['success' => false, 'message' => 'Invalid request.']); 