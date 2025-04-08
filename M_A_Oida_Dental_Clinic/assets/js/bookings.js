document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".progress-bar .step");
    const nextBtns = document.querySelectorAll(".next-btn");
    const backBtns = document.querySelectorAll(".back-btn");

    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.remove("active");
            if (i === index) step.classList.add("active");
        });

        indicators.forEach((indicator, i) => {
            indicator.classList.toggle("active", i === index);
        });
    }

    nextBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    backBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    showStep(currentStep); // initialize first step

    // --------- FETCH BOOKED TIME SLOTS ---------
    const appointmentDateInput = document.getElementById("appointment-date");
    const timeSlotButtons = document.querySelectorAll(".time-slot");

    appointmentDateInput.addEventListener("change", () => {
        const selectedDate = appointmentDateInput.value;

        if (!selectedDate) return;

        // Fetch booked time slots (you will create this backend script)
        fetch(`fetch_booked_times.php?date=${selectedDate}`)
            .then(res => res.json())
            .then(bookedSlots => {
                // Enable all buttons first
                timeSlotButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove("booked");
                });

                // Disable booked slots
                timeSlotButtons.forEach(btn => {
                    if (bookedSlots.includes(btn.textContent.trim())) {
                        btn.disabled = true;
                        btn.classList.add("booked"); // Optional: use for gray style
                    }
                });
            })
            .catch(err => {
                console.error("Error fetching booked slots:", err);
            });
    });
});

document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const currentStep = document.querySelector('.form-step.active');
        const nextStep = currentStep.nextElementSibling;
        if (nextStep && nextStep.classList.contains('form-step')) {
            currentStep.classList.remove('active');
            nextStep.classList.add('active');
            nextStep.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
