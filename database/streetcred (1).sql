-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 03:43 PM
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
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `barangay` varchar(50) NOT NULL,
  `category` enum('Drainage Issues','Waste Management','Road/Pothole','Streetlight','Traffic Concern','Security Issue','Infrastructure','Other') NOT NULL,
  `description` text NOT NULL,
  `status` enum('standby','In progress','Reported to LGU','Resolved') NOT NULL DEFAULT 'standby',
  `image_path` varchar(255) NOT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`ID`, `user_id`, `barangay`, `category`, `description`, `status`, `image_path`, `date_submitted`, `created_at`) VALUES
(1, 1, 'Tetuan', 'Road/Pothole', 'Massive pothole forming near the corner of MCLL Highway. Drivers are swerving aggressively to avoid it.', 'Resolved', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(2, 2, 'Tetuan', 'Streetlight', 'Three consecutive streetlights are completely dead along Gov. Ramos Ave, leaving the road pitch black at night.', 'In progress', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(4, 4, 'Tetuan', 'Drainage Issues', 'The main drainage canal along Don Alfaro St is heavily clogged with plastic sediment, causing water overflow during rain.', 'standby', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(5, 5, 'Tetuan', 'Other', 'Low water pressure issues reported near the local health center since early this morning without notice.', 'standby', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(6, 6, 'Tetuan', 'Road/Pothole', 'Cracked and uneven concrete slab on the main road heading up towards Pasonanca Park, causing motorcycle hazards.', 'In progress', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(7, 7, 'Tetuan', 'Infrastructure', 'Dangling telecom and internet cables are hanging dangerously close to passing cargo trucks and delivery vans.', 'standby', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(8, 8, 'Tetuan', 'Waste Management', 'An illegal open dumping site is rapidly expanding near the local riverside pathway. Strong odor present.', 'standby', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(9, 9, 'Tetuan', 'Traffic Concern', 'Unfinished culvert work has narrow lanes blocked near a busy intersection, causing major rush-hour gridlock.', 'In progress', '', '2026-05-20 10:17:28', '2026-05-22 15:47:26'),
(20, 1, 'Tetuan', 'Traffic Concern', 'Heavy traffic congestion near the intersection of Gov. Ramos Ave due to malfunctioning traffic signals.', 'Reported to LGU', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(21, 3, 'Tetuan', 'Security Issue', 'Report of missing manhole covers along the dark walkways of Don Alfaro St, creating a serious safety hazard.', 'In progress', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(22, 5, 'Tetuan', 'Infrastructure', 'The public footbridge railings near the canal are severely rusted and breaking apart. Needs immediate welding.', 'Reported to LGU', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(23, 2, 'Tetuan', 'Traffic Concern', 'Illegal double parking along the narrow secondary highways is causing massive bottlenecks during rush hour.', 'Resolved', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(25, 6, 'Tetuan', 'Drainage Issues', 'Culvert drainage breakdown has left stagnant water flooding a portion of the access street heading to the plaza.', 'standby', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(26, 8, 'Tetuan', 'Road/Pothole', 'Deep asphalt cracks and depressions stretching across the main lane near the river bridge approach.', 'In progress', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(27, 7, 'Tetuan', 'Infrastructure', 'A public concrete bench inside the community health center waiting zone has collapsed completely.', 'Resolved', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(28, 9, 'Tetuan', 'Other', 'Stray wires from an abandoned commercial signage setup are hanging loose over the pedestrian sidewalk.', 'standby', '', '2026-05-20 10:18:19', '2026-05-22 15:47:26'),
(29, 5, 'Guiwan', '', 'sasas', 'standby', '../uploads/reports/report_1779380273_020bde9bae4031f7.png', '2026-05-21 16:17:53', '2026-05-22 15:47:26'),
(30, 5, 'Divisoria', '', 'Fix Pothole pls', 'standby', '../uploads/reports/report_1779380383_989d217d4c805ed1.png', '2026-05-21 16:19:43', '2026-05-22 15:47:26'),
(31, 5, 'Guiwan', '', 'No Pulis aroundn pls help', 'standby', '../uploads/reports/report_1779380620_476e19bb7e3e17e2.png', '2026-05-21 16:23:40', '2026-05-22 15:47:26'),
(32, 5, 'La Paz', 'Drainage Issues', 'Issue nanaman sa drainage, baha lagi oh', 'standby', '../uploads/reports/report_1779380790_4629eb212e422599.png', '2026-05-21 16:26:30', '2026-05-22 15:47:26'),
(33, 10, 'Ayala', 'Streetlight', 'Streetlight is broken', 'standby', '../uploads/reports/report_1779444871_b4900963dec4d6bb.png', '2026-05-22 10:14:31', '2026-05-22 18:14:31');

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
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_reports_user` (`user_id`);

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
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
