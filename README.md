-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 15, 2024 at 10:23 PM
-- Server version: 8.0.31
-- PHP Version: 7.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ProbationTest`
--

-- --------------------------------------------------------

--
-- Table structure for table `checklists`
--

CREATE TABLE `checklists` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `priority` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `accessLevel` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checklists`
--

INSERT INTO `checklists` (`id`, `title`, `date_from`, `date_to`, `status`, `priority`, `category`, `comment`, `accessLevel`, `description`, `created_at`, `updated_at`, `is_active`) VALUES
(12, 'Checklist 1', '2024-05-20', '2024-05-20', 'active', '#28a745', 'c1', 'this is comment', '0', 'this is description', '2024-05-15 22:14:06', '2024-05-15 22:14:06', 1),
(13, 'checklist 2', '2024-05-21', '2024-05-22', 'active', '#ffc107', 'c2', 'this is comment', '1', 'this is description', '2024-05-15 22:17:05', '2024-05-15 22:17:05', 1),
(14, 'checklist3', '2024-05-23', '2024-05-24', 'active', '#28a745', 'c1', 'this is comment', '0', 'this is description', '2024-05-15 22:19:16', '2024-05-15 22:19:16', 1),
(15, 'checklist 4', '2024-05-16', '2024-05-20', 'active', '#dc3545', 'c1', 'this is comment', '0', 'this is description', '2024-05-15 22:20:46', '2024-05-15 22:20:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `title`, `start_date`, `end_date`, `category`, `created_at`, `is_active`) VALUES
(3, 'Holi Leave', '2024-05-20', '2024-05-20', 'plan', '2024-05-15 22:15:20', 1),
(4, 'Diwali leave', '2024-05-21', '2024-05-24', 'plan', '2024-05-15 22:15:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_checklists`
--

CREATE TABLE `sub_checklists` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sub_checklist_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `status` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `priority` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_by` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `checklist_id` int NOT NULL,
  `is_active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_checklists`
--

INSERT INTO `sub_checklists` (`id`, `title`, `sub_checklist_date`, `description`, `status`, `priority`, `category`, `comment`, `created_by`, `updated_by`, `checklist_id`, `is_active`) VALUES
(10, 'subChecklist 1', '2024-05-20', 'this is description', 'active', '#28a745', 'c1', 'this is comment', '2024-05-15 22:14:06', '2024-05-15 22:14:06', 12, 1),
(11, 'subchecklist 2', '2024-05-21', 'this is description', 'active', '#dc3545', 'c1', 'hello', '2024-05-15 22:17:05', '2024-05-15 22:17:05', 13, 1),
(12, 'subchecklist 3', '2024-05-22', 'this is description', 'active', '#28a745', 'c1', 'this is comment', '2024-05-15 22:19:16', '2024-05-15 22:19:16', 14, 1),
(13, 'subchecklist 3', '2024-05-20', 'this is description', 'active', '#28a745', 'c1', 'this is description', '2024-05-15 22:20:46', '2024-05-15 22:20:46', 15, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checklists`
--
ALTER TABLE `checklists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_checklists`
--
ALTER TABLE `sub_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklist_id` (`checklist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checklists`
--
ALTER TABLE `checklists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_checklists`
--
ALTER TABLE `sub_checklists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sub_checklists`
--
ALTER TABLE `sub_checklists`
  ADD CONSTRAINT `sub_checklists_ibfk_1` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
