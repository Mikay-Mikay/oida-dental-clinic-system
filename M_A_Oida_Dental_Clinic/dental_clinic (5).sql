-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 04:47 PM
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
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `clinic_branch` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` varchar(50) NOT NULL,
  `services` text NOT NULL,
  `status` enum('booked','completed','cancelled') DEFAULT 'booked',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 'Juanito', 'Mendano', 'De La', 'test@gmail.com', '09123456782', 'NCR', 'Metro Manila', 'Manila', 'Barangay 1', '2341', '2013-02-22', '$2y$10$2yeUvFIVnk.WtJNrB73OdeAMXh0zFlgorJKFzXGZC8cUAwkytIYCi', 'Male', '2025-04-12 19:00:57', 'profile_8_1744670552.jpg'),
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
(6, 'Test', 5, '12345', '2025-04-13');

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
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`clinic_branch`,`appointment_date`,`appointment_time`),
  ADD KEY `patient_id` (`patient_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `fk_patient_appointments` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_profiles`
--
ALTER TABLE `patient_profiles`
  ADD CONSTRAINT `patient_profiles_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
