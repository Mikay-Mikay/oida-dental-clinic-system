document.addEventListener('DOMContentLoaded', () => {
    const calendarContainer = document.getElementById('calendar');
    const selectedSchedule = document.getElementById('selected-schedule');
    const dateInput = document.getElementById('appointment-date');
    let selectedDate = null;
    let selectedTime = null;

    const now = new Date();
    let currentMonth = now.getMonth();
    let currentYear = now.getFullYear();

    function generateCalendar(month, year) {
        calendarContainer.innerHTML = '';
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(year, month, day);
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            dayElement.innerText = day;

            if (dateObj < today.setHours(0, 0, 0, 0)) {
                dayElement.classList.add('disabled');
            }

            // Highlight if this is the selected date
            if (
                selectedDate &&
                new Date(selectedDate).getFullYear() === year &&
                new Date(selectedDate).getMonth() === month &&
                new Date(selectedDate).getDate() === day
            ) {
                dayElement.classList.add('selected');
            }

            dayElement.addEventListener('click', () => {
                if (dayElement.classList.contains('disabled')) return;

                document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                dayElement.classList.add('selected');

                const formatted = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                selectedDate = formatted;
                dateInput.value = formatted; // Sync with hidden input
                updateSelectedDisplay();
            });

            calendarContainer.appendChild(dayElement);
        }
    }

    function updateSelectedDisplay() {
        if (selectedDate && selectedTime) {
            selectedSchedule.textContent = `${selectedDate} at ${selectedTime}`;
        } else if (selectedDate) {
            selectedSchedule.textContent = `${selectedDate}`;
        } else if (selectedTime) {
            selectedSchedule.textContent = `${selectedTime}`;
        } else {
            selectedSchedule.textContent = 'None';
        }
    }

    // Time slot selection
    document.querySelectorAll('.time-slot').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.time-slot').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            selectedTime = btn.textContent;
            updateSelectedDisplay();
        });
    });

    // Handle manual date input (input[type="date"])
    dateInput.addEventListener('change', () => {
        const newDate = new Date(dateInput.value);
        if (!isNaN(newDate)) {
            selectedDate = dateInput.value;

            const newMonth = newDate.getMonth();
            const newYear = newDate.getFullYear();

            // Update calendar view if month or year changed
            if (newMonth !== currentMonth || newYear !== currentYear) {
                currentMonth = newMonth;
                currentYear = newYear;
                generateCalendar(currentMonth, currentYear);
            }

            // Highlight the correct day after regenerating
            const calendarDays = calendarContainer.querySelectorAll('.calendar-day');
            calendarDays.forEach((dayElement, index) => {
                const dayNumber = parseInt(dayElement.textContent);
                if (dayNumber === newDate.getDate()) {
                    dayElement.classList.add('selected');
                } else {
                    dayElement.classList.remove('selected');
                }
            });

            updateSelectedDisplay();
        }
    });

    // Initial calendar load
    generateCalendar(currentMonth, currentYear);
});
