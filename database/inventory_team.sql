-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 04:58 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_team`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_change` int NOT NULL,
  `beginning_quantity` int DEFAULT NULL,
  `ending_quantity` int DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `item_id`, `action`, `quantity_change`, `beginning_quantity`, `ending_quantity`, `user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'restock', 20, 10, 30, 10, 'Bulk restock via Start Restocking button', '2026-01-15 01:22:36', '2026-01-15 01:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `available_stock` int NOT NULL DEFAULT '0',
  `reserved_stock` int NOT NULL DEFAULT '0',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_stock` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `beginning_stock_30d` int NOT NULL DEFAULT '0' COMMENT 'Stock 30 days ago',
  `total_requested_30d` int NOT NULL DEFAULT '0' COMMENT 'Total approved requests in last 30 days',
  `total_claimed_30d` int NOT NULL DEFAULT '0' COMMENT 'Total taken in last 30 days',
  `total_restocked_30d` int NOT NULL DEFAULT '0' COMMENT 'Total added in last 30 days',
  `monthly_requested` int NOT NULL DEFAULT '0',
  `monthly_claimed` int NOT NULL DEFAULT '0',
  `monthly_restocked` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `quantity`, `available_stock`, `reserved_stock`, `is_available`, `unit`, `minimum_stock`, `created_at`, `updated_at`, `beginning_stock_30d`, `total_requested_30d`, `total_claimed_30d`, `total_restocked_30d`, `monthly_requested`, `monthly_claimed`, `monthly_restocked`) VALUES
