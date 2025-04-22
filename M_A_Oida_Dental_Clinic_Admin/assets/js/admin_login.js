document.addEventListener("DOMContentLoaded", () => {
    const loginButton = document.querySelector("button");
    loginButton.addEventListener("click", (e) => {
        // Get form input values
        const email = document.querySelector("input[name='email']").value.trim();
        const adminId = document.querySelector("input[name='admin_id']").value.trim();
        const password = document.querySelector("input[name='password']").value.trim();

        // Validate inputs
        if (!email || !adminId || !password) {
            e.preventDefault(); // Prevent form submission
            alert("All fields are required!");
            return;
        }

        // Optional: Add more validation (e.g., email format)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault(); // Prevent form submission
            alert("Please enter a valid email address!");
            return;
        }

        alert("Login button clicked! Validation passed.");
    });
});