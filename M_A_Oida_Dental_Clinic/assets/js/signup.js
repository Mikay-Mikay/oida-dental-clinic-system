// --- Cascading Address Dropdown Integration with Debugging ---
// Uses jQuery for AJAX and DOM manipulation
// JSON files are in assets/js/integrate/ relative to signup.php

$(document).ready(function() {
    // Load Regions
    function loadRegions() {
        $.getJSON('assets/js/integrate/refregion.json', function(data) {
            const regionSelect = $('#region');
            regionSelect.empty().append('<option value="">Select a Region</option>');
            $.each(data, function(index, region) {
                regionSelect.append('<option value="'+ region.region_id +'">'+ region.region_description +'</option>');
            });
        }).fail(function(jqxhr, textStatus, error) {
            console.error('Failed to load regions:', textStatus, error);
        });
    }
    // Load Provinces based on Region
    function loadProvinces(regionId) {
        $('#province').empty().append('<option value="">Select a Province</option>');
        $('#city').empty().append('<option value="">Select a City/Municipality</option>');
        $('#barangay').empty().append('<option value="">Select a Barangay</option>');
        if(regionId) {
            $.getJSON('assets/js/integrate/refprovince.json', function(data) {
                let found = false;
                console.log('Selected regionId:', regionId);
                console.log('Provinces loaded:', data);
                $.each(data, function(index, province) {
                    console.log('Checking province:', province);
                    if(String(province.region_id) === String(regionId)) {
                        $('#province').append('<option value="'+ province.province_id +'">'+ province.province_name +'</option>');
                        found = true;
                    }
                });
                if (!found) { console.warn('No provinces found for region:', regionId); }
            }).fail(function(jqxhr, textStatus, error) {
                console.error('Failed to load provinces:', textStatus, error);
            });
        }
    }
    // Load Cities based on Province
    function loadCities(provinceId) {
        $('#city').empty().append('<option value="">Select a City/Municipality</option>');
        $('#barangay').empty().append('<option value="">Select a Barangay</option>');
        if(provinceId) {
            $.getJSON('assets/js/integrate/refcity.json', function(data) {
                let found = false;
                console.log('Selected provinceId:', provinceId);
                console.log('Cities loaded:', data);
                $.each(data, function(index, city) {
                    console.log('Checking city:', city);
                    if(String(city.province_id) === String(provinceId)) {
                        $('#city').append('<option value="'+ city.municipality_id +'">'+ city.municipality_name +'</option>');
                        found = true;
                    }
                });
                if (!found) { console.warn('No cities found for province:', provinceId); }
            }).fail(function(jqxhr, textStatus, error) {
                console.error('Failed to load cities:', textStatus, error);
            });
        }
    }
    // Load Barangays based on City
    function loadBarangays(cityId) {
        $('#barangay').empty().append('<option value="">Select a Barangay</option>');
        if(cityId) {
            $.getJSON('assets/js/integrate/refbrgy.json', function(data) {
                let found = false;
                console.log('Selected cityId:', cityId);
                console.log('Barangays loaded:', data);
                $.each(data, function(index, brgy) {
                    console.log('Checking barangay:', brgy);
                    if(String(brgy.municipality_id) === String(cityId)) {
                        $('#barangay').append('<option value="'+ brgy.barangay_id +'">'+ brgy.barangay_name +'</option>');
                        found = true;
                    }
                });
                if (!found) { console.warn('No barangays found for city:', cityId); }
            }).fail(function(jqxhr, textStatus, error) {
                console.error('Failed to load barangays:', textStatus, error);
            });
        }
    }
    // Event listeners
    $('#region').on('change', function() {
        const val = $(this).val();
        console.log('Region selected:', val);
        loadProvinces(val);
    });
    $('#province').on('change', function() {
        const val = $(this).val();
        console.log('Province selected:', val);
        loadCities(val);
    });
    $('#city').on('change', function() {
        const val = $(this).val();
        console.log('City selected:', val);
        loadBarangays(val);
    });
    // Initial load
    loadRegions();

    // --- Keep your existing form submission logic below ---

    document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('signup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                window.location.href = 'login.php';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// --- END Cascading Dropdown Integration ---