(1, 'Alcohol', 25, 20, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-15 02:22:36', 0, 0, 0, 0, 0, 0, 0),
(2, 'Ball Pen', 150, 0, 0, 1, 'piece', 30, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(3, 'Ballpen, Black', 1, -98, 0, 1, 'piece', 20, '2026-01-14 06:35:43', '2026-01-15 03:17:52', 0, 0, 0, 0, 0, 0, 0),
(4, 'Ballpen, Blue', 100, 0, 0, 1, 'piece', 20, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(5, 'Binder Clip', 15, 0, 0, 1, 'box', 3, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(6, 'Bond Paper (A4)', 5000, 0, 0, 1, 'ream', 1000, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(7, 'Bond Paper (Legal)', 3000, 0, 0, 1, 'ream', 500, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(8, 'Box, Landbank', 0, -10, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-15 03:17:47', 0, 0, 0, 0, 0, 0, 0),
(9, 'Brown Envelope', 200, 0, 0, 1, 'piece', 50, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(10, 'Correction Tape', 25, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(11, 'Cutter', 15, 0, 0, 1, 'piece', 3, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(12, 'DTR', 50, 40, 10, 1, 'pack', 10, '2026-01-14 06:35:43', '2026-01-14 06:55:37', 0, 0, 0, 0, 0, 0, 0),
(13, 'Disk, Blank', 50, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(14, 'Diskette', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(15, 'Divider, Loan', 20, 0, 0, 1, 'set', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(16, 'Envelope, Brown', 200, 180, 20, 1, 'piece', 50, '2026-01-14 06:35:43', '2026-01-15 01:20:45', 0, 0, 0, 0, 0, 0, 0),
(17, 'Envelope, Small (landbank)', 100, 0, 0, 1, 'piece', 20, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(18, 'Eraser', 50, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(19, 'Expandable Folder', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(20, 'Fastener', 20, 0, 0, 1, 'box', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(21, 'Folder, Brown', 0, -40, 0, 1, 'piece', 10, '2026-01-14 06:35:43', '2026-01-15 03:17:43', 0, 0, 0, 0, 0, 0, 0),
(22, 'Folder, Expandable', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(23, 'Folder, Loan, Green', 25, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(24, 'Folder, Loan, Red', 25, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(25, 'Green Proposal', 40, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(26, 'Highlighter', 60, 0, 0, 1, 'piece', 15, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(27, 'Ink, Black, 003', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(28, 'Ink, Black, 664', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(29, 'Ink, Cyan, 003', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(30, 'Ink, Cyan, 664', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(31, 'Ink, Magenta, 003', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(32, 'Ink, Magenta, 664', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(33, 'Ink, Stamp pad', 15, 0, 0, 1, 'piece', 3, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(34, 'Ink, Yellow, 003', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 0, 0, 0, 0, 0, 0, 0),
(35, 'Ink, Yellow, 664', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(36, 'Letterhead', 1000, 0, 0, 1, 'ream', 200, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(37, 'Logbook', 20, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(38, 'Looselcaf folder', 20, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(39, 'Mailing Envelope', 150, 0, 0, 1, 'box', 30, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(40, 'Marker, Black', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(41, 'Marker, Blue', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(42, 'Memo Pad/ Note Pad', 50, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(43, 'Notebook', 40, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(44, 'OB Forms', 30, 0, 0, 1, 'pad', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(45, 'Oncol Forms', 25, 0, 0, 1, 'bundle', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(46, 'Packaging Tape', 50, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(47, 'Paper Clip', 80, 0, 0, 1, 'box', 20, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(48, 'Paper clip, jumbo', 20, 0, 0, 1, 'box', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(49, 'Paper, A4', 5000, 0, 0, 1, 'ream', 1000, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(50, 'Paper, Legal', 3000, 0, 0, 1, 'ream', 500, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(51, 'Pencil', 100, 0, 0, 1, 'piece', 20, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(52, 'Pins, Big', 10, 0, 0, 1, 'box', 2, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(53, 'Pins, Push pins', 10, 0, 0, 1, 'box', 2, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(54, 'Pins, Small', 10, 0, 0, 1, 'box', 2, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(55, 'Puncher', 5, 0, 0, 1, 'piece', 1, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(56, 'Red Commercial', 100, 0, 0, 1, 'piece', 25, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(57, 'Rubber Band', 30, 0, 0, 1, 'box', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(58, 'Ruler', 25, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(59, 'Scissor', 15, 0, 0, 1, 'piece', 3, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(60, 'Scotch Tape', 40, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(61, 'Sign pen, Black', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(62, 'Sign pen, Blue', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(63, 'Sign pen, Red', 30, 0, 0, 1, 'piece', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(64, 'Signature Card', 200, 0, 0, 1, 'piece', 50, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(65, 'Stamp pad', 10, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(66, 'Stamp, Dater', 5, 0, 0, 1, 'piece', 1, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(67, 'Staple Remover', 8, 0, 0, 1, 'piece', 2, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(68, 'Staple Wire', 25, 0, 0, 1, 'box', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(69, 'Stapler', 15, 0, 0, 1, 'piece', 3, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(70, 'Sticky Note (127x76mm)', 20, 0, 0, 1, 'pad', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(71, 'Sticky Note (3x4)', 20, 0, 0, 1, 'pad', 5, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(72, 'Tape, Packaging', 50, 0, 0, 1, 'piece', 10, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0),
(73, 'Window Envelope', 100, 0, 0, 1, 'box', 20, '2026-01-14 06:35:44', '2026-01-14 06:35:44', 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_12_04_105651_create_teams_table', 1),
(6, '2025_12_04_105652_add_role_and_team_to_users_table', 1),
(7, '2025_12_04_105652_create_items_table', 1),
(8, '2025_12_04_105652_create_team_requests_table', 1),
(9, '2025_12_08_140950_create_notifications_table', 1),
(10, '2025_12_08_160740_update_notifications_table', 1),
(11, '2025_12_08_160816_recreate_notifications_table', 1),
(12, '2025_12_09_022305_add_is_read_to_notifications_table', 1),
(13, '2025_12_09_022427_convert_to_laravel_notifications', 1),
(14, '2025_12_09_035348_add_email_verification_to_users_table', 1),
(15, '2025_12_09_063134_add_is_active_to_users_table', 1),
(16, '2025_12_09_075214_add_is_read_to_notifications_table', 1),
(17, '2025_12_10_012620_update_existing_user_names', 1),
(18, '2025_12_10_084259_add_password_security_fields_to_users_table', 1),
(19, '2025_12_14_160419_add_is_available_to_items_table', 1),
(20, '2025_12_15_020511_ensure_notifications_schema_consistency', 1),
(21, '2025_12_15_052142_add_password_reset_fields_to_users_table', 1),
(22, '2025_12_16_012118_add_stock_columns_to_items_table', 1),
(23, '2025_12_16_051105_add_profile_photo_path_to_users_table', 1),
(24, '2025_12_16_074904_add_claimed_by_to_team_requests_table', 1),
(25, '2025_12_19_062452_create_inventory_logs_table', 1),
(26, '2025_12_22_015517_create_monthly_reports_table', 1),
(27, '2025_12_22_073233_add_missing_fields_to_monthly_reports_table', 1),
(28, '2025_12_23_004830_fix_notifications_table_schema', 1),
(29, '2026_01_06_103054_add_claimed_status_to_team_requests', 1),
(30, '2026_01_06_160047_fix_duplicate_stock_columns', 1),
(31, '2026_01_06_160249_add_missing_stock_columns_to_items_table', 1),
(32, '2026_01_07_081849_add_profile_photo_path_to_users_table', 1),
(33, '2026_01_08_083945_add_claimed_items_count_to_monthly_reports_table', 1),
(34, '2026_01_08_091351_remove_30day_fields_from_items_table', 1),
(35, '2026_01_08_091529_safe_remove_30day_fields_from_items_table', 1),
(36, '2026_01_08_091908_fix_team_requests_status_column', 1);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_reports`
--

CREATE TABLE `monthly_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `year` year NOT NULL,
  `month` tinyint NOT NULL,
  `beginning_stock_value` int NOT NULL DEFAULT '0',
  `total_requests` int NOT NULL DEFAULT '0',
  `total_restocked` int NOT NULL DEFAULT '0',
  `total_claimed` int NOT NULL DEFAULT '0',
  `ending_stock_value` int NOT NULL DEFAULT '0',
  `most_requested_items` json DEFAULT NULL,
  `fast_depleting_items` json DEFAULT NULL,
  `report_generated_at` timestamp NULL DEFAULT NULL,
  `is_finalized` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monthly_reports`
--

INSERT INTO `monthly_reports` (`id`, `year`, `month`, `beginning_stock_value`, `total_requests`, `total_restocked`, `total_claimed`, `ending_stock_value`, `most_requested_items`, `fast_depleting_items`, `report_generated_at`, `is_finalized`, `created_at`, `updated_at`) VALUES
(1, '2026', 1, 0, 8, 20, 5, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:43', '2026-01-15 07:12:33'),
(2, '2026', 2, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:43', '2026-01-15 07:12:33'),
(3, '2026', 3, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:43', '2026-01-15 07:12:33'),
(4, '2026', 4, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:43', '2026-01-15 07:12:33'),
(5, '2026', 5, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:43', '2026-01-15 07:12:33'),
(6, '2026', 6, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:33'),
(7, '2026', 7, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:33'),
(8, '2026', 8, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:33'),
(9, '2026', 9, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:33', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:33'),
(10, '2026', 10, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:34', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:34'),
(11, '2026', 11, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:34', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:34'),
(12, '2026', 12, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 07:12:34', 0, '2026-01-14 06:36:44', '2026-01-15 07:12:34'),
(13, '2025', 12, 0, 0, 0, 0, 0, '\"{\\\"items\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":6,\\\"name\\\":\\\"Bond Paper (A4)\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":7,\\\"name\\\":\\\"Bond Paper (Legal)\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":8,\\\"name\\\":\\\"Box, Landbank\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":9,\\\"name\\\":\\\"Brown Envelope\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":10,\\\"name\\\":\\\"Correction Tape\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}],\\\"active_teams\\\":[]}\"', '\"[]\"', '2026-01-14 08:48:01', 0, '2026-01-14 06:44:58', '2026-01-14 08:48:01'),
(14, '2025', 1, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(15, '2025', 2, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(16, '2025', 3, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(17, '2025', 4, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(18, '2025', 5, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(19, '2025', 6, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(20, '2025', 7, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(21, '2025', 8, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(22, '2025', 9, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(23, '2025', 10, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0,\\\"total_quantity\\\":null}]\"', '\"[]\"', '2026-01-15 00:27:38', 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38'),
(24, '2025', 11, 0, 0, 0, 0, 0, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Alcohol\\\",\\\"request_count\\\":0},{\\\"id\\\":2,\\\"name\\\":\\\"Ball Pen\\\",\\\"request_count\\\":0},{\\\"id\\\":3,\\\"name\\\":\\\"Ballpen, Black\\\",\\\"request_count\\\":0},{\\\"id\\\":4,\\\"name\\\":\\\"Ballpen, Blue\\\",\\\"request_count\\\":0},{\\\"id\\\":5,\\\"name\\\":\\\"Binder Clip\\\",\\\"request_count\\\":0}]\"', '\"[]\"', NULL, 0, '2026-01-15 00:27:38', '2026-01-15 00:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0c86fb4a-c764-4fd3-9b30-66fa34b63820', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 5 has submitted a new request for Alcohol\", \"type\": \"new_request\", \"items\": [\"Alcohol (Quantity: 5)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 5 from Team 5 requested 5 unit(s) of Alcohol\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 5, \"item_name\": \"Alcohol\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"department\": \"Team 5\", \"request_id\": 3, \"team_number\": null, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:04:19', '2026-01-15 01:04:19'),
('14b3252a-596a-4a8c-8126-e8dc82897048', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 5 has submitted a new request for Ballpen, Black\", \"type\": \"new_request\", \"items\": [\"Ballpen, Black (Quantity: 99)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 5 from Team 5 requested 99 unit(s) of Ballpen, Black\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 99, \"item_name\": \"Ballpen, Black\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"department\": \"Team 5\", \"request_id\": 6, \"team_number\": null, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:55:24', '2026-01-15 02:55:24'),
('1beef8a3-8f78-4425-8c53-784352b638fc', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 2 has submitted a new request for Alcohol\", \"type\": \"new_request\", \"items\": [\"Alcohol (Quantity: 10)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 2 from Team 2 requested 10 unit(s) of Alcohol\", \"team_id\": 2, \"user_id\": 2, \"quantity\": 10, \"item_name\": \"Alcohol\", \"team_name\": \"Team 2\", \"user_name\": \"Team 2\", \"department\": \"Team 2\", \"request_id\": 1, \"team_number\": null, \"requested_by\": \"Team 2\"}', NULL, '2026-01-14 06:37:51', '2026-01-14 06:37:51'),
('230568c1-c5e2-4ab1-b45c-9adc16b9de37', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 5 has submitted a new request for Envelope, Brown\", \"type\": \"new_request\", \"items\": [\"Envelope, Brown (Quantity: 10)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 5 from Team 5 requested 10 unit(s) of Envelope, Brown\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 10, \"item_name\": \"Envelope, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"department\": \"Team 5\", \"request_id\": 5, \"team_number\": null, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:20:45', '2026-01-15 01:20:45'),
('2e640f83-0fc0-4861-9912-87f20abb8c9e', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Folder, Brown (Quantity: 40)\"], \"title\": \"Request Submitted\", \"message\": \"Team 5 from Team 5 submitted a request for 40 Folder, Brown\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 40, \"item_name\": \"Folder, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"request_id\": 8, \"team_number\": 5, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:56:21', '2026-01-15 02:56:21'),
('313041f9-f663-455d-9246-df3402964663', 'App\\Notifications\\AdminNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Items have been claimed from inventory by administrator\", \"type\": \"items_claimed\", \"items\": [\"Alcohol (Quantity: 5)\"], \"title\": \"Items has been claimed\", \"message\": \"5 Alcohol has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 5, \"item_name\": \"Alcohol\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 3, \"team_number\": 5}', NULL, '2026-01-15 02:22:36', '2026-01-15 02:22:36'),
('3ee868fe-5791-48b0-81ac-c9384ad69cdd', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Envelope, Brown (Quantity: 10)\"], \"title\": \"Request Submitted\", \"message\": \"Team 5 from Team 5 submitted a request for 10 Envelope, Brown\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 10, \"item_name\": \"Envelope, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"request_id\": 5, \"team_number\": 5, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:20:45', '2026-01-15 01:20:45'),
('55ed5ac4-08ab-4de8-a0ca-11b73822ee97', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 2 has submitted a new request for DTR\", \"type\": \"new_request\", \"items\": [\"DTR (Quantity: 10)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 2 from Team 2 requested 10 unit(s) of DTR\", \"team_id\": 2, \"user_id\": 2, \"quantity\": 10, \"item_name\": \"DTR\", \"team_name\": \"Team 2\", \"user_name\": \"Team 2\", \"department\": \"Team 2\", \"request_id\": 2, \"team_number\": null, \"requested_by\": \"Team 2\"}', NULL, '2026-01-14 06:55:37', '2026-01-14 06:55:37'),
('56c6ae97-cc57-4614-be81-1786cfaddaf6', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 2, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"DTR (Quantity: 10)\"], \"title\": \"Request Submitted\", \"message\": \"Team 2 from Team 2 submitted a request for 10 DTR\", \"team_id\": 2, \"user_id\": 2, \"quantity\": 10, \"item_name\": \"DTR\", \"team_name\": \"Team 2\", \"user_name\": \"Team 2\", \"request_id\": 2, \"team_number\": 2, \"requested_by\": \"Team 2\"}', NULL, '2026-01-14 06:55:37', '2026-01-14 06:55:37'),
('5e1d9403-46a8-4abf-b942-c649d814a39d', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your requested items are now ready for pickup\", \"type\": \"request_fulfilled\", \"items\": [\"Alcohol (Quantity: 5)\"], \"title\": \"Your Request Has Been Fulfilled\", \"message\": \"5 Alcohol has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 5, \"item_name\": \"Alcohol\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 3, \"team_number\": 5}', NULL, '2026-01-15 02:22:36', '2026-01-15 02:22:36'),
('5ed099c9-8825-452a-adf7-e9b0461d07ce', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Envelope, Brown has been rejected\", \"type\": \"request_status\", \"items\": [\"Envelope, Brown (Quantity: 10)\"], \"title\": \"Request Rejected\", \"status\": \"rejected\", \"message\": \"Landy has rejected your request for 10 Envelope, Brown from Team 5\", \"team_id\": 5, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Envelope, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"department\": \"Team 5\", \"request_id\": 5, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:37:07', '2026-01-15 02:37:07'),
('680d007a-32b1-4085-8ebf-2dffef5da9b0', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Box, Landbank (Quantity: 10)\"], \"title\": \"Request Submitted\", \"message\": \"Team 5 from Team 5 submitted a request for 10 Box, Landbank\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 10, \"item_name\": \"Box, Landbank\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"request_id\": 7, \"team_number\": 5, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:55:56', '2026-01-15 02:55:56'),
('768bc12b-ca45-45fd-96c8-43fbaacea719', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your requested items are now ready for pickup\", \"type\": \"request_fulfilled\", \"items\": [\"Ballpen, Black (Quantity: 99)\"], \"title\": \"Your Request Has Been Fulfilled\", \"message\": \"99 Ballpen, Black has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 99, \"item_name\": \"Ballpen, Black\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 6, \"team_number\": 5}', NULL, '2026-01-15 03:17:53', '2026-01-15 03:17:53'),
('7f8a4b6f-964a-4afe-8362-6223547ee8d9', 'App\\Notifications\\AdminNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Items have been claimed from inventory by administrator\", \"type\": \"items_claimed\", \"items\": [\"Folder, Brown (Quantity: 40)\"], \"title\": \"Items has been claimed\", \"message\": \"40 Folder, Brown has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 40, \"item_name\": \"Folder, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 8, \"team_number\": 5}', NULL, '2026-01-15 03:17:43', '2026-01-15 03:17:43'),
('98904216-89ec-40e1-bc4b-b675c7b19f78', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Alcohol has been approved\", \"type\": \"request_status\", \"items\": [\"Alcohol (Quantity: 5)\"], \"title\": \"Request Approved\", \"status\": \"approved\", \"message\": \"Landy has approved your request for 5 Alcohol from Team 5\", \"team_id\": 5, \"user_id\": 10, \"quantity\": 5, \"item_name\": \"Alcohol\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"department\": \"Team 5\", \"request_id\": 3, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:21:56', '2026-01-15 01:21:56'),
('9b694377-7ba0-4bec-951b-ee564b6df3c9', 'App\\Notifications\\AdminNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Items have been claimed from inventory by administrator\", \"type\": \"items_claimed\", \"items\": [\"Alcohol (Quantity: 10)\"], \"title\": \"Items has been claimed\", \"message\": \"10 Alcohol has been claimed \", \"team_id\": 2, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Alcohol\", \"team_name\": \"Team 2\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 1, \"team_number\": 2}', NULL, '2026-01-15 01:21:51', '2026-01-15 01:21:51'),
('a9afa8eb-cc9a-4459-8cf8-906228aa491e', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 5 has submitted a new request for Envelope, Brown\", \"type\": \"new_request\", \"items\": [\"Envelope, Brown (Quantity: 10)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 5 from Team 5 requested 10 unit(s) of Envelope, Brown\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 10, \"item_name\": \"Envelope, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"department\": \"Team 5\", \"request_id\": 4, \"team_number\": null, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:10:26', '2026-01-15 01:10:26'),
('adc6c937-c984-423f-92a0-6c30de2e4211', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Folder, Brown has been approved\", \"type\": \"request_status\", \"items\": [\"Folder, Brown (Quantity: 40)\"], \"title\": \"Request Approved\", \"status\": \"approved\", \"message\": \"Landy has approved your request for 40 Folder, Brown from Team 5\", \"team_id\": 5, \"user_id\": 10, \"quantity\": 40, \"item_name\": \"Folder, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"department\": \"Team 5\", \"request_id\": 8, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 03:17:19', '2026-01-15 03:17:19'),
('c49bd8ad-d4f9-44b6-b0cc-d7e6a52411f5', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Envelope, Brown has been rejected\", \"type\": \"request_status\", \"items\": [\"Envelope, Brown (Quantity: 10)\"], \"title\": \"Request Rejected\", \"status\": \"rejected\", \"message\": \"Landy has rejected your request for 10 Envelope, Brown from Team 5\", \"team_id\": 5, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Envelope, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"department\": \"Team 5\", \"request_id\": 4, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:22:06', '2026-01-15 01:22:06'),
('c71e05c2-66b7-4fb7-9e9a-04db0fc03cbb', 'App\\Notifications\\AdminNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Items have been claimed from inventory by administrator\", \"type\": \"items_claimed\", \"items\": [\"Box, Landbank (Quantity: 10)\"], \"title\": \"Items has been claimed\", \"message\": \"10 Box, Landbank has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Box, Landbank\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 7, \"team_number\": 5}', NULL, '2026-01-15 03:17:47', '2026-01-15 03:17:47'),
('c763ec50-ef92-4268-b4aa-6c2a265304eb', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Ballpen, Black (Quantity: 99)\"], \"title\": \"Request Submitted\", \"message\": \"Team 5 from Team 5 submitted a request for 99 Ballpen, Black\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 99, \"item_name\": \"Ballpen, Black\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"request_id\": 6, \"team_number\": 5, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:55:24', '2026-01-15 02:55:24'),
('c932bf74-a04d-49f1-bb29-9561b5909577', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Alcohol (Quantity: 5)\"], \"title\": \"Request Submitted\", \"message\": \"Team 5 from Team 5 submitted a request for 5 Alcohol\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 5, \"item_name\": \"Alcohol\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"request_id\": 3, \"team_number\": 5, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:04:19', '2026-01-15 01:04:19'),
('cbaaaa78-4f4e-45b4-a977-e6f8f6cef89e', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 5 has submitted a new request for Folder, Brown\", \"type\": \"new_request\", \"items\": [\"Folder, Brown (Quantity: 40)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 5 from Team 5 requested 40 unit(s) of Folder, Brown\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 40, \"item_name\": \"Folder, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"department\": \"Team 5\", \"request_id\": 8, \"team_number\": null, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:56:21', '2026-01-15 02:56:21'),
('cef14f89-854d-46fe-9d80-7c9d4e01298c', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 2, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Alcohol (Quantity: 10)\"], \"title\": \"Request Submitted\", \"message\": \"Team 2 from Team 2 submitted a request for 10 Alcohol\", \"team_id\": 2, \"user_id\": 2, \"quantity\": 10, \"item_name\": \"Alcohol\", \"team_name\": \"Team 2\", \"user_name\": \"Team 2\", \"request_id\": 1, \"team_number\": 2, \"requested_by\": \"Team 2\"}', NULL, '2026-01-14 06:37:51', '2026-01-14 06:37:51'),
('d2d208d0-2d73-486d-a0cf-16f4b2731466', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your requested items are now ready for pickup\", \"type\": \"request_fulfilled\", \"items\": [\"Box, Landbank (Quantity: 10)\"], \"title\": \"Your Request Has Been Fulfilled\", \"message\": \"10 Box, Landbank has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Box, Landbank\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 7, \"team_number\": 5}', NULL, '2026-01-15 03:17:47', '2026-01-15 03:17:47'),
('d5bd340b-8dc1-41e1-86a5-f56a29fd07dc', 'App\\Notifications\\NewRequestNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Team 5 has submitted a new request for Box, Landbank\", \"type\": \"new_request\", \"items\": [\"Box, Landbank (Quantity: 10)\"], \"title\": \"New Inventory Request\", \"message\": \"Team 5 from Team 5 requested 10 unit(s) of Box, Landbank\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 10, \"item_name\": \"Box, Landbank\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"department\": \"Team 5\", \"request_id\": 7, \"team_number\": null, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 02:55:56', '2026-01-15 02:55:56'),
('d76dc36e-9461-4879-880f-4e301d8be905', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Box, Landbank has been approved\", \"type\": \"request_status\", \"items\": [\"Box, Landbank (Quantity: 10)\"], \"title\": \"Request Approved\", \"status\": \"approved\", \"message\": \"Landy has approved your request for 10 Box, Landbank from Team 5\", \"team_id\": 5, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Box, Landbank\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"department\": \"Team 5\", \"request_id\": 7, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 03:17:26', '2026-01-15 03:17:26'),
('dca7b69f-7950-4d88-bbba-80d6a9b03a60', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"A team member has submitted a new request\", \"type\": \"team_request_submitted\", \"items\": [\"Envelope, Brown (Quantity: 10)\"], \"title\": \"Request Submitted\", \"message\": \"Team 5 from Team 5 submitted a request for 10 Envelope, Brown\", \"team_id\": 5, \"user_id\": 5, \"quantity\": 10, \"item_name\": \"Envelope, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Team 5\", \"request_id\": 4, \"team_number\": 5, \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 01:10:26', '2026-01-15 01:10:26'),
('e226415b-5d40-4c0d-996e-2e0274397ebe', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 2, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your requested items are now ready for pickup\", \"type\": \"request_fulfilled\", \"items\": [\"Alcohol (Quantity: 10)\"], \"title\": \"Your Request Has Been Fulfilled\", \"message\": \"10 Alcohol has been claimed \", \"team_id\": 2, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Alcohol\", \"team_name\": \"Team 2\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 1, \"team_number\": 2}', NULL, '2026-01-15 01:21:51', '2026-01-15 01:21:51'),
('e93e003d-20c1-4495-9395-c0488f0a6b0c', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Ballpen, Black has been approved\", \"type\": \"request_status\", \"items\": [\"Ballpen, Black (Quantity: 99)\"], \"title\": \"Request Approved\", \"status\": \"approved\", \"message\": \"Landy has approved your request for 99 Ballpen, Black from Team 5\", \"team_id\": 5, \"user_id\": 10, \"quantity\": 99, \"item_name\": \"Ballpen, Black\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"department\": \"Team 5\", \"request_id\": 6, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 5\"}', NULL, '2026-01-15 03:17:37', '2026-01-15 03:17:37'),
('f2f45d5f-663a-4acb-84c4-5f7e2a5545a6', 'App\\Notifications\\AdminNotification', 'App\\Models\\User', 10, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Items have been claimed from inventory by administrator\", \"type\": \"items_claimed\", \"items\": [\"Ballpen, Black (Quantity: 99)\"], \"title\": \"Items has been claimed\", \"message\": \"99 Ballpen, Black has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 99, \"item_name\": \"Ballpen, Black\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 6, \"team_number\": 5}', NULL, '2026-01-15 03:17:53', '2026-01-15 03:17:53'),
('f4f7a688-747e-4eeb-8bdb-152fb61369aa', 'App\\Notifications\\TeamNotification', 'App\\Models\\User', 5, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your requested items are now ready for pickup\", \"type\": \"request_fulfilled\", \"items\": [\"Folder, Brown (Quantity: 40)\"], \"title\": \"Your Request Has Been Fulfilled\", \"message\": \"40 Folder, Brown has been claimed \", \"team_id\": 5, \"user_id\": 10, \"quantity\": 40, \"item_name\": \"Folder, Brown\", \"team_name\": \"Team 5\", \"user_name\": \"Landy\", \"claimed_by\": \"Landy\", \"request_id\": 8, \"team_number\": 5}', NULL, '2026-01-15 03:17:43', '2026-01-15 03:17:43'),
('f5d8b21d-844c-4af3-a042-bd4698bb6910', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 2, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for Alcohol has been approved\", \"type\": \"request_status\", \"items\": [\"Alcohol (Quantity: 10)\"], \"title\": \"Request Approved\", \"status\": \"approved\", \"message\": \"Landy has approved your request for 10 Alcohol from Team 2\", \"team_id\": 2, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"Alcohol\", \"team_name\": \"Team 2\", \"user_name\": \"Landy\", \"department\": \"Team 2\", \"request_id\": 1, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 2\"}', NULL, '2026-01-14 06:51:10', '2026-01-14 06:51:10'),
('f9044600-ddcc-4c2b-886f-b9e0e8677aba', 'App\\Notifications\\RequestStatusNotification', 'App\\Models\\User', 2, '{\"url\": \"http://127.0.0.1:8000/requests\", \"body\": \"Your request for DTR has been rejected\", \"type\": \"request_status\", \"items\": [\"DTR (Quantity: 10)\"], \"title\": \"Request Rejected\", \"status\": \"rejected\", \"message\": \"Landy has rejected your request for 10 DTR from Team 2\", \"team_id\": 2, \"user_id\": 10, \"quantity\": 10, \"item_name\": \"DTR\", \"team_name\": \"Team 2\", \"user_name\": \"Landy\", \"department\": \"Team 2\", \"request_id\": 2, \"team_number\": null, \"processed_by\": \"Landy\", \"requested_by\": \"Team 2\"}', NULL, '2026-01-14 08:53:04', '2026-01-14 08:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Team 1', '2026-01-14 06:35:39', '2026-01-14 06:35:39'),
(2, 'Team 2', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(3, 'Team 3', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(4, 'Team 4', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(5, 'Team 5', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(6, 'Team 6', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(7, 'Team 7', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(8, 'Team 8', '2026-01-14 06:35:40', '2026-01-14 06:35:40'),
(9, 'Team 9', '2026-01-14 06:35:40', '2026-01-14 06:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `team_requests`
--

CREATE TABLE `team_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `team_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity_requested` int NOT NULL,
  `status` enum('pending','approved','rejected','claimed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `claimed_by` bigint UNSIGNED DEFAULT NULL,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `admin_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_requests`
--

INSERT INTO `team_requests` (`id`, `team_id`, `item_id`, `quantity_requested`, `status`, `claimed_by`, `claimed_at`, `admin_notes`, `approved_at`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 10, 'claimed', 10, '2026-01-15 01:21:51', NULL, '2026-01-14 06:51:10', 10, '2026-01-14 06:37:49', '2026-01-15 01:21:51'),
(2, 2, 12, 10, 'rejected', NULL, NULL, NULL, '2026-01-14 08:53:04', 10, '2026-01-14 06:55:37', '2026-01-14 08:53:04'),
(3, 5, 1, 5, 'claimed', 10, '2026-01-15 02:22:36', NULL, '2026-01-15 01:21:56', 10, '2026-01-15 01:04:15', '2026-01-15 02:22:36'),
(4, 5, 16, 10, 'rejected', NULL, NULL, NULL, '2026-01-15 01:22:06', 10, '2026-01-15 01:10:26', '2026-01-15 01:22:06'),
(5, 5, 16, 10, 'rejected', NULL, NULL, 'no tampo ako sa inyo', '2026-01-15 02:37:07', 10, '2026-01-15 01:20:45', '2026-01-15 02:37:07'),
(6, 5, 3, 99, 'claimed', 10, '2026-01-15 03:17:52', 'pakabait ka', '2026-01-15 03:17:37', 10, '2026-01-15 02:55:24', '2026-01-15 03:17:52'),
(7, 5, 8, 10, 'claimed', 10, '2026-01-15 03:17:47', 'nc', '2026-01-15 03:17:26', 10, '2026-01-15 02:55:56', '2026-01-15 03:17:47'),
(8, 5, 21, 40, 'claimed', 10, '2026-01-15 03:17:43', 'sure', '2026-01-15 03:17:18', 10, '2026-01-15 02:56:21', '2026-01-15 03:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','team_member') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'team_member',
  `team_id` bigint UNSIGNED DEFAULT NULL,
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_sent_at` timestamp NULL DEFAULT NULL,
  `verification_required` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `password_change_required` tinyint(1) NOT NULL DEFAULT '0',
  `login_count` int NOT NULL DEFAULT '0',
  `first_login_at` timestamp NULL DEFAULT NULL,
  `last_password_reset_request` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `profile_photo_path`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `team_id`, `verification_token`, `verification_sent_at`, `verification_required`, `is_active`, `password_changed_at`, `password_change_required`, `login_count`, `first_login_at`, `last_password_reset_request`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'Team 1', 'team1', 'team1@inventory.com', NULL, '2026-01-14 06:35:40', '$2y$12$Lzzm7WqMOnjDJZgJzv/ZN.9.SiH2dmZG/0k3FYzDYIBdnIbpK5IE2', NULL, '2026-01-14 06:35:40', '2026-01-14 06:35:40', 'team_member', 1, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(2, 'Team 2', 'team2', 'team2@inventory.com', NULL, '2026-01-14 06:35:40', '$2y$12$AF/zsF9gFeEM51cah0jopO9mCh52dVS3EnoAPgJRG6AfS6EZmvSvq', NULL, '2026-01-14 06:35:40', '2026-01-14 06:35:40', 'team_member', 2, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3, 'Team 3', 'team3', 'team3@inventory.com', NULL, '2026-01-14 06:35:41', '$2y$12$TVdJ0JUAgRXkQaP8tjEz4uTXZ2bsG3looDKLSdJgawqUM2x4IEfJO', NULL, '2026-01-14 06:35:41', '2026-01-14 06:35:41', 'team_member', 3, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(4, 'Team 4', 'team4', 'team4@inventory.com', NULL, '2026-01-14 06:35:41', '$2y$12$P4k.c3l7x31bs2fx5oPBguqfH0M3d0ZWN7KKcEq06Prda1ss7SQQ2', NULL, '2026-01-14 06:35:41', '2026-01-14 06:35:41', 'team_member', 4, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(5, 'Team 5', 'team5', 'team5@inventory.com', NULL, '2026-01-14 06:35:42', '$2y$12$k/.acVqMej.JQvs7S9A6/.5sEUJ4nmYr9mCh6m2RkpRyBPKSeLaeG', NULL, '2026-01-14 06:35:42', '2026-01-14 06:35:42', 'team_member', 5, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(6, 'Team 6', 'team6', 'team6@inventory.com', NULL, '2026-01-14 06:35:42', '$2y$12$tZta0xSx7jeM5bbZ4IeXleTXjk3RNq5TVkIEAAH154PQTKWspw2Gm', NULL, '2026-01-14 06:35:42', '2026-01-14 06:35:42', 'team_member', 6, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(7, 'Team 7', 'team7', 'team7@inventory.com', NULL, '2026-01-14 06:35:42', '$2y$12$G8kjbJ5KmxkiVEfZYFnCf.yH5cNYnQRrfDLMo6LlfJEQz3F4ckU2u', NULL, '2026-01-14 06:35:42', '2026-01-14 06:35:42', 'team_member', 7, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(8, 'Team 8', 'team8', 'team8@inventory.com', NULL, '2026-01-14 06:35:43', '$2y$12$x/o6wHUafHr37epgLPBX0.hZywv.wVP..1La6p4q6YVgpZfFyQ5o.', NULL, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 'team_member', 8, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(9, 'Team 9', 'team9', 'team9@inventory.com', NULL, '2026-01-14 06:35:43', '$2y$12$wBt4SF1cedkCQXOpdMOA1eLv/DN/JR1D.NGfGhcFjWJ1u2jmjlLHe', NULL, '2026-01-14 06:35:43', '2026-01-14 06:35:43', 'team_member', 9, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL),
(10, 'Landy', 'landbankinventory', 'landbankinventory@gmail.com', NULL, '2026-01-14 06:36:27', '$2y$12$O1G8J9Qb7UHg8GUW9MLfZOOQJaB05GA1/nVxgxX77gZcfkz.gxT3O', NULL, '2026-01-14 06:36:27', '2026-01-14 06:36:27', 'admin', NULL, NULL, NULL, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_logs_user_id_foreign` (`user_id`),
  ADD KEY `inventory_logs_item_id_index` (`item_id`),
  ADD KEY `inventory_logs_action_index` (`action`),
  ADD KEY `inventory_logs_created_at_index` (`created_at`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `monthly_reports_year_month_unique` (`year`,`month`),
  ADD KEY `monthly_reports_year_month_index` (`year`,`month`),
  ADD KEY `monthly_reports_is_finalized_index` (`is_finalized`),
  ADD KEY `monthly_reports_report_generated_at_index` (`report_generated_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teams_name_unique` (`name`);

--
-- Indexes for table `team_requests`
--
ALTER TABLE `team_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_requests_team_id_foreign` (`team_id`),
  ADD KEY `team_requests_item_id_foreign` (`item_id`),
  ADD KEY `team_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `team_requests_claimed_by_foreign` (`claimed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_team_id_foreign` (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `team_requests`
--
ALTER TABLE `team_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `team_requests`
--
ALTER TABLE `team_requests`
  ADD CONSTRAINT `team_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `team_requests_claimed_by_foreign` FOREIGN KEY (`claimed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `team_requests_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_requests_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
