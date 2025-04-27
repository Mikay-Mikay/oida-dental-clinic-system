document.addEventListener("DOMContentLoaded", function () {
    // Notification Toggle
    const bellToggle = document.querySelector(".notification-toggle");
    const wrapper = document.querySelector(".notification-wrapper");

    if (bellToggle && wrapper) {
        bellToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            wrapper.classList.toggle("show");
        });

        document.addEventListener("click", function (e) {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove("show");
            }
        });
    }

    // Active nav link
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".nav-links a");
    navLinks.forEach(link => {
        let linkPage = link.getAttribute("href");
        if ((currentPage === "index.php" || currentPage === "homepage.php") && linkPage.includes("homepage.php")) {
            link.classList.add("active");
        } else if (linkPage === currentPage) {
            link.classList.add("active");
        }
    });

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            document.getElementById(targetId)?.scrollIntoView({
                behavior: "smooth"
            });
        });
    });

    // Mobile menu toggle
    const menuToggle = document.querySelector(".menu-toggle");
    const navMenu = document.querySelector(".nav-links");
    if (menuToggle && navMenu) {
        menuToggle.addEventListener("click", () => {
            navMenu.classList.toggle("show");
        });
    }

    // Services data
    const servicesData = {
        "checkup.png": {
            title: "Dental Check-ups & Consultation",
            description: "Regular examinations of the teeth and oral cavity to assess overall dental health and detect potential issues. This service includes a detailed consultation and the development of a personalized treatment plan."
        },
        "cleaning.png": {
            title: "Teeth Cleaning (Oral Prophylaxis)",
            description: "A professional cleaning procedure designed to remove plaque and tartar buildup from the teeth. This service helps prevent tooth decay and gum disease."
        },
        "extraction.png": {
            title: "Tooth Extraction",
            description: "The removal of a damaged or decayed tooth that cannot be restored through other dental procedures. This service is performed with careful attention to patient comfort and safety.",
        },
        "fillings.png": {
            title: "Dental Fillings/Dental Bonding",
            description: "Dental Fillings is a process of treating cavities by removing decayed material from the tooth and filling the area with a restorative material, such as composite resin, to restore the tooth's function and appearance. Dental Bonding is a minimally invasive procedure that repairs chipped, discolored, or misaligned teeth using a composite resin material to restore their natural appearance.",
        },
        "gum-treatment.png": {
            title: "Gum Treatment and Gingivectomy (Periodontal Care)",
            description: "Treatments focused on managing and treating gum diseases, such as scaling and root planing, which help remove plaque and tartar from beneath the gum line to prevent further periodontal issues.",
        },
        "whitening.png": {
            title: "Teeth Whitening",
            description: "A professional treatment designed to brighten your smile by removing stains and discoloration, resulting in a noticeably whiter smile.",
        },
        "veneers.png": {
            title: "Dental Veeners",
            description: "Custom-made, thin porcelain shells that are bonded to the front of your teeth to enhance their appearance by improving shape, color, and overall alignment.",
        },
        "braces.png": {
            title: "Metal Braces/Ceramic Braces",
            description: "A Traditional stainless steel braces used for effective teeth alignment. For Ceramic Braces, it’s  more aesthetic alternative to metal braces, offering a less noticeable appearance.",
        },
        "retainer.png": {
            title: "Clear Aligner/Retainers",
            description: "Clear Invisalign is a discreet and removable aligner used to straighten teeth comfortably without metal braces. It’s clear, custom-made, and easy to wear. A Retainers is a custom-made devices as well to maintain teeth alignment after orthodontic treatment.",
        },
        "crowns.png": {
            title: "Dental Crown",
            description: "Custom caps placed over damaged teeth to restore strength and appearance.",        },
        "bridges.png": {
            title: "Dental Bridges",
            description: "Fixed prosthetic devices used to replace one or more missing teeth by anchoring to adjacent teeth.",
        },
        "dentures.png": {
            title: "Dentures (Partial & Full)",
            description: "Removable replacements for missing teeth; partial dentures replace several teeth while full dentures replace all teeth in an arch.",
        },
        "implants.png": {
            title: "Dental Implants",
            description: "Surgically placed fixtures that serve as permanent replacements for missing teeth.",
        },
        "flouride.png": {
            title: "Flouride Treament",
            description: "Application of fluoride to strengthen children’s teeth and help prevent cavities.",
        },
        "sealants.png": {
            title: "Dental Sealants",
            description: "Protective coatings applied to the chewing surfaces of back teeth to prevent decay.",
        },
        "kidsbrace.png": {
            title: "Kids Braces & Orthodontic Care",
            description: "Early orthodontic interventions designed to correct developing alignment issues in children.",
        },
        "wisdomtooth.png": {
            title: "Wisdom Toooth Extraction (Odontectomy)",
            description: "Removal of impacted or problematic wisdom teeth.",
        },
        "rootcanal.png": {
            title: "Root Canal Treatment (Endodontics)",
            description: "Treatment for infected or inflamed tooth pulp aimed at saving the tooth.",
        },
        "tmjtreat.png": {
            title: "TMJ Treatment",
            description: "Therapeutic interventions to manage jaw pain and temporomandibular joint disorders.",
        },
        "intraoral.png": {
            title: "Intraoral X-ray",
            description: "Detailed images of individual teeth and adjacent structures, ideal for detecting cavities, bone loss, and other issues.",            
        },
        "panoramic.png": {
            title: "Panoramic X-Ray/Full Mouth X-Ray",
            description: "Is a quick and painless dental imaging technique that captures a full view of your mouth in a single image, including your teeth, jaw, and surrounding structures. It helps dentists detect issues that may not be visible during a regular check-up, such as impacted teeth, bone abnormalities, or infections.",
        },
        "cephalometric.png": {
            title: "Lateral Cephalometric X-ray",
            description: "Side profile images used primarily for orthodontic evaluations, helping assess skeletal relationships and treatment planning.",
        },
        "periapical.png": {
            title: "Periapical X-Ray/Single Tooth X-Ray",
            description: "It focuses on a specific tooth and its surrounding bone. It provides a detailed image from the crown to the root, helping the dentist diagnose problems like tooth decay, abscesses, or root infections.",
        },
        "tmjxray.png": {
            title: "TMJ Transcranial X-Ray",
            description: "A specialized imaging technique used to examine the temporomandibular joint (TMJ), which connects the jaw to the skull. It helps detect joint disorders, alignment issues, and other abnormalities that may cause jaw pain or movement problems.",
        }
    };

    // Create modal dynamically (para walang duplicate)
    
    const modalContainer = document.createElement("div");
modalContainer.id = "service-modal";
modalContainer.classList.add("modal");
modalContainer.style.display = "none"; // Ensure modal is initially hidden
modalContainer.innerHTML = `
    <div class="modal-content">
        <span class="close">×</span>
        <h2 id="modal-title"></h2>
        <p><strong>Description:</strong> <span id="modal-description"></span></p>
        <button class="book-now">Book Now</button>
    </div>
`;
    // Use existing modal in HTML
    const serviceItems = document.querySelectorAll('.service-item');
    const modal = document.getElementById('serviceModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const closeModal = document.querySelector('.modal .close');
    const bookNowBtn = document.querySelector('.modal .book-now');

    serviceItems.forEach(item => {
        item.addEventListener('click', function () {
            const imgSrc = this.querySelector('img').getAttribute('src').split('/').pop();
            const service = servicesData[imgSrc];
            if (service) {
                modalImage.src = this.querySelector('img').getAttribute('src');
                modalTitle.textContent = service.title;
                modalDescription.textContent = service.description;
                modal.style.display = 'block';
            }
        });
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    bookNowBtn.addEventListener('click', function () {
        window.location.href = 'bookings.php';
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
