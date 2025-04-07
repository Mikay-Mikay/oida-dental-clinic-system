document.addEventListener('DOMContentLoaded', () => {
    const calendarContainer = document.getElementById('calendar');
    const selectedSchedule = document.getElementById('selected-schedule');
    const dateInput = document.getElementById('appointment-date');
    let selectedDate = null;
    let selectedTime = null;

    function generateCalendar(month, year) {
        calendarContainer.innerHTML = '';
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(year, month, day);
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            dayElement.innerText = day;

            if (dateObj < today) {
                dayElement.classList.add('disabled');
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

    // Auto generate current month calendar
    const now = new Date();
    generateCalendar(now.getMonth(), now.getFullYear());
});
