// bookings.js
// Handles form navigation and validation for the 4-section appointment form

let currentStep = 1;
const totalSteps = 4; // Updated from 8 to 4 sections

// Global variables
let selectedServices = [];
let touchIndicator;

// Show the specified section and update progress bar
function showSection(step) {
  console.log(`Showing section ${step}`);
  document.querySelectorAll('.form-section').forEach((section, idx) => {
    const isActive = idx + 1 === step;
    section.classList.toggle('active', isActive);
    if (isActive) {
      console.log(`Activated section: ${section.id}`);
    }
  });
  updateProgressBar(step);
}

// Initialize the form when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  console.log("Initializing booking form - current step:", currentStep);
  
  // Log available sections for debugging
  const sections = document.querySelectorAll('.form-section');
  console.log(`Found ${sections.length} form sections:`);
  sections.forEach((section, idx) => {
    console.log(`Section ${idx + 1}: ${section.id}`);
  });
  
  // Show initial section
  showSection(currentStep);
  
  // Add event listeners to all navigation buttons
  document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      console.log(`Next button clicked from section ${currentStep}`);
      nextSection();
    });
  });
  
  document.querySelectorAll('.prev-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      console.log(`Previous button clicked from section ${currentStep}`);
      prevSection();
    });
  });
  
  // Create touch indicator element for debugging
  touchIndicator = document.createElement('div');
  touchIndicator.className = 'touch-indicator';
  document.body.appendChild(touchIndicator);
  
  // Add service selection events
  initServiceSelection();
  
  // Add clinic and doctor selection events
  initAppointmentSelections();
  
  // Initialize multi-select dropdowns
  initMultiSelectDropdowns();
  
  // Add a fallback click handler for service cards in case the regular initialization doesn't work
  addServiceCardFallbackHandlers();
});

// Initialize multi-select dropdowns with the None option behavior
function initMultiSelectDropdowns() {
  function updateSelectAppearance(selectElement) {
    const selectedOptions = Array.from(selectElement.selectedOptions);
    const isNoneSelected = selectedOptions.some(opt => opt.value === 'None');
    
    // Apply visual styling based on selection
    Array.from(selectElement.options).forEach(opt => {
      if (isNoneSelected && opt.value !== 'None') {
        opt.style.color = '#999';
        opt.style.backgroundColor = '#f0f0f0';
      } else {
        opt.style.color = '';
        opt.style.backgroundColor = '';
      }
    });
    
    // Add a message or styling to the select container
    const container = selectElement.closest('.select-dropdown');
    if (container) {
      if (isNoneSelected) {
        container.classList.add('none-selected');
      } else {
        container.classList.remove('none-selected');
      }
    }
  }

  // Initialize diseases multi-select
  const diseasesSelect = document.getElementById('diseases-select');
  if (diseasesSelect) {
    // If no options are selected, select "None" by default
    if (Array.from(diseasesSelect.selectedOptions).length === 0) {
      const noneOption = diseasesSelect.querySelector('option[value="None"]');
      if (noneOption) noneOption.selected = true;
    }
    
    // Apply initial appearance
    updateSelectAppearance(diseasesSelect);
    
    // Ensure "None" cannot be selected with other options
    diseasesSelect.addEventListener('change', function(e) {
      const selectedOptions = Array.from(this.selectedOptions);
      const noneOption = this.querySelector('option[value="None"]');
      const isNoneSelected = selectedOptions.some(opt => opt.value === 'None');
      
      if (isNoneSelected && selectedOptions.length > 1) {
        // If "None" is clicked while other options are selected, unselect everything else
        Array.from(this.options).forEach(opt => {
          opt.selected = opt.value === 'None';
        });
      } else if (selectedOptions.length === 0) {
        // If nothing is selected, select "None"
        if (noneOption) noneOption.selected = true;
      } else if (!isNoneSelected && noneOption && noneOption.selected) {
        // If another option is selected and "None" was already selected, unselect "None"
        noneOption.selected = false;
      }
      
      // Update appearance after change
      updateSelectAppearance(this);
    });
  }
  
  // Initialize allergies multi-select (same logic)
  const allergiesSelect = document.getElementById('allergies-select');
  if (allergiesSelect) {
    // If no options are selected, select "None" by default
    if (Array.from(allergiesSelect.selectedOptions).length === 0) {
      const noneOption = allergiesSelect.querySelector('option[value="None"]');
      if (noneOption) noneOption.selected = true;
    }
    
    // Apply initial appearance
    updateSelectAppearance(allergiesSelect);
    
    // Ensure "None" cannot be selected with other options
    allergiesSelect.addEventListener('change', function(e) {
      const selectedOptions = Array.from(this.selectedOptions);
      const noneOption = this.querySelector('option[value="None"]');
      const isNoneSelected = selectedOptions.some(opt => opt.value === 'None');
      
      if (isNoneSelected && selectedOptions.length > 1) {
        // If "None" is clicked while other options are selected, unselect everything else
        Array.from(this.options).forEach(opt => {
          opt.selected = opt.value === 'None';
        });
      } else if (selectedOptions.length === 0) {
        // If nothing is selected, select "None"
        if (noneOption) noneOption.selected = true;
      } else if (!isNoneSelected && noneOption && noneOption.selected) {
        // If another option is selected and "None" was already selected, unselect "None"
        noneOption.selected = false;
      }
      
      // Update appearance after change
      updateSelectAppearance(this);
    });
  }
}

// Move to next section if validation passes
function nextSection() {
  console.log(`Attempting to move from section ${currentStep} to ${currentStep + 1}`);
  
  if (validateSection(currentStep)) {
    if (currentStep < totalSteps) {
      currentStep++;
      console.log(`Successfully moved to section ${currentStep}`);
      showSection(currentStep);
      
      // Update hidden current_section field for server-side tracking
      const currentSectionInput = document.querySelector('input[name="current_section"]');
      if (currentSectionInput) {
        const sectionNames = ['services', 'appointment', 'payment', 'summary'];
        currentSectionInput.value = sectionNames[currentStep - 1] || 'services';
      }
      
      // If moving to payment section, update payment summary
      if (currentStep === 3) {
        updatePaymentSummary();
      }
      
      // If moving to summary section, prepare final data
      if (currentStep === 4) {
        prepareSummaryView();
      }
    } else {
      console.log('Already at last section');
    }
  } else {
    console.log(`Validation failed for section ${currentStep}`);
  }
}

