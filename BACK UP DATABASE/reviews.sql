-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 02:00 PM
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
(1, NULL, 'mikaela somera', NULL, 5, 'Wow! amazing service, highly recommend', '', '2025-04-27 13:08:03'),
(2, NULL, 'Anonymous', NULL, 4, 'hello ganda ng service grabe huhu di sila madamot sa kulay ng rubbers lahat available!', '', '2025-04-27 14:56:37'),
(3, 11, 'Anonymous', NULL, 3, 'sadasdasd', '[\"Gum Treatment and Gingivectomy (Periodontal Care)\"]', '2025-04-27 17:37:52'),
(4, 11, '', NULL, 5, 'superb! mabilis ang service at ang mura pa 🥰', '[\"Dental Bonding\"]', '2025-04-27 18:05:05');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
