document.addEventListener("DOMContentLoaded", function () {
    // Highlight active navigation link
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-links a");

    navLinks.forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });

    // Smooth transition between pages
    const pageLinks = document.querySelectorAll(".nav-links a, .book-now");

    pageLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const target = this.getAttribute("href");

            // Apply fade-out effect before navigating
            document.body.style.opacity = "0";
            setTimeout(() => {
                window.location.href = target;
            }, 300); // Adjust timing if needed
        });
    });

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            document.getElementById(targetId).scrollIntoView({
                behavior: "smooth"
            });
        });
    });

    // Fade-in effect on page load
    setTimeout(() => {
        document.body.style.opacity = "1";
    }, 300);
});

//Notifcation nav
document.addEventListener("DOMContentLoaded", function () {
    const bellToggle = document.querySelector(".notification-toggle");
    const wrapper = document.querySelector(".notification-wrapper");

    bellToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        wrapper.classList.toggle("show");
    });

    document.addEventListener("click", function (e) {
        if (!wrapper.contains(e.target)) {
            wrapper.classList.remove("show");
        }
    });
});
