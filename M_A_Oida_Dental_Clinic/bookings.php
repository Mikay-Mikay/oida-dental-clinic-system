<?php
require_once('session.php'); // Include session management
require_once('db.php');

// Kumuha ng user data kung naka-login
$userData = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
        SELECT first_name, last_name, email 
        FROM patients
        WHERE id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc() ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment Form - M&A Oida Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/bookings.css?v=1.1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!-- Delay ng JS upang masigurong una ang pag-render ng mga style -->
  <script src="assets/js/bookings.js" defer></script>
  <script src="assets/js/appoinment_schedule.js" defer></script>
</head>
<body>
  <!-- HEADER / NAVBAR -->
  <header>
    <nav class="navbar">
      <div class="logo-container">
        <img src="assets/photos/logo.jpg" alt="Logo" class="logo">
      </div>
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
        <a href="<?php echo isset($_SESSION['user_id']) ? 'bookings.php' : 'login.php'; ?>"
           onclick="<?php if (!isset($_SESSION['user_id'])) echo 'alert(\'Please login to book an appointment.\');'; ?>">
          <button class="book-now">Book Now</button>
        </a>
      </div>
    </nav>
  </header>

  <!-- FORM CONTAINER -->
  <main class="appointment-container">
    <h2 class="form-title">Appointment Form</h2>

    <!-- Halimbawa lang kung may Online vs Walk-in tab buttons -->
    <div class="form-tabs">
      <button type="button" class="tab-button active">Online Appointment Form</button>
      <button type="button" class="tab-button">Walk-in Appointment Form</button>
    </div>

    <!-- PROGRESS BAR (7 STEPS) -->
    <div class="progress-bar">
      <div class="step active">Personal Information</div>
      <div class="step">Dental History</div>
      <div class="step">Medical History</div>
      <div class="step">Services</div>
      <div class="step">Appointment Schedule</div>
      <div class="step">Informed Consent</div>
      <div class="step">Price</div>
      <div class="step">Booking Summary</div>
    </div>

    <form id="appointment-form" method="POST" action="submit_appointment.php">

      <!-- STEP 1: PERSONAL INFO -->
      <div class="form-step active">
        <h3 class="step-header">Part 1: PERSONAL INFORMATION RECORDS</h3>

        <!-- Halimbawa ng ilang fields; palitan mo ayon sa gusto mo -->
        <label for="full_name">Full Name:</label>
        <div class="input-with-icon">
          <input type="text" id="full_name" name="full_name" required>
          <i class="fa-solid fa-pen-to-square"></i>
          <span class="error-message">This field is required</span>
        </div>

        <label for="email">Email:</label>
        <div class="input-with-icon">
          <input type="email" id="email" name="email" required>
          <i class="fa-solid fa-pen-to-square"></i>
          <span class="error-message">This field is required</span>
        </div>

        <!-- Ilan pang personal info fields... -->

        <div class="btns">
          <!-- Walang Back button dito sa unang step -->
          <button type="button" class="next-btn">Next</button>
        </div>
      </div>

      <!-- STEP 2: DENTAL HISTORY -->
      <div class="form-step">
        <h3 class="step-header">Part 2: DENTAL HISTORY</h3>

        <!-- Galing sa screenshot mo: Gender, Contact Number (pwede phone?), Nickname, Home Number, Office Number, Fax, etc. -->
        
        <label for="gender">Gender:</label>
        <div class="input-with-icon">
          <select name="gender" id="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <!-- etc. -->
          </select>
          <i class="fa-solid fa-pen-to-square"></i>
          <span class="error-message">This field is required</span>
        </div>

        <label for="contact_number_2">Contact Number:</label>
        <div class="input-with-icon">
          <input type="tel" id="contact_number_2" name="contact_number_2" required>
          <i class="fa-solid fa-pen-to-square"></i>
          <span class="error-message">This field is required</span>
        </div>

        <label for="nickname">Nickname:</label>
        <div class="input-with-icon">
          <input type="text" id="nickname" name="nickname" placeholder="Enter your Nickname">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>

        <label for="home_no">Home Number:</label>
        <div class="input-with-icon">
          <input type="text" id="home_no" name="home_no" placeholder="Enter Home Number">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>

        <label for="office_no">Office Number:</label>
        <div class="input-with-icon">
          <input type="text" id="office_no" name="office_no" placeholder="Enter Office Number">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>

        <label for="fax_no">Fax No.:</label>
        <div class="input-with-icon">
          <input type="text" id="fax_no" name="fax_no" placeholder="Enter Fax No.">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>

        <!-- Kung may “Last Dental Visit,” “Existing Dentist,” at iba pang fields... -->
        <label for="previous_dentist">Previous Dentist:</label>
        <div class="input-with-icon">
          <input type="text" id="previous_dentist" name="previous_dentist">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>


        <label for="last_dental_visit">Last Dental Visit:</label>
        <div class="input-with-icon">
          <input type="text" id="last_dental_visit" name="last_dental_visit" placeholder="MM/DD/YYYY">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>

        <p class="info-note">
          <i class="fa-solid fa-circle-info"></i>
          Provide any additional dental history details here.
        </p>

        <div class="btns">
          <button type="button" class="back-btn">Back</button>
          <button type="button" class="next-btn">Next</button>
        </div>
      </div>

      <!-- STEP 3: MEDICAL HISTORY -->
