<?php
require_once('session.php');
require 'db.php';

// Helper function to capitalize names
function capitalizeNames($name) {
    $parts = explode(' ', trim($name));
    $parts = array_map(function($part) {
        return ucfirst(strtolower($part));
    }, $parts);
    return implode(' ', $parts);
}

$user_id = $_SESSION['user_id'];

// Fetch existing user data
$sql = "
    SELECT 
        first_name, middle_name, last_name, email,
        phone_number, date_of_birth, gender, 
        region, province, city, barangay, zip_code,
        profile_picture
    FROM patients  
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Region and Province Data
$regions = [
    'NCR' => 'National Capital Region (NCR)',
    'Region III (Central Luzon)' => 'Region III (Central Luzon)',
    'Region IV-A (Calabarzon)' => 'Region IV-A (Calabarzon)'
];

$provinces = [
    'NCR' => ['Metro Manila'],
    'Region III (Central Luzon)' => ['Pampanga', 'Bulacan', 'Nueva Ecija'],
    'Region IV-A (Calabarzon)' => ['Cavite', 'Laguna', 'Batangas']
];

// Handle form submission
$error = '';
$profile_picture = $user['profile_picture'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and capitalize names
    $first_name = capitalizeNames(filter_var(trim($_POST["first_name"]), FILTER_SANITIZE_STRING));
    $middle_name = !empty($_POST["middle_name"]) ? capitalizeNames(filter_var(trim($_POST["middle_name"]), FILTER_SANITIZE_STRING)) : '';
    $last_name = capitalizeNames(filter_var(trim($_POST["last_name"]), FILTER_SANITIZE_STRING));

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filename = $_FILES['profile_picture']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $newFilename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
            $profile_picture = $newFilename;
        } else {
            $error = "Failed to upload profile picture.";
        }
    }

    // Date handling
    try {
        $date_of_birth = DateTime::createFromFormat('d-m-Y', $_POST['date_of_birth']);
        $mysql_date = $date_of_birth->format('Y-m-d');
    } catch (Exception $e) {
        $error = "Invalid date format. Use DD-MM-YYYY";
    }

    if (!$error) {
        $update_sql = "UPDATE patients SET 
            first_name = ?, middle_name = ?, last_name = ?,
            email = ?, phone_number = ?, date_of_birth = ?,
            gender = ?, region = ?, province = ?, city = ?,
            barangay = ?, zip_code = ?, profile_picture = ?
            WHERE id = ?";

        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssssssssssssi",
            $first_name,
            $middle_name,
            $last_name,
            $_POST['email'],
            $_POST['phone_number'],
            $mysql_date,
            $_POST['gender'],
            $_POST['region'],
            $_POST['province'],
            $_POST['city'],
            $_POST['barangay'],
            $_POST['zip_code'],
            $profile_picture,
            $user_id
        );

        if ($stmt->execute()) {
            header("Location: profile.php");
            exit();
        } else {
            $error = "Update failed: " . $conn->error;
        }
    }
}

$formatted_dob = date('d-m-Y', strtotime($user['date_of_birth']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="assets/css/profiles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <div class="profile-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="header">
                <a href="profile.php" class="back-btn">Back</a>
                <h1 class="profile-title">Edit Profile</h1>
            </div>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="profile-content">
                <div class="profile-pic-section">
                    <div class="profile-pic-container">
                        <img id="profilePreview" src="uploads/profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                            alt="Profile Picture" class="profile-pic">
                    </div>
                    <div class="form-group">
                        <label>Update Profile Picture:</label>
                        <input id="profileInput" type="file" name="profile_picture" accept="image/*">
                    </div>
                </div>

                <div class="profile-info">
                    <div class="info-column">
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="firstName" name="first_name" 
                                value="<?php echo htmlspecialchars($user['first_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="middleName">Middle Name:</label>
                            <input type="text" id="middleName" name="middle_name" 
                                value="<?php echo htmlspecialchars($user['middle_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="lastName" name="last_name" 
                                value="<?php echo htmlspecialchars($user['last_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" 
                                value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number:</label>
                            <input type="text" id="phone" name="phone_number" 
                                value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth:</label>
                            <div class="date-input-container">
                                <input type="text" id="dob" name="date_of_birth" 
                                    value="<?php echo htmlspecialchars($formatted_dob); ?>">
                                <span class="date-icon">📅</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" class="styled-select">
                                <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= $user['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="info-column">
                        <div class="form-group">
                            <label for="region">Region:</label>
                            <select id="region" name="region" class="styled-select">
                                <?php foreach ($regions as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $user['region'] === $value ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="province">Province:</label>
                            <select id="province" name="province" class="styled-select">
                                <?php foreach ($provinces[$user['region']] as $province): ?>
                                    <option value="<?= $province ?>" <?= $user['province'] === $province ? 'selected' : '' ?>>
                                        <?= $province ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">City/Municipality:</label>
                            <input type="text" id="city" name="city" 
                                value="<?php echo htmlspecialchars($user['city']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="barangay">Barangay:</label>
                            <input type="text" id="barangay" name="barangay" 
                                value="<?php echo htmlspecialchars($user['barangay']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="zipCode">ZipCode:</label>
                            <input type="text" id="zipCode" name="zip_code" 
                                value="<?php echo htmlspecialchars($user['zip_code']); ?>">
                        </div>
                    </div>
                </div>

                <p class="validation-note">*Always double check your new information before saving*</p>

                <div class="action-buttons">
                    <a href="profile.php" class="cancel-btn">Cancel</a>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Date picker initialization
        flatpickr("#dob", {
            dateFormat: "d-m-Y",
            allowInput: true
        });

        // Region-Province dynamic update
        document.getElementById('region').addEventListener('change', function() {
            const provinces = <?= json_encode($provinces) ?>;
            const provinceSelect = document.getElementById('province');
            provinceSelect.innerHTML = '';
            
            provinces[this.value].forEach(province => {
                const option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                provinceSelect.appendChild(option);
            });
        });

        // Image preview functionality
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePreview');
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Add the onchange event to the file input
        document.getElementById('profileInput').addEventListener('change', previewImage);
    </script>
</body>
</html>