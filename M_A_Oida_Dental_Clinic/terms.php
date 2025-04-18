<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link rel="stylesheet" href="assets/css/terms.css">
</head>
<body>
    <button class="back-button" onclick="goBack()">Back</button>
    
    <div class="container">
        <h1>Terms and Conditions</h1>
        <div class="terms-box">
            <p>Welcome to M&A Oida Dental Clinic. By accessing our services, you agree to comply with the following terms and conditions. Please read them carefully.</p>

            <h2>1. General Terms</h2>
            <p>1.1 These terms govern the use of M&A Oida Dental Clinic’s services, including appointments and treatments.</p>
            <p>1.2 The clinic reserves the right to update these terms without prior notice.</p>

            <h2>2. Appointments and Scheduling</h2>
            <p>2.1 Patients must book appointments in advance through our web-based system, phone, or walk-in process.</p>
            <p>2.2 Late arrivals of more than 20 minutes may result in rescheduling or cancellation.</p>
            <p>2.3 Cancellations should be made at least 24 hours before the scheduled appointment. Failure to cancel on time may result in a cancellation fee.</p>

            <h2>3. Patient Records and Privacy</h2>
            <p>3.1 Patient records are confidential and stored securely within our system.</p>
            <p>3.2 Personal information will only be used for medical and administrative purposes and will not be shared without patient consent, except as required by law.</p>

            <h2>4. Payments and Fees</h2>
            <p>4.1 Payment is required upon completion of services unless a prior arrangement has been made.</p>
            <p>4.2 The clinic accepts cash, credit/debit cards, and e-wallets (e.g., GCash, Maya).</p>
            <p>4.3 For treatments requiring a down payment, the total fees will be discussed with the dentist before the procedure.</p>

            <h2>Contact Information</h2>
            <p>For questions or concerns, you may contact us:</p>
            <p><strong>Clinic Address:</strong> 123 Main St., Quezon City, Philippines</p>
            <p><strong>Contact Number:</strong> 0912-345-6789</p>
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = "signup.php";
        }
    </script>
</body>
</html>
