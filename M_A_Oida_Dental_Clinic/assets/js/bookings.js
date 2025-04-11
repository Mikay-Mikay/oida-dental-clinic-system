document.addEventListener("DOMContentLoaded", () => {
    /* ===============================
       MULTI-STEP FORM NAVIGATION
       =============================== */
    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".progress-bar .step");
    const nextBtns = document.querySelectorAll(".next-btn");
    const backBtns = document.querySelectorAll(".back-btn");
    let currentStep = 0;
    
    // Ipakita ang tamang step at i-update ang progress bar
    function showStep(index) {
      steps.forEach((step, i) => {
        step.classList.toggle("active", i === index);
      });
      indicators.forEach((indicator, i) => {
        indicator.classList.toggle("active", i === index);
      });
    }
    
    // Optional: Update ang progress bar kung nais mong ipakita ang completed steps
    function updateProgress() {
      indicators.forEach((indicator, i) => {
        if (i <= currentStep) {
          indicator.classList.add("active");
        } else {
          indicator.classList.remove("active");
        }
      });
    }
    
    // Suriin kung valid ang required fields ng kasalukuyang step
    function validateCurrentStep() {
      const currentStepElement = steps[currentStep];
      const requiredFields = currentStepElement.querySelectorAll("[required]");
      return Array.from(requiredFields).every(field => field.checkValidity());
    }
    
    // Next button handler
    nextBtns.forEach(btn => {
      btn.addEventListener("click", () => {
        if (!validateCurrentStep()) return;
        
        if (currentStep < steps.length - 1) {
          currentStep++;
          showStep(currentStep);
          updateProgress();
          document.querySelector('.form-step.active').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
          });
        }
      });
    });
    
    // Back button handler
    backBtns.forEach(btn => {
      btn.addEventListener("click", () => {
        if (currentStep > 0) {
          currentStep--;
          showStep(currentStep);
          updateProgress();
          document.querySelector('.form-step.active').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
          });
        }
      });
    });
    
    // Inisyal na pagpapakita ng unang step
    showStep(currentStep);
    updateProgress();
  
    
    /* ===============================
       TIME SLOT MANAGEMENT
       =============================== */
    const clinicSelect = document.getElementById("clinic");
    const appointmentDateInput = document.getElementById("appointment-date");
    const timeSlotButtons = document.querySelectorAll(".time-slot");
    const selectedTimeInput = document.getElementById("selected-time-input");
  
    // I-update ang mga available time slots batay sa napiling petsa at branch
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
  
    // Paghawak sa pagpili ng time slot
    timeSlotButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        if (btn.disabled) return;
        timeSlotButtons.forEach(b => b.classList.remove("selected"));
        btn.classList.add("selected");
        selectedTimeInput.value = btn.textContent.trim();
        const selectedScheduleDisplay = document.getElementById("selected-schedule");
        if (selectedScheduleDisplay) {
          selectedScheduleDisplay.textContent = `${appointmentDateInput.value} at ${selectedTimeInput.value}`;
        }
      });
    });
  
  
    /* ===============================
       CALENDAR MANAGEMENT
       =============================== */
    const calendarContainer = document.getElementById('calendar');
    const dateInput = appointmentDateInput; // Gamitin ang appointment date input
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
  
    function generateCalendar(month, year) {
      if (!calendarContainer) return;
      calendarContainer.innerHTML = ''; // Clear calendar
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      const firstDay = new Date(year, month, 1).getDay();
      
      // Magdagdag ng empty cells para align ng calendar
      for (let i = 0; i < firstDay; i++) {
        const emptyDiv = document.createElement('div');
        calendarContainer.appendChild(emptyDiv);
      }
      
      // Gumawa ng cells para sa bawat araw
      for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        dayElement.textContent = day;
        
        // Format ng petsa yyyy-mm-dd
        const dateStr = `${year}-${(month+1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const cellDate = new Date(dateStr);
        
        // I-disable ang mga nakaraang araw
        if (cellDate < today) {
          dayElement.classList.add('disabled');
        } else {
          dayElement.addEventListener('click', () => handleDateSelect(dayElement, year, month, day));
        }
        
        // I-highlight kung napili na ang araw
        if (dateInput.value === dateStr) {
          dayElement.classList.add('selected');
        }
        
        calendarContainer.appendChild(dayElement);
      }
    }
  
    function handleDateSelect(dayElement, year, month, day) {
      document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
      dayElement.classList.add('selected');
      const selectedDate = `${year}-${(month+1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
      dateInput.value = selectedDate;
      if (clinicSelect.value) {
        updateTimeSlots(selectedDate, clinicSelect.value);
      }
    }
  
    // Calendar navigation (prev and next month)
    const calendarNav = document.querySelector('.calendar-nav');
    if (calendarNav) {
      calendarNav.addEventListener('click', (e) => {
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
    }
  
    // Inisyal na pag-generate ng calendar
    generateCalendar(currentMonth, currentYear);
  });
  