document.addEventListener("DOMContentLoaded", function () {
    if (typeof locations !== "undefined") {
        populateRegions();
    } else {
        console.error("locations data is missing!");
    }

    document.getElementById("region").addEventListener("change", updateProvinces);
    document.getElementById("province").addEventListener("change", updateCities);
    document.getElementById("city").addEventListener("change", updateBarangays);

    // Handle form submission
    const form = document.querySelector("form");
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        let formData = new FormData(form);

        fetch("signup.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            if (data.status === "success") {
                alert(data.message); // Show success message
                window.location.href = "index.php"; // Redirect to login page
            } else {
                alert(data.message); // Show error message
            }
        })
        .catch(error => console.error("Error:", error));
    });
});

function populateRegions() {
    let regionSelect = document.getElementById("region");
    regionSelect.innerHTML = '<option value="">Select a Region</option>';
    
    for (let region in locations) {
        let option = new Option(region, region);
        regionSelect.add(option);
    }
}

function updateProvinces() {
    let region = document.getElementById("region").value;
    let provinceSelect = document.getElementById("province");
    provinceSelect.innerHTML = '<option value="">Select a Province</option>';
    
    if (region && locations[region]) {
        for (let province in locations[region]) {
            let option = new Option(province, province);
            provinceSelect.add(option);
        }
    }
}

function updateCities() {
    let region = document.getElementById("region").value;
    let province = document.getElementById("province").value;
    let citySelect = document.getElementById("city");
    citySelect.innerHTML = '<option value="">Select a City/Municipality</option>';

    if (region && province && locations[region][province]) {
        for (let city in locations[region][province]) {
            let option = new Option(city, city);
            citySelect.add(option);
        }
    }
}

function updateBarangays() {
    let region = document.getElementById("region").value;
    let province = document.getElementById("province").value;
    let city = document.getElementById("city").value;
    let barangaySelect = document.getElementById("barangay");
    barangaySelect.innerHTML = '<option value="">Select a Barangay</option>';

    if (region && province && city && locations[region][province][city]) {
        locations[region][province][city].forEach(barangay => {
            let option = new Option(barangay, barangay);
            barangaySelect.add(option);
        });
    }
}
