-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 06:37 PM
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
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `text` text NOT NULL,
  `services` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `patient_id`, `name`, `profile_picture`, `rating`, `text`, `services`, `date`) VALUES
(7, 11, 'Mikaela Somera', NULL, 5, 'Napakagandang serbisyo! Mabilis at magalang ang staff. Highly recommended!', '[\"Metal Braces \\/ Ceramic Braces\"]', '2025-04-30 17:11:50'),
(8, 11, 'Mikaela Somera', NULL, 4, 'Okay naman, pero medyo matagal maghintay. Maayos naman ang treatment', '[\"Teeth Whitening\"]', '2025-04-30 17:12:46'),
(9, 11, 'Anonymous', NULL, 3, 'Average lang. Maayos ang dentist pero medyo masakit ang procedure.', '[\"Dental Bonding\"]', '2025-04-30 17:13:29'),
(10, 11, 'Anonymous', NULL, 5, 'maganda ang service', '[\"Dental Check-ups & Consultation\"]', '2025-04-30 17:22:36'),
(11, 11, 'Mikaela Somera', NULL, 4, 'masakit ang ngipin ko pero maganda ang service', '[\"Tooth Extraction\"]', '2025-04-30 17:24:17'),
(12, 11, 'Anonymous', NULL, 2, 'bakit ganyan service niyo!! ang bagal!', '[\"Gum Treatment and Gingivectomy (Periodontal Care)\"]', '2025-05-01 00:03:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
