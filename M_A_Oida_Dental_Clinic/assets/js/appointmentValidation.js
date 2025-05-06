function validateAppointment() {
    let isValid = true;
    const errors = [];
    
    // Clear previous errors
    clearErrors();

    // Date validation
    const dateInput = document.getElementById('appointment-date');
    if (!dateInput || !dateInput.value) {
        showError(dateInput, 'Please select an appointment date');
        isValid = false;
    } else {
        const selectedDate = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            showError(dateInput, 'Please select a future date');
            isValid = false;
        }
        
        const dayOfWeek = selectedDate.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            showError(dateInput, 'Appointments are not available on weekends');
            isValid = false;
        }
    }

    // Time slot validation
    const selectedTimeSlot = document.querySelector('.time-slot.selected');
    if (!selectedTimeSlot) {
        showError(document.querySelector('.time-slots'), 'Please select an appointment time');
        isValid = false;
    } else if (selectedTimeSlot.classList.contains('booked')) {
        showError(selectedTimeSlot, 'This time slot is already booked. Please select another time.');
        isValid = false;
    }

    // Clinic branch validation
    const clinicSelect = document.getElementById('clinic');
    if (!clinicSelect || !clinicSelect.value) {
        showError(clinicSelect, 'Please select a clinic branch');
        isValid = false;
    }

    // Services validation
    const selectedServices = document.querySelectorAll('input[name="services[]"]:checked');
    if (selectedServices.length === 0) {
        showError(document.querySelector('.services-section'), 'Please select at least one service');
        isValid = false;
    }

    return isValid;
}

function showError(element, message) {
    if (!element) return;
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // Add error styles to the element
    element.classList.add('error-field');
    
    // Insert error message after the element
    element.parentNode.insertBefore(errorDiv, element.nextSibling);
    
    // Scroll into view if not visible
    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function clearErrors() {
    // Remove all error messages
    document.querySelectorAll('.error-message').forEach(error => error.remove());
    
    // Remove error styles from elements
    document.querySelectorAll('.error-field').forEach(element => {
        element.classList.remove('error-field');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            if (!validateAppointment()) {
                e.preventDefault();
            }
        });
    }
    
    // Add validation on time slot selection
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('time-slot')) {
            if (e.target.classList.contains('booked')) {
                showError(e.target, 'This time slot is already booked');
                return false;
            }
            clearErrors();
        }
    });
});