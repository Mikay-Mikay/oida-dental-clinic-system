// bookings.js
document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".progress-bar .step");
    const nextBtns = document.querySelectorAll(".next-btn");
    const backBtns = document.querySelectorAll(".back-btn");
    const clinicSelect = document.getElementById("clinic");
    const appointmentDateInput = document.getElementById("appointment-date");
    const timeSlotButtons = document.querySelectorAll(".time-slot");
    const selectedTimeInput = document.getElementById("selected-time-input");
    let currentStep = 0;

    // Step management functions
    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle("active", i === index);
        });
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle("active", i === index);
        });
    }

    function validateCurrentStep() {
        const currentStepElement = steps[currentStep];
        const requiredFields = currentStepElement.querySelectorAll("[required]");
        return Array.from(requiredFields).every(field => field.checkValidity());
    }

    // Step navigation handlers
    nextBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (!validateCurrentStep()) return;
            
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
                document.querySelector('.form-step.active').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        });
    });

    backBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
                document.querySelector('.form-step.active').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        });
    });

    // Time slot management
    function updateTimeSlots(selectedDate, clinicBranch) {
        if (!selectedDate || !clinicBranch) return;

        fetch(`fetch_booked_times.php?date=${selectedDate}&branch=${clinicBranch}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(bookedSlots => {
                timeSlotButtons.forEach(btn => {
                    const slotTime = btn.textContent.trim();
                    const isBooked = bookedSlots.includes(slotTime);
                    
                    btn.disabled = isBooked;
                    btn.classList.toggle("booked", isBooked);
                    btn.classList.remove("selected");
                });
            })
            .catch(err => {
                console.error("Error fetching booked slots:", err);
                alert("Error checking availability. Please try again.");
            });
    }

    // Event listeners for dynamic updates
    clinicSelect.addEventListener("change", () => {
        if (appointmentDateInput.value) {
            updateTimeSlots(appointmentDateInput.value, clinicSelect.value);
        }
    });

    appointmentDateInput.addEventListener("change", () => {
        if (clinicSelect.value) {
            updateTimeSlots(appointmentDateInput.value, clinicSelect.value);
        }
    });

    // Time slot selection
    document.querySelectorAll(".time-slot").forEach(btn => {
        btn.addEventListener("click", () => {
            if (btn.disabled) return;

            document.querySelectorAll(".time-slot").forEach(b => {
                b.classList.remove("selected");
            });
            
            btn.classList.add("selected");
            selectedTimeInput.value = btn.textContent.trim();
            document.getElementById("selected-schedule").textContent = 
                `${appointmentDateInput.value} at ${selectedTimeInput.value}`;
        });
    });

    // Initialize first step
    showStep(currentStep);
});

// appoinment_schedule.js (updated)

    const calendarContainer = document.getElementById('calendar');
    const dateInput = document.getElementById('appointment-date');
    const clinicSelect = document.getElementById('clinic');
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function generateCalendar(month, year) {
        calendarContainer.innerHTML = '';
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDay = new Date(year, month, 1).getDay();
        
        // Add empty days for calendar alignment
        for (let i = 0; i < firstDay; i++) {
            calendarContainer.appendChild(document.createElement('div'));
        }

        // Generate days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;

            const dateStr = `${year}-${(month+1).toString().padStart(2,'0')}-${day.toString().padStart(2,'0')}`;
            const isPast = new Date(dateStr) < new Date().setHours(0,0,0,0);
            
            if (isPast) {
                dayElement.classList.add('disabled');
            } else {
                dayElement.addEventListener('click', () => handleDateSelect(dayElement, year, month, day));
            }

            if (dateInput.value === dateStr) {
                dayElement.classList.add('selected');
            }

            calendarContainer.appendChild(dayElement);
        }
    }

    function handleDateSelect(dayElement, year, month, day) {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        dayElement.classList.add('selected');
        
        const selectedDate = `${year}-${(month+1).toString().padStart(2,'0')}-${day.toString().padStart(2,'0')}`;
        dateInput.value = selectedDate;
        
        if (clinicSelect.value) {
            updateTimeSlots(selectedDate, clinicSelect.value);
        }
    }

    // Calendar navigation
    document.querySelector('.calendar-nav')?.addEventListener('click', (e) => {
        if (e.target.classList.contains('prev-month')) {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
        } else if (e.target.classList.contains('next-month')) {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
        }
        generateCalendar(currentMonth, currentYear);
    });

    // Initialize calendar
    generateCalendar(currentMonth, currentYear);



    document.addEventListener("DOMContentLoaded", () => {
        const steps = document.querySelectorAll(".form-step");
        const progressSteps = document.querySelectorAll(".progress-bar .step");
        let currentStep = 0;
      
        function validateCurrentStep() {
          let isValid = true;
          const currentStepEl = steps[currentStep];
          
          // Validate all required fields in current step
          const requiredFields = currentStepEl.querySelectorAll("input[required]");
          
          requiredFields.forEach(input => {
            const inputGroup = input.closest(".input-group");
            const errorMessage = inputGroup.querySelector(".error-message");
            
            if (!input.value.trim()) {
              inputGroup.classList.add("invalid");
              errorMessage.style.display = "block";
              isValid = false;
            } else {
              inputGroup.classList.remove("invalid");
              errorMessage.style.display = "none";
            }
          });
      
          return isValid;
        }
      
        // Next button handler
        document.querySelectorAll(".next-btn").forEach(btn => {
          btn.addEventListener("click", () => {
            if (validateCurrentStep() && currentStep < steps.length - 1) {
              currentStep++;
              updateProgress();
              showStep(currentStep);
            }
          });
        });
      
        // Real-time validation
        document.querySelectorAll("input[required]").forEach(input => {
          input.addEventListener("input", () => {
            const inputGroup = input.closest(".input-group");
            const errorMessage = inputGroup.querySelector(".error-message");
            
            if (input.value.trim()) {
              inputGroup.classList.remove("invalid");
              errorMessage.style.display = "none";
            }
          });
        });
      });