// Move to previous section
function prevSection() {
  if (currentStep > 1) {
    currentStep--;
    showSection(currentStep);
    
    // Update hidden current_section field
    const currentSectionInput = document.querySelector('input[name="current_section"]');
    if (currentSectionInput) {
      const sectionNames = ['services', 'appointment', 'payment', 'summary'];
      currentSectionInput.value = sectionNames[currentStep - 1] || 'services';
    }
  }
}

// Validate current section before proceeding
function validateSection(step) {
  switch(step) {
    case 1: return validateServices();
    case 2: return validateAppointment();
    case 3: return validatePayment();
    default: return true;
  }
}

// Update progress bar based on current step
function updateProgressBar(step) {
  if (step) {
    currentStep = step;
  }
  
  // Update step indicators
  document.querySelectorAll('.step').forEach((stepEl, index) => {
    if (index + 1 < currentStep) {
      stepEl.classList.add('completed');
      stepEl.classList.remove('active');
    } else if (index + 1 === currentStep) {
      stepEl.classList.add('active');
      stepEl.classList.remove('completed');
    } else {
      stepEl.classList.remove('active', 'completed');
    }
  });
}

// ================== ERROR HANDLING FUNCTIONS ==================
function showError(element, message) {
  if (!element) return;
  
  // Clear previous error for this element
  clearErrorFor(element);
  
  const container = element.closest('.form-group') || element.parentElement;
  let errorDiv = container.querySelector('.error-message');
  
  if (!errorDiv) {
    errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    container.appendChild(errorDiv);
  }
  
  errorDiv.textContent = message;
  errorDiv.style.display = 'block';
  element.classList.add('error-field');
}

function clearErrorFor(element) {
  if (!element) return;
  
  const container = element.closest('.form-group') || element.parentElement;
  const errorDiv = container.querySelector('.error-message');
  
  if (errorDiv) {
    errorDiv.textContent = '';
    errorDiv.style.display = 'none';
  }
  
  element.classList.remove('error-field');
}

function clearErrors() {
  document.querySelectorAll('.error-message').forEach(e => {
    e.textContent = '';
    e.style.display = 'none';
  });
  document.querySelectorAll('.error-field').forEach(e => e.classList.remove('error-field'));
}

// ================== SECTION VALIDATION FUNCTIONS ==================
function validatePersonalInfo() {
  clearErrors();
  console.log('Validating Personal Info Section');
  let isValid = true;
  
  // Required form fields validation
  const fieldsToValidate = [
    { selector: 'select[name="religion"]', type: 'select', label: 'Religion' },
    { selector: 'select[name="nationality"]', type: 'select', label: 'Nationality' },
    { selector: 'select[name="region"]', type: 'select', label: 'Region' },
    { selector: 'select[name="province"]', type: 'select', label: 'Province' },
    { selector: 'input[name="city"]', type: 'text', label: 'City/Municipality' },
    { selector: 'input[name="barangay"]', type: 'text', label: 'Barangay' },
    { selector: 'input[name="zip_code"]', type: 'text', label: 'Zip Code' },
    { selector: 'input[name="contact_number"]', type: 'text', label: 'Contact Number' },
    { selector: 'input[name="email"]', type: 'email', label: 'Email' }
  ];
  
  fieldsToValidate.forEach(({ selector, type, label }) => {
    const field = document.querySelector(selector);
    if (!field) {
      console.log(`Field not found: ${selector}`);
      return;
    }
    
    console.log(`Validating field: ${selector}, value: ${field.value}`);
    const value = field.value.trim();
    let errorMsg = '';
    
    switch(type) {
      case 'text':
        if (value === '') {
          errorMsg = `${label} is required`;
          isValid = false;
        }
        break;
      case 'select':
        if (field.selectedIndex === 0 || value === '') {
          errorMsg = `Please select a ${label}`;
          isValid = false;
        }
        break;
      case 'email':
        if (value === '') {
          errorMsg = `${label} is required`;
          isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
          errorMsg = `Please enter a valid email address`;
          isValid = false;
        }
        break;
    }
    
    if (errorMsg) {
    showError(field, errorMsg);
    }
  });
  
  // Specific validation for contact number (should be 11 digits starting with 09)
  const contactField = document.querySelector('input[name="contact_number"]');
  if (contactField && contactField.value && !/^09\d{9}$/.test(contactField.value)) {
    showError(contactField, 'Contact number must be 11 digits starting with 09');
    isValid = false;
  }
  
  // Specific validation for zip code (should be 4 digits)
  const zipField = document.querySelector('input[name="zip_code"]');
  if (zipField && zipField.value && !/^\d{4}$/.test(zipField.value)) {
    showError(zipField, 'Zip Code must be 4 digits');
    isValid = false;
  }
  
  console.log('Personal Info validation result:', isValid);
  return isValid;
}

function validateDentalHistory() {
  clearErrors();
  console.log('Validating Dental History Section');
  let isValid = true;
  
  // Most fields in dental history are optional, but we can validate format if filled
  
  // Validate previous dental visit date format if provided
  const lastVisitField = document.querySelector('input[name="last_dental_visit"]');
  if (lastVisitField && lastVisitField.value) {
    if (!/^\d{4}-\d{2}-\d{2}$/.test(lastVisitField.value)) {
      showError(lastVisitField, 'Please use YYYY-MM-DD format for date');
    isValid = false;
    }
  }
  
  console.log('Dental History validation result:', isValid);
  return isValid;
}