<div class="form-step">
  <h3 class="step-header">
    Part 3: MEDICAL HISTORY 
    <span style="font-size: 0.8em; color: #666;">
      (Ignore this if NONE, proceed to Services)
    </span>
  </h3>

  <!-- Name of Physician -->
  <label for="physician_name">Name of Physician (e.g. Allice Castro):</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="physician_name" 
      name="physician_name" 
      placeholder="Enter full name of your physician" 
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Physician Specialty -->
  <label for="physician_specialty">Specialty (e.g. Cardiologist, etc.):</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="physician_specialty" 
      name="physician_specialty" 
      placeholder="Enter specialty" 
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Office Address -->
  <label for="office_address">Office Address:</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="office_address" 
      name="office_address" 
      placeholder="Enter Office Address of Physician" 
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Office Number -->
  <label for="office_number">Office Number:</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="office_number" 
      name="office_number" 
      placeholder="Enter Office Number of Physician" 
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Are you under medical treatment now? -->
  <label for="under_treatment">Are you under any medical treatment now?</label>
  <div class="input-with-icon">
    <select id="under_treatment" name="under_treatment">
      <option value="">Select YES or NO</option>
      <option value="YES">YES</option>
      <option value="NO">NO</option>
    </select>
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- If YES, what is the condition being treated? -->
  <label for="treatment_condition">If so, what is the condition being treated?</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="treatment_condition" 
      name="treatment_condition" 
      placeholder="Type condition or illness here"
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Have you had serious illness or surgical operation? -->
  <label for="serious_illness">Have you had any serious illness or surgical operation?</label>
  <div class="input-with-icon">
    <select id="serious_illness" name="serious_illness">
      <option value="">Select YES or NO</option>
      <option value="YES">YES</option>
      <option value="NO">NO</option>
    </select>
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- If YES, specify -->
  <label for="serious_illness_details">If YES, specify:</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="serious_illness_details" 
      name="serious_illness_details" 
      placeholder="Write details about your illness or operation"
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Have you ever been hospitalized? -->
  <label for="hospitalized">Have you ever been hospitalized?</label>
  <div class="input-with-icon">
    <select id="hospitalized" name="hospitalized">
      <option value="">Select YES or NO</option>
      <option value="YES">YES</option>
      <option value="NO">NO</option>
    </select>
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- If YES, specify -->
  <label for="hospitalized_details">If YES, specify:</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="hospitalized_details" 
      name="hospitalized_details" 
      placeholder="Write details about hospitalization"
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- Are you taking any prescription medication or operation medication? -->
  <label for="prescription_med">Are you taking any prescription/operation medication?</label>
  <div class="input-with-icon">
    <select id="prescription_med" name="prescription_med">
      <option value="">Select YES or NO</option>
      <option value="YES">YES</option>
      <option value="NO">NO</option>
    </select>
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <!-- If YES, specify -->
  <label for="prescription_med_details">If YES, what medication?</label>
  <div class="input-with-icon">
    <input 
      type="text" 
      id="prescription_med_details" 
      name="prescription_med_details" 
      placeholder="List medication(s) here"
    />
    <i class="fa-solid fa-pen-to-square"></i>
  </div>

  <p class="note" style="margin-top: 20px;">
    <strong>Note:</strong> If none applies, you can proceed to the next step.
  </p>

  <div class="btns">
    <button type="button" class="back-btn">Back</button>
    <button type="button" class="next-btn">Next</button>
  </div>
</div>


      <!-- STEP 3: SERVICES -->
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

      <!-- STEP 4: APPOINTMENT SCHEDULE -->
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
    <div class="calendar-nav">
        <button class="prev-month">&lt;</button>
        <div class="month-year"></div>
        <button class="next-month">&gt;</button>
    </div>
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

      <!-- STEP 5: INFORMED CONSENT -->
      <div class="form-step">
        <h3 class="step-header">Informed Consent</h3>
        <!-- ... -->
        <div class="btns">
          <button type="button" class="back-btn">Back</button>
          <button type="button" class="next-btn">Next</button>
        </div>
      </div>

      <!-- STEP 6: PRICE -->
      <div class="form-step">
        <h3 class="step-header">Price</h3>
        <!-- ... -->
        <div class="btns">
          <button type="button" class="back-btn">Back</button>
          <button type="button" class="next-btn">Next</button>
        </div>
      </div>

      <!-- STEP 7: BOOKING SUMMARY -->
      <div class="form-step">
        <h3 class="step-header">Booking Summary</h3>
        <!-- ... -->
        <div class="btns">
          <button type="button" class="back-btn">Back</button>
          <button type="submit" class="submit-btn">Confirm Booking</button>
        </div>
      </div>

    </form>
  </main>
</body>
</html>