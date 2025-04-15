


// Branch image data
const branchData = {
    "commonwealth": {
        image: "assets/photos/CWBranch_CL1.PNG", 
        map: "assets/photos/CWBranch_Maps.PNG"
    },
    "north-fairview": {
        image: "assets/photos/NFBranch_CL1.PNG", 
        map: "assets/photos/NFBranch_Maps.PNG"
    },
    "maligaya": {
        image: "assets/photos/MPBranch_CL1.PNG", 
        map: "assets/photos/MPBranch_Maps.PNG"
    },
    "montalban": {
        image: "assets/photos/MontalbanBranch_CL1.PNG", 
        map: "assets/photos/no maps.PNG"
    },
    "quiapo": {
        image: "assets/photos/QuiapoBranch_CL1.PNG", 
        map: "assets/photos/QuiapoBranch_Maps.PNG"
    },
    "kiko": {
        image: "assets/photos/KikoBranch_CL1.PNG", 
        map: "assets/photos/no maps.PNG"
    },
    "naga": {
        image: "assets/photos/NagaBranch_CL1.PNG", 
        map: "assets/photos/NagaBranch_Maps.PNG"
    }
};

// DOM Elements
const branchButtons = document.querySelectorAll('.branch-btn');
const branchImage = document.getElementById('branch-image');
const branchMap = document.getElementById('branch-map');

// Function to update branch images only
function updateBranchImages(branchId) {
    const branch = branchData[branchId];
    
    // Update images
    branchImage.src = branch.image;
    branchImage.alt = `${branchId} branch image`;
    branchMap.src = branch.map;
    branchMap.alt = `${branchId} map location`;
    
    // Update active button
    branchButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.branch === branchId) {
            btn.classList.add('active');
        }
    });
}

// Event listeners for branch buttons
branchButtons.forEach(button => {
    button.addEventListener('click', () => {
        const branchId = button.dataset.branch;
        updateBranchImages(branchId);
    });
});

// Initialize with Commonwealth branch
document.addEventListener('DOMContentLoaded', () => {
    updateBranchImages('commonwealth');
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