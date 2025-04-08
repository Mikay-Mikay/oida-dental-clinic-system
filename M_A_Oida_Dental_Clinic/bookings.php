<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - M&A Oida Dental Clinic</title>
    <link rel="stylesheet" href="assets/css/bookings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="assets/js/bookings.js" defer></script>
    <script src="assets/js/appoinment_schedule.js" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="clinics.php">Clinics</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <div class="nav-right">
                <div class="user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <button class="book-now">Book Now</button>
            </div>
        </nav>
    </header>

    <main class="appointment-container">
        <h2 class="form-title">Appointment Form</h2>

        <div class="progress-bar">
            <div class="step active">Personal Information</div>
            <div class="step">Services</div>
            <div class="step">Appointment Schedule</div>
            <div class="step">Price</div>
            <div class="step">Booking Summary</div>
        </div>

        <form id="appointment-form" method="POST" action="submit_appointment.php">
            <!-- Step 1: Personal Info -->
            <div class="form-step active">
                <h3>Personal Information</h3>

                <label>Full Name:</label>
                <div class="input-with-icon">
                    <input type="text" name="full_name" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>" placeholder="(AUTOMATIC NAME)">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <label>Email Address:</label>
                <div class="input-with-icon">
                    <input type="email" name="email" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" placeholder="(AUTOMATIC EMAIL ADDRESS)">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <label>Contact Number:</label>
                <div class="input-with-icon">
                    <input type="text" name="contact" value="<?php echo isset($_SESSION['user_contact']) ? $_SESSION['user_contact'] : ''; ?>" placeholder="(AUTOMATIC CONTACT NUMBER)">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <p class="info-note">
                    <i class="fa-solid fa-circle-info"></i> You can edit your personal details as needed...
                </p>

                <div class="btns">
                    <button class="next-btn" type="button">Next</button>
                </div>
            </div>

            <!-- Step 2: Services -->
            <div class="form-step">
                <div class="services-section">
                    <h3>Types of Services:</h3>
                    <div class="checkbox-grid">
                        <?php
                        $services = [
                            "Dental Check-ups & Consultation", "Teeth Cleaning", "Tooth Extraction", "Dental Fillings",
                            "Gum Treatment", "Teeth Whitening", "Dental Veneers", "Dental Bonding", "Metal Braces",
                            "Ceramic Braces", "Clear Aligners", "Retainers", "Dental Crown", "Dental Bridges", "Dentures",
                            "Dental Implants", "Fluoride Treatment", "Dental Sealants", "Kids' Braces",
                            "Wisdom Tooth Extraction", "Root Canal Treatment", "TMJ Treatment", "Intraoral X-ray",
                            "Panoramic X-rays", "Cephalometric X-ray"
                        ];

                        foreach ($services as $service) {
                            echo "<label><input type='checkbox' name='services[]' value='$service'> $service</label>";
                        }
                        ?>
                    </div>
                    <p class="note"><span style="color:red;">*Select 2 or more services if necessary*</span></p>
                    <div class="info-note">
                        <i class="fa fa-info-circle"></i> 
                        Please select the dental service you need. If you're unsure, choose ‘Dental Check-up & Consultation,’ and our dentist will assess the best treatment for you.
                    </div>
                </div>

                <div class="btns">
                    <button type="button" class="back-btn">Back</button>
                    <button type="button" class="next-btn">Next</button>
                </div>
            </div>

            <!-- Step 3: Schedule -->
            <div class="form-step" id="appointment-step">
                <h3>Appointment Schedule</h3>

                <div class="schedule-container">
                    <div class="form-group">
                        <label for="clinic">Clinic Branch:</label>
                        <select id="clinic" name="clinic_branch" required>
                            <option value="">Select Branch</option>
                            <option value="Commonwealth Branch">Commonwealth Branch</option>
                            <option value="North Fairview Branch">North Fairview Branch</option>
                            <option value="Maligaya Park Branch">Maligaya Park Branch</option>
                            <option value="San Isidro Branch">San Isidro Branch</option>
                            <option value="Quiapo Branch">Quiapo Branch</option>
                            <option value="Kiko Branch">Kiko Branch</option>
                            <option value="Bagong Silang Branch">Bagong Silang Branch</option>
                        </select>
                        <p class="tip">*Tip: Choose a branch near you*</p>
                    </div>

                    <div class="form-group">
                        <label for="appointment-date">Select Date:</label>
                        <input type="date" id="appointment-date" name="appointment_date" required>
                    </div>

                    <div class="form-group">
                        <label>Select Time Slot:</label>
                        <div class="time-slots" id="time-slot-container">
                            <!-- Slots will be dynamically filled by JS based on branch -->
                            <button type="button" class="time-slot">10:00 AM - 11:00 AM</button>
                            <button type="button" class="time-slot">11:00 AM - 12:00 PM</button>
                            <button type="button" class="time-slot">1:00 PM - 2:00 PM</button>
                            <button type="button" class="time-slot">2:00 PM - 3:00 PM</button>
                            <button type="button" class="time-slot">3:00 PM - 4:00 PM</button>
                            <button type="button" class="time-slot">4:00 PM - 5:00 PM</button>
                            <button type="button" class="time-slot">5:00 PM - 6:00 PM</button>
                            <button type="button" class="time-slot">6:00 PM - 7:00 PM</button>
                        </div>
                        <input type="hidden" name="appointment_time" id="selected-time-input">
                    </div>

                    <div class="form-group selected-info">
                        <p><strong>Selected Date and Time:</strong> <span id="selected-schedule">None</span></p>
                    </div>

                    <div class="form-group calendar-group">
                        <label for="calendar">Calendar View:</label>
                        <div id="calendar" class="calendar-grid"></div>
                    </div>

                    <div class="info-note">
                        <i class="fa-solid fa-circle-info"></i>
                        Please select your preferred date and time for the appointment. Availability may vary.
                    </div>
                </div>

                <div class="btns">
                    <button type="button" class="back-btn">Back</button>
                    <button type="button" class="next-btn">Next</button>
                </div>
            </div>

            <!-- Step 4: Price -->
            <div class="form-step">
                <h3>Price</h3>
                <p>Total estimated cost will be displayed here.</p>
                <div class="btns">
                    <button type="button" class="back-btn">Back</button>
                    <button type="button" class="next-btn">Next</button>
                </div>
            </div>

            <!-- Step 5: Booking Summary -->
            <div class="form-step">
                <h3>Booking Summary</h3>
                <p>Review your appointment details before confirming.</p>
                <div class="btns">
                    <button type="button" class="back-btn">Back</button>
                    <button type="submit" class="submit-btn">Confirm Booking</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
