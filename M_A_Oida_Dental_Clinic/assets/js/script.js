document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#login-form").addEventListener("submit", async function (e) {
        e.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);

        try {
            let response = await fetch("login.php", {
                method: "POST",
                body: formData,
            });

            let text = await response.text(); // Get raw response first
            console.log("Raw Response:", text); // Debugging

            if (!text.trim()) {
                console.error("Empty response from server.");
                return;
            }

            let data;
            try {
                data = JSON.parse(text); // Convert response to JSON
            } catch (jsonError) {
                console.error("JSON Parse Error:", jsonError);
                return;
            }

            console.log("Parsed JSON:", data); // Debugging

            if (data.status === "success") {
                window.location.href = "homepage.html"; // Redirect to homepage
            } else if (data.message) {
                alert(data.message); // Show error message only if there's an error
            }
        } catch (error) {
            console.error("Fetch Error:", error);
        }
    });
});