function validateMedicalHistory() {
  clearErrors();
  console.log('Validating Medical History Section');
  let isValid = true;
  
  // Required fields: overall health status
  const healthRadios = document.querySelectorAll('input[name="health"]');
  if (!Array.from(healthRadios).some(radio => radio.checked)) {
    if (healthRadios.length > 0) {
      showError(healthRadios[0].parentElement, 'Please select your overall health status');
    }
    isValid = false;
  }
  
  // Medical condition questions mapping with detail fields
  const medicalQuestionsMap = {
    'treatment': 'treatment_details',
    'operation': 'illness_details',
    'hospitalized': 'hospitalized_details',
    'medication': 'prescription_med_details',
    'tobacco': 'tobacco_details',
    'drugs': 'drugs_details'
  };
  
  // Validate each medical condition question
  Object.entries(medicalQuestionsMap).forEach(([questionName, detailsFieldName]) => {
    // Get the radio buttons for this question
    const radios = document.querySelectorAll(`input[name="${questionName}"]`);
    
    // Check if any option is selected
    const isAnySelected = Array.from(radios).some(radio => radio.checked);
    if (!isAnySelected) {
      // Show error if no option selected
      if (radios.length > 0) {
        showError(radios[0].parentElement, 'Please select Yes or No');
      isValid = false;
      }
    } else {
      // Check if "Yes" is selected but details field is empty
      const yesRadio = Array.from(radios).find(radio => radio.value === 'yes' && radio.checked);
    if (yesRadio) {
        const detailsField = document.querySelector(`input[name="${detailsFieldName}"]`);
      if (detailsField && !detailsField.value.trim()) {
        showError(detailsField, 'Please provide details for this condition');
        isValid = false;
        }
      }
    }
  });
  
  // Check women's section if applicable
  const genderField = document.querySelector('input[name="gender"][value="Female"]:checked');
  if (genderField) {
    const womensQuestions = ['pregnant', 'nursing', 'birth_control'];
    womensQuestions.forEach(question => {
      const radios = document.querySelectorAll(`input[name="${question}"]`);
      if (!Array.from(radios).some(radio => radio.checked)) {
        if (radios.length > 0) {
          showError(radios[0].parentElement, 'This question is required for female patients');
        }
        isValid = false;
      }
    });
  }
  
  console.log('Medical History validation result:', isValid);
  return isValid;
}

function validateMedicalDetails() {
  clearErrors();
  console.log('Validating Medical Details Section');
  let isValid = true;
  
  // Validate Blood Type
  const bloodTypeField = document.querySelector('select[name="blood_type"]');
  if (!bloodTypeField || !bloodTypeField.value.trim()) {
    showError(bloodTypeField, 'Blood Type is required');
    isValid = false;
  } else {
    const validBloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    if (!validBloodTypes.includes(bloodTypeField.value.trim())) {
      showError(bloodTypeField, 'Please select a valid blood type');
      isValid = false;
    }
  }
  
  // Validate Blood Pressure
  const bloodPressureField = document.querySelector('input[name="blood_pressure"]');
  if (bloodPressureField && bloodPressureField.value.trim()) {
    if (!/^\d+\/\d+\s*(mm\s*Hg)?|^n\/a$/i.test(bloodPressureField.value)) {
      showError(bloodPressureField, 'Invalid format (ex: 120/80 mm Hg or N/A)');
      isValid = false;
    }
  }
  
  // Validate Diseases (at least one must be selected)
  const diseasesSelect = document.getElementById('diseases-select');
  const diseasesError = document.getElementById('diseases-error');
  const diseasesSelected = diseasesSelect ? Array.from(diseasesSelect.selectedOptions) : [];
  const diseaseNoneSelected = diseasesSelected.some(option => option.value === 'None');
  
  if (diseasesSelected.length === 0) {
    if (diseasesError) {
      diseasesError.textContent = 'Please select at least one option (or None if not applicable)';
      diseasesError.style.display = 'block';
    } else {
      showError(diseasesSelect, 'Please select at least one option');
    }
    isValid = false;
  } else if (diseaseNoneSelected && diseasesSelected.length > 1) {
    // If "None" is selected along with other options
    if (diseasesError) {
      diseasesError.textContent = 'Please select either "None" OR specific conditions, not both';
      diseasesError.style.display = 'block';
    }
    isValid = false;
  }
  
  // Validate Allergies (at least one must be selected)
  const allergiesSelect = document.getElementById('allergies-select');
  const allergiesError = document.getElementById('allergies-error');
  const allergiesSelected = allergiesSelect ? Array.from(allergiesSelect.selectedOptions) : [];
  const allergyNoneSelected = allergiesSelected.some(option => option.value === 'None');
  
  if (allergiesSelected.length === 0) {
    if (allergiesError) {
      allergiesError.textContent = 'Please select at least one option (or None if not applicable)';
      allergiesError.style.display = 'block';
    } else {
      showError(allergiesSelect, 'Please select at least one option');
    }
    isValid = false;
  } else if (allergyNoneSelected && allergiesSelected.length > 1) {
    // If "None" is selected along with other options
    if (allergiesError) {
      allergiesError.textContent = 'Please select either "None" OR specific allergies, not both';
      allergiesError.style.display = 'block';
    }
    isValid = false;
  }
  
  // Validate Informed Consent
  const consentCheckbox = document.getElementById('consent-checkbox');
  if (consentCheckbox && !consentCheckbox.checked) {
    showError(consentCheckbox, 'You must agree to the informed consent to proceed');
    isValid = false;
  }
  
  console.log('Medical Details validation result:', isValid);
  return isValid;
}

