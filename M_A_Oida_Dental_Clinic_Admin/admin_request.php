<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Request Access Form</title>
    <link rel="stylesheet" href="assets/css/admin_request.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Back Button Outside the Container -->
    <button class="back-button" onclick="window.history.back();">Back</button>

    <div class="request-container">
        <div class="request-card">
            <img src="assets/photo/logo.jpg" alt="Logo" class="logo">
            <h1>Admin Request Access Form</h1>
            <form action="admin_request.php" method="POST">
                <div class="form-group">
                    <label for="full_name">* Full Name:</label>
                    <input type="text" name="full_name" id="full_name" placeholder="ex. Juan Dela Cruz" required>
                </div>
                <div class="form-group">
                    <label for="email">* Email Address:</label>
                    <input type="email" name="email" id="email" placeholder="ex. Juandelacruz@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">* Contact Number:</label>
                    <input type="text" name="contact_number" id="contact_number" placeholder="ex. 09874563219" required>
                </div>
                <div class="form-group">
                    <label for="role">* Role in Dental Clinic:</label>
                    <select name="role" id="role" required>
                        <option value="" disabled selected>Select your current position</option>
                        <option value="Dentist">Professional Dentist</option>
                        <option value="Helper">Dental Helper</option>
                        <option value="Admin">Manager (Admin)</option>
                        <option value="Secretary">Secretary</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reason">* Reason for Requesting Admin Access:</label>
                    <textarea name="reason" id="reason" placeholder="Briefly explain why you need access to the admin panel." required></textarea>
                </div>
                <div class="form-group">
                    <label for="preferred_admin_id">Preferred Admin ID (Optional):</label>
                    <input type="text" name="preferred_admin_id" id="preferred_admin_id" placeholder="You may suggest a preferred Admin ID (e.g., ADM-JD2025). Leave blank if not sure.">
                </div>
                <div class="info-message">
                    <i class="fa-solid fa-circle-info"></i> Don't forget to check your inbox and spam folder in Gmail and SMS for updates regarding your request.
                </div>
                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>
    </div>
    <div id="confirmationModal" class="modal">
    <div class="modal-content">
        <h2>Confirm Submission</h2>
        <p class="modal-warning">Are you sure you want to submit your request for admin access?</p>
        <p>Please make sure all the information you entered is correct. Once submitted, your request will be reviewed by admin.</p>
        <div class="modal-buttons">
            <button class="close-modal" onclick="closeModal()">No</button>
            <button class="confirm-submit" onclick="confirmSubmission()">Yes</button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <h2>Request Submitted Successfully!</h2>
        <p>Thank you for submitting your request for admin access. Our admin will review your details shortly. Please wait for further instructions via Email or SMS.</p>
        <button class="close-modal" onclick="closeSuccessModal()">OK</button>
    </div>
</div>
<script src="assets/js/admin_request.js"></script>
</body>
</html>