-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 02:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dental_clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logins`
--

CREATE TABLE `admin_logins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `admin_id` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logins`
--

INSERT INTO `admin_logins` (`id`, `email`, `name`, `admin_id`, `password`, `created_at`, `otp`, `otp_expiry`, `password_hash`) VALUES
(2, 'mikaelasomera13@gmail.com', 'Mikaela Somera', 'ADM-002', '', '2025-04-18 05:32:38', NULL, NULL, '$2y$10$VORGcxiOCQxgCoTysSJLYOlU6kG5R8Nfj7lvCQf90GP0TBzh.mdLK'),
(3, 'marcgermineganan05@gmail.com', 'Marc Germine Ganan', 'ADM-001', '', '2025-04-18 05:48:30', NULL, NULL, '$2y$10$oGGCuQib4oDVZM3hMG39iuh5vtX4vkbK.QN0xtHB.wrdXtFxHF6T.');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `clinic_branch` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` varchar(20) NOT NULL,
  `services` text NOT NULL,
  `status` enum('pending','booked','completed','cancelled','rescheduled','canceled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `health` enum('yes','no') NOT NULL,
  `pregnant` enum('yes','no') DEFAULT NULL,
  `nursing` enum('yes','no') DEFAULT NULL,
  `birth_control` enum('yes','no') DEFAULT NULL,
  `blood_pressure` varchar(50) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `consent` tinyint(1) NOT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `dental_insurance` varchar(100) DEFAULT NULL,
  `previous_dentist` varchar(100) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `clinic_branch`, `appointment_date`, `appointment_time`, `services`, `status`, `created_at`, `health`, `pregnant`, `nursing`, `birth_control`, `blood_pressure`, `blood_type`, `medical_history`, `allergies`, `consent`, `religion`, `nationality`, `occupation`, `dental_insurance`, `previous_dentist`, `doctor_id`) VALUES