function validateServices() {
  clearErrors();
  console.log('Validating Services Section');
  
  const servicesSelected = document.querySelectorAll('.service-checkbox:checked');
  const errorContainer = document.getElementById('services-error');
  const servicesContainer = document.querySelector('.services-container');
  
  if (servicesSelected.length === 0) {
    if (errorContainer) {
      errorContainer.textContent = 'Please select at least one dental service to proceed';
      errorContainer.style.display = 'block';
      
      // Add shake animation class to the services container
      if (servicesContainer) {
        servicesContainer.classList.add('error-highlight');
        servicesContainer.classList.add('shake');
        
        // Remove animation classes after animation completes
        setTimeout(() => {
          servicesContainer.classList.remove('error-highlight', 'shake');
        }, 1000);
      }
      
      // Smoothly scroll to the error message
      errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    return false;
  }
  
  // Clear error state if validation passes
  if (errorContainer) {
    errorContainer.style.display = 'none';
  }
  if (servicesContainer) {
    servicesContainer.classList.remove('error-highlight', 'shake');
  }
  
  return true;
}

function validateAppointment() {
  clearErrors();
  console.log('Validating Appointment Section');
  let isValid = true;
  
  // Validate clinic branch selection
  const clinicBranch = document.querySelector('select[name="clinic_branch"]');
  const clinicError = document.getElementById('clinic-error');
  
  if (!clinicBranch || !clinicBranch.value) {
    isValid = false;
    if (clinicError) {
      clinicError.textContent = 'Please select a clinic branch';
      clinicError.style.display = 'block';
    } else {
      showError(clinicBranch, 'Please select a clinic branch');
    }
  } else if (clinicError) {
    clinicError.style.display = 'none';
  }
  
  // Validate doctor selection - ALWAYS required
  const doctorSelect = document.getElementById('doctor');
  const doctorError = document.getElementById('doctor-error');
  
  if (!doctorSelect || !doctorSelect.value) {
      isValid = false;
      if (doctorError) {
        doctorError.textContent = 'Please select a doctor';
        doctorError.style.display = 'block';
      } else {
        showError(doctorSelect, 'Please select a doctor');
      }
    } else if (doctorError) {
      doctorError.style.display = 'none';
  }
  
  // Validate appointment date and time
  const appointmentDate = document.getElementById('appointment-date');
  const timeSlot = document.querySelector('.time-slot.selected');
  const dateError = document.getElementById('appointment-date-error');
  
  if (!appointmentDate || !appointmentDate.value) {
    isValid = false;
    if (dateError) {
      dateError.textContent = 'Please select a date for your appointment';
      dateError.style.display = 'block';
    } else {
      showError(appointmentDate, 'Please select a date');
    }
  } else if (!timeSlot) {
    isValid = false;
    if (dateError) {
      dateError.textContent = 'Please select a time slot for your appointment';
      dateError.style.display = 'block';
    } else {
      alert('Please select a time slot for your appointment');
    }
  } else if (timeSlot.classList.contains('booked') || timeSlot.disabled) {
    // Check if the selected time slot is already booked
    isValid = false;
    if (dateError) {
      dateError.textContent = 'This time slot is already booked. Please select another time.';
      dateError.style.display = 'block';
    } else {
      alert('This time slot is already booked. Please select another time.');
    }
  } else if (dateError) {
    dateError.style.display = 'none';
  }
  
  // If date is selected, ensure it's not in the past
  if (appointmentDate && appointmentDate.value) {
    const selectedDate = new Date(appointmentDate.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
      isValid = false;
      if (dateError) {
        dateError.textContent = 'Appointment date cannot be in the past';
        dateError.style.display = 'block';
      } else {
        showError(appointmentDate, 'Date cannot be in the past');
      }
    }
  }
  
  // Update hidden fields with selected values
  if (isValid) {
    updateAppointmentHiddenFields();
  }
  
  console.log('Appointment validation result:', isValid);
  return isValid;
}

function validatePayment() {
  // Payment section is informational only, no validation required
  return true;
}

// ================== UI INTERACTION FUNCTIONS ==================
/**
 * Initialize the service selection functionality
 */
function initServiceSelection() {
    console.log('Initializing service selection...');
    
    // Initialize selectedServices array if it doesn't exist
    if (!window.selectedServices) {
        window.selectedServices = [];
    }
    
    // Select all service cards with direct DOM query
    const serviceCards = document.querySelectorAll('.service-card');
    console.log(`Found ${serviceCards.length} service cards`);
    
    if (serviceCards.length === 0) {
        console.warn('No service cards found on the page');
        return;
    }
    
    // Create feedback element if it doesn't exist
    let feedbackEl = document.getElementById('selection-feedback');
    if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.id = 'selection-feedback';
        document.body.appendChild(feedbackEl);
    }
    
    // Clear any existing event listeners by removing and re-adding elements
    serviceCards.forEach(card => {
        const clone = card.cloneNode(true);
        card.parentNode.replaceChild(clone, card);
    });
    
    // Get fresh references to the cloned cards
    const refreshedCards = document.querySelectorAll('.service-card');
    
    // Add accessibility attributes and store original classes
    refreshedCards.forEach(card => {
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-pressed', card.classList.contains('selected') ? 'true' : 'false');
        
        // Get service information from data attributes (already in the HTML)
        const serviceName = card.getAttribute('data-service-name');
        
        // Find associated checkbox
        const checkbox = card.querySelector('input[type="checkbox"]');
        
        // Check if this service is already in the selectedServices array
        if (window.selectedServices.some(s => s.name === serviceName)) {
            card.classList.add('selected');
            card.setAttribute('aria-pressed', 'true');
            if (checkbox) checkbox.checked = true;
        }
        
        // Function to toggle selection state
        const toggleSelection = (event) => {
            // Prevent default behavior
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            // Get service information
            const serviceName = card.getAttribute('data-service-name');
            const servicePrice = parseInt(card.getAttribute('data-service-price'), 10) || 0;
            
            if (!serviceName) {
                console.error('Service card missing data-service-name attribute', card);
                return;
            }
            
            // Toggle selected class
            const wasSelected = card.classList.contains('selected');
            
            // Find associated checkbox
            const checkbox = card.querySelector('input[type="checkbox"]');
            
            if (wasSelected) {
                // Remove from selected
                card.classList.remove('selected', 'pulse-animation');
                card.setAttribute('aria-pressed', 'false');
                if (checkbox) checkbox.checked = false;
                
                // Remove from selectedServices array
                window.selectedServices = window.selectedServices.filter(s => s.name !== serviceName);
                
                // Show feedback
                showFeedback(`Removed: ${serviceName}`);
        } else {
                // Add to selected
                card.classList.add('selected');
                card.classList.add('pulse-animation');
                card.setAttribute('aria-pressed', 'true');
                if (checkbox) checkbox.checked = true;
                
                // Add to selectedServices array if not already present
                if (!window.selectedServices.some(s => s.name === serviceName)) {
                    window.selectedServices.push({
                        name: serviceName,
                        price: servicePrice
                    });
                }
                
                // Show feedback
                showFeedback(`Added: ${serviceName}`);
                
                // Remove pulse animation after it completes
                setTimeout(() => {
                    card.classList.remove('pulse-animation');
                }, 300);
            }
            
            // Update selected services UI if the function exists
            if (typeof updateSelectedServicesUI === 'function') {
                updateSelectedServicesUI();
            }
            
            // Calculate total price based on selected services
            if (typeof calculateTotal === 'function') {
                calculateTotal();
            }
            
            // Update payment summary if the function exists
            if (typeof updatePaymentSummary === 'function') {
                updatePaymentSummary();
            }
            
            console.log('Selected services:', window.selectedServices);
        };
        
        // Add click event listener
        card.addEventListener('click', toggleSelection);
        
        // Add touch event listeners
        card.addEventListener('touchstart', function(e) {
            // Add active class on touch start
            card.classList.add('active-touch');
        }, { passive: true });
        
        card.addEventListener('touchend', function(e) {
            // Remove active class
            card.classList.remove('active-touch');
            // Only toggle if this is a tap (not a scroll)
            if (!window.isScrolling) {
                toggleSelection(e);
            }
        });
        
        card.addEventListener('touchcancel', function() {
            card.classList.remove('active-touch');
        });
        
        // Add keyboard event listener
        card.addEventListener('keydown', function(e) {
            // Toggle on Enter or Space
            if (e.key === 'Enter' || e.key === ' ') {
                toggleSelection(e);
            }
        });
    });
    
    // Track scrolling to differentiate between taps and scrolls
    let isScrolling = false;
    document.addEventListener('scroll', function() {
        isScrolling = true;
        clearTimeout(window.scrollTimeout);
        window.scrollTimeout = setTimeout(function() {
            isScrolling = false;
            window.isScrolling = false;
        }, 100);
    }, { passive: true });
    window.isScrolling = false;
    
    // Function to show feedback toast
    function showFeedback(message) {
        const feedbackEl = document.getElementById('selection-feedback');
        if (!feedbackEl) return;
        
        feedbackEl.textContent = message;
        feedbackEl.style.display = 'block';
        
        // Trigger reflow for animation
        feedbackEl.offsetHeight;
        
        feedbackEl.style.opacity = '1';
        
        // Hide after 1.5 seconds
        clearTimeout(window.feedbackTimeout);
        window.feedbackTimeout = setTimeout(() => {
            feedbackEl.style.opacity = '0';
            setTimeout(() => {
                feedbackEl.style.display = 'none';
            }, 300);
        }, 1500);
    }
    
    console.log('Service selection initialization complete');
}

