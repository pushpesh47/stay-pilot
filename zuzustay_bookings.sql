-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 29, 2026 at 07:07 AM
-- Server version: 10.6.25-MariaDB-cll-lve
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zuzustay_bookings`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive','trashed') NOT NULL DEFAULT 'active',
  `added_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `remember_token`, `role_id`, `status`, `added_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, NULL, '$2y$12$eHsFf3G06oDkKhuiCkTXoOWQCtgYSPt/mgFOheMcK/I1hDObaPpYq', NULL, 1, 'active', NULL, '2025-02-18 01:29:53', '2026-04-13 22:52:45', NULL),
(2, 'Ashish', 'ashish@gmail.com', '7675432123', NULL, '$2y$12$V/IgHQ4qHY40AQDtE8Dlh.vPj8R7APaOA9MP8w5JA7RbbJZVc9HJS', NULL, 2, 'active', '1', '2026-04-09 04:55:00', '2026-04-09 04:55:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `status`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'WiFi', NULL, 'active', 0, '2026-04-13 04:13:10', '2026-04-13 04:13:10', NULL),
(2, 'AC', NULL, 'active', 0, '2026-04-13 04:13:16', '2026-04-13 04:13:16', NULL),
(3, 'Parking', NULL, 'active', 0, '2026-04-13 04:13:25', '2026-04-13 04:13:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:6:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:7:\"heading\";s:1:\"c\";s:4:\"name\";s:1:\"d\";s:5:\"title\";s:1:\"e\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:36:{i:0;a:6:{s:1:\"a\";s:1:\"1\";s:1:\"b\";s:11:\"Manage Role\";s:1:\"c\";s:11:\"role.create\";s:1:\"d\";s:6:\"Create\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:6:{s:1:\"a\";s:1:\"2\";s:1:\"b\";s:11:\"Manage Role\";s:1:\"c\";s:9:\"role.view\";s:1:\"d\";s:4:\"View\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:6:{s:1:\"a\";s:1:\"3\";s:1:\"b\";s:11:\"Manage Role\";s:1:\"c\";s:9:\"role.edit\";s:1:\"d\";s:4:\"Edit\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:6:{s:1:\"a\";s:1:\"4\";s:1:\"b\";s:11:\"Manage Role\";s:1:\"c\";s:11:\"role.delete\";s:1:\"d\";s:6:\"Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:6:{s:1:\"a\";s:1:\"5\";s:1:\"b\";s:18:\"Manage Admin Users\";s:1:\"c\";s:14:\"adminuser.view\";s:1:\"d\";s:4:\"View\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:6:{s:1:\"a\";s:1:\"6\";s:1:\"b\";s:18:\"Manage Admin Users\";s:1:\"c\";s:16:\"adminuser.create\";s:1:\"d\";s:6:\"Create\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:6:{s:1:\"a\";s:1:\"7\";s:1:\"b\";s:18:\"Manage Admin Users\";s:1:\"c\";s:14:\"adminuser.edit\";s:1:\"d\";s:4:\"Edit\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:6:{s:1:\"a\";s:1:\"8\";s:1:\"b\";s:18:\"Manage Admin Users\";s:1:\"c\";s:16:\"adminuser.delete\";s:1:\"d\";s:6:\"Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:6:{s:1:\"a\";s:1:\"9\";s:1:\"b\";s:18:\"Manage Admin Users\";s:1:\"c\";s:16:\"adminuser.status\";s:1:\"d\";s:6:\"Status\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:6:{s:1:\"a\";s:2:\"10\";s:1:\"b\";s:18:\"Manage Admin Users\";s:1:\"c\";s:22:\"adminuser.show-profile\";s:1:\"d\";s:12:\"View Profile\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:6:{s:1:\"a\";s:2:\"11\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:13:\"property.view\";s:1:\"d\";s:4:\"View\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:6:{s:1:\"a\";s:2:\"12\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:15:\"property.create\";s:1:\"d\";s:6:\"Create\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:6:{s:1:\"a\";s:2:\"13\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:13:\"property.edit\";s:1:\"d\";s:4:\"Edit\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:6:{s:1:\"a\";s:2:\"14\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:15:\"property.delete\";s:1:\"d\";s:6:\"Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:6:{s:1:\"a\";s:2:\"15\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:15:\"property.status\";s:1:\"d\";s:6:\"Status\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:6:{s:1:\"a\";s:2:\"16\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:15:\"property.images\";s:1:\"d\";s:10:\"Add Images\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:6:{s:1:\"a\";s:2:\"17\";s:1:\"b\";s:15:\"Manage Property\";s:1:\"c\";s:21:\"propertyimages.delete\";s:1:\"d\";s:12:\"Image Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:6:{s:1:\"a\";s:2:\"18\";s:1:\"b\";s:14:\"Manage Amenity\";s:1:\"c\";s:14:\"amenity.create\";s:1:\"d\";s:6:\"Create\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:6:{s:1:\"a\";s:2:\"19\";s:1:\"b\";s:14:\"Manage Amenity\";s:1:\"c\";s:12:\"amenity.view\";s:1:\"d\";s:4:\"View\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:6:{s:1:\"a\";s:2:\"20\";s:1:\"b\";s:14:\"Manage Amenity\";s:1:\"c\";s:12:\"amenity.edit\";s:1:\"d\";s:4:\"Edit\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:6:{s:1:\"a\";s:2:\"21\";s:1:\"b\";s:14:\"Manage Amenity\";s:1:\"c\";s:14:\"amenity.delete\";s:1:\"d\";s:6:\"Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:6:{s:1:\"a\";s:2:\"22\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:22:\"offline-booking.create\";s:1:\"d\";s:6:\"Create\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:6:{s:1:\"a\";s:2:\"23\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:20:\"offline-booking.view\";s:1:\"d\";s:4:\"View\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:6:{s:1:\"a\";s:2:\"24\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:20:\"offline-booking.edit\";s:1:\"d\";s:4:\"Edit\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:6:{s:1:\"a\";s:2:\"25\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:22:\"offline-booking.delete\";s:1:\"d\";s:6:\"Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:6:{s:1:\"a\";s:2:\"26\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:24:\"offline-booking.extended\";s:1:\"d\";s:16:\"Booking Extended\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:6:{s:1:\"a\";s:2:\"27\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:34:\"offline-booking.payremainingamount\";s:1:\"d\";s:20:\"Pay Remaining Amount\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:6:{s:1:\"a\";s:2:\"28\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:30:\"offline-booking.paymenthistory\";s:1:\"d\";s:15:\"Payment History\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:6:{s:1:\"a\";s:2:\"29\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:27:\"offline-booking.exportexcel\";s:1:\"d\";s:12:\"Export Excel\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:6:{s:1:\"a\";s:2:\"30\";s:1:\"b\";s:15:\"Offline Booking\";s:1:\"c\";s:25:\"offline-booking.exportcsv\";s:1:\"d\";s:10:\"Export Csv\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:6:{s:1:\"a\";s:2:\"31\";s:1:\"b\";s:14:\"Manage Expense\";s:1:\"c\";s:14:\"expense.create\";s:1:\"d\";s:6:\"Create\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:6:{s:1:\"a\";s:2:\"32\";s:1:\"b\";s:14:\"Manage Expense\";s:1:\"c\";s:12:\"expense.view\";s:1:\"d\";s:4:\"View\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:6:{s:1:\"a\";s:2:\"33\";s:1:\"b\";s:14:\"Manage Expense\";s:1:\"c\";s:12:\"expense.edit\";s:1:\"d\";s:4:\"Edit\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:6:{s:1:\"a\";s:2:\"34\";s:1:\"b\";s:14:\"Manage Expense\";s:1:\"c\";s:14:\"expense.delete\";s:1:\"d\";s:6:\"Delete\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:6:{s:1:\"a\";s:2:\"35\";s:1:\"b\";s:14:\"Manage Expense\";s:1:\"c\";s:19:\"expense.exportexcel\";s:1:\"d\";s:12:\"Export Excel\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:6:{s:1:\"a\";s:2:\"36\";s:1:\"b\";s:14:\"Manage Expense\";s:1:\"c\";s:17:\"expense.exportcsv\";s:1:\"d\";s:10:\"Export Csv\";s:1:\"e\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";s:1:\"1\";s:1:\"c\";s:5:\"Admin\";s:1:\"e\";s:5:\"admin\";}i:1;a:3:{s:1:\"a\";s:1:\"2\";s:1:\"c\";s:5:\"Staff\";s:1:\"e\";s:5:\"admin\";}}}', 1780149894);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'active',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `slug`, `status`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Lucknow', 'lucknow', 'active', 1, NULL, '2026-04-04 07:39:48', '2026-04-04 07:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `room_no` varchar(25) DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `amount`, `room_no`, `received_by`, `expense_date`, `payment_mode`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Internet recharge', 707.00, '302', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:39:40', '2026-05-01 09:43:23', NULL),
(2, 'Internet recharge', 707.00, '910', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:39:57', '2026-05-01 16:39:57', NULL),
(3, 'Internet recharge', 707.00, '904', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:40:25', '2026-05-01 16:40:25', NULL),
(4, 'Internet recharge', 707.00, '917', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:41:10', '2026-05-01 16:41:10', NULL),
(5, 'Internet recharge', 707.00, '1130', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:41:30', '2026-05-01 16:41:30', NULL),
(6, 'Internet recharge', 707.00, '1121', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:41:51', '2026-05-01 16:41:51', NULL),
(7, 'Rent', 14500.00, '1129', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:44:36', '2026-05-01 16:44:36', NULL),
(8, 'Rent', 14500.00, '1130', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:47:34', '2026-05-01 16:47:34', NULL),
(9, 'Rent', 23000.00, '1129', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:48:34', '2026-05-01 16:48:34', NULL),
(10, 'Rent', 13650.00, '302', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:48:56', '2026-05-01 16:48:56', NULL),
(11, 'Rent', 22000.00, '1121', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:53:02', '2026-05-01 16:53:02', NULL),
(12, 'Rent', 18000.00, '910', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:53:25', '2026-05-01 16:53:25', NULL),
(13, 'Rent', 25000.00, '904', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:53:45', '2026-05-01 16:53:45', NULL),
(14, 'Rent', 25000.00, '917', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 16:57:42', '2026-05-01 16:57:42', NULL),
(15, 'Dental Kit', 200.00, '1129', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:06:21', '2026-05-01 17:06:21', NULL),
(16, 'Dental Kit', 200.00, '1130', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:07:34', '2026-05-01 17:07:34', NULL),
(17, 'Dental Kit', 200.00, '1029', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:08:04', '2026-05-01 17:08:04', NULL),
(18, 'Dental Kit', 200.00, '302', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:13:50', '2026-05-01 17:13:50', NULL),
(19, 'Dental Kit', 200.00, '1121', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:14:20', '2026-05-01 17:14:20', NULL),
(20, 'Dental Kit', 200.00, '910', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:14:35', '2026-05-01 17:14:35', NULL),
(21, 'Dental Kit', 200.00, '904', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:14:56', '2026-05-01 17:14:56', NULL),
(22, 'Dental Kit', 200.00, '917', NULL, '2026-05-01', 'gpay', NULL, 1, '2026-05-01 17:15:14', '2026-05-01 17:15:14', NULL),
(23, 'Water', 1100.00, NULL, NULL, '2026-05-09', 'gpay', NULL, 1, '2026-05-11 12:29:07', '2026-05-11 12:29:07', NULL),
(24, 'May Maintainence', 3333.50, '910', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:48:33', '2026-05-13 18:48:33', NULL),
(25, 'May Maintainence', 3333.50, '917', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:48:49', '2026-05-13 18:48:49', NULL),
(26, 'May Maintainence', 3333.50, '904', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:49:06', '2026-05-13 18:49:06', NULL),
(27, 'May Maintainence', 3333.50, '1029', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:49:21', '2026-05-13 11:50:57', NULL),
(28, 'May Maintainence', 3333.50, '1121', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:49:46', '2026-05-13 18:49:46', NULL),
(29, 'May Maintainence', 3333.50, '1129', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:50:02', '2026-05-13 18:50:02', NULL),
(30, 'May Maintainence', 3333.50, '1130', NULL, '2026-05-13', 'gpay', NULL, 1, '2026-05-13 18:50:17', '2026-05-13 18:50:17', NULL),
(31, 'Recharhe done Raj', 900.00, NULL, NULL, '2026-05-27', 'gpay', NULL, 1, '2026-05-28 13:59:21', '2026-05-28 13:59:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1),
(1, 'App\\Models\\Admin', 7),
(2, 'App\\Models\\Admin', 2);

-- --------------------------------------------------------

--
-- Table structure for table `offline_bookings`
--

CREATE TABLE `offline_bookings` (
  `id` bigint(20) NOT NULL,
  `show_booking_id` varchar(255) DEFAULT NULL,
  `room_no` varchar(10) DEFAULT NULL,
  `total_guests` int(11) DEFAULT 2,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `source` enum('offline','airbnb','mmt','goibibo') DEFAULT 'offline',
  `payment_mode` enum('gpay','phonepe','netbanking','cash') DEFAULT 'netbanking',
  `cash_received_by` varchar(100) DEFAULT NULL,
  `per_day_price` decimal(10,2) DEFAULT NULL,
  `booking_days` int(11) NOT NULL,
  `transferred_to_owner` enum('yes','no') DEFAULT 'no',
  `owner_payment_screenshot` varchar(255) DEFAULT NULL,
  `early_checkin_charges` decimal(10,2) DEFAULT 0.00,
  `late_checkout_charges` decimal(10,2) DEFAULT 0.00,
  `damage_charges` decimal(10,2) DEFAULT 0.00,
  `total_charges` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `added_by` int(11) NOT NULL,
  `by_refernece` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offline_bookings`
--

