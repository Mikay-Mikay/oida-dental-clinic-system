document.addEventListener("DOMContentLoaded", function () {
    // Get current page filename
    const currentPage = window.location.pathname.split("/").pop();

    // Fix active link highlighting
    const navLinks = document.querySelectorAll(".nav-links a");
    navLinks.forEach(link => {
        let linkPage = link.getAttribute("href");

        if ((currentPage === "index.html" || currentPage === "homepage.html") && linkPage.includes("homepage.html")) {
            link.classList.add("active");
        } else if (linkPage === currentPage) {
            link.classList.add("active");
        }
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

    // Mobile menu toggle
    const menuToggle = document.querySelector(".menu-toggle");
    const navMenu = document.querySelector(".nav-links");

    if (menuToggle) {
        menuToggle.addEventListener("click", () => {
            navMenu.classList.toggle("show");
        });
    }

    // Service descriptions
    const servicesData = {
        "checkup.jpg": {
            title: "Dental Check-ups & Consultation",
            description: "Regular examinations of the teeth and oral cavity to assess overall dental health and detect potential issues.",
            price: "₱750 per consultation"
        },
        "cleaning.jpg": {
            title: "Teeth Cleaning (Oral Prophylaxis)",
            description: "Removal of plaque, tartar, and stains to maintain oral hygiene and prevent gum disease.",
            price: "₱1,000 per session"
        },
        "extraction.jpg": {
            title: "Tooth Extraction",
            description: "Safe and professional removal of damaged or problematic teeth to relieve pain and prevent infections.",
            price: "₱1,500 per tooth"
        },
        "fillings.jpg": {
            title: "Dental Fillings",
            description: "Restoration of decayed or damaged teeth using high-quality dental materials.",
            price: "₱2,000 per filling"
        },
        "gum-treatment.jpg": {
            title: "Gum Treatment",
            description: "Treatments focused on managing and treating gum diseases, such as scaling and root planing, which help remove plaque and tartar from beneath the gum line to prevent further periodontal issues.",
            price: "5,000 per quadrant"
        },
        "whitening.jpg": {
            title: "Teeth Whitening",
            description: "A professional treatment designed to brighten your smile by removing stains and discoloration, resulting in a noticeably whiter smile.",
            price: "₱12,000 per session"
        },
        "veeners.jpg": {
            title: "Dental Veeners",
            description: "Custom-made, thin porcelain shells that are bonded to the front of your teeth to enhance their appearance by improving shape, color, and overall alignment.",
            price: "₱6,000 per tooth"
        },
        "bonding.jpg": {
            title: "Dental Bonding",
            description: "A minimally invasive procedure that repairs chipped, discolored, or misaligned teeth using a composite resin material to restore their natural appearance.",
            price: "₱4,000 per tooth"
        },
        "braces.jpg": {
            title: "Metal Braces",
            description: "A Traditional stainless steel braces used for effective teeth alignment.",
            price: "₱50,000 for a full treatment package"
        },
        "ceramic.jpg": {
            title: "Ceramic Braces",
            description: "A more aesthetic alternative to metal braces, offering a less noticeable appearance.",
            price: "₱70,000 for a full treatment package"
        },
        "alligner.jpg": {
            title: "Clear Alligner",
            description: "Removable, transparent aligners that straighten teeth without the need for traditional braces.",
            price: "₱12,000 for a complete treatment plan"
        },
        "retainer.jpg": {
            title: "Retainers",
            description: "Custom-made devices to maintain teeth alignment after orthodontic treatment.",
            price: " ₱5,000 per retainer"
        },
        "crown.jpg": {
            title: "Dental Crown",
            description: "Custom caps placed over damaged teeth to restore strength and appearance.",
            price: "₱5,000 per crown"
        },
        "bridges.jpg": {
            title: "Dental Bridges",
            description: "Fixed prosthetic devices used to replace one or more missing teeth by anchoring to adjacent teeth.",
            price: "₱20,000 for a three-unit bridge"
        },
        "denture.jpg": {
            title: "Dentures",
            description: "Removable replacements for missing teeth; partial dentures replace several teeth while full dentures replace all teeth in an arch.",
            price: "Partial Dentures: ₱15,000 Full Dentures: ₱25,000 per arch"
        },
        "implants.jpg": {
            title: "Dental Implants",
            description: "Surgically placed fixtures that serve as permanent replacements for missing teeth.",
            price: " ₱45,000 per implant"
        },
        "flouride.jpg": {
            title: "Flouride Treament",
            description: "Application of fluoride to strengthen children’s teeth and help prevent cavities.",
            price: "₱800 per application"
        },
        "sealant.jpg": {
            title: "Dental Sealants",
            description: "Protective coatings applied to the chewing surfaces of back teeth to prevent decay.",
            price: "₱1,000 for a set of teeth"
        },
        "kids.jpg": {
            title: "Kids Braces",
            description: "Early orthodontic interventions designed to correct developing alignment issues in children.",
            price: "₱30,000 for a comprehensive treatment package"
        },
        "wisdom.jpg": {
            title: "Wisdom Toooth Extraction",
            description: "Removal of impacted or problematic wisdom teeth.",
            price: "Simple Extraction: ₱4,000 per tooth Impacted Extraction: ₱10,000 per tooth"
        },
        "root.jpg": {
            title: "Root Canal Treatment",
            description: "Treatment for infected or inflamed tooth pulp aimed at saving the tooth.",
            price: "₱1,500 per tooth"
        },
        "tmj.jpg": {
            title: "TMJ Treatment",
            description: "Therapeutic interventions to manage jaw pain and temporomandibular joint disorders.",
            price: " ₱7,000 per session"
        },
        "intraoral.jpg": {
            title: "Intraoral X-ray",
            description: "Detailed images of individual teeth and adjacent structures, ideal for detecting cavities, bone loss, and other issues.",
            price: "₱500 per set"
        },
        "panoramic.jpg": {
            title: "Panoramic X-rays",
            description: "A comprehensive view capturing the entire jaw, teeth, and surrounding areas, essential for overall dental assessments.",
            price: "₱1,500 per scan"
        },
        "cephalometric.jpg": {
            title: "Cephalometric X-ray",
            description: "Side profile images used primarily for orthodontic evaluations, helping assess skeletal relationships and treatment planning.",
            price: "₱1,000 per scan"
        }
    };

    // Create modal dynamically (para walang duplicate)
    const modalContainer = document.createElement("div");
    modalContainer.id = "service-modal";
    modalContainer.classList.add("modal");
    modalContainer.innerHTML = `
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modal-title"></h2>
            <p><strong>Description:</strong> <span id="modal-description"></span></p>
            <p><strong>Price:</strong> <span id="modal-price"></span></p>
            <button class="book-now">Book Now</button>
        </div>
    `;
    document.body.appendChild(modalContainer);

    const modalTitle = document.getElementById("modal-title");
    const modalDescription = document.getElementById("modal-description");
    const modalPrice = document.getElementById("modal-price");
    const closeModal = modalContainer.querySelector(".close");

    // Open modal when clicking on a service item
    document.querySelectorAll(".service-item").forEach(service => {
        service.addEventListener("click", function () {
            let imgSrc = this.querySelector("img").getAttribute("src").split("/").pop(); // Get only filename

            if (servicesData[imgSrc]) {
                let serviceInfo = servicesData[imgSrc];

                modalTitle.textContent = serviceInfo.title;
                modalDescription.textContent = serviceInfo.description;
                modalPrice.textContent = serviceInfo.price;
                modalContainer.style.display = "flex"; // Show modal
            }
        });
    });

    // Close modal when clicking the close button
    closeModal.addEventListener("click", function () {
        modalContainer.style.display = "none";
    });

    // Close modal when clicking outside the modal content
    window.addEventListener("click", function (event) {
        if (event.target === modalContainer) {
            modalContainer.style.display = "none";
        }
    });
});