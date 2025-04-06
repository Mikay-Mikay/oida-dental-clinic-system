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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/reviews.css">
    <script src="assets/js/reviews.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="clinics.php">Clinics</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <div class="nav-right">
                <button class="book-now">Book Now</button>
                <div class="user-icon"><?php session_start(); ?>
<a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\');'; ?>">
    <i class='fa-solid fa-user' style='font-size: 24px;'></i>
</a></div>
            </div>
        </nav>
    </header>
    
    <section class="reviews-section">
        <h2>Reviews</h2>
        
        <div class="reviews-container-wrapper">
            <button id="toggleFilterBtn" class="toggle-filter-btn">
                <i class="fa-solid fa-filter"></i>
            </button>

            <aside class="filter-sidebar">
                <h3>Sort By Time:</h3>
                <label><input type="radio" name="time" value="past-few-days"> Past Few Days</label>
                <label><input type="radio" name="time" value="past-few-weeks"> Past Few Weeks</label>
                <label><input type="radio" name="time" value="past-few-months"> Past Few Months</label>
                <label><input type="radio" name="time" value="past-few-years"> Past Few Years</label>
                <label><input type="radio" name="time" value="all-time"> All Time</label>
                
                <h3>Sort By Ratings:</h3>
                <label><input type="radio" name="rating" value="5"> &#9733;&#9733;&#9733;&#9733;&#9733; 5 Star</label>
                <label><input type="radio" name="rating" value="4"> &#9733;&#9733;&#9733;&#9733;&#9734; 4 Star</label>
                <label><input type="radio" name="rating" value="3"> &#9733;&#9733;&#9733;&#9734;&#9734; 3 Star</label>
                <label><input type="radio" name="rating" value="2"> &#9733;&#9733;&#9734;&#9734;&#9734; 2 Star</label>
                <label><input type="radio" name="rating" value="1"> &#9733;&#9734;&#9734;&#9734;&#9734; 1 Star</label>
                
                <button id="filterBtn">Filter</button>
            </aside>

            <div class="reviews-container" id="reviewsContainer">
                <!-- Reviews will be loaded here -->
            </div>
        </div>

        <button id="addReviewBtn">Add a Review</button>
    </section>

    <!-- Add Review Modal -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add a Review</h2>
            <input type="text" id="reviewerName" placeholder="Your Name">
            <div class="star-rating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <textarea id="reviewText" placeholder="Write your review..."></textarea>
            <button id="submitReview">Submit</button>
        </div>
    </div>
</body>
</html>
