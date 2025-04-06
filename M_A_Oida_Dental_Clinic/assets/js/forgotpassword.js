document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const emailInput = document.querySelector("input[type='email']");

    form.addEventListener("submit", async function (event) {
        event.preventDefault(); // Prevent default form submission

        const email = emailInput.value.trim();
        
        if (validateEmail(email)) {
            // Check if the email exists in the database
            const emailExists = await checkEmailInDatabase(email);
            
            if (emailExists) {
                // Store email in sessionStorage (optional, for use in OTP page)
                sessionStorage.setItem("userEmail", email);

                // Redirect to OTP page
                window.location.href = "otp.php";
            } else {
                alert("Email not found. Please enter a registered email.");
            }
        } else {
            alert("Please enter a valid email address.");
        }
    });

    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    async function checkEmailInDatabase(email) {
        try {
            const response = await fetch("check_email.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: email }),
            });

            const result = await response.json();
            return result.exists; // Returns true if email exists, false otherwise
        } catch (error) {
            console.error("Error checking email:", error);
            return false;
        }
    }
});
