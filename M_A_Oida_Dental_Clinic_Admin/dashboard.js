document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    const sidebarToggleBtn = document.getElementById("sidebarToggleBtn");

    // Toggle sidebar visibility
    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("closed");
        mainContent.classList.toggle("sidebar-closed");
    });

    // Show sidebar when the toggle button is clicked
    sidebarToggleBtn.addEventListener("click", () => {
        sidebar.classList.remove("closed");
        mainContent.classList.remove("sidebar-closed");
    });
})