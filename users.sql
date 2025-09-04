-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 10:04 PM
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
-- Database: `blood_donation`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','donor') DEFAULT 'donor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `blood_group` varchar(5) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `blood_group`, `phone`) VALUES
(1, 'Admin User', 'admin@example.com', 'admin123', 'admin', '2025-08-24 19:00:51', NULL, NULL),
(2, 'John Doe', 'john@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'O-', '08012345605'),
(3, 'Jane Smith', 'jane@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'AB+', '08012345604'),
(4, 'Bob Lee', 'bob@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'A+', '08012345602'),
(5, 'Alice Kim', 'alice@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'O+', '08012345601'),
(6, 'Tom Clark', 'tom@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'A+', '08012345610'),
(7, 'Lucy Gray', 'lucy@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'A-', '08012345606'),
(8, 'Mark Hill', 'mark@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'B+', '08012345607'),
(9, 'Nina Rose', 'nina@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'AB-', '08012345608'),
(10, 'Sam White', 'sam@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'O+', '08012345609'),
(11, 'Emma Black', 'emma@example.com', 'pass123', 'donor', '2025-08-24 19:00:51', 'B-', '08012345603');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
