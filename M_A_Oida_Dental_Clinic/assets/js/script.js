document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("#login-form");
    
    loginForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
            const formData = new FormData(this);
            const response = await fetch("login.php", {
                method: "POST",
                body: formData
            });

            // Handle empty responses
            const text = await response.text();
            if (!text) {
                throw new Error("Empty response from server");
            }

            // Parse response once
            const data = JSON.parse(text);
            console.log("Server Response:", data);

            if (data.status === "success") {
                // Force full page reload to apply session
                window.location.href = data.redirect || "homepage.php";
            } else {
                alert(data.message || "Login failed");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Login error: " + error.message);
        } finally {
            submitBtn.disabled = false;
        }
    });
});

function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(inputId + '-eye');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}