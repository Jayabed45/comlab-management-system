-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 06:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comlab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `message`, `date_posted`) VALUES
(1, 'hoi', 'ang pc ayaw e kaomn', '2025-05-29 00:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_reads`
--

CREATE TABLE `announcement_reads` (
  `read_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `read_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_reports`
--

CREATE TABLE `maintenance_reports` (
  `report_id` int(11) NOT NULL,
  `pc_id` int(11) DEFAULT NULL,
  `reported_by` int(11) DEFAULT NULL,
  `report_text` text DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_reports`
--

INSERT INTO `maintenance_reports` (`report_id`, `pc_id`, `reported_by`, `report_text`, `report_date`) VALUES
(1, 1, 2, 'gubang mouse amaw', '2025-05-28 14:36:44'),
(2, 1, 2, 'guba ang monitor', '2025-05-28 14:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `pcs`
--

CREATE TABLE `pcs` (
  `pc_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `pc_number` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `used_by` int(11) DEFAULT NULL,
  `cpu` varchar(100) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `storage` varchar(100) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pcs`
--

INSERT INTO `pcs` (`pc_id`, `room_id`, `pc_number`, `is_active`, `used_by`, `cpu`, `ram`, `storage`, `gpu`, `os`) VALUES
(1, 1, 1, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(2, 2, 1, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(3, 3, 1, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(4, 4, 1, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(5, 1, 2, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(6, 2, 2, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(7, 3, 2, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(8, 4, 2, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(9, 1, 3, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(10, 2, 3, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(11, 3, 3, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(12, 4, 3, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(13, 1, 4, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(14, 2, 4, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(15, 3, 4, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(16, 4, 4, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(17, 1, 5, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(18, 2, 5, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(19, 3, 5, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(20, 4, 5, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(21, 1, 6, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(22, 2, 6, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(23, 3, 6, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(24, 4, 6, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(25, 1, 7, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(26, 2, 7, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(27, 3, 7, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(28, 4, 7, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(29, 1, 8, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(30, 2, 8, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(31, 3, 8, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(32, 4, 8, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(33, 1, 9, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(34, 2, 9, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(35, 3, 9, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(36, 4, 9, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(37, 1, 10, 1, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(38, 2, 10, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(39, 3, 10, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10'),
(40, 4, 10, 0, NULL, 'Intel i5', '8GB', '256GB SSD', 'Intel UHD', 'Windows 10');

-- --------------------------------------------------------

--
-- Table structure for table `pc_usage_logs`
--

CREATE TABLE `pc_usage_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pc_id` int(11) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_number`) VALUES
(1, '101'),
(2, '102'),
(3, '103'),
(4, '104');

-- --------------------------------------------------------

--
-- Table structure for table `usage_logs`
--

CREATE TABLE `usage_logs` (
  `usage_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pc_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usage_logs`
--

INSERT INTO `usage_logs` (`usage_log_id`, `user_id`, `pc_id`, `login_time`, `logout_time`) VALUES
(1, 2, 1, '2025-05-28 15:47:35', '2025-05-28 15:47:36'),
(2, 2, 1, '2025-05-28 06:26:21', '2025-05-28 06:29:51'),
(3, 2, 68, '2025-05-28 06:30:15', '2025-05-28 06:31:19'),
(4, 2, 1, '2025-05-28 06:31:37', '2025-05-28 06:31:39'),
(5, 2, 1, '2025-05-28 06:35:33', '2025-05-28 06:38:37'),
(6, 2, 1, '2025-05-28 06:39:27', '2025-05-31 19:59:25'),
(7, 2, 1, '2025-05-31 20:04:23', '2025-05-31 20:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','student') DEFAULT 'student',
  `course` varchar(50) DEFAULT NULL,
  `year` varchar(50) DEFAULT NULL,
  `section` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `role`, `course`, `year`, `section`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$FleM1dryyvj9behszu0/Q.fGYtRPpAZlLG049eZzgexQiesqmglTW', 'admin', NULL, NULL, NULL),
(2, 'Carmel', 'carmel@gmail.com', '$2y$10$9NaNIa8kZ4hA9pVhDT29UuJyUXpoPEoGHCEPsVTenU5nT1HBmkW4S', 'student', 'BSIT', '2nd year', 'B');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  ADD PRIMARY KEY (`read_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `maintenance_reports`
--
ALTER TABLE `maintenance_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `pc_id` (`pc_id`),
  ADD KEY `reported_by` (`reported_by`);

--
-- Indexes for table `pcs`
--
ALTER TABLE `pcs`
  ADD PRIMARY KEY (`pc_id`),
  ADD UNIQUE KEY `unique_room_pc` (`room_id`,`pc_number`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `used_by` (`used_by`);

--
-- Indexes for table `pc_usage_logs`
--
ALTER TABLE `pc_usage_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pc_id` (`pc_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `usage_logs`
--
ALTER TABLE `usage_logs`
  ADD PRIMARY KEY (`usage_log_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pc_id` (`pc_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  MODIFY `read_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_reports`
--
ALTER TABLE `maintenance_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pcs`
--
ALTER TABLE `pcs`
  MODIFY `pc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `pc_usage_logs`
--
ALTER TABLE `pc_usage_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usage_logs`
--
ALTER TABLE `usage_logs`
  MODIFY `usage_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  ADD CONSTRAINT `announcement_reads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `announcement_reads_ibfk_2` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`announcement_id`);

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `maintenance_reports`
--
ALTER TABLE `maintenance_reports`
  ADD CONSTRAINT `maintenance_reports_ibfk_1` FOREIGN KEY (`pc_id`) REFERENCES `pcs` (`pc_id`),
  ADD CONSTRAINT `maintenance_reports_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `pcs`
--
ALTER TABLE `pcs`
  ADD CONSTRAINT `pcs_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `pcs_ibfk_2` FOREIGN KEY (`used_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pcs_ibfk_3` FOREIGN KEY (`used_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `pc_usage_logs`
--
ALTER TABLE `pc_usage_logs`
  ADD CONSTRAINT `pc_usage_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pc_usage_logs_ibfk_2` FOREIGN KEY (`pc_id`) REFERENCES `pcs` (`pc_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
