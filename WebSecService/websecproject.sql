-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 23, 2025 at 09:51 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websecproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_activities`
--

CREATE TABLE `case_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity_type` enum('created','assigned','status_changed','priority_changed','comment_added','internal_note_added','resolved','closed','reopened') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_customer_visible` tinyint(1) NOT NULL DEFAULT 0,
  `is_system_generated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `case_activities`
--

INSERT INTO `case_activities` (`id`, `case_id`, `user_id`, `activity_type`, `title`, `description`, `metadata`, `is_customer_visible`, `is_system_generated`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'created', 'Case automatically created from low rating', 'Case created from 2-star product review', NULL, 0, 1, '2025-05-23 16:39:47', '2025-05-23 16:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `customer_service_cases`
--

CREATE TABLE `customer_service_cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_number` varchar(255) NOT NULL,
  `product_comment_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('open','in_progress','waiting_customer','resolved','closed') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `category` enum('product_quality','shipping','service','billing','other') NOT NULL DEFAULT 'product_quality',
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `resolution` text DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `first_response_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `response_time_hours` int(11) DEFAULT NULL,
  `resolution_time_hours` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_service_cases`
--

INSERT INTO `customer_service_cases` (`id`, `case_number`, `product_comment_id`, `customer_id`, `product_id`, `assigned_to`, `status`, `priority`, `category`, `subject`, `description`, `resolution`, `internal_notes`, `assigned_at`, `first_response_at`, `resolved_at`, `closed_at`, `response_time_hours`, `resolution_time_hours`, `created_at`, `updated_at`) VALUES
(1, 'CS-2025-001', 1, 1, 1, NULL, 'open', 'high', 'product_quality', 'Low rating (2 stars) for MacBook Pro 16\"', 'Customer left a 2-star review: \"This product is not good quality. Very disappointed.\"', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 16:39:47', '2025-05-23 16:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `cancellation_type` enum('customer','employee') DEFAULT NULL COMMENT 'Type of cancellation: customer (self-service) or employee (admin action)',
  `staff_notes` text DEFAULT NULL COMMENT 'Internal notes by staff for cancellations or other administrative actions',
  `resolved` tinyint(1) NOT NULL DEFAULT 0,
  `admin_response` text DEFAULT NULL,
  `resolved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `grade` varchar(255) NOT NULL,
  `credit_hours` int(11) NOT NULL,
  `term` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2023_10_01_000000_create_grades_table', 1),
(5, '2025_03_24_085039_create_products_table', 1),
(6, '2025_03_24_085050_add_credits_to_users', 1),
(7, '2025_03_24_085804_create_permission_tables', 1),
(8, '2025_03_24_093618_create_order_tables', 1),
(9, '2025_03_24_095804_create_roles_and_permissions', 1),
(10, '2025_03_25_000000_add_facebook_id_to_users_table', 1),
(11, '2025_04_22_165843_add_google_id_to_users_table', 1),
(12, '2025_04_22_165844_replace_facebook_id_with_linkedin_id', 1),
(13, '2025_04_25_144401_create_notifications_table', 1),
(14, '2025_04_25_151408_add_cancellation_fields_to_orders_table', 1),
(15, '2025_04_25_151508_add_photos_to_products_table', 1),
(16, '2025_04_26_071511_add_theme_preferences_to_users_table', 1),
(17, '2025_04_26_090505_add_management_level_to_users_table', 1),
(18, '2025_04_26_090612_create_feedbacks_table', 1),
(19, '2025_04_26_090613_add_cancellation_type_to_feedbacks_table', 1),
(20, '2025_04_26_091642_add_management_level_to_roles_table', 1),
(21, '2023_06_05_000000_replace_facebook_id_with_linkedin_id', 2),
(22, '2025_05_23_155508_create_editor_role', 3),
(23, '2025_05_23_180039_add_management_level_to_roles_table', 4),
(24, '2024_03_20_add_certificate_columns_to_users', 5),
(25, '2025_05_23_193140_create_product_comments_table', 6),
(26, '2025_05_23_193149_create_customer_service_cases_table', 7),
(27, '2025_05_23_193208_create_case_activities_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 15),
(4, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 15),
(5, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 15),
(6, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 15),
(7, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 15),
(8, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 15),
(9, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 15),
(10, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 15),
(11, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 15),
(12, 'App\\Models\\User', 1),
(12, 'App\\Models\\User', 15),
(13, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 15),
(14, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 15),
(15, 'App\\Models\\User', 1),
(15, 'App\\Models\\User', 15),
(16, 'App\\Models\\User', 1),
(16, 'App\\Models\\User', 15),
(17, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 15),
(18, 'App\\Models\\User', 1),
(18, 'App\\Models\\User', 15),
(19, 'App\\Models\\User', 1),
(19, 'App\\Models\\User', 15),
(20, 'App\\Models\\User', 1),
(20, 'App\\Models\\User', 15),
(21, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 15),
(25, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 15),
(26, 'App\\Models\\User', 1),
(26, 'App\\Models\\User', 15),
(27, 'App\\Models\\User', 1),
(27, 'App\\Models\\User', 15),
(28, 'App\\Models\\User', 1),
(28, 'App\\Models\\User', 15),
(29, 'App\\Models\\User', 1),
(29, 'App\\Models\\User', 15),
(30, 'App\\Models\\User', 1),
(30, 'App\\Models\\User', 15),
(31, 'App\\Models\\User', 1),
(32, 'App\\Models\\User', 1),
(33, 'App\\Models\\User', 1),
(33, 'App\\Models\\User', 15),
(34, 'App\\Models\\User', 1),
(34, 'App\\Models\\User', 15);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 12),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 13),
(5, 'App\\Models\\User', 14);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `cancelled_by`, `cancelled_at`, `shipping_address`, `billing_address`, `created_at`, `updated_at`) VALUES
(1, 3, 249.99, 'pending', NULL, NULL, NULL, NULL, '2025-05-23 16:43:46', '2025-05-23 16:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 249.99, '2025-05-23 16:43:46', '2025-05-23 16:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'show_users', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(2, 'edit_users', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(3, 'delete_users', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(4, 'admin_users', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(5, 'add_products', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(6, 'edit_products', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(7, 'delete_products', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(8, 'list_products', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(9, 'list_customers', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(10, 'manage_employees', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(11, 'place_order', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(12, 'view_orders', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(13, 'manage_orders', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(14, 'cancel_order', 'web', '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(15, 'manage_users', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(16, 'manage_roles', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(17, 'manage_permissions', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(18, 'view_logs', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(19, 'access_admin_panel', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(20, 'create_employee', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(21, 'assign_management_level', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(22, 'view_products', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(23, 'view_customers', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(24, 'manage_feedback', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(25, 'view_customer_feedback', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(26, 'view_feedback', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(27, 'respond_to_feedback', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(28, 'view_order_cancellations', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(29, 'receive_cancellation_notifications', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(30, 'manage_notifications', 'web', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(31, 'add admins', 'web', '2025-05-23 12:55:56', '2025-05-23 12:55:56'),
(32, 'view admins', 'web', '2025-05-23 12:55:56', '2025-05-23 12:55:56'),
(33, 'manage_roles_permissions', 'web', '2025-05-23 15:05:47', '2025-05-23 15:05:47'),
(34, 'assign_admin_role', 'web', '2025-05-23 15:05:47', '2025-05-23 15:05:47');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(32) NOT NULL,
  `name` varchar(128) NOT NULL,
  `model` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `main_photo` varchar(255) DEFAULT NULL,
  `additional_photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_photos`)),
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `model`, `description`, `main_photo`, `additional_photos`, `price`, `stock_quantity`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'LAPTOP-001', 'MacBook Pro 16\"', 'Apple MacBook Pro 2023', 'Powerful laptop with M2 Pro chip, 16GB RAM, 512GB SSD', NULL, NULL, 2499.99, 50, 'macbook.jpg', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(2, 'PHONE-001', 'iPhone 15 Pro', 'Apple iPhone 15 Pro 256GB', 'Latest iPhone with A17 Pro chip, 256GB storage, and amazing camera system', NULL, NULL, 1099.99, 100, 'iphone.jpg', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(3, 'TABLET-001', 'iPad Air', 'Apple iPad Air 2022', 'Sleek tablet with M1 chip, 10.9-inch display, and 256GB storage', NULL, NULL, 749.99, 75, 'ipad.jpg', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(4, 'LAPTOP-002', 'Dell XPS 15', 'Dell XPS 15 9530', 'Premium Windows laptop with 13th Gen Intel Core i7, 16GB RAM, 1TB SSD', NULL, NULL, 1899.99, 30, 'dell.jpg', '2025-05-19 19:01:11', '2025-05-19 19:01:11'),
(5, 'ACCESSORY-001', 'AirPods Pro', 'Apple AirPods Pro 2nd Gen', 'Wireless earbuds with active noise cancellation and spatial audio', NULL, NULL, 249.99, 149, 'airpods.jpg', '2025-05-19 19:01:11', '2025-05-23 16:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_comments`
--

CREATE TABLE `product_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_comments`
--

INSERT INTO `product_comments` (`id`, `product_id`, `user_id`, `rating`, `comment`, `is_verified_purchase`, `is_approved`, `approved_at`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 'This product is not good quality. Very disappointed.', 1, 1, '2025-05-23 16:39:47', NULL, '2025-05-23 16:39:47', '2025-05-23 16:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `management_level` enum('low','middle','high') DEFAULT NULL COMMENT 'Role management level: low (customer tasks), middle (+ low management), high (full access)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `management_level`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', NULL, '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(2, 'Employee', 'web', NULL, '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(3, 'Customer', 'web', NULL, '2025-05-19 18:57:48', '2025-05-19 18:57:48'),
(4, 'Editor', 'web', NULL, '2025-05-23 12:55:56', '2025-05-23 12:55:56'),
(5, 'Customer Service', 'web', 'low', '2025-05-23 15:29:29', '2025-05-23 15:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(4, 4),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(8, 3),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(11, 3),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(14, 3),
(15, 1),
(16, 1),
(16, 4),
(17, 1),
(17, 4),
(18, 1),
(18, 4),
(19, 1),
(19, 4),
(20, 1),
(21, 1),
(22, 2),
(23, 2),
(24, 2),
(25, 1),
(25, 2),
(25, 5),
(26, 1),
(26, 2),
(26, 5),
(27, 1),
(27, 2),
(27, 5),
(28, 1),
(28, 2),
(28, 5),
(29, 1),
(29, 2),
(29, 5),
(30, 1),
(30, 5),
(31, 4),
(32, 4),
(33, 4),
(34, 4);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `credits` decimal(15,2) NOT NULL DEFAULT 12000.00,
  `remember_token` varchar(100) DEFAULT NULL,
  `management_level` enum('low','middle','high') DEFAULT NULL COMMENT 'User management level: low (customer tasks), middle (+ low management), high (full access)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `linkedin_id` varchar(255) DEFAULT NULL,
  `theme_dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  `theme_color` varchar(255) NOT NULL DEFAULT 'default',
  `certificate_serial` varchar(255) DEFAULT NULL,
  `certificate_dn` text DEFAULT NULL,
  `last_certificate_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `credits`, `remember_token`, `management_level`, `created_at`, `updated_at`, `google_id`, `linkedin_id`, `theme_dark_mode`, `theme_color`, `certificate_serial`, `certificate_dn`, `last_certificate_login`) VALUES
(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$NQDVUJB.XQ.85/ZgF1LZJutznVLfJP40qBb3dtDuSP0CDqAdXFL6i', 12000.00, NULL, NULL, '2025-05-19 19:01:11', '2025-05-23 10:28:14', NULL, NULL, 1, 'default', NULL, NULL, NULL),
(2, 'Employee User', 'employee@example.com', NULL, '$2y$12$E0Ut/DMNWnuUEiL9jHbbnOI2NeaT9LCJeny1/cNirIx5u/RPa7eDW', 12000.00, NULL, NULL, '2025-05-19 19:01:11', '2025-05-19 19:01:11', NULL, NULL, 0, 'default', NULL, NULL, NULL),
(3, 'Customer One', 'customer1@example.com', NULL, '$2y$12$xAXCk0y80mqPRt5C8B//nemqo7eZU4DdvfsVs2euhX3ETOp9ws3gK', 4750.01, NULL, NULL, '2025-05-19 19:01:11', '2025-05-23 16:43:46', NULL, NULL, 0, 'default', NULL, NULL, NULL),
(4, 'Customer Two', 'customer2@example.com', NULL, '$2y$12$xQu9SrCWALs4Q.vCqngtseAbunSfss2r4kUUknM4AeBCCmCCxTauG', 8000.00, NULL, NULL, '2025-05-19 19:01:11', '2025-05-19 19:01:11', NULL, NULL, 0, 'default', NULL, NULL, NULL),
(5, 'ahmed_customer', 'customer@gamil.com', NULL, '$2y$12$SIODrIqjPY5KG5mPFtJaR.uxYtBp4UhMB4MQsu1LNedvJ2T107xTC', 0.00, NULL, NULL, '2025-05-19 21:12:13', '2025-05-23 10:16:25', NULL, NULL, 1, 'calm', NULL, NULL, NULL),
(7, 'Ahmed Mohamed', 'ahmedabdallah14.2005@gmail.com', '2025-05-23 10:33:51', '$2y$12$xywWmNKMzka8dyejpUQRvewkfJZlNDvuH9ENiF7kd4ThpaZcdpi5W', 1000.00, NULL, NULL, '2025-05-23 10:33:51', '2025-05-23 10:33:51', '116162219353671623217', NULL, 0, 'default', NULL, NULL, NULL),
(10, 'ahmed_editor', 'editor@gmail.com', NULL, '$2y$12$2JMZH/4aCPpczq/cpye7..UQgXQjOm7CYCbQISfu.5TfV.xoywFHC', 0.00, NULL, NULL, '2025-05-23 10:44:36', '2025-05-23 13:42:48', NULL, NULL, 1, 'default', NULL, NULL, NULL),
(12, 'ahmed mo', 'aaa@gmail.com', NULL, '$2y$12$4UDaguIxRv1CXhxPzTCtT.xH0yjPDpu4kyg3vE0zweMeH5AwRzwxq', 0.00, NULL, NULL, '2025-05-23 14:14:32', '2025-05-23 14:14:32', NULL, NULL, 0, 'default', NULL, NULL, NULL),
(13, 'Editor User', 'editor@example.com', NULL, '$2y$12$tVZG1v4tet6skx6XzgNxeO.Mn5m89sxD1vc2EGF66zjaPT6f6LvyW', 12000.00, NULL, 'high', '2025-05-23 15:04:01', '2025-05-23 15:04:01', NULL, NULL, 0, 'default', NULL, NULL, NULL),
(14, 'Customer Service Rep', 'customerservice@example.com', '2025-05-23 15:29:30', '$2y$12$v2XLwMOxf3509kYWDDJmkuYKFNiGRLk8y0ig5nQvV2MMHT3/yAdc.', 12000.00, NULL, 'low', '2025-05-23 15:29:30', '2025-05-23 15:29:30', NULL, NULL, 0, 'default', NULL, NULL, NULL),
(15, 'Test Admin', 'test-admin@example.com', NULL, '$2y$12$WEKMCpnqCCfHCf5uHiQdHO1PiuK8j6Ipjvj65lY.4DON53wKBhx7C', 12000.00, NULL, 'high', '2025-05-23 15:50:23', '2025-05-23 15:50:23', NULL, NULL, 0, 'default', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `case_activities`
--
ALTER TABLE `case_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_activities_case_id_created_at_index` (`case_id`,`created_at`),
  ADD KEY `case_activities_user_id_index` (`user_id`),
  ADD KEY `case_activities_activity_type_index` (`activity_type`),
  ADD KEY `case_activities_is_customer_visible_index` (`is_customer_visible`);

--
-- Indexes for table `customer_service_cases`
--
ALTER TABLE `customer_service_cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_service_cases_case_number_unique` (`case_number`),
  ADD KEY `customer_service_cases_product_comment_id_foreign` (`product_comment_id`),
  ADD KEY `customer_service_cases_product_id_foreign` (`product_id`),
  ADD KEY `customer_service_cases_status_index` (`status`),
  ADD KEY `customer_service_cases_priority_index` (`priority`),
  ADD KEY `customer_service_cases_assigned_to_index` (`assigned_to`),
  ADD KEY `customer_service_cases_customer_id_index` (`customer_id`),
  ADD KEY `customer_service_cases_created_at_index` (`created_at`),
  ADD KEY `customer_service_cases_case_number_index` (`case_number`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedbacks_order_id_foreign` (`order_id`),
  ADD KEY `feedbacks_user_id_foreign` (`user_id`),
  ADD KEY `feedbacks_resolved_by_foreign` (`resolved_by`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_cancelled_by_foreign` (`cancelled_by`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_comments`
--
ALTER TABLE `product_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_comments_approved_by_foreign` (`approved_by`),
  ADD KEY `product_comments_product_id_is_approved_index` (`product_id`,`is_approved`),
  ADD KEY `product_comments_user_id_index` (`user_id`),
  ADD KEY `product_comments_rating_index` (`rating`),
  ADD KEY `product_comments_created_at_index` (`created_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `case_activities`
--
ALTER TABLE `case_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_service_cases`
--
ALTER TABLE `customer_service_cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_comments`
--
ALTER TABLE `product_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `case_activities`
--
ALTER TABLE `case_activities`
  ADD CONSTRAINT `case_activities_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `customer_service_cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_service_cases`
--
ALTER TABLE `customer_service_cases`
  ADD CONSTRAINT `customer_service_cases_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_service_cases_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_service_cases_product_comment_id_foreign` FOREIGN KEY (`product_comment_id`) REFERENCES `product_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_service_cases_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_comments`
--
ALTER TABLE `product_comments`
  ADD CONSTRAINT `product_comments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