/**
 * Update the UI to show selected services
 */
function updateSelectedServicesUI() {
    const selectedServicesList = document.getElementById('selected-services-list');
    if (!selectedServicesList) return;
    
    // Clear the list
    selectedServicesList.innerHTML = '';
    
    // If no services selected, show message
    if (!window.selectedServices || window.selectedServices.length === 0) {
        const emptyMessage = document.createElement('div');
        emptyMessage.textContent = 'No services selected yet';
        emptyMessage.className = 'empty-selection';
        selectedServicesList.appendChild(emptyMessage);
        return;
    }
    
    // Add each selected service to the list
    window.selectedServices.forEach(service => {
        const listItem = document.createElement('div');
        listItem.className = 'selected-service-item';
        
        const nameSpan = document.createElement('span');
        nameSpan.className = 'service-name';
        nameSpan.textContent = service.name;
        
        const removeButton = document.createElement('button');
        removeButton.className = 'remove-service-btn';
        removeButton.innerHTML = '<i class="fas fa-times"></i>';
        removeButton.setAttribute('aria-label', `Remove ${service.name}`);
        
        removeButton.onclick = function() {
            // Find the card with this service name and deselect it
            const card = document.querySelector(`.service-card[data-service-name="${service.name}"]`);
            if (card) {
                // Trigger a click to deselect
                card.click();
            } else {
                // If card not found, just remove from array
                window.selectedServices = window.selectedServices.filter(s => s.name !== service.name);
                updateSelectedServicesUI();
                
                // Update totals and payment summary
                if (typeof calculateTotal === 'function') {
                    calculateTotal();
                }
                
                if (typeof updatePaymentSummary === 'function') {
                    updatePaymentSummary();
                }
            }
        };
        
        listItem.appendChild(nameSpan);
        listItem.appendChild(removeButton);
        
        selectedServicesList.appendChild(listItem);
    });
}

function initAppointmentSelections() {
  // Handle clinic branch selection
  const clinicSelect = document.getElementById('clinic');
  if (clinicSelect) {
    clinicSelect.addEventListener('change', function() {
      // Show doctor selection based on branch
      updateDoctorOptions(this.value);
      
      // Show calendar
      const calendarContainer = document.querySelector('.calendar-container');
      if (calendarContainer) {
        calendarContainer.style.display = 'block';
      }
    });
  }
  
  // Initialize doctor container visibility
  const doctorContainer = document.getElementById('doctor-container');
  if (doctorContainer) {
    doctorContainer.style.display = 'none';
  }
}

function updateDoctorOptions(branch) {
  const doctorContainer = document.getElementById('doctor-container');
  const doctorSelect = document.getElementById('doctor');
  
  if (!doctorContainer || !doctorSelect || !window.doctorsJson) return;
  
  // Clear current options
  doctorSelect.innerHTML = '<option value="">Select a Doctor</option>';
  
  // If no branch selected, hide doctor selection
  if (!branch) {
    doctorContainer.style.display = 'none';
    return;
  }
  
  // Get doctors for this branch
  const doctors = window.doctorsJson[branch] || [];
  
  // Add doctor options
  doctors.forEach(doctor => {
    const option = document.createElement('option');
    option.value = doctor.id;
    option.textContent = `Dr. ${doctor.first_name} ${doctor.last_name} (${doctor.specialization})`;
    doctorSelect.appendChild(option);
  });
  
  // Always show doctor selection
  doctorContainer.style.display = 'block';
}

function calculateTotal() {
  const selectedServices = document.querySelectorAll('.service-checkbox:checked');
  let total = 0;
  
  selectedServices.forEach(checkbox => {
    const card = checkbox.closest('.service-card');
    const servicePrice = parseInt(card.getAttribute('data-service-price'), 10) || 0;
    total += servicePrice;
  });
  
  // Update total price display if it exists
  const totalDisplay = document.getElementById('total-price');
  if (totalDisplay) {
    totalDisplay.textContent = `₱${total.toLocaleString()}`;
  }
  
  // Update hidden total field if it exists
  const totalInput = document.querySelector('input[name="total_price"]');
  if (totalInput) {
    totalInput.value = total;
  }
  
  return total;
}

function updatePaymentSummary() {
  console.log('Updating payment summary...');
  const selectedServices = document.querySelectorAll('.service-checkbox:checked');
  
  // Find the payment services list container
  const servicesList = document.getElementById('payment-services-list');
  if (!servicesList) {
    console.warn('Could not find payment-services-list element');
    return;
  }
  
  let servicesHTML = '';
  let total = 0;
  
  // Build HTML for selected services
  selectedServices.forEach(checkbox => {
    const card = checkbox.closest('.service-card');
    if (!card) return;
    
    const serviceName = card.getAttribute('data-service-name');
    const servicePrice = parseInt(card.getAttribute('data-service-price'), 10) || 0;
    
    total += servicePrice;
    servicesHTML += `
      <div class="service-item">
        <div>${serviceName}</div>
        <div>₱${servicePrice.toLocaleString()}</div>
      </div>
    `;
  });
  
  // Add total row
  if (selectedServices.length > 0) {
    servicesHTML += `
      <div class="total-row">
        <div>TOTAL:</div>
        <div>₱${total.toLocaleString()}</div>
      </div>
    `;
  } else {
    servicesHTML = `
      <div class="service-item">
        <div>No services selected</div>
        <div>₱0</div>
      </div>
      <div class="total-row">
        <div>TOTAL:</div>
        <div>₱0</div>
      </div>
    `;
  }
  
  // Update the services list
  servicesList.innerHTML = servicesHTML;
  
  // Update hidden total input if it exists
  const totalInput = document.querySelector('input[name="total_price"]');
  if (totalInput) {
    totalInput.value = total;
  }
}

