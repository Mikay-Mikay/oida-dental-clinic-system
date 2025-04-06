document.addEventListener("DOMContentLoaded", function () {
    const otpInput = document.getElementById("otp-input");
    const verifyBtn = document.getElementById("verify-btn");
    const countdownSpan = document.getElementById("countdown");

    let otpCode = generateOTP();
    let countdown = 100; // Set expiration time

    console.log("Generated OTP:", otpCode); // Simulate sending OTP

    // Countdown timer
    const timer = setInterval(() => {
        countdown--;
        countdownSpan.textContent = countdown + "s";

        if (countdown <= 0) {
            clearInterval(timer);
            alert("OTP expired! Request a new one.");
            window.location.href = "forgotpassword.php";
        }
    }, 1000);

    // Verify OTP
    verifyBtn.addEventListener("click", function () {
        if (otpInput.value === otpCode) {
            alert("OTP Verified! Redirecting...");
            window.location.href = "resetpassword.php";
        } else {
            alert("Invalid OTP! Try again.");
        }
    });

    // Function to generate OTP
    function generateOTP() {
        return Math.floor(100000 + Math.random() * 900000).toString(); // 6-digit OTP
    }
});
