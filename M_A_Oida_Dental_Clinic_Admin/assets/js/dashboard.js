// Sidebar toggle functionality
const sidebar = document.getElementById('sidebar');
const menuToggle = document.getElementById('menuToggle');
const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

// Check if elements exist before adding event listeners
if (menuToggle && sidebar) {
    // Toggle sidebar when menu icon is clicked
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('closed'); // Add or remove 'closed' class
    });
}

if (sidebarToggleBtn && sidebar) {
    // Toggle sidebar when toggle button is clicked
    sidebarToggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('closed'); // Add or remove 'closed' class
    });
}

//Available Dentists Section
document.addEventListener('DOMContentLoaded', () => {
    const dentistList = document.querySelector('.dentist-list');

    const loadDentists = (filter = 'online') => {
        fetch('fetch_dentists.php')
            .then(response => response.json())
            .then(data => {
                dentistList.innerHTML = ''; // Clear current list
                let visibleCount = 0;

                data.forEach(dentist => {
                    if (filter === 'all' || dentist.status === filter) {
                        const card = document.createElement('div');
                        card.className = 'dentist-card';
                        card.setAttribute('data-status', dentist.status);
                        card.innerHTML = `
                            <img src="${dentist.photo}" alt="${dentist.name}">
                            <p>${dentist.name}</p>
                            <span>${dentist.specialty}</span>
                            <span class="dentist-status ${dentist.status}">${dentist.status.charAt(0).toUpperCase() + dentist.status.slice(1)}</span>
                        `;
                        dentistList.appendChild(card);
                        visibleCount++;
                    }
                });

                if (visibleCount === 0) {
                    dentistList.innerHTML = `<p>No dentists are currently ${filter}.</p>`;
                }
            })
            .catch(err => {
                console.error('Failed to fetch dentists:', err);
                dentistList.innerHTML = '<p>Error fetching dentist data.</p>';
            });
    };

    // Load only online by default
    loadDentists();

    // Set filter buttons
    const filterButtons = document.querySelectorAll('[data-filter]');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const status = button.getAttribute('data-filter');
            loadDentists(status);
        });
    });

    // Auto-refresh every 10 seconds
    setInterval(() => {
        const activeFilterBtn = document.querySelector('[data-filter].active') || { getAttribute: () => 'online' };
        const currentFilter = activeFilterBtn.getAttribute('data-filter');
        loadDentists(currentFilter);
    }, 10000); // 10 seconds
});



document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('patientsPieChart').getContext('2d');
    let patientsPieChart;

    // Function to fetch data and update the chart
    function fetchChartData() {
        fetch('fetch_appointments.php') // Call the PHP endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const totalPatients = data.male + data.female + data.other;

                // Update percentages
                const malePercent = totalPatients > 0 ? ((data.male / totalPatients) * 100).toFixed(1) : 0;
                const femalePercent = totalPatients > 0 ? ((data.female / totalPatients) * 100).toFixed(1) : 0;
                const otherPercent = totalPatients > 0 ? ((data.other / totalPatients) * 100).toFixed(1) : 0;

                // Update counts and percentages in the DOM
                document.getElementById('maleCount').textContent = data.male || 0;
                document.getElementById('femaleCount').textContent = data.female || 0;
                document.getElementById('otherCount').textContent = data.other || 0;

                document.getElementById('malePercent').textContent = `${malePercent}%`;
                document.getElementById('femalePercent').textContent = `${femalePercent}%`;
                document.getElementById('otherPercent').textContent = `${otherPercent}%`;

                const chartData = [data.male || 0, data.female || 0, data.other || 0];

                if (patientsPieChart) {
                    // Update existing chart
                    patientsPieChart.data.datasets[0].data = chartData;
                    patientsPieChart.update();
                } else {
                    // Create new chart
                    patientsPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Male Patients', 'Female Patients', 'Other Patients'],
                            datasets: [{
                                label: 'Patients Distribution',
                                data: chartData,
                                backgroundColor: [
                                    '#3498db', // Blue
                                    '#e74c3c', // Red
                                    '#9b59b6'  // Purple
                                ],
                                borderColor: '#ffffff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: '#34495e',
                                        font: {
                                            size: 14
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);

                // Set default chart data to 0 if there's an error
                if (patientsPieChart) {
                    patientsPieChart.data.datasets[0].data = [0, 0, 0];
                    patientsPieChart.update();
                }
            });
    }

    // Fetch data initially and set interval for updates
    fetchChartData();
    setInterval(fetchChartData, 10000); // Update every 10 seconds
});

