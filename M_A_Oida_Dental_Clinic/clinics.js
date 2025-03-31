document.addEventListener("DOMContentLoaded", function () {
    // Highlight active navigation link
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-links a");
    
    navLinks.forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active"); // Add 'active' class to highlight current page
        }
    });
    
    // Smooth scrolling for future internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            document.getElementById(targetId).scrollIntoView({
                behavior: "smooth"
            });
        });
    });
    
    // Future mobile menu toggle (if needed)
    const menuToggle = document.querySelector(".menu-toggle");
    const navMenu = document.querySelector(".nav-links");
    
    if (menuToggle) {
        menuToggle.addEventListener("click", () => {
            navMenu.classList.toggle("show");
        });
    }
    
    // Clinic Branch Info Update (for clinics.html)
    const branchButtons = document.querySelectorAll(".branch-btn");
    const branchName = document.getElementById("branch-name");
    const branchLocation = document.getElementById("branch-location");
    
    const branchData = {
        "Commonwealth": "#3 Martan St, Brgy. Manggahan Commonwealth, Quezon City.",
        "North Fairview": "#45 Samsonville, North Fairview, Quezon City.",
        "Maligaya Park": "Unit 2, Maligaya Park Plaza, Caloocan City.",
        "San Isidro": "San Isidro Street, Antipolo City.",
        "Quiapo": "123 P. Casal Street, Quiapo, Manila.",
        "Kiko": "San Francisco Street, San Juan City.",
        "Naga": "Panganiban Drive, Naga City, Bicol.",
    };
    
    branchButtons.forEach(button => {
        button.addEventListener("click", function () {
            const branch = this.getAttribute("data-branch");
            branchName.textContent = branch + " Branch";
            branchLocation.textContent = branchData[branch] || "Location not found.";
        });
    });
});
