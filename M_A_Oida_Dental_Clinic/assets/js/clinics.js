document.addEventListener("DOMContentLoaded", function () {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector(".menu-toggle");
    const navMenu = document.querySelector(".nav-links");
    const menuIcon = menuToggle ? menuToggle.querySelector("i") : null;

    if (menuToggle && navMenu) {
        menuToggle.addEventListener("click", () => {
            navMenu.classList.toggle("show");
            if (menuIcon) {
                menuIcon.classList.toggle("fa-xmark");
            }
        });

        // Close menu when clicking outside
        document.addEventListener("click", (e) => {
            if (window.innerWidth <= 768) {
                if (!navMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                    navMenu.classList.remove("show");
                    if (menuIcon) {
                        menuIcon.classList.remove("fa-xmark");
                    }
                }
            }
        });
    }

    // Branch Details Update
    const branchButtons = document.querySelectorAll(".branch-btn");
    const branchName = document.getElementById("branch-name");
    const branchLocation = document.getElementById("branch-location");
    const branchHours = document.getElementById("branch-hours");

    const branchData = {
        "Commonwealth": {
            location: "#3 Martan St, Brgy. Manggahan Commonwealth, Quezon City.",
            hours: "Mon to Sat: 10:00 AM – 7:00 PM"
        },
        "North Fairview": {
            location: "#45 Samsonville, North Fairview, Quezon City.",
            hours: "Mon to Sat: 9:00 AM – 6:00 PM"
        },
        "Maligaya Park": {
            location: "Unit 2, Maligaya Park Plaza, Caloocan City.",
            hours: "Mon to Sat: 10:00 AM – 7:00 PM"
        },
        "San Isidro": {
            location: "San Isidro Street, Antipolo City.",
            hours: "Mon to Sat: 8:00 AM – 5:00 PM"
        },
        "Quiapo": {
            location: "123 P. Casal Street, Quiapo, Manila.",
            hours: "Mon to Sat: 10:00 AM – 8:00 PM"
        },
        "Kiko": {
            location: "San Francisco Street, San Juan City.",
            hours: "Mon to Sat: 9:00 AM – 6:00 PM"
        },
        "Naga": {
            location: "Panganiban Drive, Naga City, Bicol.",
            hours: "Mon to Sat: 9:00 AM – 5:00 PM"
        }
    };

    if (branchButtons.length > 0 && branchName && branchLocation && branchHours) {
        branchButtons.forEach(button => {
            button.addEventListener("click", function() {
                const branch = this.getAttribute("data-branch");
                if (branchData[branch]) {
                    branchName.textContent = `${branch} Branch`;
                    branchLocation.textContent = branchData[branch].location;
                    branchHours.textContent = branchData[branch].hours;
                }
            });
        });
    }

    // Active Navigation Link Highlight
    const currentPage = location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-links a");
    
    if (navLinks.length > 0) {
        navLinks.forEach(link => {
            if (link.getAttribute("href") === currentPage) {
                link.classList.add("active");
            }
        });
    }
});
