<?php
require 'db.php'; // Database connection

header("Content-Type: application/json"); // Set response type

// Handle form submission (POST request from JavaScript)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["name"], $data["rating"], $data["text"])) {
        $name = $conn->real_escape_string($data["name"]);
        $rating = (int) $data["rating"];
        $text = $conn->real_escape_string($data["text"]);
        $date = date("Y-m-d H:i:s");

        // SQL query to insert review
        $query = "INSERT INTO reviews (name, rating, text, date) VALUES ('$name', '$rating', '$text', '$date')";

        if ($conn->query($query)) {
            echo json_encode(["success" => true, "message" => "Review submitted successfully!"]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["success" => false, "message" => "Error submitting review."]);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "message" => "Invalid input data."]);
    }
    exit;
}

// Fetch reviews from database (GET request)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $reviews = [];
    $result = $conn->query("SELECT * FROM reviews ORDER BY date DESC");

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                "name" => htmlspecialchars($row["name"]),
                "rating" => (int) $row["rating"],
                "text" => htmlspecialchars($row["text"]),
                "date" => htmlspecialchars($row["date"]),
            ];
        }
        echo json_encode($reviews);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "message" => "Error fetching reviews."]);
    }
    exit;
}

// Default response for unsupported methods
http_response_code(405); // Method Not Allowed
echo json_encode(["success" => false, "message" => "Method not allowed."]);
?>
