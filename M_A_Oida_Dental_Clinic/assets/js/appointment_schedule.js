// appointment_schedule.js
document.addEventListener('DOMContentLoaded', () => {
    console.log('Appointment schedule script loaded');
    initializeCalendar();
});

function initializeCalendar() {
    const calendarContainer = document.getElementById('calendar');
    const dateInput = document.getElementById('appointment-date');
    const appointmentDatetimeInput = document.getElementById('appointment-datetime');
    const clinicSelect = document.getElementById('clinic');
    const monthYearElement = document.querySelector('.month-year');
    const doctorSelect = document.getElementById('doctor');
    
    if (!calendarContainer) {
        console.log('Calendar container not found. The element might not be loaded yet.');
        // Retry initialization after a short delay
        setTimeout(initializeCalendar, 500);
        return;
    }
    
    console.log('Calendar container found, initializing calendar...');
    let currentDate = new Date();
    
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Make generateCalendar globally available
    window.generateCalendar = generateCalendar;

    // Main calendar generation function
    function generateCalendar(month, year) {
        console.log(`Generating calendar for ${month}/${year}`);
        if (!calendarContainer) {
            console.error('Calendar container not found!');
            return;
        }
        
        calendarContainer.innerHTML = '';
        
        // Create weekday headers
        weekdays.forEach(day => {
            const header = document.createElement('div');
            header.className = 'calendar-header';
            header.textContent = day;
            calendarContainer.appendChild(header);
        });

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        
        // Add empty days for previous month
        for (let i = 0; i < firstDay.getDay(); i++) {
            calendarContainer.appendChild(createEmptyDay());
        }

        // Create days for current month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = createCalendarDay(year, month, day);
            calendarContainer.appendChild(dayElement);
        }

        updateMonthYearDisplay(month, year);
    }

    function createCalendarDay(year, month, day) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        dayElement.textContent = day;

        const dateObj = new Date(year, month, day);
        const today = new Date();
        
        dateObj.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        
        // Check if date is in the past or is a weekend (Saturday = 6, Sunday = 0)
        const dayOfWeek = dateObj.getDay();
        const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
        
        if (dateObj < today || isWeekend) {
            dayElement.classList.add('disabled');
            if (isWeekend) {
                dayElement.title = 'Weekend - Not Available';
            } else {
                dayElement.title = 'Past date - Not Available';
            }
        } else {
            dayElement.addEventListener('click', () => handleDateSelect(dayElement, year, month, day));
        }

        if (dateInput && dateInput.value === dateObj.toISOString().slice(0,10)) {
            dayElement.classList.add('selected');
        }

        return dayElement;
    }

    function handleDateSelect(dayElement, year, month, day) {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        dayElement.classList.add('selected');
        
        const selectedDate = `${year}-${(month+1).toString().padStart(2,'0')}-${day.toString().padStart(2,'0')}`;
        if (dateInput) dateInput.value = selectedDate;
        
        if (document.getElementById('appointment-date-error')) {
            document.getElementById('appointment-date-error').style.display = 'none';
        }
        
        generateTimeSlots();
        const doctorElem = document.getElementById('doctor');
        const doctorIdVal = doctorElem ? doctorElem.value : '';
        if (clinicSelect && clinicSelect.value && doctorIdVal) {
            updateTimeSlots(selectedDate, clinicSelect.value, doctorIdVal);
        } else {
            // Disable all slots until a doctor is selected
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.disabled = true;
                slot.classList.add('disabled');
                slot.classList.remove('selected');
                slot.title = 'Select doctor first';
            });
        }
    }

    // Time slot management
    let allSlots = [];
    let slotLabels = {};
    function fetchAllSlots(callback) {
        fetch('fetch_booked_slots.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '' // No params needed for all slots
        })
        .then(response => response.json())
        .then(data => {
            if (data.all_slots) {
                allSlots = Object.keys(data.all_slots);
                slotLabels = data.all_slots;
                if (typeof callback === 'function') callback();
            }
        });
    }

    // --- ENFORCE NON-CLICKABLE BOOKED SLOTS (FINALIZED) ---
    function updateTimeSlots(selectedDate, clinicBranch, doctorId) {
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.disabled = true;
            slot.classList.add('disabled');
            slot.title = 'Checking availability...';
        });

        // Check if selected date is today and get current time
        const now = new Date();
        const selectedDateObj = new Date(selectedDate);
        const isToday = selectedDateObj.toDateString() === now.toDateString();
        
        fetch('fetch_booked_slots.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `date=${selectedDate}&branch=${encodeURIComponent(clinicBranch)}&doctor_id=${encodeURIComponent(doctorId)}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.all_slots) return;
            document.querySelectorAll('.time-slot').forEach(slot => {
                const slotTime = slot.dataset.slotTime;
                const [time, period] = slotTime.split(' ');
                const [hours, minutes] = time.split(':');
                
                // Convert slot time to Date object for comparison
                const slotDate = new Date(selectedDate);
                let slotHours = parseInt(hours);
                
                // Convert to 24-hour format
                if (period.toLowerCase() === 'pm' && slotHours < 12) {
                    slotHours += 12;
                } else if (period.toLowerCase() === 'am' && slotHours === 12) {
                    slotHours = 0;
                }
                
                slotDate.setHours(slotHours, parseInt(minutes), 0);
                
                // If it's today and the time has passed, disable the slot
                if (isToday && slotDate <= now) {
                    slot.disabled = true;
                    slot.classList.add('disabled');
                    slot.classList.remove('selected');
                    slot.title = 'This time has already passed';
                    return;
                }

                // Check availability for future time slots
                if (data.available_slots && data.available_slots.includes(slotTime)) {
                    slot.disabled = false;
                    slot.classList.remove('disabled');
                    slot.title = 'Available';
                } else {
                    slot.disabled = true;
                    slot.classList.add('disabled');
                    slot.classList.remove('selected');
                    slot.title = 'Not available';
                }
            });

            // If the currently selected slot is now unavailable, clear selection
            const selectedSlot = document.querySelector('.time-slot.selected');
            if (selectedSlot && selectedSlot.disabled) {
                selectedSlot.classList.remove('selected');
                updateSelectedScheduleDisplay();
            }
        })
        .catch(error => {
            console.error('Error checking availability:', error);
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.disabled = false;
                slot.classList.remove('disabled');
                slot.title = 'Available (could not verify with server)';
            });
        });
    }

    // --- RESTORE AND ENHANCE TIME SLOT UI ---
    // Use original generateTimeSlots logic if allSlots is empty (fallback)
    function generateTimeSlots() {
        const timeSlotsContainer = document.querySelector('.time-slots');
        if (!timeSlotsContainer) {
            console.error('Time slots container not found');
            return;
        }
        timeSlotsContainer.innerHTML = '';
        // If slots are loaded from backend, use them
        if (allSlots && allSlots.length > 0) {
            allSlots.forEach(slotTime => {
                const timeSlot = createTimeSlot(slotTime);
                timeSlotsContainer.appendChild(timeSlot);
            });
        } else {
            // Fallback to original hardcoded slots (10:00 am - 7:00 pm, skipping 12:00 pm)
            const fallbackSlots = [10, 11, 13, 14, 15, 16, 17, 18, 19];
            fallbackSlots.forEach(hour => {
                const formatted = formatSlotTimeForDB(hour);
                const timeSlot = createTimeSlot(formatted);
                timeSlotsContainer.appendChild(timeSlot);
            });
        }
    }
    // For backend slots (uses slotLabels)
    function createTimeSlot(slotTime) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'time-slot';
        button.textContent = slotLabels[slotTime] || slotTime;
        button.dataset.slotTime = slotTime;
        button.addEventListener('click', () => {
            if (button.disabled) return;
            document.querySelectorAll('.time-slot').forEach(b => b.classList.remove("selected"));
            button.classList.add("selected");
            updateSelectedScheduleDisplay();
        });
        return button;
    }
    // Format fallback slot to match DB (e.g., 10:00 am)
    function formatSlotTimeForDB(hour) {
        const ampm = hour >= 12 ? 'pm' : 'am';
        let displayHour = hour > 12 ? hour - 12 : hour;
        if (displayHour === 0) displayHour = 12;
        return `${displayHour < 10 ? '0' : ''}${displayHour}:00 ${ampm}`;
    }
    // For fallback (hardcoded, legacy, not used anymore)
    function createTimeSlotLegacy(hour) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'time-slot';
        button.textContent = formatTime(hour);
        button.dataset.slotTime = (hour < 10 ? '0' : '') + hour + ':00';
        button.addEventListener('click', () => {
            if (button.disabled) return;
            document.querySelectorAll('.time-slot').forEach(b => b.classList.remove("selected"));
            button.classList.add("selected");
            updateSelectedScheduleDisplay();
        });
        return button;
    }
    function formatTime(hour) {
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        return `${displayHour}:00 ${ampm}`;
    }
    // --- END RESTORE ---

    function updateSelectedScheduleDisplay() {
        if (!dateInput || !dateInput.value) return;
        
        const selectedTimeSlot = document.querySelector('.time-slot.selected');
        if (!selectedTimeSlot) return;
        
        const timeText = selectedTimeSlot.textContent.trim();
        const timeValue = convertTo24Hour(timeText);
        
        if (appointmentDatetimeInput) {
            appointmentDatetimeInput.value = `${dateInput.value} ${timeValue}`;
            console.log(`Selected datetime: ${appointmentDatetimeInput.value}`);
        }
        
        // Set appointment time to time-only value (for database)
        const appointmentTimeInput = document.getElementById('appointment-time');
        if (appointmentTimeInput) {
            appointmentTimeInput.value = timeText;
            console.log(`Selected time: ${appointmentTimeInput.value}`);
        }
        
        // Update the selected schedule display
        const selectedScheduleDisplay = document.getElementById("selected-schedule");
        if (selectedScheduleDisplay) {
            const selectedBranch = clinicSelect ? clinicSelect.value || 'No branch selected' : 'No branch selected'; 
            const formattedDate = formatDate(dateInput.value);
            
            // Get doctor information if available
            const doctorText = doctorSelect ? doctorSelect.options[doctorSelect.selectedIndex].text : 'No doctor selected';
            
            selectedScheduleDisplay.innerHTML = `
                <strong>Your Selected Appointment:</strong><br>
                Date: ${formattedDate}<br>
                Time: ${timeText}<br>
                Branch: ${selectedBranch}<br>
                Doctor: ${doctorText}
            `;
            
            // Clear any validation errors
            const errorContainer = document.getElementById('appointment-error-container');
            if (errorContainer) {
                errorContainer.textContent = '';
                errorContainer.style.display = 'none';
            }
        }
    }
    
    function formatDate(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }

    function convertTo24Hour(timeString) {
        const [time, period] = timeString.split(' ');
        let [hours, minutes] = time.split(':');
        hours = parseInt(hours);
        
        if (period === 'PM' && hours < 12) hours += 12;
        if (period === 'AM' && hours === 12) hours = 0;
        
        return `${hours.toString().padStart(2, '0')}:${minutes}:00`;
    }

    function updateMonthYearDisplay(month, year) {
        if (!monthYearElement) return;
        
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        monthYearElement.textContent = `${monthNames[month]} ${year}`;
    }

    function createEmptyDay() {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'calendar-day empty';
        return emptyDay;
    }

    function validateAppointmentSelection() {
        let isValid = true;
        const dateInput = document.getElementById('appointment-date');
        const timeSlot = document.querySelector('.time-slot.selected');
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(e => e.remove());
        if (!dateInput || !dateInput.value) {
            showCalendarError('Please select a date');
            isValid = false;
        }
        if (!timeSlot) {
            showCalendarError('Please select a time slot');
            isValid = false;
        }
        return isValid;
    }

    function showCalendarError(message) {
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        document.getElementById('calendar').appendChild(errorElement);
    }

    // Navigation function to use with the validation system
    window.validateAppointment = function() {
        const clinicBranch = clinicSelect ? clinicSelect.value : null;
        const isValid = validateAppointmentSelection();
        if (!isValid) return false;
        
        const errorContainer = document.getElementById('appointment-error-container');
        if (errorContainer) {
            errorContainer.textContent = '';
            errorContainer.style.display = 'none';
        }
        
        return true;
    };

    // Clinic selection change handler
    if (clinicSelect) {
        clinicSelect.addEventListener('change', function() {
            const selectedDate = dateInput.value;
            const branch = this.value;
            const doctorId = doctorSelect ? doctorSelect.value : '';
            // Refresh slots UI
            generateTimeSlots();
            if (selectedDate && branch && doctorId) {
                updateTimeSlots(selectedDate, branch, doctorId);
            } else {
                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.disabled = true;
                    slot.classList.add('disabled');
                    slot.classList.remove('selected');
                    slot.title = 'Select date and doctor';
                });
            }
        });
    }

    // Doctor selection change handler
    if (doctorSelect) {
        doctorSelect.addEventListener('change', function() {
            const selectedDate = dateInput.value;
            const branch = clinicSelect.value;
            const doctorId = this.value;
            // Refresh slots UI
            generateTimeSlots();
            if (selectedDate && branch && doctorId) {
                updateTimeSlots(selectedDate, branch, doctorId);
            } else {
                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.disabled = true;
                    slot.classList.add('disabled');
                    slot.classList.remove('selected');
                    slot.title = 'Select date and branch';
                });
            }
        });
    }

    // Event Listeners
    document.querySelector('.calendar-nav').addEventListener('click', (e) => {
        if (e.target.classList.contains('prev-month')) {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
        } else if (e.target.classList.contains('next-month')) {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
        }
    });

    // Initial Setup
    generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
    generateTimeSlots();
}