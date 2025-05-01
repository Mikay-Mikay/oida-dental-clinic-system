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

document.addEventListener("DOMContentLoaded", function () {
    // Highlight active navigation link
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-links a");
    
    navLinks.forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active"); // Add 'active' class to highlight current page
        }
    });

    // Smooth transition when navigating between pages
    const pageLinks = document.querySelectorAll(".nav-links a, .book-now");
    
    pageLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const target = this.getAttribute("href");

            // Apply fade-out effect before navigating
            document.body.style.opacity = "0";
            setTimeout(() => {
                window.location.href = target;
            }, 300); // Transition duration
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

    // Fade-in effect when page loads
    document.body.style.opacity = "0";
    setTimeout(() => {
        document.body.style.opacity = "1";
    }, 300);
});

// === your existing DOMContentLoaded blocks ===
// (notifications, link highlighting, smooth transitions…)


// === new branch & map loader ===
const branches = {
    "north-fairview": {
      img:    "assets/photos/regalado_branch.png",
      mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3668.0860597628844!2d121.0596292799073!3d14.711035491402756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b10d18e2494d%3A0x7ad9da87e3339a6d!2sM%20and%20A%20Oida%20Dental%20Clinic!5e1!3m2!1sen!2sph!4v1746116763981!5m2!1sen!2sph"
    }
  };
  
  
  
  document.addEventListener("DOMContentLoaded", () => {
    const branchKey = "north-fairview";
  
    // swap in branch image (redundant if hard-coded in PHP, but safe)
    const imgEl = document.querySelector(".contact-image");
    if (imgEl && branches[branchKey]) {
      imgEl.src = branches[branchKey].img;
    }
  
    // load map iframe
    const mapIframe = document.getElementById("googleMap");
    if (mapIframe && branches[branchKey]) {
      mapIframe.src = branches[branchKey].mapUrl;
    }
  });
  
