-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 04:46 PM
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
-- Database: `recycling_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

CREATE TABLE `admin_activity_log` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` enum('Approve User','Block User','Unblock User','Delete User','Delete Waste','Delete Request') DEFAULT NULL,
  `target_id` int(11) NOT NULL,
  `target_type` enum('User','Waste','Request') DEFAULT NULL,
  `action_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `admin_dashboard_stats`
-- (See below for the actual view)
--
CREATE TABLE `admin_dashboard_stats` (
`total_users` bigint(21)
,`total_waste_items` bigint(21)
,`total_buy_requests` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `buy_requests`
--

CREATE TABLE `buy_requests` (
  `request_id` int(11) NOT NULL,
  `waste_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `status` enum('Pending','Accepted','Rejected','Cancelled') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_history`
--

CREATE TABLE `collection_history` (
  `history_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_requests`
--

CREATE TABLE `collection_requests` (
  `collection_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `pickup_status` enum('Assigned','Picked Up','Completed','Issue') DEFAULT 'Assigned',
  `pickup_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(4, 'Collection Agent'),
(3, 'System Admin'),
(2, 'Waste Buyer'),
(1, 'Waste Seller');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `is_blocked` tinyint(1) DEFAULT 0,
  `availability_status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `full_name`, `email`, `phone`, `password_hash`, `address`, `is_approved`, `is_blocked`, `availability_status`, `created_at`) VALUES
(1, 1, 'Siam Ahamed', 'siamahamedab@gmail.com', '01304984437', '$2y$10$nlysQJVMxuOA6vfLwBKQou7YtSqb/HTbrtVXqsfgmU8jvrVbN3EMe', NULL, 1, 0, 1, '2025-12-22 14:09:05'),
(2, 4, 'a', 'a@gmail.com', '32132', '$2y$10$k.a/VanyuDxB.ck7hyOdDOGhcrZP0DT/SavipjoOaPip.k1WWJcA6', NULL, 1, 0, 1, '2026-01-05 16:29:23'),
(6, 1, 'MD Ibrahim Khalil', 'ss@gmail.com', '3213243545', '$2y$10$X7tsmrCLHHem4cnt6oRXq.tVqLUZIhPdEEW7rmU.nMTEgbgH5Hnh.', NULL, 1, 0, 1, '2026-01-05 16:40:58'),
(10, 3, 'System Administrator', 'admin@gmail.com', '1234567890', '$2y$10$E3Y2T8CdQugWNgOGiT3HquPbYkqx8dp3DQjToTsUbKKdjzF.wNsp.', NULL, 1, 0, 1, '2026-01-19 14:29:27'),
(13, 1, 'se', 'se@gmail.com', '1324231123', '$2y$10$rxwly9F/Z1yU3pRc3W3/6Oa9Mn1MQpNg85jhtVq/VlKmb/pkrBCPu', NULL, 1, 0, 1, '2026-01-19 14:38:41'),
(14, 2, 'be', 'be@gmail.com', '3353453425324', '$2y$10$sNT0KBAP3d1WLA1mfl7Vn.x481EXRc8W2rYacsPxR3xUpwFV8PAuW', NULL, 1, 0, 1, '2026-01-19 14:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `waste_categories`
--

CREATE TABLE `waste_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_categories`
--

INSERT INTO `waste_categories` (`category_id`, `category_name`) VALUES
(5, 'Electronic Waste'),
(3, 'Glass'),
(4, 'Metal'),
(6, 'Organic Waste'),
(2, 'Paper'),
(1, 'Plastic');

-- --------------------------------------------------------

--
-- Table structure for table `waste_items`
--

CREATE TABLE `waste_items` (
  `waste_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Available','Requested','Sold') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `admin_dashboard_stats`
--
DROP TABLE IF EXISTS `admin_dashboard_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_dashboard_stats`  AS SELECT (select count(0) from `users`) AS `total_users`, (select count(0) from `waste_items`) AS `total_waste_items`, (select count(0) from `buy_requests`) AS `total_buy_requests` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `buy_requests`
--
ALTER TABLE `buy_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `waste_id` (`waste_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `collection_history`
--
ALTER TABLE `collection_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `collection_requests`
--
ALTER TABLE `collection_requests`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `waste_categories`
--
ALTER TABLE `waste_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `waste_items`
--
ALTER TABLE `waste_items`
  ADD PRIMARY KEY (`waste_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buy_requests`
--
ALTER TABLE `buy_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_history`
--
ALTER TABLE `collection_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_requests`
--
ALTER TABLE `collection_requests`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `waste_categories`
--
ALTER TABLE `waste_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `waste_items`
--
ALTER TABLE `waste_items`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD CONSTRAINT `admin_activity_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `buy_requests`
--
ALTER TABLE `buy_requests`
  ADD CONSTRAINT `buy_requests_ibfk_1` FOREIGN KEY (`waste_id`) REFERENCES `waste_items` (`waste_id`),
  ADD CONSTRAINT `buy_requests_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `collection_history`
--
ALTER TABLE `collection_history`
  ADD CONSTRAINT `collection_history_ibfk_1` FOREIGN KEY (`collection_id`) REFERENCES `collection_requests` (`collection_id`);

--
-- Constraints for table `collection_requests`
--
ALTER TABLE `collection_requests`
  ADD CONSTRAINT `collection_requests_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `buy_requests` (`request_id`),
  ADD CONSTRAINT `collection_requests_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `waste_items`
--
ALTER TABLE `waste_items`
  ADD CONSTRAINT `waste_items_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `waste_items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `waste_categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