INSERT INTO `offline_bookings` (`id`, `show_booking_id`, `room_no`, `total_guests`, `total_amount`, `paid_amount`, `check_in`, `check_out`, `source`, `payment_mode`, `cash_received_by`, `per_day_price`, `booking_days`, `transferred_to_owner`, `owner_payment_screenshot`, `early_checkin_charges`, `late_checkout_charges`, `damage_charges`, `total_charges`, `status`, `added_by`, `by_refernece`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BOK68603', '1129', 2, 2100.00, 2100.00, '2026-05-01 12:00:00', '2026-05-02 12:00:00', 'offline', 'gpay', NULL, 2100.00, 1, 'yes', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-01 11:54:25', '2026-05-06 08:05:15', NULL),
(2, 'BOK57664', '904', 2, 2070.00, 2070.00, '2026-05-01 00:00:00', '2026-05-02 00:00:00', 'airbnb', 'gpay', NULL, 2070.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-01 17:24:20', '2026-05-01 17:41:14', NULL),
(3, 'BOK99093', '910', 2, 1863.00, 1863.00, '2026-05-01 13:00:00', '2026-05-02 10:00:00', 'airbnb', 'gpay', NULL, 1863.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-01 17:43:39', '2026-05-02 11:49:58', NULL),
(4, 'BOK68539', '1130', 1, 4000.00, 4000.00, '2026-05-01 12:00:00', '2026-05-03 10:00:00', 'offline', 'gpay', NULL, 2000.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, 'Afaq', NULL, '2026-05-01 20:23:27', '2026-05-02 14:19:38', NULL),
(5, 'BOK47517', '1029', 2, 2200.00, 2200.00, '2026-05-01 12:00:00', '2026-05-02 10:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-01 21:10:38', '2026-05-01 21:10:38', NULL),
(6, 'BOK74215', '904', 2, 2570.00, 2570.00, '2026-05-02 13:00:00', '2026-05-03 10:00:00', 'airbnb', 'gpay', NULL, 2070.00, 1, 'no', NULL, 500.00, NULL, NULL, 500.00, 'active', 0, NULL, NULL, '2026-05-02 11:49:33', '2026-05-02 11:49:33', NULL),
(7, 'BOK49433', '910', 2, 3615.60, 3615.60, '2026-05-02 13:00:00', '2026-05-04 10:00:00', 'airbnb', 'gpay', NULL, 1807.80, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-02 13:19:27', '2026-05-02 13:19:27', NULL),
(8, 'BOK91977', '917', 2, 2500.00, 2500.00, '2026-05-02 13:00:00', '2026-05-03 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-02 13:21:51', '2026-05-02 14:23:14', NULL),
(9, 'BOK68267', '1121', 2, 1500.00, 1500.00, '2026-05-01 12:00:00', '2026-05-02 12:00:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-02 14:06:24', '2026-05-04 13:00:46', NULL),
(10, 'BOK77778', '917', 2, 2300.00, 2300.00, '2026-05-03 12:00:00', '2026-05-04 10:00:00', 'offline', 'gpay', NULL, 2300.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 11:23:53', '2026-05-04 11:23:53', NULL),
(11, 'BOK65737', '917', 2, 2500.00, 2500.00, '2026-05-02 12:00:00', '2026-05-03 12:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 12:54:03', '2026-05-04 05:55:14', NULL),
(12, 'BOK62093', '917', 2, 9000.00, 9000.00, '2026-05-15 13:00:00', '2026-05-18 12:00:00', 'offline', 'gpay', NULL, 2500.00, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 12:59:33', '2026-05-18 13:49:23', NULL),
(13, 'BOK70575', '1129', 2, 2200.00, 2200.00, '2026-05-03 12:00:00', '2026-05-04 12:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 13:04:15', '2026-05-04 13:04:15', NULL),
(14, 'BOK62762', '1129', 2, 2000.00, 2000.00, '2026-05-02 12:00:00', '2026-05-03 12:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, 'Afaq ref', NULL, '2026-05-04 13:07:35', '2026-05-04 13:07:35', NULL),
(15, 'BOK22838', '1029', 2, 2511.00, 2511.00, '2026-05-05 05:00:00', '2026-05-06 10:00:00', 'mmt', 'gpay', NULL, 1911.00, 1, 'no', NULL, 600.00, NULL, NULL, 600.00, 'active', 0, NULL, NULL, '2026-05-04 13:22:23', '2026-05-04 06:28:03', NULL),
(16, 'BOK47675', '1129', 2, 1500.00, 1500.00, '2026-05-04 11:00:00', '2026-05-04 15:00:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 13:34:02', '2026-05-04 06:34:40', NULL),
(17, 'BOK84933', '1029', 2, 2438.00, 2438.00, '2026-05-03 14:00:00', '2026-05-04 10:00:00', 'airbnb', 'gpay', NULL, 2438.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 14:03:53', '2026-05-04 14:03:53', NULL),
(18, 'BOK26288', '904', 2, 1932.00, 1932.00, '2026-05-04 13:00:00', '2026-05-05 10:00:00', 'airbnb', 'gpay', NULL, 1932.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, 'Booked 904 via airbnb but Shifted in 1129 bcz of continuous construction  noise.', '2026-05-04 16:43:31', '2026-05-04 11:34:39', NULL),
(19, 'BOK48388', '917', 2, 2700.00, 2700.00, '2026-05-04 13:00:00', '2026-05-05 10:00:00', 'offline', 'gpay', NULL, 2700.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 17:27:17', '2026-05-04 19:00:26', NULL),
(20, 'BOK12131', '302', 2, 1713.00, 1713.00, '2026-05-04 20:00:00', '2026-05-05 10:00:00', 'mmt', 'gpay', NULL, 1713.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-04 22:34:23', '2026-05-04 22:34:23', NULL),
(21, 'BOK57128', '1129', 2, 1800.00, 1800.00, '2026-05-05 14:00:00', '2026-05-05 22:00:00', 'offline', 'gpay', NULL, 1800.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-05 16:31:54', '2026-05-05 16:31:54', NULL),
(22, 'BOK88547', '904', 2, 4700.00, 4700.00, '2026-05-05 12:00:00', '2026-05-07 12:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-05 18:15:50', '2026-05-05 21:47:45', NULL),
(23, 'BOK45881', '917', 2, 4700.00, 4700.00, '2026-05-05 12:00:00', '2026-05-07 12:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-05 18:17:41', '2026-05-05 21:49:11', NULL),
(24, 'BOK81040', '910', 2, 6000.00, 6000.00, '2026-05-05 12:00:00', '2026-05-08 10:00:00', 'offline', 'gpay', NULL, 2000.00, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-05 18:19:43', '2026-05-06 15:06:49', NULL),
(25, 'BOK32550', '1130', 2, 2500.00, 2500.00, '2026-05-05 20:00:00', '2026-05-06 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-05 21:40:51', '2026-05-06 14:40:19', NULL),
(26, 'BOK35307', '1121', 2, 2500.00, 2500.00, '2026-05-05 12:00:00', '2026-05-06 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-06 11:12:56', '2026-05-06 11:12:56', NULL),
(27, 'BOK28617', '1029', 2, 2600.00, 2600.00, '2026-05-06 10:00:00', '2026-05-07 10:00:00', 'offline', 'gpay', NULL, 2300.00, 1, 'yes', 'uploads/bookings/SFJbX5iqa4ZHEKjcPowIigGYTI6w8UThapP9v8ew.jpg', 300.00, NULL, NULL, 300.00, 'active', 0, NULL, NULL, '2026-05-06 14:35:12', '2026-05-06 14:35:12', NULL),
(28, 'BOK75246', '1130', 2, 1500.00, 1500.00, '2026-05-06 14:00:00', '2026-05-06 18:00:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-06 14:40:25', '2026-05-06 14:40:25', NULL),
(29, 'BOK74895', '1129', 2, 2000.00, 2000.00, '2026-05-06 12:00:00', '2026-05-06 18:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'yes', 'uploads/bookings/4f9Wrwcnl6x08q3unlehnuUf6YuocSItUhutmL09.webp', NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-06 15:02:55', '2026-05-06 15:02:55', NULL),
(30, 'BOK38230', '302', 2, 1711.00, 1711.00, '2026-05-07 10:00:00', '2026-05-08 10:00:00', 'mmt', 'gpay', NULL, 1711.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-07 11:55:58', '2026-05-07 11:55:58', NULL),
(31, 'BOK27491', '1129', 2, 1911.00, 1911.00, '2026-05-07 10:00:00', '2026-05-08 10:00:00', 'mmt', 'gpay', NULL, 1911.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-07 12:37:29', '2026-05-07 12:37:29', NULL),
(32, 'BOK21800', '904', 2, 6072.00, 6072.00, '2026-05-07 13:00:00', '2026-05-10 10:00:00', 'airbnb', 'gpay', NULL, 2024.00, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-07 12:43:36', '2026-05-07 12:43:36', NULL),
(33, 'BOK57919', '1130', 2, 2000.00, 2000.00, '2026-05-07 12:00:00', '2026-05-07 18:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-07 18:46:08', '2026-05-07 18:46:08', NULL),
(34, 'BOK83356', '910', 2, 6000.00, 6000.00, '2026-05-09 13:00:00', '2026-05-12 10:00:00', 'offline', 'gpay', NULL, 2000.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, 'Satyam Yadav', NULL, '2026-05-07 18:50:38', '2026-05-11 13:03:29', NULL),
(35, 'BOK44010', '1121', 2, 2500.00, 2500.00, '2026-05-07 13:00:00', '2026-05-08 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-07 18:54:42', '2026-05-07 18:54:42', NULL),
(36, 'BOK51005', '917', 2, 1886.00, 1886.00, '2026-05-07 19:00:00', '2026-05-08 10:00:00', 'airbnb', 'gpay', NULL, 1886.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-07 20:51:36', '2026-05-08 14:59:39', NULL),
(37, 'BOK15497', '917', 2, 4000.00, 1000.00, '2026-05-09 13:00:00', '2026-05-10 10:00:00', 'offline', 'gpay', NULL, 4000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-08 14:36:57', '2026-05-09 17:20:57', NULL),
(38, 'BOK56070', '1029', 2, 2400.00, 2400.00, '2026-05-08 13:00:00', '2026-05-09 10:00:00', 'offline', 'gpay', NULL, 2400.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-08 14:44:00', '2026-05-08 15:43:40', NULL),
(39, 'BOK67692', '1130', 1, 8822.00, 8822.00, '2026-05-08 13:00:00', '2026-05-11 12:00:00', 'mmt', 'gpay', NULL, 3822.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-08 14:46:14', '2026-05-11 12:53:01', NULL),
(40, 'BOK30002', '917', 2, 1886.00, 1886.00, '2026-05-08 13:00:00', '2026-05-09 10:00:00', 'airbnb', 'gpay', NULL, 1886.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-08 14:50:14', '2026-05-08 14:50:14', NULL),
(41, 'BOK12869', '302', 2, 1725.00, 1725.00, '2026-05-08 13:00:00', '2026-05-09 10:00:00', 'airbnb', 'gpay', NULL, 1725.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-08 14:52:30', '2026-05-08 14:52:30', NULL),
(42, 'BOK83051', '1129', 2, 4000.00, 4000.00, '2026-05-08 13:00:00', '2026-05-10 10:00:00', 'offline', 'gpay', NULL, 2000.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, 'Afaq', NULL, '2026-05-08 18:41:36', '2026-05-11 12:55:04', NULL),
(43, 'BOK90469', '1029', 2, 7500.00, 5000.00, '2026-05-09 13:00:00', '2026-05-12 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-09 17:12:28', '2026-05-11 15:44:51', NULL),
(44, 'BOK81469', '917', 2, 2700.00, 2700.00, '2026-05-11 13:00:00', '2026-05-12 10:00:00', 'offline', 'gpay', NULL, 2700.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-09 17:23:23', '2026-05-09 17:23:23', NULL),
(45, 'BOK76676', '1121', 1, 3772.00, 3772.00, '2026-05-08 23:00:00', '2026-05-10 10:00:00', 'airbnb', 'gpay', NULL, 1886.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-09 17:27:42', '2026-05-09 17:27:42', NULL),
(46, 'BOK89376', '1129', 2, 1911.00, 1911.00, '2026-05-10 13:00:00', '2026-05-11 10:00:00', 'mmt', 'gpay', NULL, 1911.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-11 12:58:18', '2026-05-11 12:58:18', NULL),
(47, 'BOK56298', '1130', 2, 2400.00, 2400.00, '2026-05-12 13:00:00', '2026-05-13 10:00:00', 'offline', 'gpay', NULL, 2400.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-12 13:43:32', '2026-05-12 13:43:32', NULL),
(48, 'BOK70142', '904', 2, 2200.00, 2200.00, '2026-05-12 13:00:00', '2026-05-13 10:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-12 14:30:44', '2026-05-12 14:30:44', NULL),
(49, 'BOK40544', '910', 2, 1500.00, 1500.00, '2026-05-12 15:00:00', '2026-05-12 17:00:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-12 17:12:28', '2026-05-12 17:12:28', NULL),
(50, 'BOK73493', '1129', 2, 1000.00, 0.00, '2026-05-12 12:00:00', '2026-05-12 18:00:00', 'offline', 'gpay', NULL, 1000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-12 17:26:49', '2026-05-12 17:26:49', NULL),
(51, 'BOK99992', '917', 2, 2000.00, 2000.00, '2026-05-13 13:00:00', '2026-05-13 19:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-13 17:31:57', '2026-05-13 17:31:57', NULL),
(52, 'BOK34718', '1029', 2, 1800.00, 1800.00, '2026-05-13 09:30:00', '2026-05-13 15:00:00', 'offline', 'gpay', NULL, 1800.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-13 17:48:21', '2026-05-13 17:48:21', NULL),
(53, 'BOK46314', '910', 2, 8000.00, 8000.00, '2026-05-13 13:00:00', '2026-05-17 10:00:00', 'offline', 'gpay', NULL, 2666.67, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-13 17:52:39', '2026-05-17 21:27:23', NULL),
(54, 'BOK23062', '904', 2, 2500.00, 2500.00, '2026-05-14 11:00:00', '2026-05-15 13:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-13 18:45:08', '2026-05-13 18:45:08', NULL),
(55, 'BOK62007', '1029', 1, 12500.00, 9900.00, '2026-05-13 16:00:00', '2026-05-18 12:00:00', 'offline', 'gpay', NULL, 2500.00, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-14 11:55:14', '2026-05-18 14:20:01', NULL),
(56, 'BOK39190', '1029', 2, 13200.00, 13200.00, '2026-05-21 13:00:00', '2026-05-27 10:00:00', 'offline', 'gpay', NULL, 2200.00, 6, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-14 12:00:41', '2026-05-22 21:20:51', NULL),
(57, 'BOK49504', '917', 2, 2000.00, 2000.00, '2026-05-14 13:00:00', '2026-05-14 19:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-14 13:31:13', '2026-05-14 13:31:13', NULL),
(58, 'BOK55987', '1129', 2, 2500.00, 2500.00, '2026-05-14 13:00:00', '2026-05-15 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-14 13:48:49', '2026-05-14 13:52:40', NULL),
(59, 'BOK87011', '1130', 2, 2000.00, 2000.00, '2026-05-14 13:00:00', '2026-05-15 10:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-15 14:49:44', '2026-05-15 14:54:07', NULL),
(60, 'BOK89535', '1129', 2, 2000.00, 2000.00, '2026-05-15 13:00:00', '2026-05-16 10:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-15 14:53:55', '2026-05-15 14:53:55', NULL),
(61, 'BOK32465', '1121', 1, 2000.00, 2000.00, '2026-05-15 13:00:00', '2026-05-16 10:00:00', 'offline', 'cash', 'zuzusty inc', 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-16 19:14:33', '2026-05-16 19:14:33', NULL),
(62, 'BOK97575', '904', 2, 2200.00, 2200.00, '2026-05-15 13:00:00', '2026-05-16 10:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-16 19:19:16', '2026-05-16 19:19:50', NULL),
(63, 'BOK65463', '1130', 2, 3800.00, 3800.00, '2026-05-15 13:00:00', '2026-05-17 10:00:00', 'offline', 'gpay', NULL, 1900.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-16 19:23:18', '2026-05-16 19:23:18', NULL),
(64, 'BOK47513', '302', 2, 1500.00, 1500.00, '2026-05-16 13:00:00', '2026-05-17 10:00:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, 'Ashish singh', NULL, '2026-05-17 21:35:49', '2026-05-17 21:35:49', NULL),
(65, 'BOK55192', '1129', 2, 6000.00, 0.00, '2026-05-18 13:00:00', '2026-05-21 10:00:00', 'offline', 'gpay', NULL, 2000.00, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-18 13:59:59', '2026-05-24 08:03:00', NULL),
(66, 'BOK77587', '1129', 2, 2000.00, 0.00, '2026-05-22 13:00:00', '2026-05-23 10:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-18 14:05:41', '2026-05-24 08:08:33', NULL),
(67, 'BOK33631', '910', 2, 1500.00, 1500.00, '2026-05-20 16:00:00', '2026-05-20 19:00:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-20 22:43:17', '2026-05-20 22:43:17', NULL),
(68, 'BOK30145', '917', 2, 2000.00, 2000.00, '2026-05-20 13:00:00', '2026-05-20 19:00:00', 'offline', 'cash', 'Sanjeet', 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-20 22:46:03', '2026-05-20 22:46:03', NULL),
(69, 'BOK24129', '904', 2, 2000.00, 2000.00, '2026-05-21 13:00:00', '2026-05-21 19:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-22 13:07:33', '2026-05-22 13:07:33', NULL),
(70, 'BOK26110', '910', 2, 9000.00, 9000.00, '2026-05-21 13:00:00', '2026-05-25 18:00:00', 'offline', 'gpay', NULL, 2000.00, 3, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-22 13:08:40', '2026-05-26 13:58:36', NULL),
(71, 'BOK21510', '1129', 2, 4400.00, 4400.00, '2026-05-23 13:00:00', '2026-05-25 10:00:00', 'offline', 'gpay', NULL, 2200.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-22 21:24:04', '2026-05-22 21:24:04', NULL),
(72, 'BOK26065', '904', 2, 2000.00, 2000.00, '2026-05-22 13:00:00', '2026-05-22 19:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-22 21:26:44', '2026-05-22 21:26:44', NULL),
(73, 'BOK23899', '917', 2, 5000.00, 5000.00, '2026-05-23 13:00:00', '2026-05-25 10:00:00', 'offline', 'gpay', NULL, 2500.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-22 21:29:43', '2026-05-22 21:33:57', NULL),
(74, 'BOK27218', '904', 2, 2500.00, 2500.00, '2026-05-23 13:00:00', '2026-05-24 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-23 18:16:58', '2026-05-23 18:16:58', NULL),
(75, 'BOK72454', '904', 2, 2500.00, 1250.00, '2026-05-24 13:00:00', '2026-05-25 10:00:00', 'offline', 'gpay', NULL, 2500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-23 19:09:34', '2026-05-23 19:09:34', NULL),
(76, 'BOK58483', '302', 2, 2300.00, 1800.00, '2026-05-23 13:00:00', '2026-05-24 13:00:00', 'offline', 'gpay', NULL, 2300.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-23 20:50:11', '2026-05-23 20:50:11', NULL),
(77, 'BOK16642', '1130', 1, 18234.00, 5734.00, '2026-05-18 13:00:00', '2026-05-26 12:00:00', 'offline', 'gpay', NULL, 1911.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-24 15:05:51', '2026-05-24 08:14:52', NULL),
(78, 'BOK74181', '1129', 2, 1500.00, 1500.00, '2026-05-25 12:30:00', '2026-05-25 16:30:00', 'offline', 'gpay', NULL, 1500.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-26 13:45:38', '2026-05-26 13:45:38', NULL),
(79, 'BOK35464', '1129', 2, 900.00, 900.00, '2026-05-26 12:00:00', '2026-05-26 17:00:00', 'offline', 'gpay', NULL, 900.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-26 14:02:35', '2026-05-27 21:44:12', NULL),
(80, 'BOK51348', '904', 2, 2000.00, 2000.00, '2026-05-25 13:00:00', '2026-05-25 18:00:00', 'offline', 'cash', 'Sanjeet', 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-26 14:18:43', '2026-05-26 14:18:43', NULL),
(81, 'BOK10043', '904', 2, 2000.00, 2000.00, '2026-05-25 19:00:00', '2026-05-26 10:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-26 14:21:46', '2026-05-26 14:21:46', NULL),
(82, 'BOK72626', '910', 2, 4000.00, 2000.00, '2026-05-27 13:00:00', '2026-05-29 11:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-27 21:43:48', '2026-05-28 13:45:49', NULL),
(83, 'BOK18632', '1029', 2, 2000.00, 2000.00, '2026-05-27 13:00:00', '2026-05-28 18:00:00', 'offline', 'gpay', NULL, 1000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-27 21:47:29', '2026-05-28 13:13:31', NULL),
(84, 'BOK25032', '1130', 2, 1000.00, 1000.00, '2026-05-27 13:00:00', '2026-05-28 10:00:00', 'offline', 'gpay', NULL, 1000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-27 21:49:46', '2026-05-27 21:52:39', NULL),
(85, 'BOK15570', '917', 2, 2000.00, 2000.00, '2026-05-26 13:00:00', '2026-05-27 10:00:00', 'offline', 'gpay', NULL, 2000.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, 'Shivshankar singh', NULL, '2026-05-27 21:55:18', '2026-05-27 21:55:18', NULL),
(86, 'BOK97668', '904', 2, 2200.00, 2200.00, '2026-05-27 13:00:00', '2026-05-28 10:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-28 13:15:08', '2026-05-28 13:15:08', NULL),
(87, 'BOK22689', '904', 2, 5000.00, 5000.00, '2026-05-28 12:00:00', '2026-05-30 10:00:00', 'offline', 'gpay', NULL, 2500.00, 2, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-28 13:57:12', '2026-05-28 13:57:12', NULL),
(88, 'BOK41018', '1129', 2, 2200.00, 2200.00, '2026-05-29 13:00:00', '2026-05-30 10:00:00', 'offline', 'gpay', NULL, 2200.00, 1, 'no', NULL, NULL, NULL, NULL, 0.00, 'active', 0, NULL, NULL, '2026-05-29 21:06:13', '2026-05-29 21:06:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `offline_booking_extensions`
--

CREATE TABLE `offline_booking_extensions` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `old_checkout` datetime DEFAULT NULL,
  `new_checkout` datetime DEFAULT NULL,
  `extra_days` int(11) DEFAULT NULL,
  `extra_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offline_booking_extensions`
--

INSERT INTO `offline_booking_extensions` (`id`, `booking_id`, `old_checkout`, `new_checkout`, `extra_days`, `extra_amount`, `created_at`, `updated_at`) VALUES
(1, 4, '2026-05-02 00:00:00', '2026-05-03 00:00:00', 1, 2000.00, '2026-05-02 14:17:18', '2026-05-02 14:17:18'),
(2, 22, '2026-05-06 00:00:00', '2026-05-07 00:00:00', 1, 2500.00, '2026-05-05 21:44:03', '2026-05-05 14:44:55'),
(3, 23, '2026-05-06 00:00:00', '2026-05-07 00:00:00', 1, 2500.00, '2026-05-05 21:48:14', '2026-05-05 14:48:57'),
(4, 39, '2026-05-09 00:00:00', '2026-05-11 00:00:00', 2, 5000.00, '2026-05-11 12:40:29', '2026-05-11 05:43:29'),
(6, 43, '2026-05-10 00:00:00', '2026-05-11 00:00:00', 1, 2500.00, '2026-05-11 12:51:17', '2026-05-11 12:51:17'),
(7, 34, '2026-05-11 00:00:00', '2026-05-12 00:00:00', 1, 2000.00, '2026-05-11 13:02:57', '2026-05-11 13:02:57'),
(8, 43, '2026-05-11 00:00:00', '2026-05-12 00:00:00', 1, 2500.00, '2026-05-11 15:44:51', '2026-05-11 15:44:51'),
(9, 55, '2026-05-16 10:00:00', '2026-05-17 10:00:00', 1, 2500.00, '2026-05-15 13:52:41', '2026-05-15 13:52:41'),
(10, 55, '2026-05-17 10:00:00', '2026-05-18 12:00:00', 1, 2500.00, '2026-05-17 21:32:54', '2026-05-17 14:41:16'),
(11, 12, '2026-05-18 10:00:00', '2026-05-18 17:00:00', 0, 1500.00, '2026-05-18 13:46:00', '2026-05-18 06:46:48'),
(12, 77, '2026-05-21 10:00:00', '2026-05-24 10:00:00', 3, 7500.00, '2026-05-24 15:09:35', '2026-05-24 15:09:35'),
(13, 77, '2026-05-24 10:00:00', '2026-05-26 10:00:00', 2, 5000.00, '2026-05-24 15:10:19', '2026-05-24 08:11:39'),
(14, 70, '2026-05-24 10:00:00', '2026-05-25 18:00:00', 1, 3000.00, '2026-05-26 13:51:18', '2026-05-26 06:52:16'),
(15, 83, '2026-05-28 10:00:00', '2026-05-28 18:00:00', 0, 1000.00, '2026-05-28 13:10:50', '2026-05-28 06:12:18'),
(16, 82, '2026-05-28 10:00:00', '2026-05-29 11:00:00', 1, 2000.00, '2026-05-28 20:44:48', '2026-05-28 13:45:22');

-- --------------------------------------------------------

--
-- Table structure for table `offline_booking_guests`
--

CREATE TABLE `offline_booking_guests` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `aadhaar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offline_booking_guests`
--

INSERT INTO `offline_booking_guests` (`id`, `booking_id`, `name`, `phone`, `aadhaar`, `created_at`, `updated_at`) VALUES
(1, 1, 'Manvendra Tripathi', '8423735897', 'uploads/aadhaar/3CU6E6SjNSu8Zoj0XiIBO6ZJCdEMPp2Bjx0Lp6W9.pdf', '2026-05-01 11:54:25', '2026-05-01 11:54:25'),
(2, 1, 'Sakshi Tripathi', NULL, 'uploads/aadhaar/eKnRGS5qmIgqwt8yl4EGUDNt3Xq1Wo8WSgg94CaP.jpg', '2026-05-01 11:54:25', '2026-05-01 11:54:25'),
(3, 2, 'Himanshu Yadav', '9918100463', 'uploads/aadhaar/je4e1NtIQLCdG53MYxMmYlyiRbnqAMlew1tR4ptA.pdf', '2026-05-01 17:24:20', '2026-05-01 17:41:14'),
(4, 2, 'Raman Singh Yadav', NULL, 'uploads/aadhaar/x8khOWtJ5Euaxv15w56k5X969F1H8NiBYkNeVJ08.pdf', '2026-05-01 17:24:20', '2026-05-01 17:41:14'),
(5, 3, 'Shivam Chandani', '7355145494', 'uploads/aadhaar/iDiDppIP1IxMZbv7igPIx0AKbUdkNKL0UFDURw64.pdf', '2026-05-01 17:43:39', '2026-05-02 11:49:58'),
(6, 3, 'HANSHIKA', NULL, 'uploads/aadhaar/r37qUKLhcjGedCl7hoJVxpg9eNkLnjt5TBKA3Boi.pdf', '2026-05-01 17:43:39', '2026-05-02 11:49:58'),
(7, 4, 'Radha Krishn Tripathi', '6306003169', 'uploads/aadhaar/JhltO3TY0u0YgohOW6zKDvydfphM7DHJ2FAF7ZKc.jpg,uploads/aadhaar/oXhq5jn0WGF5gFJm61rTcryQh6SN8UJYA657MoxB.jpg', '2026-05-01 20:23:27', '2026-05-02 14:18:20'),
(8, 5, 'Gajera rohitkumar parshottambhai', '6306003169', 'uploads/aadhaar/RkzocdOC2LE1aePF2CSSdHpqTvXBtFcO0OClE7Y9.jpg,uploads/aadhaar/gYeVsszDjeCIesUCBXNWibZKtwCOT7fx09PNfZSW.jpg', '2026-05-01 21:10:38', '2026-05-01 21:10:38'),
(9, 5, 'Bhojani yash', NULL, 'uploads/aadhaar/RxneT66gkgdp2x3eGysUWqcSAd49ldH4YAeLQ911.jpg', '2026-05-01 21:10:38', '2026-05-01 21:10:38'),
(10, 6, 'Syed Md', '9330586672', 'uploads/aadhaar/bD3BsrcRf3xvgl291frFoorWVSM2oNm63z0cUUvm.jpg', '2026-05-02 11:49:33', '2026-05-02 11:49:33'),
(11, 6, 'Shahreen Fatima', NULL, 'uploads/aadhaar/km0lUlO7IsFC3XHuzZso5C9BmOciET0pHYpxgpD1.jpg,uploads/aadhaar/VtmBbHaEL0QUD2gkdeFI6RAOvOr6Xuf3UnC8oWPU.jpg', '2026-05-02 11:49:33', '2026-05-02 11:49:33'),
(12, 7, 'Kalindi Singh', '7838277852', 'uploads/aadhaar/gkGPON1y0knEvRYSUjX20mnWNWnF3oT9aDQm2lbz.jpg', '2026-05-02 13:19:27', '2026-05-02 13:19:27'),
(13, 7, 'Girish Chandra Singh', NULL, 'uploads/aadhaar/SNOGMzvhUmcKpie1EB08a2zaLEw2PGk7MKdF3xq9.jpg,uploads/aadhaar/ARexUSrQDPKXgDJ5DgUOaXGTbll5tiH6OIN7gT0I.jpg', '2026-05-02 13:19:27', '2026-05-02 13:19:27'),
(14, 8, 'Abhay tiwari', '7905820935', NULL, '2026-05-02 13:21:51', '2026-05-02 13:21:51'),
(15, 8, 'Aditi singh', NULL, NULL, '2026-05-02 13:21:51', '2026-05-02 13:21:51'),
(16, 9, 'TINKULEE LIMBU', '6306003169', 'uploads/aadhaar/SSBuF8bXdrNzLrmXIKwV1Fmxqa7LlnBM10oWN20Q.jpg,uploads/aadhaar/M4okI99wwCps1GMZNLRZVhw4yRdZe5SkOREXad98.jpg', '2026-05-02 14:06:24', '2026-05-04 13:00:46'),
(17, 10, 'Shubham rai', '8840522136', 'uploads/aadhaar/Qddg6zcvvqI0N6IzVWxmLvHcem25Qt8iBF7q7O2g.jpg', '2026-05-04 11:23:53', '2026-05-04 11:23:53'),
(18, 10, 'Shivani rai', NULL, 'uploads/aadhaar/gs13XnrhfFbZjeLFV8yINUCz1J4daiOEF9LjLU9X.jpg,uploads/aadhaar/jXKsew5poanmMhP07xihA0lRZAWhHFHuYonE6lCM.jpg', '2026-05-04 11:23:53', '2026-05-04 11:23:53'),
(19, 11, 'Vishal pandey', '9695542875', 'uploads/aadhaar/KSiJHpMnD4ofezmTwo9kqVQjRnAPfSE3doAS9zPK.jpg,uploads/aadhaar/HTWGpNklVcScoDpjH4b1WLjii6v44fDkggvXCR1L.jpg', '2026-05-04 12:54:03', '2026-05-04 12:54:03'),
(20, 11, 'Anjali rajpoot', NULL, 'uploads/aadhaar/hKOmfjH6X3Z64pjFBY70TW2DvG0gofQiQMKJMott.jpg,uploads/aadhaar/9wy54ob9KRA0uEhWviqrIGZME951C8csULfHmlXG.jpg', '2026-05-04 12:54:03', '2026-05-04 12:54:03'),
(21, 12, 'Himanshu suri', '8493805468', 'uploads/aadhaar/fWLdBlpgIoDQqOdB4y06wAvHiPHjzrKTUd5KFm3M.jpg,uploads/aadhaar/EcNbwEQHRRXGgYdj4Qj1YLQeb9WB1MazQnm2gaNY.jpg', '2026-05-04 12:59:33', '2026-05-04 12:59:33'),
(22, 12, 'Anam fatima', NULL, 'uploads/aadhaar/wCtOT5mgVD6ky9k5WW75Fg8HjBG1LsrqjCEDoxBP.jpg,uploads/aadhaar/DMsHE1RENau9NrKoNAlH5ddzOnjOF91aZs6iO5Nf.jpg', '2026-05-04 12:59:33', '2026-05-04 12:59:33'),
(23, 13, 'Prajjwal sharma', '8470943166', 'uploads/aadhaar/CAYnOzDllnk1i9iupwWDofU6qGqPoI5frUIgWUJF.jpg,uploads/aadhaar/5IbTuGJRd6Cx4O4B8xMy3ehOW2Xn5ukZUUiW9bJS.jpg', '2026-05-04 13:04:15', '2026-05-04 13:04:15'),
(24, 13, 'Km priya', NULL, 'uploads/aadhaar/4LLjgPdNscoOZiL6PTftkL2zpotnixFqPaO2T5mO.jpg', '2026-05-04 13:04:15', '2026-05-04 13:04:15'),
(25, 14, 'Arshi Naaz', '6306003169', 'uploads/aadhaar/p4XOWQDnT9rclE8eSU05RwwAK5XqM0YEFtkLuM8i.jpg,uploads/aadhaar/EwI1Km670SNDalxkXeO7agiCG9jePXWo3N76weuC.jpg', '2026-05-04 13:07:35', '2026-05-04 13:07:35'),
(26, 14, 'Shriti Singh', NULL, 'uploads/aadhaar/xrEF5eOv3heHypNFZOxfxF8Wx3FfNAk52lEjOITc.jpg', '2026-05-04 13:07:35', '2026-05-04 13:07:35'),
(27, 15, 'Nimisha Kushwaha', '8005460593', 'uploads/aadhaar/w80YquaRCJtNNSVXiwZsAXYdy667lU9WOPKfOfdB.jpg', '2026-05-04 13:22:23', '2026-05-04 13:25:51'),
(28, 16, 'Akarshit Srivastava', '9580467376', 'uploads/aadhaar/Cw4ynYlOtcMvGERTk4jNk6rd60vKaFEWstMudA9F.jpg,uploads/aadhaar/Iq0QBRJyXoXib0e4v8oeIOkV4kYFhYXGvn2m7JIM.jpg', '2026-05-04 13:34:02', '2026-05-04 13:34:02'),
(29, 16, 'Sandhya Singh', NULL, 'uploads/aadhaar/f08sH0OSSvOWJ8boIKHvFZncO65x2fCTzaoy65GM.jpg,uploads/aadhaar/z80kw9BkZrKQqpa9PJYrkYUxa3quThTk3qL3l6X3.jpg', '2026-05-04 13:34:02', '2026-05-04 13:34:02'),
(30, 17, 'Priyam', '9559768297', 'uploads/aadhaar/43wps2jooLcs8Yr0OlfR2FE1pykUiPo4H6iPacym.jpg', '2026-05-04 14:03:53', '2026-05-04 14:03:53'),
(31, 17, 'Sweta Parwat', NULL, 'uploads/aadhaar/tZhfUVdmX1okb9k5RpyMaAZT0TGW2QwwZLIbCIR9.jpg,uploads/aadhaar/q6JaMKDczEktfcVj1kMcUCnmC3ySiQ3j4kzKuzmA.jpg', '2026-05-04 14:03:53', '2026-05-04 14:03:53'),
(32, 18, 'Khushaal Srivastava', '7233947406', 'uploads/aadhaar/gGk79sMVaL8HZ13x0FB73aJFB9Ek5vy16vfQkoFv.jpg,uploads/aadhaar/y9juXOblVipAjTjH8oMhIVwH0aqgQyrz7Yiv2zeb.jpg', '2026-05-04 16:43:31', '2026-05-04 11:12:07'),
(33, 18, 'Anchal Kanoujiya', NULL, 'uploads/aadhaar/6Qe31teOwG4gXH6Xvv3Kz9WN5vub9XivNGJJhujp.jpg', '2026-05-04 16:43:31', '2026-05-04 16:43:31'),
(34, 19, 'Ayush Sachan', '7860183335', 'uploads/aadhaar/8z2nKCrl8IAbT9OJruGIQFH4peePcEKefSqrdRS9.jpg,uploads/aadhaar/rNSiVHevyHoovMcuxyUWI0w57wZgqPbRxiVN5FjN.jpg', '2026-05-04 17:27:17', '2026-05-04 19:00:26'),
(35, 19, 'Sadhana Gupta', NULL, 'uploads/aadhaar/M9YoDsI8Daa9itTcNh2LrdqqBchS3w2hbE9FwU94.jpg', '2026-05-04 19:00:26', '2026-05-04 19:00:26'),
(36, 20, 'Akshat Agarwal', '7275641455', 'uploads/aadhaar/vbZGmVKCd0wkBBXbOqSvGLTlZa15mnJLKskYhrcL.pdf', '2026-05-04 22:34:23', '2026-05-04 22:34:23'),
(37, 20, 'Shubhra Pandey', NULL, 'uploads/aadhaar/m9dZrPyMAKxuLtKRgt52B8M1Bytvbqurssbh8lyz.jpg,uploads/aadhaar/Cay1LubD36jdwfTG1OjyIr4ti0fpPNWx2k82UTJB.jpg', '2026-05-04 22:34:23', '2026-05-04 22:34:23'),
(38, 21, 'Afsan Ali', '9518221688', 'uploads/aadhaar/PqFypRneCPegC3esDlCPI62cN9dK6EmsNuilhzg0.jpg', '2026-05-05 16:31:54', '2026-05-05 16:31:54'),
(39, 21, 'Aqsa Khan', NULL, 'uploads/aadhaar/v98Cht0z5lJvCM4GWtOCgxAOMIPqz4q9R96PAoOi.jpg,uploads/aadhaar/Jos9TLetrt0J06GeYZfA7za95l3fD2dFkYmpXCHQ.jpg', '2026-05-05 16:31:54', '2026-05-05 16:31:54'),
(40, 22, 'Samarth', '9455669098', 'uploads/aadhaar/qXRH47VHqWb2KAl2zLwooAWdxJsu6J97oDAgbtJl.jpg,uploads/aadhaar/dUxgAYxBzMvD8AwPc6V4hXYNW5fC0O0WfpGQkFiH.jpg', '2026-05-05 18:15:50', '2026-05-05 18:15:50'),
(41, 22, 'Sarika', NULL, 'uploads/aadhaar/SKkDzjtk9y48fzMJIhdowNdORmvxWrrmge3axlZj.jpg,uploads/aadhaar/GcugcALJsLJRaFB2hNQN0f6xnUNJ9Zs7BWoetMO6.jpg', '2026-05-05 18:15:50', '2026-05-05 18:15:50'),
(42, 23, 'Abhay', '9455669098', 'uploads/aadhaar/UGaCk4fJ6z4UgF7tZTqYsUyuJzz45YFp1hl5owBk.jpg,uploads/aadhaar/OiDTLEZ18FeyDN56Fz6kaeVnCp7FP2igu2X8Xjm7.jpg', '2026-05-05 18:17:41', '2026-05-05 18:17:41'),
(43, 23, 'Nikhat Parveen', NULL, 'uploads/aadhaar/ENlCx7cZJwOygSD9A41zhp8IIhZEUq6jBksON9oX.jpg', '2026-05-05 18:17:41', '2026-05-05 18:17:41'),
(44, 24, 'Tarun kukreja', '9892375057', NULL, '2026-05-05 18:19:43', '2026-05-05 18:19:43'),
(45, 25, 'Gaurav jaiswal', '7007880588', 'uploads/aadhaar/OKgQVpAjOlI2cScZ39xG1NXLcGmPftrsoJ19zD4u.jpg,uploads/aadhaar/ZgyKlMA5O4teaIrp4JEQ0zKTYnG3SdeNKRFUXMSh.jpg', '2026-05-05 21:40:51', '2026-05-06 14:40:19'),
(46, 25, 'Priya singh', NULL, 'uploads/aadhaar/mx0Ybi3hiXVXLytiMUceFSn1iNMqUqRHT1KpJq7r.jpg,uploads/aadhaar/TIgnU5PNMOumKlnfj9iHSTUdhZJgAUFx4TCjbYgz.jpg', '2026-05-05 21:40:51', '2026-05-06 14:40:19'),
(47, 26, 'Krishna bhangde', '7505377899', 'uploads/aadhaar/zXIR5hG4J7nkNJ0fhvAFHUUvgHW3mJWxPSTUdJBQ.pdf', '2026-05-06 11:12:56', '2026-05-06 11:12:56'),
(48, 26, 'Gunjan joshi', NULL, 'uploads/aadhaar/ACAwwXmvgyHyj8bQjcp2ydN4Yzz3x6ZowjczTNqw.pdf', '2026-05-06 11:12:56', '2026-05-06 11:12:56'),
(49, 27, 'Suraj Tiwari', '6390374963', 'uploads/aadhaar/sa6CcbZKk4UNVOkmww2ZpqjEWdRlpuywYEOWMX1c.jpg,uploads/aadhaar/52RnJQ8FaZdj6dYigK11C8BliF4HhufLfe7gHxgN.jpg', '2026-05-06 14:35:12', '2026-05-06 14:35:12'),
(50, 27, 'Priya Tiwari', NULL, 'uploads/aadhaar/p7kLCGkYusWrGPxPnhxPQmib2t72BjhUqkNgXlnG.jpg,uploads/aadhaar/ySrHJbYoA3mgbJIhC0csyw2dxsjDG1CjF5KREEvr.jpg', '2026-05-06 14:35:12', '2026-05-06 14:35:12'),
(51, 28, 'Princee Yadav', '7318223682', 'uploads/aadhaar/Rvury94CD80P1LoY1uslecA7kms05ksfF3Jdpyxm.jpg', '2026-05-06 14:40:25', '2026-05-06 14:40:25'),
(52, 28, 'ARSALAN URRA', NULL, 'uploads/aadhaar/vOFl5C8q0a5HiTe7mUf3Ily6hGR9jGr1GhNmVbDl.jpg', '2026-05-06 14:40:25', '2026-05-06 14:40:25'),
(53, 29, 'Ajayveer', '9889899110', NULL, '2026-05-06 15:02:55', '2026-05-06 15:02:55'),
(54, 29, 'Chitra shukla', NULL, NULL, '2026-05-06 15:02:55', '2026-05-06 15:02:55'),
(55, 30, 'Sova Yadav', '6306017691', 'uploads/aadhaar/phIyjhvuFJ08rzWEhVJmU3m6lweVtNu9WKKmxfyk.jpg,uploads/aadhaar/4pFfKC9BHnejXdpzS8RaPTsOpf5fwu5UpV4UFPCA.jpg', '2026-05-07 11:55:58', '2026-05-07 11:55:58'),
(56, 30, 'Ngawang Paldon', NULL, 'uploads/aadhaar/plCKDUNqNHEectD2k5MMRXzseeCYpeScoeXRKKDn.jpg', '2026-05-07 11:55:58', '2026-05-07 11:55:58'),
(57, 31, 'Sachin Raikar', '8762273654', 'uploads/aadhaar/3mFprRKXjwEr1lTCb1pV2z6OGYiXnmFRVIbVDN6h.jpg', '2026-05-07 12:37:29', '2026-05-07 12:37:29'),
(58, 31, 'Dhanashree B. Pai', NULL, 'uploads/aadhaar/jVYo4bmXyy3mAX95J9qDsKm0KziATqIlm5L6YABp.jpg', '2026-05-07 12:37:29', '2026-05-07 12:37:29'),
(59, 32, 'Saksham Mahajan', '9888599928', 'uploads/aadhaar/z7QlUMdlId6ES7nNXbco3cJ1bEz0choAfma0IkQZ.jpg', '2026-05-07 12:43:36', '2026-05-07 12:43:36'),
(60, 32, 'Aayushi', NULL, 'uploads/aadhaar/IMiDHwz3SgOkx9lml4DnQi9xgDAeiquKgRvQ5fQO.jpg', '2026-05-07 12:43:36', '2026-05-07 12:43:36'),
(61, 33, 'Aditi sharma', '8787031446', 'uploads/aadhaar/KrBfapO29hFXldAWNCgerTszGduvW0iu6S7xyEFm.jpg', '2026-05-07 18:46:08', '2026-05-07 18:46:08'),
(62, 33, 'Vivek singh', NULL, 'uploads/aadhaar/rCVRNhu4BpBNBC5gKf6A1hZXZDSxCO8Gdg01JYXA.jpg,uploads/aadhaar/JU7W0vKPFfHb2MpnGs7SLPZVNa3XBRARL3zof6YB.jpg', '2026-05-07 18:46:08', '2026-05-07 18:46:08'),
(63, 34, 'Satyam Yadav', '8874129389', 'uploads/aadhaar/D9GgE7mvApaWSINPgTi3n5jGUWPorI4Qtnbz8Jq9.jpg,uploads/aadhaar/G5cgdQlu4rc2yC3yNz2skbHijeNGryHxawoiB6Uk.jpg', '2026-05-07 18:50:38', '2026-05-11 13:00:13'),
(64, 35, 'Nihal kumar gupta', '7004452707', 'uploads/aadhaar/WMTFq20drfzWSa2rJYgzYsOmHuGB0qXGS5tM9vRT.pdf', '2026-05-07 18:54:42', '2026-05-07 18:54:42'),
(65, 36, 'Sayed Ahmad', '9838768910', 'uploads/aadhaar/QIQEC29LJ7nscSMC0qAuuWOjSbGjgKwFV9V6Sr0O.jpg,uploads/aadhaar/moAb96MMupFaIeUYiaxok088p2nz3vGuxQIXK4w8.jpg', '2026-05-07 20:51:36', '2026-05-08 14:59:39'),
(66, 37, 'Satyam Yadav', '8874129389', 'uploads/aadhaar/D9GgE7mvApaWSINPgTi3n5jGUWPorI4Qtnbz8Jq9.jpg,uploads/aadhaar/G5cgdQlu4rc2yC3yNz2skbHijeNGryHxawoiB6Uk.jpg', '2026-05-08 14:36:57', '2026-05-09 17:20:57'),
(67, 38, 'Mukul', '7895085901', 'uploads/aadhaar/7vRvkscRACWUrI9aOk1cqfxqJkfYkJEVq1iv5JS6.jpg,uploads/aadhaar/oNfTz1RSF5RUZcnkm1YALW6IM251iNnSBUBUyqnH.jpg', '2026-05-08 14:44:00', '2026-05-08 14:44:00'),
(68, 38, 'Aabha Singh', NULL, 'uploads/aadhaar/MGrapZTD04v0orwm8jULVsePtkIEReX3Zbi96agw.jpg,uploads/aadhaar/NJ3yQDtlRBnBSrvgyJ8p1FCDn6GFfg5YmqXAOLcv.jpg', '2026-05-08 14:44:00', '2026-05-08 14:44:00'),
(69, 39, 'Gurjeet Singh', '6306003169', 'uploads/aadhaar/RZte1ugP4hWeHtMDUtJnpyxZkyz4ANSkWOvM8sr7.jpg,uploads/aadhaar/F5cpSlGzETNkdvfKDGFa8vnFDmKDEFW1BpQoAyyl.jpg', '2026-05-08 14:46:14', '2026-05-09 17:13:15'),
(70, 40, 'Imad Chaudhary', '9915585427', 'uploads/aadhaar/ZzmeEFYY8V0SGllvx3r5uNuLeGok0N0xMNCSENOd.jpg,uploads/aadhaar/SZNIvU4FM6NKqEqjbAa0F5Y0NWZwuxuMqWC2AKe2.jpg', '2026-05-08 14:50:14', '2026-05-08 14:50:14'),
(71, 40, 'Zainab Malwan', NULL, 'uploads/aadhaar/X7Qo69wKW4mojUjGWjnnG75HDNtVX7VJsKls3U5e.jpg,uploads/aadhaar/2563Cb6B0Bv62YqJ9ms6gNUtqs6HSdFXqSa7i7Ti.jpg', '2026-05-08 14:50:14', '2026-05-08 14:50:14'),
(72, 41, 'Ayaz Malik', '7906337923', 'uploads/aadhaar/DUOIcvGF6orrkEH2lXMxyaKwrmBLgv6sxoPmMcrj.jpg', '2026-05-08 14:52:30', '2026-05-08 14:52:30'),
(73, 41, 'ALVIYA PARVEZ', NULL, 'uploads/aadhaar/jGRkcOOZfrBQfxHaqigGzi1pOPDEQ7l1JY6IZJX7.jpg', '2026-05-08 14:52:30', '2026-05-08 14:52:30'),
(74, 36, 'Rishabh Agnihotri', NULL, 'uploads/aadhaar/w8rtrGNwui5qcdVjnSraNmnHAMYfjNrtuSANW876.jpg,uploads/aadhaar/Xt4E5UNZotaD7zgiasXaAUMWsMiIVmyRrzYlWMio.jpg', '2026-05-08 14:59:39', '2026-05-08 14:59:39'),
(75, 42, 'Shashwat Sandeep Tiwari', '6306003169', 'uploads/aadhaar/QAgRdVfRiDgEOeBy85HYOcPUVMXIjppPZTnb83dH.jpg,uploads/aadhaar/RhsYiDseedYSPXVHwlRPH5ZxaM3gGB7ALs2puDnY.jpg', '2026-05-08 18:41:36', '2026-05-08 18:41:36'),
(76, 42, 'Ruchika Sunil Bhad', NULL, 'uploads/aadhaar/UvBV3EX2KbsFUk8GFp6BuH2kKSbUcvyeTKlfX1UD.jpg', '2026-05-08 18:41:36', '2026-05-08 18:41:36'),
(77, 43, 'Ayush singh', '6306003169', 'uploads/aadhaar/8jM0kJuZn36FPW2OegMPisGYs554wwHPLoBIYjC7.jpg,uploads/aadhaar/vILIqVnNNmTSw39XxlgwUAfBRLIcgWP7Iwf6EniE.jpg', '2026-05-09 17:12:28', '2026-05-09 17:12:28'),
(78, 43, 'Priyanshi', NULL, 'uploads/aadhaar/xDYzMDou9hAisXZFGFcLAyj12EeznPa0EoRERHBF.jpg,uploads/aadhaar/poOTCeal3dcFT3nAlLCGV9roiOFUgBw3K9J1wZvd.jpg', '2026-05-09 17:12:28', '2026-05-09 17:12:28'),
(79, 37, 'Shreya Rai', NULL, 'uploads/aadhaar/RK5cEzp8hA4WOCKDXH5L5AkF4lTSlJaJxOn5R9WD.jpg', '2026-05-09 17:20:57', '2026-05-09 17:20:57'),
(80, 44, 'Mudassir Taj', '9794113638', 'uploads/aadhaar/g5ny1XuFMrde7inIB9aClTaZwKTxCV21AiH5VjkN.jpg', '2026-05-09 17:23:23', '2026-05-09 17:23:23'),
(81, 44, 'Fatima Khatoon', NULL, 'uploads/aadhaar/Y6Qd0w7lErwSSgvpLuXlOux9yF8wZPZwOckTI7yb.jpg', '2026-05-09 17:23:23', '2026-05-09 17:23:23'),
(82, 45, 'Mahesh Singaram', '8143656592', 'uploads/aadhaar/XmjzZGov1dZ6HzhP0OrDRWN7xc5rT73ZDlM7GQQI.jpg', '2026-05-09 17:27:42', '2026-05-09 17:27:42'),
(83, 46, 'Vaibhav Sharma', '7905186002', 'uploads/aadhaar/3bosOgPkCK4r9QK68WL3ow2TgKeNQa6AYes3Lc0g.jpg', '2026-05-11 12:58:18', '2026-05-11 12:58:18'),
(84, 46, 'ANKITA SINGH', NULL, 'uploads/aadhaar/8E2bEwKnNG6BGXKZdVU0H9QQJX2c0N7mXTte1PAt.jpg,uploads/aadhaar/ebccMMqJ0jjaDRoBoEuLrOoJ3mvGtgV7xPQzlcBA.jpg', '2026-05-11 12:58:18', '2026-05-11 12:58:18'),
(85, 47, 'Mukesh Tiwari', '8840096315', 'uploads/aadhaar/DoTO3A7w62NqaBf8EmY7ZHe9DQwKtu9wrwPwIBNa.jpg,uploads/aadhaar/KbvL3a9o3AUsqZ7KtSWNIodnCLibv9uXllSnHzrL.jpg', '2026-05-12 13:43:32', '2026-05-12 13:43:32'),
(86, 47, 'ANULIKA SRIVASTAVA', NULL, 'uploads/aadhaar/knwGP6Bl3y0RvC2rznoYUJSuQEnLCZ5XW9Er780h.jpg,uploads/aadhaar/BuqfAniFi9POHIX3PGyYVMJNMoWOnzbTWyf2to4a.jpg', '2026-05-12 13:43:32', '2026-05-12 13:43:32'),
(87, 48, 'Vijay prakash tiwari', '9473655147', NULL, '2026-05-12 14:30:44', '2026-05-12 14:30:44'),
(88, 48, 'rebika rai', NULL, NULL, '2026-05-12 14:30:44', '2026-05-12 14:30:44'),
(89, 49, 'Ajayveer', '9889899110', NULL, '2026-05-12 17:12:28', '2026-05-12 17:12:28'),
(90, 49, 'Chitra shukla', NULL, NULL, '2026-05-12 17:12:28', '2026-05-12 17:12:28'),
(91, 50, 'Sanjay', '9250055202', NULL, '2026-05-12 17:26:49', '2026-05-12 17:26:49'),
(92, 50, 'Manisha', NULL, NULL, '2026-05-12 17:26:49', '2026-05-12 17:26:49'),
(93, 51, 'Vivek singh', '8368267523', 'uploads/aadhaar/oXml65SoHYqzkkq438u1obd0XtKrbECPhBnvBlNM.jpg,uploads/aadhaar/mUDWcHPp1VqyEoPs6srb55PrGz48rSqVP1eR8wt6.jpg', '2026-05-13 17:31:57', '2026-05-13 17:31:57'),
(94, 51, 'Aditi Sharma', NULL, 'uploads/aadhaar/pM8i23mQcoWNyAnabvphZ0jbTtakN1OHvRq3n62K.jpg', '2026-05-13 17:31:57', '2026-05-13 17:31:57'),
(95, 52, 'Shantanu pandey', '9793986068', 'uploads/aadhaar/dK72j8ib22InlCEnUg9misFep9ClCvuPA2rYMMHf.jpg,uploads/aadhaar/dxMuhfD0YsOnusIRgDoaqIvMii3h7cWA4b5qefTZ.jpg', '2026-05-13 17:48:21', '2026-05-13 17:48:21'),
(96, 52, 'Vanshita shukla', NULL, 'uploads/aadhaar/ueEenSnjR3RlfU3R9zTpgvyAs6L3M6i4a4FbaAw5.jpg', '2026-05-13 17:48:21', '2026-05-13 17:48:21'),
(97, 53, 'Tarun kukreja', '9892375057', NULL, '2026-05-13 17:52:39', '2026-05-13 17:52:39'),
(98, 54, 'Akshat Gupta', '9919909844', 'uploads/aadhaar/HGjoQTNy0U1dbDl4QtnBrIeCqT4U4A8ylMWmUzCB.pdf', '2026-05-13 18:45:08', '2026-05-13 18:45:08'),
(99, 54, 'Vranda Rastogi', NULL, 'uploads/aadhaar/FUbn9xZLlCda8hf5rfQJOwoP7kWfXIEM2KdsT0Y9.pdf', '2026-05-13 18:45:08', '2026-05-13 18:45:08'),
(100, 55, 'Ankit Sabarwal', '9074606808', 'uploads/aadhaar/8WEjx46NJ3rNEgs21PX78zqOe961l0pOoIO2AvZ1.jpg', '2026-05-14 11:55:14', '2026-05-14 11:55:14'),
(101, 56, 'Abhay srivastava', '8953171369', 'uploads/aadhaar/zxUlpVTvy8FLL6vxSi47dPkfGlQduoXZS4dxNo6W.pdf', '2026-05-14 12:00:41', '2026-05-14 12:04:31'),
(102, 56, 'Archita mishra', NULL, 'uploads/aadhaar/DWIsm6mx3lTXi6j0aN5VQpDFw6UsjW9PjizcNlX2.jpg,uploads/aadhaar/uQdWFOjRF2GVrpO1rIkhKCfW3WuKC2M4DV9UJbgb.jpg', '2026-05-14 12:00:41', '2026-05-14 12:04:31'),
(103, 57, 'Vivek singh', '8368267523', 'uploads/aadhaar/oXml65SoHYqzkkq438u1obd0XtKrbECPhBnvBlNM.jpg,uploads/aadhaar/mUDWcHPp1VqyEoPs6srb55PrGz48rSqVP1eR8wt6.jpg', '2026-05-14 13:31:13', '2026-05-14 13:31:13'),
(104, 57, 'Aditi Sharma', NULL, 'uploads/aadhaar/cB43UFLbHVK4yayQ3DG3zR4LXhY3UlVjSNJmz6T3.jpg', '2026-05-14 13:31:13', '2026-05-14 13:31:13'),
(105, 58, 'Anurag kashyap', '8528008655', NULL, '2026-05-14 13:48:49', '2026-05-14 13:52:27'),
(106, 58, 'Monika rawat', NULL, NULL, '2026-05-14 13:48:49', '2026-05-14 13:52:27'),
(107, 59, 'Manvir singh', '9999777462', 'uploads/aadhaar/VtwPIz2qtJogFo3MaAOgCrHd2fcMuJOahO62RZLl.jpg', '2026-05-15 14:49:44', '2026-05-15 14:54:07'),
(108, 59, 'Arvind kumar', NULL, 'uploads/aadhaar/AYPTSLD8lGmrtEJKUi478G1Rpaq3EEODjSygzpmr.jpg', '2026-05-15 14:49:44', '2026-05-15 14:54:07'),
(109, 60, 'Manvir singh', '9999777462', 'uploads/aadhaar/VtwPIz2qtJogFo3MaAOgCrHd2fcMuJOahO62RZLl.jpg', '2026-05-15 14:53:55', '2026-05-15 14:53:55'),
(110, 60, 'Arvind kumar', NULL, 'uploads/aadhaar/noPcAz7XZ7NdHCTDrGu4dUZgNZHUfH5jnJQ8L5N0.jpg', '2026-05-15 14:53:55', '2026-05-15 14:53:55'),
(111, 61, 'Praveen kumar', '9956314000', 'uploads/aadhaar/UG50tqTxVo4HAyr42qHqnQCjRUvjsQATazmglpc0.jpg,uploads/aadhaar/K2b0xx4rm8BmjKvpeAwP0tW5SBZuXLPLgL6HYQ3B.jpg', '2026-05-16 19:14:33', '2026-05-16 19:14:33'),
(112, 62, 'Yash', '9955047300', 'uploads/aadhaar/cRQNhcIoOFj8os60Ql8MBhglm5zHrk01moPbTxFE.jpg,uploads/aadhaar/ukQjEMkYrNsmlLGpFxTr4AAEnLfOLG7sqMSoRDUM.jpg', '2026-05-16 19:19:16', '2026-05-16 19:19:16'),
(113, 62, 'Nikhil', NULL, 'uploads/aadhaar/73OdgGCC4Jh4kDo6gMrbkiNbXCp8QiFIbQZUIPKJ.jpg,uploads/aadhaar/CketAIw7kvvMcNrIShepbZoocpaK6uEXzZA6NnMT.jpg', '2026-05-16 19:19:16', '2026-05-16 19:19:16'),
(114, 63, 'Abhishek sengar', '8077764277', 'uploads/aadhaar/0Zkk6TNwuVqVYl8p0UjLOT70Ti06zL4j9OvlNCnI.jpg,uploads/aadhaar/xqzv29BbwaMJsVKdReQ1OGxKsygvZ0YRtDj0G0im.jpg', '2026-05-16 19:23:18', '2026-05-16 19:23:18'),
(115, 63, 'Ajay kumar', NULL, 'uploads/aadhaar/3WsEc4uAFaumPqe7kCU3TqfoGrSMbtgi9TxpxPjn.jpg,uploads/aadhaar/rTX0brm8a2mMOoBaO5Fn0oRV1iP6pJTG7YtTbNP6.jpg', '2026-05-16 19:23:18', '2026-05-16 19:23:18'),
(116, 64, 'Afzal khan', '8795161751', 'uploads/aadhaar/bVDvhUgkHRiwxcrHWP8tfzbdG17O0KNEd63GpFWk.jpg,uploads/aadhaar/BQGO4oFul9n3pM1GKmgn2dpBah0WaYETHZw5QD4T.jpg', '2026-05-17 21:35:49', '2026-05-17 21:35:49'),
(117, 64, 'Shraddha singh', NULL, 'uploads/aadhaar/qxh3qWTQ9U0RG84Y8ye9c3q7tyTeJ2zbGPgU1nFp.jpg,uploads/aadhaar/BaNcQaCo3WCX5dJh6C3tx5KEPOIKkUnd9dZzP2m7.jpg', '2026-05-17 21:35:49', '2026-05-17 21:35:49'),
(118, 65, 'Vijay kapoor', '9193013555', NULL, '2026-05-18 13:59:59', '2026-05-18 13:59:59'),
(119, 65, 'PS Bisht', NULL, NULL, '2026-05-18 13:59:59', '2026-05-18 13:59:59'),
(120, 66, 'Vijay kapoor', '9193013555', NULL, '2026-05-18 14:05:41', '2026-05-18 14:05:41'),
(121, 66, 'PS Bisht', NULL, NULL, '2026-05-18 14:05:41', '2026-05-18 14:05:41'),
(122, 67, 'Romil Kumar Gupta', '7678857421', 'uploads/aadhaar/WoeXNYx13W29KT5IqjNG6DZ6A715jXx3UKXfRgQx.jpg,uploads/aadhaar/yMzY8R2SdIEIPLgH8vYT9GznMfO9a60XDUXEqs0y.jpg', '2026-05-20 22:43:17', '2026-05-20 22:43:17'),
(123, 67, 'Khushi Verma', NULL, 'uploads/aadhaar/ufAIy8KsF2P4vadnPadv0r3sz4iG7gUtJhqzILzK.pdf', '2026-05-20 22:43:17', '2026-05-20 22:43:17'),
(124, 68, 'aamir', '9935322470', 'uploads/aadhaar/sUUe2N6IQrbcfuXcq6YVFJVcBh7xVw1xvM0jn2wa.jpg,uploads/aadhaar/UY7qn1TVisxmp3XDclEfkhkh3dvPgWjGsZBfxs0R.jpg', '2026-05-20 22:46:03', '2026-05-20 22:46:03'),
(125, 68, 'Huda afsal', NULL, 'uploads/aadhaar/W2qjvFY2AlRSFsleuteq7eY0Y21rxirPN40qmV7K.jpg,uploads/aadhaar/c3Z8MbEJcBdZhXkCsJo6d5nzBpP87pksNQVBGgIJ.jpg', '2026-05-20 22:46:03', '2026-05-20 22:46:03'),
(126, 69, 'Vivek singh', '8368267523', 'uploads/aadhaar/oXml65SoHYqzkkq438u1obd0XtKrbECPhBnvBlNM.jpg,uploads/aadhaar/mUDWcHPp1VqyEoPs6srb55PrGz48rSqVP1eR8wt6.jpg', '2026-05-22 13:07:33', '2026-05-22 13:07:33'),
(127, 69, 'Aditi sharma', NULL, NULL, '2026-05-22 13:07:33', '2026-05-22 13:07:33'),
(128, 70, 'Tarun kukreja', '9892375057', NULL, '2026-05-22 13:08:40', '2026-05-22 13:08:40'),
(129, 71, 'Sakshi yadav', '9305864107', 'uploads/aadhaar/54bM8J5UMxIN5eI2b8qhEG09fAQb6DNqDM0bmg3a.jpg,uploads/aadhaar/ftm7Kxnd78Rn0FztYFGM6ibgiv7o2Ueg59tSti73.jpg', '2026-05-22 21:24:04', '2026-05-22 21:24:04'),
(130, 71, 'Mohd. Ahtisham', NULL, 'uploads/aadhaar/To9e1tmkhFAts6eOaZobBLPrRy5wwOBH60ap3Q3i.jpg,uploads/aadhaar/OEtvWRl32FLvGS4bflrqyrlk1Rdt7d2OBfd55Gjt.jpg', '2026-05-22 21:24:04', '2026-05-22 21:24:04'),
(131, 72, 'Vivek singh', '8368267523', 'uploads/aadhaar/oXml65SoHYqzkkq438u1obd0XtKrbECPhBnvBlNM.jpg,uploads/aadhaar/mUDWcHPp1VqyEoPs6srb55PrGz48rSqVP1eR8wt6.jpg', '2026-05-22 21:26:44', '2026-05-22 21:26:44'),
(132, 72, 'Aditi sharma', NULL, NULL, '2026-05-22 21:26:44', '2026-05-22 21:26:44'),
(133, 73, 'Vikas singh', '9453796909', 'uploads/aadhaar/Gt8vclI0GPPgdzPcuffwJBMgazSMlXN043IEaFQo.jpg,uploads/aadhaar/fLUU2EoPKlS0UcbI3bgUZC0Q4tRiGEUOQnz2OE29.jpg', '2026-05-22 21:29:43', '2026-05-22 21:33:57'),
(134, 73, 'Monisha modi', NULL, 'uploads/aadhaar/aUvYiFM1QlkOd3EHk16iXUPQz4hKr8fsVfYzKfz6.jpg,uploads/aadhaar/WxZk3Sqi4nDenHJky53vnEjmgRerHacY9y5Mt6Mg.jpg', '2026-05-22 21:29:43', '2026-05-22 21:33:57'),
(135, 74, 'Ankita ghosh', '7408860770', NULL, '2026-05-23 18:16:58', '2026-05-23 18:16:58'),
(136, 74, 'Abhishek kumar', NULL, NULL, '2026-05-23 18:16:58', '2026-05-23 18:16:58'),
(137, 75, 'Param shukla', '9839901292', 'uploads/aadhaar/gbdEnZVLnyOLACjptrmKhCepsWm0KiGt6aTRmMA2.jpg,uploads/aadhaar/X1tJeKhcAzmYoDa9nJquE4k0mkozN1xM38EAraai.jpg', '2026-05-23 19:09:34', '2026-05-23 19:09:34'),
(138, 75, 'Manali srivastava', NULL, 'uploads/aadhaar/24cOMC8w4g0dNDEP5MqTp9gaL94fCkVDfKa86lNT.jpg,uploads/aadhaar/zt4slPwQKSybvoBrWyI64KeXFXvjHvwiNAkdLdkC.jpg', '2026-05-23 19:09:34', '2026-05-23 19:09:34'),
(139, 76, 'Hritik srivastava', '9554083688', 'uploads/aadhaar/ckVgQvrSpAnpPL66BXPoFTcoh7qcgwce1Wmc7X48.pdf', '2026-05-23 20:50:11', '2026-05-23 20:50:11'),
(140, 76, 'Shreepriya agarwal', NULL, 'uploads/aadhaar/IVJtY9giRicRKfRJDoJEJO3X9FBjqW1rsnMqaCZo.jpg,uploads/aadhaar/HTJDAHqGtBF6bl9TrY9RVghbcaD4eKSrurADPAkw.jpg', '2026-05-23 20:50:11', '2026-05-23 20:50:11'),
(141, 77, 'Gurjeet Singh', '7702246195', 'uploads/aadhaar/fp8UOaMQhlFmpTeLWo3RFHn21TFdO9e7gzDSmjMb.jpg,uploads/aadhaar/ZdfTl1y7ivJabPa5jngABBce3RQZvIN5BxACCEYv.jpg', '2026-05-24 15:05:51', '2026-05-24 15:05:51'),
(142, 78, 'Himanshu sachin', '6393484122', 'uploads/aadhaar/5lbiL8rlMBKJYOKliyXhwaIYBgWuBEFhzjjM9vBs.jpg,uploads/aadhaar/orAyEVtr1nivhEbbK9yYJd7OAaw88P7swlbzF23d.jpg', '2026-05-26 13:45:38', '2026-05-26 13:45:38'),
(143, 78, 'Arunima saxena', NULL, 'uploads/aadhaar/N18IQHEpMWEyCTSbzDMiupaoZnVCO7h8knZpxBlU.jpg,uploads/aadhaar/fJZaV5UajOmAJSYGEUC6cJXt7nGMIcJEpn34QOGS.jpg', '2026-05-26 13:45:38', '2026-05-26 13:45:38'),
(144, 79, 'Shiv shakti awasthi', '8960461055', 'uploads/aadhaar/YR69prBPu79wLQ2Ug9tw4hGYEt7CdGcAg9wJCehX.jpg,uploads/aadhaar/25greifAZPtwNCtEcp8g85Xq5QGiZizjYpiu4wiZ.jpg', '2026-05-26 14:02:35', '2026-05-26 14:16:45'),
(145, 79, 'Ayushi Tripathi', NULL, 'uploads/aadhaar/UDSoyuUpM9YkuTareXE9N4smobzEGMhTjYnrcGlt.jpg,uploads/aadhaar/H2h6XheNXto4vaIaoc8dDkMah77s0wwccjljvljd.jpg', '2026-05-26 14:16:45', '2026-05-26 14:16:45'),
(146, 80, 'Ajayveer', '9889899110', NULL, '2026-05-26 14:18:43', '2026-05-26 14:18:43'),
(147, 80, 'Chitra shukla', NULL, NULL, '2026-05-26 14:18:43', '2026-05-26 14:18:43'),
(148, 81, 'Vineet mishra', '7985449941', 'uploads/aadhaar/XV6soXdNnQs3T92okvSVZ7nliNOmlmb1YQFon5VR.jpg,uploads/aadhaar/evBajLjTtoL74ojqFiJcLcKvUGYSUFSknh0XzGNq.jpg', '2026-05-26 14:21:46', '2026-05-26 14:21:46'),
(149, 81, 'Mohd. Shaqlain', NULL, 'uploads/aadhaar/VtUpyXPRVUlshwC2VyYLx4y5hWRr0dhKpEpNnq9D.jpg', '2026-05-26 14:21:46', '2026-05-26 14:21:46'),
(150, 82, 'Ravi pratap singh', '7754046760', 'uploads/aadhaar/Jgaa7MdoitxSbxGK4F5oXBhHo4WnRuMGVeM3wCQm.jpg', '2026-05-27 21:43:48', '2026-05-27 21:43:48'),
(151, 82, 'Swapna shaw', NULL, 'uploads/aadhaar/Zwp7aq1Rd3xqca8x83NtWPSo1T4CMWEMDVlzY9vl.jpg,uploads/aadhaar/GK4vVblnTOWKSxY4YKpHcvEFDmpSZxueyTI1GcWh.jpg', '2026-05-27 21:43:48', '2026-05-27 21:43:48'),
(152, 83, 'Avinash singh', '7505666555', NULL, '2026-05-27 21:47:29', '2026-05-27 21:47:29'),
(153, 83, 'Shailendra Singh Sengar', NULL, 'uploads/aadhaar/qlzcwlWpKcQ1Jmk3KRqIWUke9IqkKGY6fxWLX5wy.jpg,uploads/aadhaar/mEVua5xdtrJSvIPb0BB5ci4NPHgfGNFGB9JlXgJM.jpg', '2026-05-27 21:47:29', '2026-05-27 21:47:29'),
(154, 84, 'Hari Om', '7505666555', 'uploads/aadhaar/erjSYYzTMnns18DCwO7PbwgeOfbmk5hkHeayhDDS.jpg,uploads/aadhaar/uNPlisHui0aW1fzzMetvz7ip88DspGfmqSPdG3dv.jpg', '2026-05-27 21:49:46', '2026-05-27 21:49:46'),
(155, 84, 'Seeta Sharan', NULL, 'uploads/aadhaar/LQ3tnupm7mEW2MhoGySMh8viEI0ch7huWea2oxtF.jpg,uploads/aadhaar/IZk1FoSTgTCeVsbdJpo9s3AKBCHlboXWS6k9UdZb.jpg', '2026-05-27 21:49:46', '2026-05-27 21:49:46'),
(156, 85, 'Radhika tiwari', '7666861273', 'uploads/aadhaar/t7R1PZ3ygxjdEw3F5h8vjImhQi2wo6aGSa887iYT.jpg,uploads/aadhaar/LjVNKjlFoTaMckmXyRAGHh7wKrM79AEtjNsvXPx3.jpg', '2026-05-27 21:55:19', '2026-05-27 21:55:19'),
(157, 85, 'Siddharth khanna', NULL, 'uploads/aadhaar/Jke0eGlYZqncCkwvwKdb4etDFb8O8knAEZQ7YTCI.jpg', '2026-05-27 21:55:19', '2026-05-27 21:55:19'),
(158, 86, 'Vijay prakash tiwari', '9473655147', NULL, '2026-05-28 13:15:08', '2026-05-28 13:15:08'),
(159, 86, 'rebika rai', NULL, NULL, '2026-05-28 13:15:08', '2026-05-28 13:15:08'),
(160, 87, 'Saurabh Kapoor', '7860100085', 'uploads/aadhaar/nYTDpR83C2yKCWpYdsk5RR1o5BRrt83JyGySmcm4.jpg,uploads/aadhaar/yC5dMFCE2mh4rS6EkdAcu8Tnm2Uvox7jg1tql4ox.jpg', '2026-05-28 13:57:12', '2026-05-28 13:57:12'),
(161, 87, 'Vartika Tripathi', NULL, 'uploads/aadhaar/VJS8pGpvBcbPdFADT4GaExQFovY8XbN2EaokKGay.jpg,uploads/aadhaar/ZotSlMiblaQstz0x6YMTjd5YL4OEVyo1LUntdDp6.jpg', '2026-05-28 13:57:12', '2026-05-28 13:57:12'),
(162, 88, 'Aditya jaiswal', '8953650404', 'uploads/aadhaar/RjumsO03VcIjBc2NjUL153vPhLB9hTVv0QHJQuEu.jpg,uploads/aadhaar/GZBgsKzp7OXZC3P8LdfGMvJiV0Y5WMzi3lvvizdg.jpg', '2026-05-29 21:06:13', '2026-05-29 21:06:13'),
(163, 88, 'Arpita mishra', NULL, 'uploads/aadhaar/NKXi4u9B0BSEEk25Fbj3psHjUce8EFpxTGczEmGE.jpg,uploads/aadhaar/FxPZwHXDkEZCA4McqNcPutuMor6BmyYfeTaEB5zB.jpg', '2026-05-29 21:06:13', '2026-05-29 21:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `offline_booking_payments`
--

CREATE TABLE `offline_booking_payments` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `payment_mode` enum('gpay','phonepe','netbanking','cash') DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `transferred_owner` enum('Yes','No') NOT NULL DEFAULT 'No',
  `payment_screenshot` text DEFAULT NULL,
  `receivedby` varchar(255) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offline_booking_payments`
--

INSERT INTO `offline_booking_payments` (`id`, `booking_id`, `paid_amount`, `payment_mode`, `transaction_id`, `transferred_owner`, `payment_screenshot`, `receivedby`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2100.00, 'gpay', 'INV53535', 'Yes', NULL, NULL, '2026-05-01 04:54:25', '2026-05-01 11:54:25', '2026-05-05 22:14:52'),
(2, 2, 2070.00, 'gpay', 'INV57238', 'Yes', NULL, NULL, '2026-05-01 10:24:20', '2026-05-01 17:24:20', '2026-05-05 15:15:30'),
(3, 3, 1863.00, 'gpay', 'INV54946', 'Yes', NULL, NULL, '2026-05-01 10:43:39', '2026-05-01 17:43:39', '2026-05-05 15:15:34'),
(4, 4, 2000.00, 'gpay', 'INV89926', 'Yes', NULL, NULL, '2026-05-01 13:23:27', '2026-05-01 20:23:27', '2026-05-05 15:15:37'),
(5, 5, 2200.00, 'gpay', 'INV31326', 'Yes', NULL, NULL, '2026-05-01 14:10:38', '2026-05-01 21:10:38', '2026-05-05 15:16:00'),
(6, 6, 2570.00, 'gpay', 'INV70035', 'Yes', NULL, NULL, '2026-05-02 04:49:33', '2026-05-02 11:49:33', '2026-05-05 15:16:05'),
(7, 7, 3615.60, 'gpay', 'INV97370', 'Yes', NULL, NULL, '2026-05-02 06:19:27', '2026-05-02 13:19:27', '2026-05-05 15:17:57'),
(8, 8, 500.00, 'gpay', 'INV25181', 'Yes', NULL, NULL, '2026-05-02 06:21:51', '2026-05-02 13:21:51', '2026-05-05 15:18:02'),
(9, 9, 1500.00, 'gpay', 'INV22003', 'Yes', NULL, NULL, '2026-05-02 07:06:24', '2026-05-02 14:06:24', '2026-05-05 15:18:06'),
(10, 4, 2000.00, 'gpay', 'INV26781', 'Yes', NULL, NULL, '2026-05-02 07:19:38', '2026-05-02 14:19:38', '2026-05-05 15:18:24'),
(11, 8, 2000.00, 'cash', 'INV56137', 'Yes', NULL, 'Sanjeet', '2026-05-02 07:23:14', '2026-05-02 14:23:14', '2026-05-05 15:18:27'),
(12, 10, 2300.00, 'gpay', 'INV75098', 'Yes', NULL, NULL, '2026-05-04 04:23:53', '2026-05-04 11:23:53', '2026-05-05 15:18:31'),
(13, 11, 2500.00, 'gpay', 'INV24184', 'Yes', NULL, NULL, '2026-05-04 05:54:03', '2026-05-04 12:54:03', '2026-05-05 15:18:35'),
(14, 12, 2000.00, 'gpay', 'INV20243', 'Yes', NULL, NULL, '2026-05-04 05:59:33', '2026-05-04 12:59:33', '2026-05-05 15:18:39'),
(15, 13, 2200.00, 'gpay', 'INV43322', 'Yes', NULL, NULL, '2026-05-04 06:04:15', '2026-05-04 13:04:15', '2026-05-05 15:18:42'),
(16, 14, 2000.00, 'gpay', 'INV99127', 'Yes', NULL, NULL, '2026-05-04 06:07:35', '2026-05-04 13:07:35', '2026-05-05 15:18:45'),
(17, 15, 2511.00, 'gpay', 'INV97688', 'Yes', NULL, NULL, '2026-05-04 06:22:23', '2026-05-04 13:22:23', '2026-05-05 15:18:49'),
(18, 16, 1500.00, 'gpay', 'INV61305', 'Yes', NULL, NULL, '2026-05-04 06:34:02', '2026-05-04 13:34:02', '2026-05-05 15:18:53'),
(19, 17, 2438.00, 'gpay', 'INV48324', 'Yes', NULL, NULL, '2026-05-04 07:03:53', '2026-05-04 14:03:53', '2026-05-05 15:18:56'),
(20, 18, 1932.00, 'gpay', 'INV20714', 'Yes', NULL, NULL, '2026-05-04 09:43:31', '2026-05-04 16:43:31', '2026-05-05 15:19:00'),
(21, 19, 2700.00, 'gpay', 'INV12069', 'Yes', NULL, NULL, '2026-05-04 10:27:17', '2026-05-04 17:27:17', '2026-05-05 15:19:04'),
(22, 20, 1713.00, 'gpay', 'INV19170', 'Yes', NULL, NULL, '2026-05-04 15:34:23', '2026-05-04 22:34:23', '2026-05-05 15:19:41'),
(23, 21, 1800.00, 'gpay', 'INV92727', 'Yes', NULL, NULL, '2026-05-05 09:31:54', '2026-05-05 16:31:54', '2026-05-05 15:19:50'),
(24, 22, 2200.00, 'gpay', 'INV62235', 'Yes', NULL, NULL, '2026-05-05 11:15:50', '2026-05-05 18:15:50', '2026-05-06 08:04:24'),
(25, 23, 2200.00, 'gpay', 'INV16375', 'Yes', NULL, NULL, '2026-05-05 11:17:41', '2026-05-05 18:17:41', '2026-05-06 08:04:28'),
(27, 25, 2500.00, 'gpay', 'INV16658', 'Yes', NULL, NULL, '2026-05-05 14:40:51', '2026-05-05 21:40:51', '2026-05-06 08:04:35'),
(28, 22, 2500.00, 'gpay', 'INV81281', 'Yes', NULL, NULL, '2026-05-05 14:47:45', '2026-05-05 21:47:45', '2026-05-06 08:04:39'),
(29, 23, 2500.00, 'gpay', 'INV75136', 'Yes', NULL, NULL, '2026-05-05 14:49:11', '2026-05-05 21:49:11', '2026-05-06 08:04:42'),
(30, 26, 2500.00, 'gpay', 'INV96303', 'Yes', NULL, NULL, '2026-05-06 04:12:56', '2026-05-06 11:12:56', '2026-05-06 08:04:46'),
(31, 27, 2600.00, 'gpay', 'INV96096', 'Yes', 'uploads/bookings/SFJbX5iqa4ZHEKjcPowIigGYTI6w8UThapP9v8ew.jpg', NULL, '2026-05-06 07:35:12', '2026-05-06 14:35:12', '2026-05-06 14:35:12'),
(32, 28, 1500.00, 'gpay', 'INV34468', 'Yes', NULL, NULL, '2026-05-06 07:40:25', '2026-05-06 14:40:25', '2026-05-06 08:04:50'),
(33, 29, 2000.00, 'gpay', 'INV26984', 'Yes', 'uploads/bookings/4f9Wrwcnl6x08q3unlehnuUf6YuocSItUhutmL09.webp', NULL, '2026-05-06 08:02:55', '2026-05-06 15:02:55', '2026-05-06 15:02:55'),
(34, 24, 6000.00, 'cash', 'INV25047', 'No', NULL, 'Sanjeet', '2026-05-06 08:06:49', '2026-05-06 15:06:49', '2026-05-08 15:56:28'),
(63, 52, 1800.00, 'gpay', 'INV10914', 'No', NULL, NULL, '2026-05-13 10:48:21', '2026-05-13 17:48:21', '2026-05-13 17:48:21'),
(36, 30, 1711.00, 'gpay', 'INV43846', 'No', NULL, NULL, '2026-05-07 04:55:58', '2026-05-07 11:55:58', '2026-05-07 11:55:58'),
(37, 31, 1911.00, 'gpay', 'INV40630', 'No', NULL, NULL, '2026-05-07 05:37:29', '2026-05-07 12:37:29', '2026-05-07 12:37:29'),
(38, 32, 6072.00, 'gpay', 'INV31335', 'No', NULL, NULL, '2026-05-07 05:43:36', '2026-05-07 12:43:36', '2026-05-07 12:43:36'),
(39, 33, 2000.00, 'gpay', 'INV34026', 'No', NULL, NULL, '2026-05-07 11:46:08', '2026-05-07 18:46:08', '2026-05-07 18:46:08'),
(40, 34, 1000.00, 'gpay', 'INV43935', 'Yes', NULL, NULL, '2026-05-07 11:50:38', '2026-05-07 18:50:38', '2026-05-11 13:01:02'),
(41, 35, 2500.00, 'gpay', 'INV70708', 'No', NULL, NULL, '2026-05-07 11:54:42', '2026-05-07 18:54:42', '2026-05-07 18:54:42'),
(42, 36, 1886.00, 'gpay', 'INV62159', 'No', NULL, NULL, '2026-05-07 13:51:36', '2026-05-07 20:51:36', '2026-05-08 14:59:39'),
(43, 37, 1000.00, 'gpay', 'INV39870', 'No', NULL, NULL, '2026-05-08 07:36:57', '2026-05-08 14:36:57', '2026-05-09 17:20:57'),
(44, 38, 1200.00, 'gpay', 'INV98714', 'Yes', NULL, NULL, '2026-05-08 07:44:00', '2026-05-08 14:44:00', '2026-05-08 15:43:47'),
(45, 39, 3822.00, 'gpay', 'INV79812', 'No', NULL, NULL, '2026-05-08 07:46:14', '2026-05-08 14:46:14', '2026-05-09 17:13:15'),
(46, 40, 1886.00, 'gpay', 'INV78566', 'No', NULL, NULL, '2026-05-08 07:50:14', '2026-05-08 14:50:14', '2026-05-08 14:50:14'),
(47, 41, 1725.00, 'gpay', 'INV59671', 'No', NULL, NULL, '2026-05-08 07:52:30', '2026-05-08 14:52:30', '2026-05-08 14:52:30'),
(48, 38, 1200.00, 'gpay', 'INV76514', 'Yes', NULL, NULL, '2026-05-08 08:43:40', '2026-05-08 15:43:40', '2026-05-08 15:43:40'),
(49, 42, 4000.00, 'gpay', 'INV66749', 'Yes', NULL, NULL, '2026-05-08 11:41:36', '2026-05-08 18:41:36', '2026-05-11 12:55:04'),
(50, 43, 2500.00, 'gpay', 'INV90249', 'No', NULL, NULL, '2026-05-09 10:12:28', '2026-05-09 17:12:28', '2026-05-09 17:12:28'),
(51, 44, 2700.00, 'gpay', 'INV93723', 'No', NULL, NULL, '2026-05-09 10:23:23', '2026-05-09 17:23:23', '2026-05-09 17:23:23'),
(52, 45, 5000.00, 'gpay', 'INV58451', 'No', NULL, NULL, '2026-05-09 10:27:42', '2026-05-09 17:27:42', '2026-05-11 05:41:16'),
(53, 43, 2500.00, 'cash', 'INV66238', 'Yes', NULL, 'Sanjeet', '2026-05-11 05:51:57', '2026-05-11 12:51:57', '2026-05-11 12:51:57'),
(54, 39, 5000.00, 'gpay', 'INV93322', 'No', NULL, NULL, '2026-05-11 05:53:01', '2026-05-11 12:53:01', '2026-05-11 12:53:01'),
(55, 46, 1911.00, 'gpay', 'INV99967', 'No', NULL, NULL, '2026-05-11 05:58:18', '2026-05-11 12:58:18', '2026-05-11 12:58:18'),
(56, 34, 3000.00, 'gpay', 'INV86302', 'Yes', NULL, NULL, '2026-05-11 06:00:53', '2026-05-11 13:00:53', '2026-05-11 13:01:07'),
(57, 34, 2000.00, 'gpay', 'INV89681', 'Yes', NULL, NULL, '2026-05-11 06:03:29', '2026-05-11 13:03:29', '2026-05-11 13:03:29'),
(58, 47, 2400.00, 'gpay', 'INV74077', 'No', NULL, NULL, '2026-05-12 06:43:32', '2026-05-12 13:43:32', '2026-05-12 13:43:32'),
(59, 48, 2200.00, 'gpay', 'INV78843', 'No', NULL, NULL, '2026-05-12 07:30:44', '2026-05-12 14:30:44', '2026-05-12 14:30:44'),
(60, 49, 1500.00, 'gpay', 'INV11313', 'No', NULL, NULL, '2026-05-12 10:12:28', '2026-05-12 17:12:28', '2026-05-12 17:12:28'),
(61, 50, 0.00, 'gpay', 'INV24688', 'No', NULL, NULL, '2026-05-12 10:26:49', '2026-05-12 17:26:49', '2026-05-12 17:26:49'),
(62, 51, 2000.00, 'gpay', 'INV89269', 'No', NULL, NULL, '2026-05-13 10:31:57', '2026-05-13 17:31:57', '2026-05-13 17:31:57'),
(79, 64, 1500.00, 'gpay', 'INV97268', 'No', NULL, NULL, '2026-05-17 14:35:49', '2026-05-17 21:35:49', '2026-05-17 21:35:49'),
(65, 54, 2500.00, 'gpay', 'INV26550', 'No', NULL, NULL, '2026-05-13 11:45:08', '2026-05-13 18:45:08', '2026-05-13 18:45:08'),
(66, 55, 7500.00, 'gpay', 'INV41341', 'No', NULL, NULL, '2026-05-14 04:55:14', '2026-05-14 11:55:14', '2026-05-14 11:55:14'),
(67, 56, 5000.00, 'gpay', 'INV82675', 'No', NULL, NULL, '2026-05-14 05:00:41', '2026-05-14 12:00:41', '2026-05-14 12:04:31'),
(68, 57, 2000.00, 'gpay', 'INV49235', 'No', NULL, NULL, '2026-05-14 06:31:13', '2026-05-14 13:31:13', '2026-05-14 13:31:13'),
(69, 58, 2000.00, 'gpay', 'INV83529', 'No', NULL, NULL, '2026-05-14 06:48:49', '2026-05-14 13:48:49', '2026-05-14 13:52:27'),
(70, 58, 500.00, 'gpay', 'INV73324', 'No', NULL, NULL, '2026-05-14 06:52:40', '2026-05-14 13:52:40', '2026-05-14 13:52:40'),
(71, 12, 5500.00, 'cash', 'INV21119', 'Yes', NULL, 'Sanjeet', '2026-05-15 06:19:51', '2026-05-15 13:19:51', '2026-05-18 13:36:43'),
(72, 59, 2000.00, 'gpay', 'INV47918', 'No', NULL, NULL, '2026-05-15 07:49:44', '2026-05-15 14:49:44', '2026-05-15 14:54:07'),
(73, 60, 2000.00, 'gpay', 'INV41641', 'No', NULL, NULL, '2026-05-15 07:53:55', '2026-05-15 14:53:55', '2026-05-15 14:53:55'),
(74, 61, 2000.00, 'cash', 'INV21747', 'Yes', NULL, 'zuzusty inc', '2026-05-16 12:14:33', '2026-05-16 19:14:33', '2026-05-18 13:52:04'),
(75, 62, 500.00, 'gpay', 'INV10348', 'No', NULL, NULL, '2026-05-16 12:19:16', '2026-05-16 19:19:16', '2026-05-16 19:19:16'),
(76, 62, 1700.00, 'cash', 'INV67623', 'No', NULL, 'Sanjeet', '2026-05-16 12:19:50', '2026-05-16 19:19:50', '2026-05-16 19:19:50'),
(77, 63, 3800.00, 'gpay', 'INV42803', 'No', NULL, NULL, '2026-05-16 12:23:18', '2026-05-16 19:23:18', '2026-05-16 19:23:18'),
(78, 53, 8000.00, 'cash', 'INV21544', 'No', NULL, 'Raaj', '2026-05-17 14:27:15', '2026-05-17 21:27:15', '2026-05-17 21:27:15'),
(80, 12, 1500.00, 'gpay', 'INV58067', 'No', NULL, NULL, '2026-05-18 06:49:23', '2026-05-18 13:49:23', '2026-05-18 13:49:23'),
(81, 65, 0.00, 'gpay', 'INV52300', 'No', NULL, NULL, '2026-05-18 06:59:59', '2026-05-18 13:59:59', '2026-05-18 13:59:59'),
(82, 66, 0.00, 'gpay', 'INV85503', 'No', NULL, NULL, '2026-05-18 07:05:41', '2026-05-18 14:05:41', '2026-05-18 14:05:41'),
(83, 55, 2400.00, 'gpay', 'INV60683', 'No', NULL, NULL, '2026-05-18 07:20:01', '2026-05-18 14:20:01', '2026-05-18 14:20:01'),
(84, 67, 1500.00, 'gpay', 'INV65535', 'No', NULL, NULL, '2026-05-20 15:43:17', '2026-05-20 22:43:17', '2026-05-20 22:43:17'),
(85, 68, 2000.00, 'cash', 'INV32047', 'No', NULL, 'Sanjeet', '2026-05-20 15:46:03', '2026-05-20 22:46:03', '2026-05-20 22:46:03'),
(86, 69, 2000.00, 'gpay', 'INV45254', 'No', NULL, NULL, '2026-05-22 06:07:33', '2026-05-22 13:07:33', '2026-05-22 13:07:33'),
(87, 70, 9000.00, 'cash', 'INV84893', 'No', NULL, 'Sanjeet', '2026-05-22 06:08:40', '2026-05-22 13:08:40', '2026-05-26 13:58:36'),
(88, 56, 8200.00, 'gpay', 'INV30482', 'No', NULL, NULL, '2026-05-22 14:20:51', '2026-05-22 21:20:51', '2026-05-22 21:20:51'),
(89, 71, 4400.00, 'gpay', 'INV70591', 'No', NULL, NULL, '2026-05-22 14:24:04', '2026-05-22 21:24:04', '2026-05-22 21:24:04'),
(90, 72, 2000.00, 'gpay', 'INV31339', 'No', NULL, NULL, '2026-05-22 14:26:44', '2026-05-22 21:26:44', '2026-05-22 21:26:44'),
(91, 73, 5000.00, 'gpay', 'INV14660', 'No', NULL, NULL, '2026-05-22 14:29:43', '2026-05-22 21:29:43', '2026-05-22 21:33:57'),
(92, 74, 2500.00, 'gpay', 'INV14356', 'No', NULL, NULL, '2026-05-23 11:16:58', '2026-05-23 18:16:58', '2026-05-23 18:16:58'),
(93, 75, 1250.00, 'gpay', 'INV41084', 'No', NULL, NULL, '2026-05-23 12:09:34', '2026-05-23 19:09:34', '2026-05-23 19:09:34'),
(94, 76, 1800.00, 'gpay', 'INV55049', 'No', NULL, NULL, '2026-05-23 13:50:11', '2026-05-23 20:50:11', '2026-05-23 20:50:11'),
(95, 77, 5734.00, 'gpay', 'INV24289', 'No', NULL, NULL, '2026-05-24 08:05:51', '2026-05-24 15:05:51', '2026-05-24 15:05:51'),
(96, 78, 1500.00, 'gpay', 'INV26796', 'No', NULL, NULL, '2026-05-26 06:45:38', '2026-05-26 13:45:38', '2026-05-26 13:45:38'),
(97, 79, 900.00, 'gpay', 'INV27975', 'No', NULL, NULL, '2026-05-26 07:02:35', '2026-05-26 14:02:35', '2026-05-27 21:44:12'),
(98, 80, 2000.00, 'cash', 'INV23375', 'No', NULL, 'Sanjeet', '2026-05-26 07:18:43', '2026-05-26 14:18:43', '2026-05-26 14:18:43'),
(99, 81, 2000.00, 'gpay', 'INV85716', 'No', NULL, NULL, '2026-05-26 07:21:46', '2026-05-26 14:21:46', '2026-05-26 14:21:46'),
(100, 82, 2000.00, 'gpay', 'INV68921', 'No', NULL, NULL, '2026-05-27 14:43:48', '2026-05-27 21:43:48', '2026-05-27 21:43:48'),
(101, 83, 1000.00, 'gpay', 'INV97738', 'No', NULL, NULL, '2026-05-27 14:47:29', '2026-05-27 21:47:29', '2026-05-27 14:51:36'),
(104, 85, 2000.00, 'gpay', 'INV62859', 'No', NULL, NULL, '2026-05-27 14:55:19', '2026-05-27 21:55:19', '2026-05-27 21:55:19'),
(103, 84, 1000.00, 'gpay', 'INV28147', 'No', NULL, NULL, '2026-05-27 14:52:33', '2026-05-27 21:52:33', '2026-05-27 21:52:33'),
(105, 83, 1000.00, 'gpay', 'INV45016', 'No', NULL, NULL, '2026-05-28 06:13:31', '2026-05-28 13:13:31', '2026-05-28 13:13:31'),
(106, 86, 2200.00, 'gpay', 'INV71657', 'No', NULL, NULL, '2026-05-28 06:15:08', '2026-05-28 13:15:08', '2026-05-28 13:15:08'),
(107, 87, 5000.00, 'gpay', 'INV77319', 'No', NULL, NULL, '2026-05-28 06:57:12', '2026-05-28 13:57:12', '2026-05-28 13:57:12'),
(108, 88, 2200.00, 'gpay', 'INV40587', 'No', NULL, NULL, '2026-05-29 14:06:13', '2026-05-29 21:06:13', '2026-05-29 21:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `heading`, `name`, `title`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Manage Role', 'role.create', 'Create', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(2, 'Manage Role', 'role.view', 'View', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(3, 'Manage Role', 'role.edit', 'Edit', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(4, 'Manage Role', 'role.delete', 'Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(5, 'Manage Admin Users', 'adminuser.view', 'View', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(6, 'Manage Admin Users', 'adminuser.create', 'Create', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(7, 'Manage Admin Users', 'adminuser.edit', 'Edit', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(8, 'Manage Admin Users', 'adminuser.delete', 'Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(9, 'Manage Admin Users', 'adminuser.status', 'Status', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(10, 'Manage Admin Users', 'adminuser.show-profile', 'View Profile', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(11, 'Manage Property', 'property.view', 'View', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(12, 'Manage Property', 'property.create', 'Create', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(13, 'Manage Property', 'property.edit', 'Edit', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(14, 'Manage Property', 'property.delete', 'Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(15, 'Manage Property', 'property.status', 'Status', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(16, 'Manage Property', 'property.images', 'Add Images', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(17, 'Manage Property', 'propertyimages.delete', 'Image Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(18, 'Manage Amenity', 'amenity.create', 'Create', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(19, 'Manage Amenity', 'amenity.view', 'View', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(20, 'Manage Amenity', 'amenity.edit', 'Edit', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(21, 'Manage Amenity', 'amenity.delete', 'Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(22, 'Offline Booking', 'offline-booking.create', 'Create', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(23, 'Offline Booking', 'offline-booking.view', 'View', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(24, 'Offline Booking', 'offline-booking.edit', 'Edit', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(25, 'Offline Booking', 'offline-booking.delete', 'Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(26, 'Offline Booking', 'offline-booking.extended', 'Booking Extended', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(27, 'Offline Booking', 'offline-booking.payremainingamount', 'Pay Remaining Amount', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(28, 'Offline Booking', 'offline-booking.paymenthistory', 'Payment History', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(29, 'Offline Booking', 'offline-booking.exportexcel', 'Export Excel', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(30, 'Offline Booking', 'offline-booking.exportcsv', 'Export Csv', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(31, 'Manage Expense', 'expense.create', 'Create', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(32, 'Manage Expense', 'expense.view', 'View', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(33, 'Manage Expense', 'expense.edit', 'Edit', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(34, 'Manage Expense', 'expense.delete', 'Delete', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(35, 'Manage Expense', 'expense.exportexcel', 'Export Excel', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09'),
(36, 'Manage Expense', 'expense.exportcsv', 'Export Csv', 'admin', '2025-07-09 06:11:09', '2025-07-09 06:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `city_id` bigint(20) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `house_rules` longtext DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_pricings`
--

CREATE TABLE `property_pricings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `weekend_price` decimal(10,2) DEFAULT NULL,
  `festival_price` decimal(10,2) DEFAULT NULL,
  `min_stay` int(11) NOT NULL DEFAULT 1,
  `max_stay` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '2025-07-09 11:41:09', '2025-07-09 11:41:09'),
(2, 'Staff', 'admin', '2025-07-11 10:07:54', '2025-07-11 10:07:54');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6yeuLa6ZbeHqdYNXzenWpX3RfMVryvS7bO1E46Z6', 1, '223.181.124.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNTZqM0RBUDdMUVg2WVRtSXRwR082SzVZelNEemVSa1BVdzQ2UFNSViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8venV6dXN0YXkuenV6dWNvZGVzLmNvbS9hZG1pbi9vZmZsaW5lYm9va2luZ3MiO3M6NToicm91dGUiO3M6Mjc6ImFkbWluLm9mZmxpbmVib29raW5ncy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1779954423),
('jNkVpTUyCGuhapcPzWlhMYFyci6esCAcqLT14FtC', 1, '49.37.66.46', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2V4UmE4Sjlnd2FLTHRUcUJKTkFBdWlYWUp5dlRlaDZGbU1ZRjVxbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjE6Imh0dHBzOi8venV6dXN0YXkuenV6dWNvZGVzLmNvbS9hZG1pbi9vZmZsaW5lLWJvb2tpbmdzL3JlcG9ydHMiO3M6NToicm91dGUiO3M6Mjk6ImFkbWluLm9mZmxpbmVib29raW5ncy5yZXBvcnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1780063554),
('lgSbhboxmboF1nC8zazUCsz7Lx1BPCBWoNAZEdKj', 1, '223.181.124.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieHVhRXNkR0dZMFVST1Y3N2pudVBxUk5PbmFsdk1MUlc4WHZjTzdzZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjYxOiJodHRwczovL3p1enVzdGF5Lnp1enVjb2Rlcy5jb20vYWRtaW4vb2ZmbGluZS1ib29raW5ncy9leUpwZGlJNklqbHNVREZEVDJ4T1pscHBlbll5ZFM5SGRGbG9ORUU5UFNJc0luWmhiSFZsSWpvaVNIcGlkWGhaYzAxM2FsUnlWSEl3U1ZGcmFGZ3pkejA5SWl3aWJXRmpJam9pTWprM1kyUmtObVUzWVRWbE9XSmhNbVJpTjJSaE56RTJObVkxWVdJek56UmpZelpoTWpCbFpUWmtPR1k0WldRd01tTXlNR00yWmpRNE1UbGlOemN3TWlJc0luUmhaeUk2SWlKOS9leHRlbmQiO3M6NToicm91dGUiO3M6Mjg6ImFkbWluLm9mZmxpbmVib29raW5ncy5leHRlbmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1779976823),
('mIkHPey9t9sFDyA83qEEE4soSQ0GOrmFm6D5POcp', 1, '106.219.170.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjRJeHcxWXlFMzQwRURYQ1RSbTJuWURFRmQ0Z1lVTWp3S3JsWkt2eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjE6Imh0dHBzOi8venV6dXN0YXkuenV6dWNvZGVzLmNvbS9hZG1pbi9vZmZsaW5lLWJvb2tpbmdzL3JlcG9ydHMiO3M6NToicm91dGUiO3M6Mjk6ImFkbWluLm9mZmxpbmVib29raW5ncy5yZXBvcnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1780063577),
('O9pMYqp60SZUUIyFnU3HRchpJd75lb5OMfCEKFNK', NULL, '223.181.124.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclZSVWRnUEo4MVFwczg4cDVkTTlIQ2tVeTI3N2tFdGQyRDM2bm9CTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8venV6dXN0YXkuenV6dWNvZGVzLmNvbS9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1779964535);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_name` varchar(191) NOT NULL,
  `setting_value` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'booking_edit_time_in_day', '7', '2026-04-09 09:49:28', '2026-04-09 10:13:27'),
(2, 'booking_extend_in_day', '15', '2026-04-09 10:16:27', '2026-05-18 06:37:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `pincode` varchar(15) DEFAULT NULL,
  `dealer_type` varchar(25) DEFAULT NULL,
  `id_proof` varchar(200) DEFAULT NULL,
  `profile_image` varchar(200) DEFAULT NULL,
  `added_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role_id`, `password`, `mobile`, `address`, `city`, `pincode`, `dealer_type`, `id_proof`, `profile_image`, `added_by`, `status`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin1@gmail.com', NULL, 1, '$2y$10$cYl9oyDQHfHDbDJag5xsJO8GfX6ZRcZA/oMUTWXef7mHPSgEKvpYu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'inactive', NULL, NULL, '2025-07-09 11:41:11', '2026-04-13 15:51:42'),
(2, 'sfsdf', 'as@gmail.com', NULL, 1, '$2y$12$tDMpG2EUzMwqWCdkFPqJ8.5JUYT81gWeK0DTybTA1JrngjLdPzcwS', '8977777777', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'inactive', NULL, NULL, '2026-01-17 06:48:00', '2026-01-17 06:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_metas`
--

CREATE TABLE `user_metas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(191) NOT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`meta_value`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`,`deleted_at`),
  ADD KEY `admins_role_id_foreign` (`role_id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `offline_bookings`
--
ALTER TABLE `offline_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offline_booking_extensions`
--
ALTER TABLE `offline_booking_extensions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `offline_booking_guests`
--
ALTER TABLE `offline_booking_guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `offline_booking_payments`
--
ALTER TABLE `offline_booking_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_property_id` (`property_id`);

--
-- Indexes for table `property_pricings`
--
ALTER TABLE `property_pricings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_property_id` (`property_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_added_by_foreign` (`added_by`);

--
-- Indexes for table `user_metas`
--
ALTER TABLE `user_metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_metas_user_id_meta_key_index` (`user_id`,`meta_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offline_bookings`
--
ALTER TABLE `offline_bookings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `offline_booking_extensions`
--
ALTER TABLE `offline_booking_extensions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `offline_booking_guests`
--
ALTER TABLE `offline_booking_guests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `offline_booking_payments`
--
ALTER TABLE `offline_booking_payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_pricings`
--
ALTER TABLE `property_pricings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_metas`
--
ALTER TABLE `user_metas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_metas`
--
ALTER TABLE `user_metas`
  ADD CONSTRAINT `user_metas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