(15, 8, 'Commonwealth Branch', '2025-04-25', '5:00 PM', 'Dental Check-ups & Consultation', '', '2025-04-24 23:25:18', 'yes', NULL, NULL, NULL, NULL, 'A+', 'Thyroid Problem', 'Local Anesthetic', 1, NULL, NULL, NULL, NULL, NULL, 2),
(16, 8, 'Commonwealth Branch', '2025-04-30', '2:00 PM', 'Dental Check-ups & Consultation', '', '2025-04-24 23:41:50', 'yes', NULL, NULL, NULL, NULL, 'A-', 'Heart Attack, Thyroid Problem, Heart Disease', 'Penicillin', 1, NULL, NULL, NULL, NULL, NULL, 2),
(17, 8, 'Commonwealth Branch', '2025-04-25', '4:00 PM', 'Dental Check-ups & Consultation', '', '2025-04-24 23:46:05', 'yes', NULL, NULL, NULL, NULL, 'A+', 'Thyroid Problem, Heart Disease, Diabetes', 'Local Anesthetic', 1, NULL, NULL, NULL, NULL, NULL, 2),
(18, 4, 'Commonwealth Branch', '2025-04-28', '2:00 PM', 'Dental Bridges, Dental Check-ups & Consultation', '', '2025-04-26 10:58:35', 'yes', NULL, NULL, NULL, NULL, 'AB+', 'None', 'None', 1, NULL, NULL, NULL, NULL, NULL, 2),
(20, 1, 'Commonwealth Branch', '2025-05-01', '10:00 AM', 'Dental Check-ups & Consultation', '', '2025-04-26 11:55:21', 'yes', NULL, NULL, NULL, '120/80', 'A+', 'None', 'None', 1, 'Catholic', 'Filipino', 'Engineer', NULL, NULL, 2),
(24, 6, 'Maligaya Park Branch', '2025-04-29', '11:00 AM', 'Dental Bridges', '', '2025-04-26 12:23:25', 'yes', NULL, NULL, NULL, NULL, 'AB+', 'None', 'None', 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dentists`
--

CREATE TABLE `dentists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `status` enum('online','offline') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `clinic_branch` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `first_name`, `last_name`, `specialization`, `clinic_branch`, `is_active`, `created_at`) VALUES
(1, 'Maria', 'Santos', 'General Dentistry', 'Commonwealth Branch', 1, '2025-04-23 08:32:57'),
(2, 'Juan', 'Reyes', 'Orthodontics', 'Commonwealth Branch', 1, '2025-04-23 08:32:57'),
(3, 'Ana', 'Garcia', 'Pediatric Dentistry', 'North Fairview Branch', 1, '2025-04-23 08:32:57'),
(4, 'Pedro', 'Lim', 'Oral Surgery', 'North Fairview Branch', 1, '2025-04-23 08:32:57'),
(5, 'Sofia', 'Cruz', 'Periodontics', 'Maligaya Park Branch', 1, '2025-04-23 08:32:57'),
(6, 'Miguel', 'De Guzman', 'Endodontics', 'Maligaya Park Branch', 1, '2025-04-23 08:32:57'),
(7, 'Camila', 'Tan', 'Prosthodontics', 'San Isidro Branch', 1, '2025-04-23 08:32:57'),
(8, 'Gabriel', 'Morales', 'Cosmetic Dentistry', 'San Isidro Branch', 1, '2025-04-23 08:32:57'),
(9, 'Isabella', 'Navarro', 'General Dentistry', 'Quiapo Branch', 1, '2025-04-23 08:32:57'),
(10, 'Mateo', 'Villanueva', 'Orthodontics', 'Quiapo Branch', 1, '2025-04-23 08:32:57'),
(11, 'Valentina', 'Ramos', 'Pediatric Dentistry', 'Kiko Branch', 1, '2025-04-23 08:32:57'),
(12, 'Daniel', 'Dela Cruz', 'Oral Surgery', 'Kiko Branch', 1, '2025-04-23 08:32:57'),
(13, 'Olivia', 'Torres', 'Periodontics', 'Bagong Silang Branch', 1, '2025-04-23 08:32:57'),
(14, 'Sebastian', 'Lopez', 'Endodontics', 'Bagong Silang Branch', 1, '2025-04-23 08:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `region` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `date_of_birth` date NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) NOT NULL DEFAULT 'profile-placeholder.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `phone_number`, `region`, `province`, `city`, `barangay`, `zip_code`, `date_of_birth`, `password_hash`, `gender`, `created_at`, `profile_picture`) VALUES
(3, 'joseph', 'speed', 'watkinson', 'marcgermine2003@gmail.com', '09776907092', 'NCR', 'Metro Manila', 'Quezon City', 'Batasan Hills', '1126', '2003-10-29', '$2y$10$8iK/7vt5W1culiBO6YyA8Ohh2TfawU4ncfYuSfsdEUyul8V61kGFK', 'Male', '2025-03-28 15:14:01', 'profile-placeholder.jpg'),
(4, 'Marc Germine', 'Panizales', 'Ganan', 'marcgermineganan05@gmail.com', '09776907092', 'Region III (Central Luzon)', 'Pampanga', 'Angeles City', 'Balibago', '1126', '2003-10-29', '$2y$10$fOXGahW2OnUIW2sXN81PgecG1GbwiTJcDwt1AMO/TlxUJBB5r9N9u', 'Male', '2025-03-28 15:49:22', 'profile-placeholder.jpg'),
(6, 'ichigo', 'pirate', 'bankai', 'marcgermineganan2003@gmail.com', '09776907092', 'Region IV-A (Calabarzon)', 'Cavite', 'Dasmariñas', 'Burol', '1126', '2003-10-29', '$2y$10$FSl8D4laFLOiAWrJohrI.OlmQ1EEc4Kn1xqpzrsiNqmyfUtrr14sy', 'Male', '2025-04-10 10:17:43', 'profile-placeholder.jpg'),
(7, 'ichigo', 'pirate', 'bankai', 'marcgermineganan03@gmail.com', '09776907092', 'NCR', 'Metro Manila', 'Quezon City', 'Batasan Hills', '1126', '2003-10-29', '$2y$10$.P9l00L9Nep7dvP9jNrU5OVlqmLZNJIAeNI4hhefGA1D.ofmasRle', 'Male', '2025-04-10 15:05:49', 'profile-placeholder.jpg'),
(8, 'Juanito', 'Mendano', 'De La', 'test@gmail.com', '09123456782', 'NCR', 'Metro Manila', 'Manila', 'Barangay uno', '2341', '2013-02-22', '$2y$10$2yeUvFIVnk.WtJNrB73OdeAMXh0zFlgorJKFzXGZC8cUAwkytIYCi', 'Male', '2025-04-12 19:00:57', 'profile_8_1744670552.jpg'),
(9, 'Juan', 'sad', 'sasf', 'test2@gmail.com', '09123456783', 'NCR', 'Metro Manila', 'Manila', 'Barangay 1', '1234', '2024-10-30', '$2y$10$PWkn4RlBF14iUPzCgxIPh.PPNFgEppWPlSnrey1dqH2ds.3SkJ0Dy', 'Male', '2025-04-12 19:25:17', 'profile-placeholder.jpg'),
(10, 'lei', 'mendao', 'De La', 'test3@gmail.com', '09123456783', 'NCR', 'Metro Manila', 'Quezon City', 'Commonwealth', '2341', '2024-07-10', '$2y$10$gOEi2Q/WoE.6no0XpJnmF.cKuKPG0UnV2T8D0N3x29n7lDhdvalw.', 'Male', '2025-04-12 19:53:19', 'profile-placeholder.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `patient_profiles`
--

CREATE TABLE `patient_profiles` (
  `patient_id` int(11) NOT NULL,
  `medical_history` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `insurance_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_profiles`
--

INSERT INTO `patient_profiles` (`patient_id`, `medical_history`, `created_at`, `insurance_info`) VALUES
(4, NULL, '2025-04-06 17:11:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `rating`, `text`, `date`) VALUES
(1, 'marc', 5, 'marc', '2025-04-03'),
(3, 'test', 3, '123', '2025-04-08'),
(6, 'Test', 5, '12345', '2025-04-13'),
(7, 'gdfgdfgdf', 4, 'ertdsgdf', '2025-04-21');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `description`, `is_active`, `created_at`) VALUES
(1, 'Dental Check-ups & Consultation', 500.00, 'Regular dental check-up and consultation with our professionals', 1, '2025-04-23 16:32:57'),
(2, 'Dental Crown', 8000.00, 'Custom-made dental crowns to restore damaged teeth', 1, '2025-04-23 16:32:57'),
(3, 'Intraoral X-ray', 300.00, 'Detailed X-ray images of individual teeth', 1, '2025-04-23 16:32:57'),
(4, 'Teeth Cleaning (Oral Prophylaxis)', 1500.00, 'Professional teeth cleaning and plaque removal', 1, '2025-04-23 16:32:57'),
(5, 'Dental Bridges', 12000.00, 'Fixed prosthetic device to replace missing teeth', 1, '2025-04-23 16:32:57'),
(6, 'Panoramic X-ray/Full Mouth X-Ray', 1500.00, 'Complete view of your entire mouth in a single image', 1, '2025-04-23 16:32:57'),
(7, 'Tooth Extraction', 2000.00, 'Removal of damaged or problematic teeth', 1, '2025-04-23 16:32:57'),
(8, 'Dentures (Partial & Full)', 15000.00, 'Removable replacements for missing teeth', 1, '2025-04-23 16:32:57'),
(9, 'TMJ Treatment', 5000.00, 'Treatment for temporomandibular joint disorders', 1, '2025-04-23 16:32:57'),
(10, 'Dental Fillings (Composite)', 1500.00, 'Tooth-colored fillings to repair cavities', 1, '2025-04-23 16:32:57'),
(11, 'Root Canal Treatment', 8000.00, 'Procedure to treat infected tooth pulp', 1, '2025-04-23 16:32:57'),
(12, 'Teeth Whitening', 6000.00, 'Professional teeth whitening treatment', 1, '2025-04-23 16:32:57'),
(13, 'Orthodontic Braces', 40000.00, 'Braces to correct teeth alignment and bite issues', 1, '2025-04-23 16:32:57'),
(14, 'Dental Implant', 50000.00, 'Artificial tooth roots to support replacement teeth', 1, '2025-04-23 16:32:57'),
(15, 'Gum Surgery', 10000.00, 'Surgical procedures to treat gum disease', 1, '2025-04-23 16:32:57'),
(16, 'Wisdom Tooth Extraction', 5000.00, 'Removal of wisdom teeth', 1, '2025-04-23 16:32:57'),
(17, 'Pediatric Dental Care', 1000.00, 'Specialized dental care for children', 1, '2025-04-23 16:32:57'),
(18, 'Dental Veneers', 10000.00, 'Thin shells to improve the appearance of front teeth', 1, '2025-04-23 16:32:57'),
(19, 'Night Guard', 4500.00, 'Custom guard to protect teeth during sleep', 1, '2025-04-23 16:32:57'),
(20, 'Dental Sealants', 800.00, 'Protective coating for teeth to prevent decay', 1, '2025-04-23 16:32:57'),
(21, 'Full Mouth Rehabilitation', 250000.00, 'Complete restoration of all teeth', 1, '2025-04-23 16:32:57'),
(22, 'Sleep Apnea Treatment', 20000.00, 'Dental solutions for sleep apnea', 1, '2025-04-23 16:32:57'),
(23, 'Dental Emergency Care', 1000.00, 'Immediate care for dental emergencies', 1, '2025-04-23 16:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logins`
--
ALTER TABLE `admin_logins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `admin_id` (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`clinic_branch`,`appointment_date`,`appointment_time`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `idx_branch_date` (`clinic_branch`,`appointment_date`),
  ADD KEY `fk_doctor_id` (`doctor_id`);

--
-- Indexes for table `dentists`
--
ALTER TABLE `dentists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `patient_profiles`
--
ALTER TABLE `patient_profiles`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logins`
--
ALTER TABLE `admin_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `dentists`
--
ALTER TABLE `dentists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `patient_profiles`
--
ALTER TABLE `patient_profiles`
  ADD CONSTRAINT `patient_profiles_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
