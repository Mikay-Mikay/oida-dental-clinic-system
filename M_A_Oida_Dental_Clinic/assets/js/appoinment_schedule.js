// appoinment_schedule.js
document.addEventListener('DOMContentLoaded', () => {
    const calendarContainer = document.getElementById('calendar');
    const dateInput = document.getElementById('appointment-date');
    const clinicSelect = document.getElementById('clinic');
    const monthYearElement = document.querySelector('.month-year');
    let currentDate = new Date();
    
    // Weekday headers
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    function generateCalendar(month, year) {
        calendarContainer.innerHTML = '';
        
        // Add weekday headers
        weekdays.forEach(day => {
            const header = document.createElement('div');
            header.className = 'calendar-header';
            header.textContent = day;
            calendarContainer.appendChild(header);
        });

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        
        // Add empty days for calendar alignment
        for (let i = 0; i < firstDay.getDay(); i++) {
            calendarContainer.appendChild(createEmptyDay());
        }

        // Generate days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;

            const dateObj = new Date(year, month, day);
            const today = new Date();
            
            // Normalize dates to midnight for accurate comparison
            dateObj.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            
            // Disable if date is today or in the past
            const isPastOrToday = dateObj <= today;

            if (isPastOrToday) {
                dayElement.classList.add('disabled');
            } else {
                dayElement.addEventListener('click', () => handleDateSelect(dayElement, year, month, day));
            }

            if (dateInput.value === dateObj.toISOString().slice(0,10)) {
                dayElement.classList.add('selected');
            }

            calendarContainer.appendChild(dayElement);
        }

        // Update month/year display
        monthYearElement.textContent = 
            new Date(year, month).toLocaleString('default', { month: 'long', year: 'numeric' });
    }

    function createEmptyDay() {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'calendar-day empty';
        return emptyDay;
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
    document.querySelector('.calendar-nav').addEventListener('click', (e) => {
        if (e.target.classList.contains('prev-month')) {
            currentDate.setMonth(currentDate.getMonth() - 1);
        } else if (e.target.classList.contains('next-month')) {
            currentDate.setMonth(currentDate.getMonth() + 1);
        }
        generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
    });

    // Initialize calendar with current month
    generateCalendar(currentDate.getMonth(), currentDate.getFullYear());

    // Sync calendar with date input changes
    dateInput.addEventListener('change', () => {
        const newDate = new Date(dateInput.value);
        if (!isNaN(newDate)) {
            currentDate = newDate;
            generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
        }
    });

    // Time slot update function (connected with bookings.js)
    function updateTimeSlots(selectedDate, clinicBranch) {
        if (!selectedDate || !clinicBranch) return;

        fetch(`fetch_booked_times.php?date=${selectedDate}&branch=${clinicBranch}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(bookedSlots => {
                document.querySelectorAll('.time-slot').forEach(btn => {
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
});