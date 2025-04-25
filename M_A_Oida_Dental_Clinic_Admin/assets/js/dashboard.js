document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation links
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    
    // Add click event listener to each link
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the target section
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                // Scroll smoothly to the section
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update active state of navigation links
                navLinks.forEach(link => link.classList.remove('bg-blue-100', 'text-blue-900'));
                this.classList.add('bg-blue-100', 'text-blue-900');
            }
        });
    });

    // Character counter for reason textarea
    const reasonText = document.getElementById('reasonText');
    const charCount = document.getElementById('charCount');
    
    reasonText.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
    });
});

// Modal functionality
let currentAction = '';
let currentPatient = '';
let currentDate = '';
let currentTime = '';
let currentReason = '';
let currentBookingRef = '';
let currentService = '';

function showConfirmModal(action, patient, date, time, bookingRef, service) {
    currentAction = action;
    currentPatient = patient;
    currentDate = date;
    currentTime = time;
    currentBookingRef = bookingRef;
    currentService = service;

    const modal = document.getElementById('confirmModal');
    const modalTitle = document.getElementById('modalTitle');
    const actionText = document.getElementById('actionText');
    const patientName = document.getElementById('patientName');
    const appointmentDate = document.getElementById('appointmentDate');
    const appointmentTime = document.getElementById('appointmentTime');
    const submitButton = document.getElementById('submitButton');

    // Set text and styles based on action
    if (action === 'approve') {
        modalTitle.textContent = 'Confirm Approval of Appointment';
        actionText.textContent = 'approve';
        actionText.className = 'font-semibold text-green-600';
        submitButton.className = 'px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors';
    } else {
        modalTitle.textContent = 'Confirm Decline of Appointment';
        actionText.textContent = 'decline';
        actionText.className = 'font-semibold text-red-600';
        submitButton.className = 'px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors';
    }
    
    // Set appointment details
    patientName.textContent = patient;
    appointmentDate.textContent = date;
    appointmentTime.textContent = time;

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function showReasonModal() {
    const modal = document.getElementById('reasonModal');
    const reasonText = document.getElementById('reasonText');
    const charCount = document.getElementById('charCount');
    
    // Reset textarea and counter
    reasonText.value = '';
    charCount.textContent = '0';
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideReasonModal() {
    const modal = document.getElementById('reasonModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function handleConfirm() {
    hideConfirmModal();
    
    // Only show reason modal for decline action
    if (currentAction === 'decline') {
        setTimeout(() => {
            showReasonModal();
        }, 300);
    } else {
        // For approve action, directly move to upcoming and show success
        moveToUpcoming();
        setTimeout(() => {
            showSuccessModal();
        }, 300);
    }
}

function handleReasonSubmit() {
    const reasonText = document.getElementById('reasonText');
    currentReason = reasonText.value.trim();
    
    if (!currentReason) {
        alert('Please provide a reason for declining the appointment.');
        return;
    }
    
    hideReasonModal();
    moveToCanceled();
    
    // Show success modal after reason submission
    setTimeout(() => {
        showSuccessModal();
    }, 300);
}

function showSection(sectionName) {
    // Hide all sections
    const sections = document.querySelectorAll('.appointment-section');
    sections.forEach(section => section.classList.add('hidden'));
    
    // Show selected section
    const selectedSection = document.getElementById(`${sectionName}-section`);
    if (selectedSection) {
        selectedSection.classList.remove('hidden');
    }
    
    // Update button styles
    const buttons = document.querySelectorAll('.status-btn');
    buttons.forEach(button => {
        button.classList.remove('active');
        button.style.opacity = '0.7';
    });
    
    const activeButton = document.querySelector(`.${sectionName.charAt(0).toUpperCase() + sectionName.slice(1)}`);
    if (activeButton) {
        activeButton.classList.add('active');
        activeButton.style.opacity = '1';
    }
}

function moveToUpcoming() {
    const newRow = createAppointmentRow({
        bookingRef: currentBookingRef,
        patientName: currentPatient,
        service: currentService,
        date: currentDate,
        time: currentTime,
        status: 'upcoming'
    });

    // Find the Upcoming section's table
    const upcomingSection = document.querySelector('#upcoming-section tbody');
    if (upcomingSection) {
        upcomingSection.appendChild(newRow);
    }

    // Remove from Pending section
    removeFromCurrentSection(currentBookingRef);
}

function moveToCanceled() {
    const newRow = createAppointmentRow({
        bookingRef: currentBookingRef,
        patientName: currentPatient,
        service: currentService,
        date: currentDate,
        time: currentTime,
        status: 'canceled'
    });

    // Find the Canceled section's table
    const canceledSection = document.querySelector('#canceled-section tbody');
    if (canceledSection) {
        canceledSection.appendChild(newRow);
    }

    // Remove from Pending section
    removeFromCurrentSection(currentBookingRef);
}

function removeFromCurrentSection(bookingRef) {
    // Find and remove the current row
    const currentRow = document.querySelector(`tr[data-booking-ref="${bookingRef}"]`);
    if (currentRow) {
        currentRow.remove();
    }
}

function createAppointmentRow(data) {
    const tr = document.createElement('tr');
    tr.className = 'border-t border-gray-400';
    tr.setAttribute('data-booking-ref', data.bookingRef);

    const statusClasses = {
        upcoming: 'bg-green-700',
        rescheduled: 'bg-blue-800',
        canceled: 'bg-red-700'
    };

    tr.innerHTML = `
        <td class="border-r border-gray-400 font-semibold px-2 py-1 whitespace-nowrap">
            ${data.bookingRef}
        </td>
        <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
            ${data.patientName}
        </td>
        <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
            ${data.service}
        </td>
        <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
            ${data.date}
        </td>
        <td class="border-r border-gray-400 px-2 py-1 whitespace-nowrap">
            ${data.time}
        </td>
        <td class="px-2 py-1 whitespace-nowrap">
            <span class="px-3 py-1 text-white text-xs font-semibold rounded ${statusClasses[data.status]}">
                ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
            </span>
        </td>
    `;

    return tr;
}

function showSuccessModal() {
    const modal = document.getElementById('successModal');
    const successIcon = document.getElementById('successIcon');
    const successTitle = document.getElementById('successTitle');
    const successAction = document.getElementById('successAction');
    const successButton = document.getElementById('successButton');
    const patientName = document.getElementById('successPatientName');
    const appointmentDate = document.getElementById('successDate');
    const appointmentTime = document.getElementById('successTime');

    // Set styles based on action
    if (currentAction === 'approve') {
        successIcon.className = 'fas fa-check-circle text-5xl text-green-500';
        successTitle.textContent = 'Approval Successful!';
        successAction.textContent = 'approved';
        successButton.className = 'px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors';
    } else {
        successIcon.className = 'fas fa-times-circle text-5xl text-red-500';
        successTitle.textContent = 'Appointment Declined';
        successAction.textContent = 'declined';
        successButton.className = 'px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors';
    }

    // Set appointment details
    patientName.textContent = currentPatient;
    appointmentDate.textContent = currentDate;
    appointmentTime.textContent = currentTime;

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Here you can send the data to your backend
    console.log({
        action: currentAction,
        patient: currentPatient,
        date: currentDate,
        time: currentTime,
        reason: currentReason
    });
}

function hideSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modals when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    [
        { id: 'confirmModal', hide: hideConfirmModal },
        { id: 'reasonModal', hide: hideReasonModal },
        { id: 'successModal', hide: hideSuccessModal }
    ].forEach(modal => {
        document.getElementById(modal.id).addEventListener('click', function(e) {
            if (e.target === this) {
                modal.hide();
            }
        });
    });
});

// Canceled Appointments Functions
function viewCancelDetails(bookingId) {
    // Show the modal
    const modal = document.getElementById('cancelDetailsModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // In a real application, you would fetch the details from the server
    // For now, we'll use sample data
    const sampleData = {
        bookingRef: bookingId,
        canceledBy: 'Patient',
        reason: 'Personal emergency',
        notes: 'Patient called to inform about a family emergency and requested to cancel the appointment.',
        cancelDate: 'April 21, 2025'
    };

    // Update modal content
    document.getElementById('modalBookingRef').textContent = sampleData.bookingRef;
    document.getElementById('modalCanceledBy').textContent = sampleData.canceledBy;
    document.getElementById('modalCancelReason').textContent = sampleData.reason;
    document.getElementById('modalNotes').textContent = sampleData.notes;
}

function closeCancelDetails() {
    const modal = document.getElementById('cancelDetailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function rescheduleAppointment(bookingId) {
    // This function would typically open a rescheduling form or modal
    // For now, we'll just show an alert
    const confirmReschedule = confirm('Would you like to reschedule this appointment?');
    if (confirmReschedule) {
        // Here you would typically open a scheduling interface
        alert('Opening scheduling interface for booking: ' + bookingId);
    }
}

// Filter functions for canceled appointments
function filterCanceledAppointments() {
    const filterType = document.getElementById('cancelFilter').value;
    const filterDate = document.getElementById('cancelDateFilter').value;
    
    // In a real application, you would fetch filtered data from the server
    console.log('Filtering by:', filterType, 'and date:', filterDate);
    
    // For now, we'll just log the filter criteria
    if (filterType !== 'all') {
        console.log('Filtering by cancellation type:', filterType);
    }
    if (filterDate) {
        console.log('Filtering by date:', filterDate);
    }
}

// Event listeners for filters
document.addEventListener('DOMContentLoaded', function() {
    const cancelFilter = document.getElementById('cancelFilter');
    const cancelDateFilter = document.getElementById('cancelDateFilter');
    
    if (cancelFilter) {
        cancelFilter.addEventListener('change', filterCanceledAppointments);
    }
    
    if (cancelDateFilter) {
        cancelDateFilter.addEventListener('change', filterCanceledAppointments);
    }
});

// Access Request Functions
let currentAccessAction = '';
let currentAccessName = '';
let currentAccessRole = '';

function showAccessConfirmModal(action, name, role) {
    currentAccessAction = action;
    currentAccessName = name;
    currentAccessRole = role;

    const modal = document.getElementById('accessConfirmModal');
    const modalTitle = document.getElementById('accessModalTitle');
    const actionText = document.getElementById('accessActionText');
    const requestName = document.getElementById('accessRequestName');
    const requestRole = document.getElementById('accessRequestRole');
    const submitButton = document.getElementById('accessSubmitButton');

    // Set text and styles based on action
    if (action === 'approve') {
        modalTitle.textContent = 'Confirm Access Approval';
        actionText.textContent = 'approve';
        actionText.className = 'font-semibold text-green-600';
        submitButton.className = 'px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors';
    } else {
        modalTitle.textContent = 'Confirm Access Rejection';
        actionText.textContent = 'reject';
        actionText.className = 'font-semibold text-red-600';
        submitButton.className = 'px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors';
    }

    requestName.textContent = name;
    requestRole.textContent = role;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideAccessConfirmModal() {
    const modal = document.getElementById('accessConfirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function handleAccessConfirm() {
    // Here you would typically send the data to your backend
    console.log({
        action: currentAccessAction,
        name: currentAccessName,
        role: currentAccessRole
    });

    // Remove the row from the table
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.querySelector('td').textContent.trim() === currentAccessName) {
            row.remove();
        }
    });

    hideAccessConfirmModal();

    // Show success message (you can implement a toast notification here)
    alert(`Access request ${currentAccessAction}d successfully`);
}

function showAccessDetails(name) {
    const modal = document.getElementById('accessDetailsModal');
    document.getElementById('detailsName').textContent = name;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideAccessDetails() {
    const modal = document.getElementById('accessDetailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modals when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    [
        { id: 'accessConfirmModal', hide: hideAccessConfirmModal },
        { id: 'accessDetailsModal', hide: hideAccessDetails }
    ].forEach(modal => {
        const element = document.getElementById(modal.id);
        if (element) {
            element.addEventListener('click', function(e) {
                if (e.target === this) {
                    modal.hide();
                }
            });
        }
    });
});