function updateAppointmentHiddenFields() {
  // Get selected values
  const branch = document.getElementById('clinic')?.value || '';
  const date = document.getElementById('appointment-date')?.value || '';
  const timeSlot = document.querySelector('.time-slot.selected');
  const time = timeSlot ? timeSlot.textContent.trim() : '';
  const doctorId = document.getElementById('doctor')?.value || '';
  
  // Get doctor name for display purposes
  const doctorSelect = document.getElementById('doctor');
  const doctorName = doctorSelect && doctorSelect.selectedIndex > 0 ? 
                     doctorSelect.options[doctorSelect.selectedIndex].text : 
                     'No doctor selected';
  
  // Update hidden fields
  const hiddenFields = {
    'clinic_branch': branch,
    'appointment_date': date,
    'appointment_time': time,
    'doctor_id': doctorId,
    'selected_doctor_name': doctorName
  };
  
  Object.entries(hiddenFields).forEach(([name, value]) => {
    const field = document.querySelector(`input[name="${name}"]`);
    if (field) {
      field.value = value;
    } else {
      // Create field if it doesn't exist
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      input.value = value;
      document.getElementById('appointmentForm').appendChild(input);
    }
  });
  
  // Format for datetime display
  const datetime = date && time ? `${date} ${time}` : '';
  const datetimeField = document.querySelector('input[name="appointment_datetime"]');
  if (datetimeField) {
    datetimeField.value = datetime;
  }
  
  // Update selected schedule display
  const scheduleDisplay = document.getElementById('selected-schedule');
  if (scheduleDisplay) {
    const doctorName = document.getElementById('doctor')?.selectedOptions?.[0]?.text || 'No doctor selected';
    const formattedDate = date ? new Date(date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'No date selected';
    
    scheduleDisplay.innerHTML = `
      <strong>Your Selected Appointment:</strong><br>
      Date: ${formattedDate}<br>
      Time: ${time}<br>
      Branch: ${branch}<br>
      Doctor: ${doctorName}
    `;
  }
}

function prepareSummaryView() {
  // This function populates the summary view with all collected data
  console.log('Preparing summary view...');
  
  // Personal information fields
  const summaryNameField = document.getElementById('summary-name');
  const summaryDobField = document.getElementById('summary-dob');
  
  // Check if name and DOB fields already have content from PHP
  const nameHasContent = summaryNameField && 
                        summaryNameField.textContent && 
                        summaryNameField.textContent.trim() !== '' &&
                        summaryNameField.textContent !== 'Name not provided' && 
                        !summaryNameField.textContent.includes('undefined');
                        
  const dobHasContent = summaryDobField && 
                       summaryDobField.textContent && 
                       summaryDobField.textContent.trim() !== '' &&
                       summaryDobField.textContent !== 'Date of birth not provided' && 
                       !summaryDobField.textContent.includes('undefined');
  
  console.log('Existing content check:', { 
    nameField: summaryNameField?.textContent,
    dobField: summaryDobField?.textContent,
    nameHasContent,
    dobHasContent
  });
  
  // Only try to get name from form if PHP didn't already provide it
  let fullName = '';
  if (!nameHasContent) {
    // Get first and last name from form inputs if they exist
    const firstNameInput = document.querySelector('input[name="first_name"]');
    const lastNameInput = document.querySelector('input[name="last_name"]');
    const firstName = firstNameInput?.value || '';
    const lastName = lastNameInput?.value || '';
    
    fullName = (firstName || lastName) ? `${firstName} ${lastName}`.trim() : '';
    
    // If still no name found, try other options
    if (!fullName) {
      const fullNameInput = document.querySelector('input[name="full_name"]');
      if (fullNameInput?.value) {
        fullName = fullNameInput.value;
      }
    }
    
    // Update name field only if we found a name and PHP didn't already set it
    if (fullName && summaryNameField) {
      summaryNameField.textContent = fullName;
    }
  }
  
  // Only try to get DOB if PHP didn't already provide it
  if (!dobHasContent && summaryDobField) {
    const dob = document.querySelector('input[name="dob"]')?.value || 
               document.querySelector('input[name="date_of_birth"]')?.value || '';
    
    if (dob) {
      try {
        // Try to format the date
        const date = new Date(dob);
        const formattedDate = date.toLocaleDateString('en-US', { 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric' 
        });
        summaryDobField.textContent = formattedDate;
      } catch (e) {
        console.error('Error formatting date:', e);
        summaryDobField.textContent = dob;
      }
    }
  }
  
  // Other personal fields that aren't already provided by PHP
  const otherPersonalFields = {
    'summary-email': document.querySelector('input[name="email"]')?.value || '',
    'summary-contact': document.querySelector('input[name="contact_number"]')?.value || '',
    'summary-address': [
      document.querySelector('input[name="barangay"]')?.value || '',
      document.querySelector('input[name="city"]')?.value || '',
      document.querySelector('select[name="province"]')?.value || '',
      document.querySelector('select[name="region"]')?.value || '',
      document.querySelector('input[name="zip_code"]')?.value || ''
    ].filter(value => value !== '').join(', ')
  };
  
  // Update other personal information in summary
  Object.entries(otherPersonalFields).forEach(([id, value]) => {
    const element = document.getElementById(id);
    if (element && (!element.textContent || element.textContent.trim() === '')) {
      element.textContent = value || 'Not provided';
    }
  });
  
  // Health status
  const healthStatus = document.querySelector('input[name="health"]:checked')?.value || '';
  console.log('Health status:', healthStatus);
  
  // Try to locate the health status element
  const healthStatusLabel = document.querySelector('.summary-field:first-child .summary-label');
  if (healthStatusLabel && healthStatusLabel.textContent.includes('Health Status')) {
    const healthStatusElement = healthStatusLabel.nextElementSibling;
    if (healthStatusElement) {
      console.log('Found health status element:', healthStatusElement);
      healthStatusElement.textContent = healthStatus === 'yes' ? 'Good Health' : 
                                        healthStatus === 'no' ? 'Not in Good Health' : 'Not specified';
    } else {
      console.log('Could not find health status element next to label');
    }
  } else {
    console.log('Could not find health status label element');
  }
  
  // Medical information
  const bloodType = document.querySelector('select[name="blood_type"]')?.value || '';
  const bloodPressure = document.querySelector('input[name="blood_pressure"]')?.value || '';
  console.log('Blood pressure value:', bloodPressure);
  
  // Update blood pressure field
  const bloodPressureElement = document.getElementById('summary-blood-pressure');
  if (bloodPressureElement) {
    console.log('Found blood pressure element:', bloodPressureElement);
    if (bloodPressure) {
      bloodPressureElement.textContent = bloodPressure;
    } else if (bloodPressureElement.textContent === '') {
      bloodPressureElement.textContent = 'Not specified';
    }
  } else {
    console.log('Could not find blood pressure element with ID "summary-blood-pressure"');
    
    // Try alternative selector
    const bpLabels = document.querySelectorAll('.summary-label');
    for (const label of bpLabels) {
      if (label.textContent.includes('Blood Pressure')) {
        const bpElement = label.nextElementSibling;
        if (bpElement) {
          console.log('Found blood pressure element by label:', bpElement);
          if (bloodPressure) {
            bpElement.textContent = bloodPressure;
          } else if (bpElement.textContent === '') {
            bpElement.textContent = 'Not specified';
          }
        }
        break;
      }
    }
  }
  
  // Get allergies
  const allergiesSelect = document.getElementById('allergies-select');
  let allergiesText = 'None';
  if (allergiesSelect) {
    const selectedAllergies = Array.from(allergiesSelect.selectedOptions).map(option => option.value);
    allergiesText = selectedAllergies.length > 0 ? selectedAllergies.join(', ') : 'None';
  }
  
  // Get diseases/conditions
  const diseasesSelect = document.getElementById('diseases-select');
  let diseasesText = 'None';
  if (diseasesSelect) {
    const selectedDiseases = Array.from(diseasesSelect.selectedOptions).map(option => option.value);
    diseasesText = selectedDiseases.length > 0 ? selectedDiseases.join(', ') : 'None';
  }
  
  const medicalFields = {
    'summary-blood-type': bloodType,
    'summary-allergies': allergiesText,
    'summary-diseases': diseasesText
  };
  
  // Log values for debugging
  console.log('Medical fields:', medicalFields);
  
  // Update medical information in summary
  Object.entries(medicalFields).forEach(([id, value]) => {
    const element = document.getElementById(id);
    if (element) {
      element.textContent = value || 'Not specified';
    }
  });
  
  // Find the blood pressure field and update it
  const bloodPressureBox = document.querySelector('.summary-row:nth-child(2) .summary-box');
    if (bloodPressureBox) {
      bloodPressureBox.textContent = bloodPressure || 'Not specified';
  }
  
  // Medical history questions
  const medicalHistoryQuestions = [
    { field: 'treatment', details: 'treatment_details', label: 'Under treatment for any condition:' },
    { field: 'operation', details: 'illness_details', label: 'Had serious illness or operation:' },
    { field: 'hospitalized', details: 'hospitalized_details', label: 'Been hospitalized:' },
    { field: 'medication', details: 'prescription_med_details', label: 'Taking prescription medication:' },
    { field: 'tobacco', details: 'tobacco_details', label: 'Use tobacco products:' },
    { field: 'drugs', details: 'drugs_details', label: 'Use alcohol or other drugs:' }
  ];
  
  // Create or update rows in the medical history table
  const historyTable = document.querySelector('.summary-history-table');
  
  // Only use JavaScript to populate the medical history table if there are no existing rows
  // (PHP hasn't already populated it)
  if (historyTable && historyTable.rows.length === 0) {
    console.log('Medical history table is empty, populating with JavaScript');
    
    // Create rows for each medical history question
    medicalHistoryQuestions.forEach(question => {
      const answer = document.querySelector(`input[name="${question.field}"]:checked`)?.value || 'Not specified';
      const details = document.querySelector(`textarea[name="${question.details}"]`)?.value || '';
      
      const row = document.createElement('tr');
      
      const labelCell = document.createElement('td');
      labelCell.textContent = question.label;
      row.appendChild(labelCell);
      
      const answerCell = document.createElement('td');
      answerCell.textContent = answer.charAt(0).toUpperCase() + answer.slice(1);
      row.appendChild(answerCell);
      
      if (answer === 'yes' && details) {
        const detailsCell = document.createElement('td');
        detailsCell.textContent = details;
        row.appendChild(detailsCell);
      }
      
      historyTable.appendChild(row);
    });
  } else if (historyTable) {
    console.log('Medical history table already has rows, not modifying');
  } else {
    console.log('Could not find medical history table');
  }
  
  // Update services list
  const servicesList = document.querySelector('.summary-services-list');
  if (servicesList) {
    console.log('Found services list element');
    
    // Get all checked service checkboxes
    const selectedServices = document.querySelectorAll('.service-checkbox:checked');
    console.log(`Found ${selectedServices.length} selected services`);
    
    // Check if services are selected
    if (selectedServices.length === 0) {
      // If no services selected, show message (don't overwrite if already set by PHP)
      if (!servicesList.querySelector('.summary-service-item')) {
        console.log('No selected services found, setting empty message');
        servicesList.innerHTML = '<div class="summary-service-item"><div>No services selected</div></div>';
      }
    } else {
      // Only update if not already populated by PHP
      if (servicesList.querySelectorAll('.summary-service-item').length === 0 || 
          servicesList.textContent.includes('No services selected')) {
        console.log('Updating services list with selected services');
        let servicesHTML = '';
        
        selectedServices.forEach(checkbox => {
          const card = checkbox.closest('.service-card');
          if (card) {
            const serviceName = card.getAttribute('data-service-name');
            console.log('Adding service to summary:', serviceName);
            servicesHTML += `
              <div class="summary-service-item">
                <div class="service-name">${serviceName}</div>
              </div>
            `;
          }
        });
        
        if (servicesHTML) {
          servicesList.innerHTML = servicesHTML;
        } else {
          servicesList.innerHTML = '<div class="summary-service-item"><div>No services selected</div></div>';
        }
      } else {
        console.log('Services list already populated, not updating');
      }
    }
  } else {
    console.log('Could not find services list element');
  }
  
  // Update appointment details
  console.log('Updating appointment details...');
  
  // Get appointment values
  const clinicBranch = document.querySelector('select[name="clinic_branch"]')?.value || '';
  const appointmentDate = document.getElementById('appointment-date')?.value || '';
  const appointmentTime = document.querySelector('.time-slot.selected')?.textContent?.trim() || 
                         document.querySelector('input[name="appointment_time"]')?.value || '';
  
  console.log('Appointment values:', { clinicBranch, appointmentDate, appointmentTime });
  
  // Get doctor information
  const doctorSelect = document.getElementById('doctor');
  let doctorName = 'No doctor selected';
  
  if (doctorSelect && doctorSelect.selectedIndex > 0) {
    doctorName = doctorSelect.options[doctorSelect.selectedIndex].text;
    console.log('Selected doctor:', doctorName);
  }
  
  // Format the date
  let formattedDate = 'Date not selected';
  if (appointmentDate) {
    try {
      const date = new Date(appointmentDate);
      formattedDate = date.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
      console.log('Formatted date:', formattedDate);
    } catch (e) {
      console.error('Error formatting date:', e);
    }
  }
  
  // Find the appointment details section
  const appointmentSection = document.querySelector('.summary-section:nth-of-type(3)');
  if (appointmentSection) {
    console.log('Found appointment section');
    
    // Find or create elements
    let dateElement = appointmentSection.querySelector('p:nth-of-type(1)');
    let branchElement = appointmentSection.querySelector('p:nth-of-type(2)');
    let doctorElement = appointmentSection.querySelector('p:nth-of-type(3)');
    
    // Update or create date element
    if (dateElement) {
      dateElement.textContent = `${formattedDate} at ${appointmentTime || 'Time not selected'}`;
    } else {
      console.log('Date element not found, looking for alternative');
      
      // Try to find elements with specific pattern
      const paragraphs = appointmentSection.querySelectorAll('p');
      for (const p of paragraphs) {
        if (p.textContent.includes('Date') || p.textContent.includes('at')) {
          p.textContent = `${formattedDate} at ${appointmentTime || 'Time not selected'}`;
          console.log('Updated date element with content:', p.textContent);
          break;
        }
      }
    }
    
    // Update or create branch element
    if (branchElement) {
      branchElement.textContent = clinicBranch || 'Branch not selected';
    } else {
      console.log('Branch element not found, looking for alternative');
      
      // Try to find elements with branch information
      const paragraphs = appointmentSection.querySelectorAll('p');
      for (const p of paragraphs) {
        if (p.textContent.includes('Branch') || p.textContent.includes('Clinic')) {
          p.textContent = clinicBranch || 'Branch not selected';
          console.log('Updated branch element with content:', p.textContent);
          break;
        }
      }
    }
    
    // Update or create doctor element
    if (doctorElement) {
      doctorElement.textContent = doctorName;
    } else {
      console.log('Doctor element not found, looking for alternative');
      
      // Try to find elements with doctor information
      const paragraphs = appointmentSection.querySelectorAll('p');
      for (const p of paragraphs) {
        if (p.textContent.includes('Doctor')) {
          p.textContent = doctorName;
          console.log('Updated doctor element with content:', p.textContent);
          break;
        }
      }
    }
  } else {
    console.log('Could not find appointment section');
  }
  
  // Final notes
  const notesField = document.querySelector('textarea[name="additional_notes"]');
  const summaryNotes = document.getElementById('summary-notes');
  if (notesField && summaryNotes) {
    summaryNotes.textContent = notesField.value || 'No additional notes';
  }
  
  console.log('Summary view preparation complete');
}

// Fallback click handlers for service cards
function addServiceCardFallbackHandlers() {
  console.log('Adding fallback handlers for service cards');
  
  // Add a global click handler as a fallback
  document.body.addEventListener('click', function(event) {
    // Check if a service card or any of its children was clicked
    const card = event.target.closest('.service-card');
    if (!card) return;
    
    console.log('Service card clicked via fallback handler:', card.getAttribute('data-service-name'));
    
    // Get the checkbox
    const checkbox = card.querySelector('.service-checkbox');
    if (checkbox) {
      // Toggle checkbox state
      checkbox.checked = !checkbox.checked;
      
      // Toggle selected class
      card.classList.toggle('selected', checkbox.checked);
      
      // Force recalculation of selected services and total
      try {
        // Try to update the selected services list
        const servicesList = document.getElementById('selected-services-list');
        if (servicesList) {
          updateServicesList();
        }
        
        // Try to calculate the total
        calculateTotal();
      } catch (e) {
        console.error('Error updating services:', e);
      }
    }
  });
  
  // Helper function to update services list
  function updateServicesList() {
    const servicesList = document.getElementById('selected-services-list');
    if (!servicesList) return;
    
    // Clear the list
    servicesList.innerHTML = '';
    
    // Get all checked checkboxes
    const checkedBoxes = document.querySelectorAll('.service-checkbox:checked');
    
    // If no services selected, show empty message
    if (checkedBoxes.length === 0) {
      const emptyMessage = document.createElement('div');
      emptyMessage.className = 'empty-selection';
      emptyMessage.textContent = 'No services selected yet';
      servicesList.appendChild(emptyMessage);
      return;
    }
    
    // Add each selected service
    checkedBoxes.forEach(box => {
      const card = box.closest('.service-card');
      if (!card) return;
      
      const name = card.getAttribute('data-service-name');
      
      const item = document.createElement('div');
      item.className = 'selected-service-item';
      item.innerHTML = `
        <div class="selected-service-name">${name}</div>
      `;
      
      servicesList.appendChild(item);
    });
  }
}

/**
 * Opens the dental consent modal
 */
function openConsentModal() {
  const modal = document.getElementById('consentModal');
  if (!modal) {
    console.error('Consent modal not found');
    return;
  }
  
  modal.style.display = 'block';
  
  // Add close functionality if not already added
  const closeBtn = modal.querySelector('.close');
  if (closeBtn) {
    // Remove existing event listener to prevent duplicates
    closeBtn.removeEventListener('click', closeConsentModal);
    // Add new event listener
    closeBtn.addEventListener('click', closeConsentModal);
  }
  
  // Close when clicking outside the modal content
  if (!modal.hasOutsideClickHandler) {
    modal.addEventListener('click', function(event) {
      if (event.target === modal) {
        closeConsentModal();
      }
    });
    modal.hasOutsideClickHandler = true;
  }
}

/**
 * Closes the dental consent modal
 */
function closeConsentModal() {
  const modal = document.getElementById('consentModal');
  if (modal) {
    modal.style.display = 'none';
  }
}