document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "reviews.php";
    let selectedRating = 0;
    const stars = document.querySelectorAll(".star");
    const modal = document.getElementById("reviewModal");
    const filterSidebar = document.querySelector('.filter-sidebar');

    // Star Rating Interaction
    stars.forEach(star => {
        star.addEventListener("mouseover", function () {
            const value = parseInt(this.dataset.value);
            stars.forEach((s, index) => {
                s.classList.toggle("active", index < value);
            });
        });

        star.addEventListener("click", function () {
            selectedRating = parseInt(this.dataset.value);
            stars.forEach(s => s.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Modal Controls
    document.getElementById("addReviewBtn").addEventListener("click", () => {
        modal.style.display = "block";
    });

    document.querySelector(".close").addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === modal) modal.style.display = "none";
    });

    // Filter Sidebar Toggle
    document.getElementById('toggleFilterBtn').addEventListener('click', () => {
        filterSidebar.classList.toggle('show');
    });

    // Review Submission
    document.getElementById("submitReview").addEventListener("click", async function () {
        const name = document.getElementById("reviewerName").value.trim();
        const text = document.getElementById("reviewText").value.trim();

        if (!name || !text || selectedRating <= 0) {
            alert("Please complete all fields and select a rating!");
            return;
        }

        try {
            const response = await fetch(apiUrl, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name, rating: selectedRating, text })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Failed to submit review");
            }

            if (data.success) {
                // Clear form and close modal
                modal.style.display = "none";
                document.getElementById("reviewerName").value = "";
                document.getElementById("reviewText").value = "";
                stars.forEach(s => s.classList.remove("active"));
                selectedRating = 0;
                
                // Refresh reviews
                await fetchReviews();
            }
        } catch (error) {
            console.error("Submission Error:", error);
            alert(error.message);
        }
    });

    // Fetch and Display Reviews
    async function fetchReviews() {
        try {
            const response = await fetch(`${apiUrl}?api=1`);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Failed to load reviews");
            }

            const reviewsContainer = document.getElementById("reviewsContainer");
            reviewsContainer.innerHTML = data.map(review => `
                <div class="review">
                    <h3>${review.name}</h3>
                    <div class="rating-stars">
                        ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                    </div>
                    <p class="review-text">${review.text}</p>
                    <small class="review-date">
                        ${new Date(review.date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}
                    </small>
                </div>
            `).join('');
        } catch (error) {
            console.error("Fetch Error:", error);
            alert(error.message);
        }
    }

    // Initial load of reviews
    fetchReviews();
});