<?php
require_once('session.php'); // Centralized session check
require 'db.php';
// Updated POST handler (inside PHP block)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data["name"], $data["rating"], $data["text"])) {
        $stmt = $conn->prepare("INSERT INTO reviews (name, rating, text, date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sis", $data["name"], $data["rating"], $data["text"]);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Review submitted successfully!"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error submitting review."]);
        }
        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid input data."]);
    }
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['api'])) {
    header("Content-Type: application/json");
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
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error fetching reviews."]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Reviews - M&A Oida Dental Clinic</title>
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
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"
                   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to access your profile.\');'; ?>">
                    <div class="user-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
                   onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
                    <button class="book-now">Book Now</button>
                </a>
            </div>
        </nav>
    </header>

    
    <section class="reviews-section">
        <h1>Patient Reviews</h1>
        
        <div class="reviews-container-wrapper">
            <button id="toggleFilterBtn" class="toggle-filter-btn">
                <i class="fa-solid fa-filter"></i> Filters
            </button>

            <aside class="filter-sidebar">
                <h3>Sort By Time:</h3>
                <div class="filter-group">
                    <label><input type="radio" name="time" value="past-few-days"> Past Few Days</label>
                    <label><input type="radio" name="time" value="past-few-weeks"> Past Few Weeks</label>
                    <label><input type="radio" name="time" value="past-few-months"> Past Few Months</label>
                    <label><input type="radio" name="time" value="past-few-years"> Past Few Years</label>
                    <label><input type="radio" name="time" value="all-time" checked> All Time</label>
                </div>
                
                <h3>Sort By Ratings:</h3>
                <div class="filter-group">
                    <label><input type="radio" name="rating" value="5"> ★★★★★ 5 Star</label>
                    <label><input type="radio" name="rating" value="4"> ★★★★☆ 4 Star</label>
                    <label><input type="radio" name="rating" value="3"> ★★★☆☆ 3 Star</label>
                    <label><input type="radio" name="rating" value="2"> ★★☆☆☆ 2 Star</label>
                    <label><input type="radio" name="rating" value="1"> ★☆☆☆☆ 1 Star</label>
                </div>
                
                <button id="filterBtn" class="apply-filters">Apply Filters</button>
            </aside>

            <div class="reviews-container" id="reviewsContainer">
                <!-- Dynamic content loaded via JavaScript -->
            </div>
        </div>

        <button id="addReviewBtn" class="cta-button">Share Your Experience</button>
    </section>

    <!-- Review Modal -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Share Your Experience</h2>
            <input type="text" id="reviewerName" placeholder="Your Full Name" required>
            <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" data-value="<?= $i ?>">★</span>
                <?php endfor; ?>
            </div>
            <textarea id="reviewText" placeholder="Tell us about your experience..." required></textarea>
            <button id="submitReview" class="cta-button">Submit Review</button>
        </div>
    </div>
</body>
</html>