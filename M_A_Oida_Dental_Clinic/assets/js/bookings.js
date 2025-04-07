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
});
