-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 12:29 PM
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
-- Database: `streetcred`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('citizen','barangay_admin','lgu_admin','') NOT NULL,
  `lgu_department` enum('City Engineering Office','City Environment & Natural Resources Office','City General Services Office','City Traffic Operations Management','Public Order and Safety Office','Other Local Governance Dept.') DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `first_name`, `last_name`, `phone_number`, `email`, `role`, `lgu_department`, `password_hash`, `barangay`, `created_at`) VALUES
(1, 'Marcus', 'Go', '09123456789', 'marcuse@streetcred.gov', 'barangay_admin', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Tetuan', '2026-05-19 17:45:57'),
(2, 'Willen', 'Santuyo', '09234567890', 'willen.tetuan@streetcred.gov', 'barangay_admin', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Tetuan', '2026-05-19 17:45:57'),
(3, 'Abdullah', 'Ratag', '09345678901', 'abdullah.stamaria@streetcred.gov', 'barangay_admin', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Sta. Maria', '2026-05-19 17:45:57'),
(4, 'Marc', 'Arbilera', '09456789012', 'marc.lgu@zamboangacity.gov', 'lgu_admin', 'City Environment & Natural Resources Office', '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Tetuan\r\n', '2026-05-19 17:45:57'),
(5, 'Elen', 'Cruz', '09567890123', 'marcus.citizen@gmail.com', 'citizen', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Tetuan', '2026-05-19 17:45:57'),
(6, 'Juan', 'Dela Cruz', '09678901234', 'juan.delacruz@gmail.com', 'citizen', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Tetuan', '2026-05-19 17:45:57'),
(7, 'Maria', 'Clara', '09789012345', 'maria.clara@gmail.com', 'citizen', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Sta. Maria', '2026-05-19 17:45:57'),
(8, 'Pedro', 'Penduko', '09890123456', 'pedro.penduko@gmail.com', 'citizen', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Talon-Talon', '2026-05-19 17:45:57'),
(9, 'Test', 'User', '09901234567', 'test.citizen@gmail.com', 'citizen', NULL, '$2y$10$Oj5GDXTujAP.Gm78/8m71.UV6eyWcl8s0AIai87v1eV.wjC96ZCrq', 'Sta. Maria', '2026-05-19 17:45:57'),
(10, 'Euan', 'Chiang', '09778788667', 'euan@gmail.com', 'citizen', NULL, '$2y$10$mFZd9ONtvUOKPmv2pGjeoebiUVCC7ODCieHwk72G9lp47vQpx9lzG', 'Putik', '2026-05-22 08:48:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
