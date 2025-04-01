const locations = {
    "NCR": {
        "Metro Manila": {
            "Manila": ["Barangay 1", "Barangay 2", "Barangay 3"],
            "Quezon City": ["Commonwealth", "Batasan Hills", "Diliman"],
            "Makati": ["Poblacion", "Bel-Air", "Guadalupe Viejo"]
        }
    },
    "Region IV-A (Calabarzon)": {
        "Cavite": {
            "Dasmariñas": ["Salitran", "Burol", "San Agustin"],
            "Bacoor": ["Talaba", "Zapote", "Molino"]
        },
        "Laguna": {
            "Santa Rosa": ["Balibago", "Market Area", "Tagapo"],
            "Calamba": ["Canlubang", "Real", "Pansol"]
        }
    },
    "Region III (Central Luzon)": {
        "Pampanga": {
            "San Fernando": ["Dolores", "San Agustin", "Sindalan"],
            "Angeles City": ["Balibago", "Pulungbulu", "Malabanias"]
        }
    }
};

function populateRegions() {
    let regionSelect = document.getElementById("region");
    regionSelect.innerHTML = '<option value="">Select a Region</option>';
    Object.keys(locations).forEach(region => {
        let option = new Option(region, region);
        regionSelect.add(option);
    });
}

function updateProvinces() {
    let region = document.getElementById("region").value;
    let provinceSelect = document.getElementById("province");
    provinceSelect.innerHTML = '<option value="">Select a Province</option>';

    if (region && locations[region]) {
        Object.keys(locations[region]).forEach(province => {
            let option = new Option(province, province);
            provinceSelect.add(option);
        });
    }
}

function updateCities() {
    let region = document.getElementById("region").value;
    let province = document.getElementById("province").value;
    let citySelect = document.getElementById("city");
    citySelect.innerHTML = '<option value="">Select a City/Municipality</option>';

    if (region && province && locations[region][province]) {
        Object.keys(locations[region][province]).forEach(city => {
            let option = new Option(city, city);
            citySelect.add(option);
        });
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

document.addEventListener("DOMContentLoaded", populateRegions);
