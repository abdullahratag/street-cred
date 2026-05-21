-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 04:48 PM
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
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`ID`, `user_id`, `barangay`, `category`, `description`, `status`, `image_path`, `date_submitted`) VALUES
(1, 1, 'Tetuan', 'Road/Pothole', 'Massive pothole forming near the corner of MCLL Highway. Drivers are swerving aggressively to avoid it.', 'Resolved', '', '2026-05-20 10:17:28'),
(2, 2, 'Tetuan', 'Streetlight', 'Three consecutive streetlights are completely dead along Gov. Ramos Ave, leaving the road pitch black at night.', 'In progress', '', '2026-05-20 10:17:28'),
(3, 3, 'Tetuan', 'Waste Management', 'Uncollected garbage bags piling up near the Tetuan Elementary School perimeter wall, attracting stray animals.', 'standby', '', '2026-05-20 10:17:28'),
(4, 4, 'Tetuan', 'Drainage Issues', 'The main drainage canal along Don Alfaro St is heavily clogged with plastic sediment, causing water overflow during rain.', 'standby', '', '2026-05-20 10:17:28'),
(5, 5, 'Tetuan', 'Other', 'Low water pressure issues reported near the local health center since early this morning without notice.', 'standby', '', '2026-05-20 10:17:28'),
(6, 6, 'Tetuan', 'Road/Pothole', 'Cracked and uneven concrete slab on the main road heading up towards Pasonanca Park, causing motorcycle hazards.', 'In progress', '', '2026-05-20 10:17:28'),
(7, 7, 'Tetuan', 'Infrastructure', 'Dangling telecom and internet cables are hanging dangerously close to passing cargo trucks and delivery vans.', 'standby', '', '2026-05-20 10:17:28'),
(8, 8, 'Tetuan', 'Waste Management', 'An illegal open dumping site is rapidly expanding near the local riverside pathway. Strong odor present.', 'standby', '', '2026-05-20 10:17:28'),
(9, 9, 'Tetuan', 'Traffic Concern', 'Unfinished culvert work has narrow lanes blocked near a busy intersection, causing major rush-hour gridlock.', 'In progress', '', '2026-05-20 10:17:28'),
(20, 1, 'Tetuan', 'Traffic Concern', 'Heavy traffic congestion near the intersection of Gov. Ramos Ave due to malfunctioning traffic signals.', 'Reported to LGU', '', '2026-05-20 10:18:19'),
(21, 3, 'Tetuan', 'Security Issue', 'Report of missing manhole covers along the dark walkways of Don Alfaro St, creating a serious safety hazard.', 'In progress', '', '2026-05-20 10:18:19'),
(22, 5, 'Tetuan', 'Infrastructure', 'The public footbridge railings near the canal are severely rusted and breaking apart. Needs immediate welding.', 'Reported to LGU', '', '2026-05-20 10:18:19'),
(23, 2, 'Tetuan', 'Traffic Concern', 'Illegal double parking along the narrow secondary highways is causing massive bottlenecks during rush hour.', 'Resolved', '', '2026-05-20 10:18:19'),
(24, 4, 'Tetuan', 'Security Issue', 'Loitering and lack of visible barangay tanod patrols near the dark alleyways behind the public market after 10 PM.', 'standby', '', '2026-05-20 10:18:19'),
(25, 6, 'Tetuan', 'Drainage Issues', 'Culvert drainage breakdown has left stagnant water flooding a portion of the access street heading to the plaza.', 'standby', '', '2026-05-20 10:18:19'),
(26, 8, 'Tetuan', 'Road/Pothole', 'Deep asphalt cracks and depressions stretching across the main lane near the river bridge approach.', 'In progress', '', '2026-05-20 10:18:19'),
(27, 7, 'Tetuan', 'Infrastructure', 'A public concrete bench inside the community health center waiting zone has collapsed completely.', 'Resolved', '', '2026-05-20 10:18:19'),
(28, 9, 'Tetuan', 'Other', 'Stray wires from an abandoned commercial signage setup are hanging loose over the pedestrian sidewalk.', 'standby', '', '2026-05-20 10:18:19');

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
  `password_hash` varchar(255) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `first_name`, `last_name`, `phone_number`, `email`, `role`, `password_hash`, `barangay`, `created_at`) VALUES
(1, 'Marcus', 'Go', '09123456789', 'marcuse@streetcred.gov', 'barangay_admin', '$2y$10$VPyjeKBE0xspWlvQBEwh9Od49Z/RUzjCHmwxdNwcEcklPjqu2/SRy', 'Tetuan', '2026-05-19 17:45:57'),
(2, 'Willen', 'Santuyo', '09234567890', 'willen.tetuan@streetcred.gov', 'barangay_admin', '$2y$10$abcdefghijklmnopqrstuvwx1234567890abcdefghi...', 'Tetuan', '2026-05-19 17:45:57'),
(3, 'Abdullah', 'Ratag', '09345678901', 'abdullah.stamaria@streetcred.gov', 'barangay_admin', '$2y$10$abcdefghijklmnopqrstuvwx1234567890abcdefghi...', 'Sta. Maria', '2026-05-19 17:45:57'),
(4, 'Marc', 'Arbilera', '09456789012', 'marc.lgu@zamboangacity.gov', 'lgu_admin', '$2y$10$fV3.vO9hFis9Sj3yqfE6be0E8S1jGZ15Z7Miz93v8/4KREf6QZByS\r\n\r\n', 'Tetuan\r\n', '2026-05-19 17:45:57'),
(5, 'Elen', 'Cruz', '09567890123', 'marcus.citizen@gmail.com', 'citizen', '$2y$10$cajjpYkI7FEMKhsJRMAcYu80lnxuKbqOv2Nv0NQoRwNoSmel4K2hS', 'Tetuan', '2026-05-19 17:45:57'),
(6, 'Juan', 'Dela Cruz', '09678901234', 'juan.delacruz@gmail.com', 'citizen', '$2y$10$abcdefghijklmnopqrstuvwx1234567890abcdefghi...', 'Tetuan', '2026-05-19 17:45:57'),
(7, 'Maria', 'Clara', '09789012345', 'maria.clara@gmail.com', 'citizen', '$2y$10$abcdefghijklmnopqrstuvwx1234567890abcdefghi...', 'Sta. Maria', '2026-05-19 17:45:57'),
(8, 'Pedro', 'Penduko', '09890123456', 'pedro.penduko@gmail.com', 'citizen', '$2y$10$abcdefghijklmnopqrstuvwx1234567890abcdefghi...', 'Talon-Talon', '2026-05-19 17:45:57'),
(9, 'Test', 'User', '09901234567', 'test.citizen@gmail.com', 'citizen', '$2y$10$abcdefghijklmnopqrstuvwx1234567890abcdefghi...', 'Sta. Maria', '2026-05-19 17:45:57');

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
