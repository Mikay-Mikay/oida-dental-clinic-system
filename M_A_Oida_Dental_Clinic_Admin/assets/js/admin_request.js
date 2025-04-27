// Function to show the confirmation modal
function showModal() {
    document.getElementById('confirmationModal').style.display = 'flex';
}

// Function to close the confirmation modal
function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

// Function to show the success modal
function showSuccessModal() {
    document.getElementById('successModal').style.display = 'flex';
}

// Function to close the success modal and redirect to admin_login.php
function closeSuccessModal() {
    window.location.href = 'admin_login.php'; // Redirect to admin_login.php
}

// Function to confirm submission
function confirmSubmission() {
    closeModal(); // Close the confirmation modal
    showSuccessModal(); // Show the success modal
}

// Attach event listener to the form
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent actual form submission
    showModal(); // Show the confirmation modal
});