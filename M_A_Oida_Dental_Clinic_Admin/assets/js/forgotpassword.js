document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", (event) => {
        event.preventDefault(); // Prevent default form submission

        const email = form.querySelector('input[name="email"]').value;

        // Send the email to the server for OTP generation
        fetch("process_forgotpassword.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `email=${encodeURIComponent(email)}`,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Redirect to OTP verification page
                    window.location.href = `verify_otp.html?email=${encodeURIComponent(email)}`;
                } else {
                    alert(data.message || "An error occurred. Please try again.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });
    });
});