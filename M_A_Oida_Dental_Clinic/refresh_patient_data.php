<?php
require_once('session.php');
require_once('db.php');

header('Content-Type: application/json');

$response = array();

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }

    // Get basic patient info only
    $stmt = $conn->prepare("
        SELECT first_name, last_name, date_of_birth
        FROM patients 
        WHERE id = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    
    if ($userData) {
        $response['success'] = true;
        $response['name'] = $userData['first_name'] . ' ' . $userData['last_name'];
        
        // Format date of birth
        if (!empty($userData['date_of_birth'])) {
            $dob = new DateTime($userData['date_of_birth']);
            $response['dob'] = $dob->format('F j, Y');
        }
        
    } else {
        throw new Exception("User data not found");
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

echo json_encode($response);