document.addEventListener('DOMContentLoaded', () => {
    const daysHtml = document.querySelector('.calendar__days');
    const monthHtml = document.querySelector('.calendar__month');
    const yearHtml = document.querySelector('.calendar__year');
    const appointmentsList = document.querySelector('.appointments-list');

    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    // Function to fetch appointments from the server
    function fetchAppointments() {
        return fetch('fetch_appointments.php') // Replace with your PHP endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('Error fetching appointments:', error);
                return [];
            });
    }

    // Function to render calendar
    function renderCalendar(month, year, appointments) {
        daysHtml.innerHTML = '';
        const firstDay = new Date(year, month, 1).getDay();
        const totalDays = new Date(year, month + 1, 0).getDate();

        // Update month and year
        monthHtml.textContent = new Date(year, month).toLocaleString('default', { month: 'long' });
        yearHtml.textContent = year;

        // Render blank days
        for (let i = 0; i < firstDay; i++) {
            const blankDay = document.createElement('div');
            blankDay.classList.add('calendar__day');
            daysHtml.appendChild(blankDay);
        }

        // Render days with appointments
        for (let day = 1; day <= totalDays; day++) {
            const date = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar__day');
            if (date === today.toISOString().split('T')[0]) {
                dayElement.classList.add('today');
            }
            dayElement.innerHTML = `<div class="calendar__day__number">${day}</div>`;

            // Add appointments for the day
            const dayAppointments = appointments.filter(app => app.date === date);
            dayAppointments.forEach(app => {
                const appointmentElement = document.createElement('div');
                appointmentElement.classList.add('appointment');
                appointmentElement.textContent = `${app.full_name} (${app.time})`;
                dayElement.appendChild(appointmentElement);
            });

            daysHtml.appendChild(dayElement);
        }
    }

    // Function to render appointments list
    function renderAppointmentsList(appointments) {
        appointmentsList.innerHTML = '';
        appointments.forEach(app => {
            const appointmentCard = document.createElement('div');
            appointmentCard.classList.add('appointment-card');
            appointmentCard.innerHTML = `
                <img src="${app.image}" alt="User Image" class="appointment-image">
                <div class="appointment-details">
                    <p>${app.full_name}</p>
                    <span>${app.time}</span>
                </div>
            `;
            appointmentsList.appendChild(appointmentCard);
        });
    }

    // Function to update calendar and appointments dynamically
    function updateAppointments() {
        fetchAppointments().then(appointments => {
            renderCalendar(currentMonth, currentYear, appointments);
            renderAppointmentsList(appointments);
        });
    }

    // Initial render
    updateAppointments();

    // Event listeners for next/prev buttons
    document.querySelector('.prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateAppointments();
    });

    document.querySelector('.next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateAppointments();
    });

    // Periodic updates every 10 seconds
    setInterval(updateAppointments, 10000);
});

document.addEventListener('DOMContentLoaded', () => {
    const appointmentsSection = document.querySelector('.appointments-section');
    const hideButton = document.getElementById('hideAppointments');
    const showButton = document.getElementById('showAppointments');

    // Hide Appointments Section
    hideButton.addEventListener('click', () => {
        appointmentsSection.style.display = 'none';
        hideButton.style.display = 'none';
        showButton.style.display = 'inline-flex'; // Show the "Show" button
    });

    // Show Appointments Section
    showButton.addEventListener('click', () => {
        appointmentsSection.style.display = 'block';
        hideButton.style.display = 'inline-flex'; // Show the "Hide" button
        showButton.style.display = 'none';
    });
});

//Bar Chart for Appointments
const ctx = document.getElementById('appointmentsChart').getContext('2d');

  const chartData = {
    2024: [80, 100, 90, 130, 150, 120, 140, 160, 170, 190, 175, 160],
    2025: [100, 110, 95, 140, 160, 120, 135, 150, 170, 190, 160, 165],
    2026: [90, 105, 100, 125, 140, 110, 130, 140, 160, 180, 155, 150],
  };

  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  const backgroundColors = [
    '#38cffe', '#5ad2f4', '#3ba1c4', '#2d6aa3',
    '#7d4ea3', '#e85d90', '#f76791', '#fc814a',
    '#f9a602', '#fdb833', '#e1d849', '#564787'
  ];

  let chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Appointments',
        data: chartData[2025], // default year
        backgroundColor: backgroundColors
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 200
        }
      }
    }
  });

  function updateChart(year) {
    // Update active button styling
    document.querySelectorAll('.year-selector button').forEach(btn => {
      btn.classList.remove('active');
      if (btn.textContent.includes(year)) btn.classList.add('active');
    });

    chart.data.datasets[0].data = chartData[year];
    chart.update();
  }

  //APPOINTMENTS 
  document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function () {
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
        item.classList.add('active');

        // Hide all sections
        document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));

        // Show the targeted section
        const target = item.getAttribute('data-target');
        const targetSection = document.getElementById(target);
        if (targetSection) {
            targetSection.classList.add('active');
            targetSection.scrollIntoView({ behavior: 'smooth' });
        }

        // Scroll to top if dashboard is clicked
        if (target === 'dashboard') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
});

document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Remove active class from all filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Filter the appointments based on the clicked button
        const filter = button.textContent.toLowerCase(); // Get the filter type from the button's text
        filterAppointments(filter);
    });
});

function filterAppointments(filter) {
    const rows = document.querySelectorAll('.appointments-table tbody tr');
    rows.forEach(row => {
        if (row.classList.contains(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Appointment Modal

document.querySelectorAll('.approve-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Get appointment details
        const row = button.closest('tr');
        const patientName = row.querySelector('td:nth-child(2)').textContent;
        const appointmentDate = row.querySelector('td:nth-child(4)').textContent + ' at ' + row.querySelector('td:nth-child(5)').textContent;

        // Set modal content
        document.getElementById('patientName').textContent = patientName;
        document.getElementById('appointmentDate').textContent = appointmentDate;

        // Show approval modal
        document.getElementById('approvalModal').style.display = 'block';
    });
});

document.getElementById('cancelBtn').addEventListener('click', function () {
    // Hide approval modal
    document.getElementById('approvalModal').style.display = 'none';
});

document.getElementById('submitBtn').addEventListener('click', function () {
    // Get appointment details from approval modal
    const patientName = document.getElementById('patientName').textContent;
    const appointmentDate = document.getElementById('appointmentDate').textContent;

    // Set success modal content
    document.getElementById('successPatientName').textContent = patientName;
    document.getElementById('successAppointmentDate').textContent = appointmentDate;

    // Hide approval modal
    document.getElementById('approvalModal').style.display = 'none';

    // Show success modal
    document.getElementById('successModal').style.display = 'block';
});

document.getElementById('okBtn').addEventListener('click', function () {
    // Hide success modal
    document.getElementById('successModal').style.display = 'none';
});
