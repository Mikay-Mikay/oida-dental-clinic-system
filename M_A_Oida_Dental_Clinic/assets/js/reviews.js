document.addEventListener("DOMContentLoaded", function () {
    const reviews = [];
    const apiUrl = "reviews.php"; // Siguraduhin na tama ang path sa server mo

    async function fetchReviews() {
        try {
            const response = await fetch(apiUrl);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            reviews.length = 0; // Clear existing data
            data.forEach(review => reviews.push(review));
            displayReviews();
        } catch (error) {
            console.error("Error fetching reviews:", error);
            alert("Error fetching reviews. Check console for details.");
        }
    }

    function displayReviews() {
        const reviewsContainer = document.getElementById("reviewsContainer");
        reviewsContainer.innerHTML = "";
        reviews.forEach(review => {
            const reviewElement = document.createElement("div");
            reviewElement.classList.add("review");
            reviewElement.innerHTML = `
                <h3>${review.name}</h3>
                <p>${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</p>
                <p>${review.text}</p>
                <small>${review.date}</small>
            `;
            reviewsContainer.appendChild(reviewElement);
        });
    }

    // Open Modal
    const modal = document.getElementById("reviewModal");
    const addReviewBtn = document.getElementById("addReviewBtn");
    const closeModal = document.querySelector(".close");

    addReviewBtn.addEventListener("click", function () {
        modal.style.display = "block";
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Star Rating System
    const stars = document.querySelectorAll(".star");
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener("mouseover", function () {
            let value = this.getAttribute("data-value");
            stars.forEach(s => s.classList.remove("active"));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add("active");
            }
        });

        star.addEventListener("click", function () {
            selectedRating = this.getAttribute("data-value");
        });
    });

    // Submit Review
    document.getElementById("submitReview").addEventListener("click", async function () {
        const name = document.getElementById("reviewerName").value.trim();
        const text = document.getElementById("reviewText").value.trim();
        const date = new Date().toLocaleDateString("en-US");

        if (!name || !text || selectedRating <= 0) {
            alert("Please fill out all fields and select a rating.");
            return;
        }

        const newReview = { name, rating: parseInt(selectedRating), text, date };
        console.log("Sending data:", newReview);

        try {
            const response = await fetch(apiUrl, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(newReview),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            console.log("Response received:", data);

            if (data.success) {
                reviews.push(newReview);
                displayReviews();
                modal.style.display = "none";

                // Reset fields
                document.getElementById("reviewerName").value = "";
                document.getElementById("reviewText").value = "";
                stars.forEach(s => s.classList.remove("active"));
                selectedRating = 0;
            } else {
                alert("Error: " + data.message);
            }
        } catch (error) {
            console.error("Fetch error:", error);
            alert("Error submitting review. Check console for details.");
        }
    });

    // Fetch initial reviews when the page loads
    fetchReviews();

    // Sidebar Functionality
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    const filterSidebar = document.querySelector('.filter-sidebar');

    toggleFilterBtn.addEventListener('click', function () {
        filterSidebar.classList.toggle('show');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    const filterSidebar = document.querySelector('.filter-sidebar');

    toggleFilterBtn.addEventListener('click', function() {
        filterSidebar.classList.toggle('show');
    });
});