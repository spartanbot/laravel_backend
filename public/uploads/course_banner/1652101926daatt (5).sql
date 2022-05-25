-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2022 at 02:02 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `daatt`
--

-- --------------------------------------------------------

--
-- Table structure for table `basic_information`
--

CREATE TABLE `basic_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `i_am_a` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `basic_information`
--

INSERT INTO `basic_information` (`id`, `first_name`, `last_name`, `user_name`, `i_am_a`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'CR of Class', 'Monitring', 'english', 'Teacher', '$2y$10$mrGbsIXAmc4An1oVXs4qPONTTBDVPUyRdJmWrV9MOJiVYZy6EO7PW', '1', '2021-12-16 01:55:35', '2021-12-16 01:55:35'),
(2, 'Akhilesh', 'Akhilesh kl', 'kumarmamteshwar', 'student', '$2y$10$zuZ8d030VxheS/I.5L.t8.bpucJql.TpiFQZ2ggpcs7wy9PlKho0S', '1', '2021-12-27 16:45:57', '2021-12-27 16:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `course_id` int(255) NOT NULL,
  `seller_id` bigint(20) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_fee` int(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Digital Course', '', '2022-02-03', '2022-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `course_title` varchar(200) NOT NULL,
  `course_description` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `grade_label` varchar(200) NOT NULL,
  `course_banner` varchar(255) NOT NULL,
  `course_content` varchar(4000) NOT NULL,
  `course_fee` int(200) NOT NULL,
  `affiliation` varchar(255) DEFAULT NULL,
  `submission_type` varchar(255) DEFAULT NULL,
  `difficulty` varchar(255) DEFAULT NULL,
  `seller_id` int(11) NOT NULL,
  `verify` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_title`, `course_description`, `subject`, `category_id`, `language_id`, `grade_label`, `course_banner`, `course_content`, `course_fee`, `affiliation`, `submission_type`, `difficulty`, `seller_id`, `verify`, `created_at`, `updated_at`) VALUES
(2, 'First Course2', 'First Course', 'Eng', 1, 1, 'Testing', 'banner1645543716Screenshot (1).png', '1645543716Screenshot (1).png', 32, NULL, NULL, NULL, 42, 1, '2022-02-22', '2022-02-28'),
(3, 'First Course3', 'First Course2', 'eng', 1, 1, 'test', 'banner1645628937Screenshot (1).png', '', 56, NULL, NULL, NULL, 39, 1, '2022-02-23', '2022-02-28'),
(4, 'First Course78', 'First Course27', 'eng', 1, 1, 'test', 'banner1648189315Alef Bet Chart.pdf', '1648189315Brachot Poster.pdf', 100, NULL, NULL, NULL, 42, 1, '2022-03-25', '2022-03-25'),
(5, 'Alef-Bet Chart', 'Have a beautiful, colorful Alef-Bet poster, to hang in your classroom or to print out for your students! Product features include: Hebrew letter, gematria, and transliteration.', 'Alef-Bet \nLetter Recognition', 1, 1, 'K', 'banner1651662287IMG-20220122-WA0006.jpg', '1651662287PANCHRAM_cv (1).pdf', 56, 'Modern orthodox', 'Printable', 'None', 42, 1, '2022-05-04', '2022-05-04');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 39, 3, '2022-03-25 00:21:30', '2022-03-25 00:21:30'),
(2, 43, 4, '2022-03-25 01:02:41', '2022-03-25 01:02:41'),
(3, 43, 3, '2022-03-25 01:02:42', '2022-03-25 01:02:42'),
(4, 43, 3, '2022-04-08 05:02:14', '2022-04-08 05:02:14'),
(7, 43, 5, '2022-05-09 00:00:34', '2022-05-09 00:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meet_times` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meet_minuts` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructor_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructor_description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructor_amount` int(11) NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `course_name`, `subject`, `meet_times`, `meet_minuts`, `grade_level`, `instructor_image`, `instructor_description`, `instructor_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MCA', 'Computer science', '10', '10 mints', 'First grade', 'Enaglish/Hindi', 'Teacher skfhskd isadhaskjd', 1000, '0', '2021-12-14 02:15:32', '2021-12-14 03:48:08'),
(2, 'BCA', 'Computer science', '10', '10 mints', 'First grade', 'Enaglish/Hindi', 'Teacher skfhskd isadhaskjd', 1000, '1', '2021-12-14 02:16:01', '2021-12-14 02:16:01'),
(3, 'Msc', 'Computer science', '10', '10 mints', 'First grade', 'Enaglish/Hindi', 'Teacher skfhskd isadhaskjd', 1000, '1', '2021-12-14 02:16:31', '2021-12-14 02:16:31'),
(4, 'Bsc', 'Computer science', '10', '10 mints', 'First grade', 'Enaglish/Hindi', 'Teacher skfhskd isadhaskjd', 1000, '0', '2021-12-14 02:17:35', '2021-12-27 16:54:53'),
(5, 'Physics', 'testing', '12', '434', '55', 'images/instructor/Nv1pjgdNGooTKLwB0wjsgjS6JUGeQspZKG1k2Eug.jpg', 'jhg', 1000, '1', '2021-12-14 05:35:44', '2021-12-14 05:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `language_name` varchar(200) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `language_name`, `created_at`, `updated_at`) VALUES
(1, 'English', '2022-02-08', '2022-02-08'),
(2, 'En', '2022-02-08', '2022-02-08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2021_10_30_164222_create_users', 1),
(3, '2021_12_08_045938_create_resourses_table', 1),
(4, '2021_12_08_055638_create_course_instructors_table', 1),
(5, '2021_12_08_073351_create_basic_information_table', 1),
(6, '2021_12_09_092729_create_categories_table', 1),
(7, '2021_12_09_095143_create_products_table', 1),
(8, '2021_12_14_064805_create_instructors_table', 2),
(9, '2021_12_15_043725_create_seller_dashboards_table', 3),
(10, '2021_12_15_045838_create_sellers_table', 4),
(11, '2021_12_16_062417_create_user_basicinfos_table', 5),
(12, '2021_12_20_053907_create_payment_details_table', 6),
(13, '2022_03_10_065612_create_enrollments_table', 6),
(14, '2022_03_11_094735_create_transaction_table', 7),
(15, '2022_03_16_070303_create_resourse_table', 7),
(16, '2022_04_21_075842_create_testimonal', 7),
(17, '2022_04_25_075725_create_products_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `status` varchar(11) NOT NULL,
  `total` float NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `charge_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `status`, `total`, `fullname`, `email`, `charge_id`, `transaction_id`, `created_at`, `updated_at`) VALUES
(4, 39, 'succeeded', 56, 'panchest', 'panchram.svinfotech@gmail.com', 'ch_3Kh63XDWukM2jk371AO2LbCs', 'txn_3Kh63XDWukM2jk371vNwpxtT', '2022-03-25 05:51:27', '2022-03-25 05:51:29'),
(5, 43, 'succeeded', 156, 'suneel', 'suneel@spartanbots.com', 'ch_3Kh6hPDWukM2jk370HnDScIG', 'txn_3Kh6hPDWukM2jk370Jmj0Cyg', '2022-03-25 06:32:39', '2022-03-25 06:32:40'),
(6, 43, 'succeeded', 56, 'suneel', 'suneel@spartanbots.com', 'ch_3KmF6tDWukM2jk3704zGZxsz', 'txn_3KmF6tDWukM2jk370ajXTcGu', '2022-04-08 10:31:58', '2022-04-08 10:32:13'),
(11, 43, 'succeeded', 56, 'suneel', 'suneel@spartanbots.com', 'ch_3KxPAyDWukM2jk373GvKE6st', 'txn_3KxPAyDWukM2jk373YB4YX3D', '2022-05-09 05:30:33', '2022-05-09 05:30:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `seller_id` bigint(20) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_fee` int(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `course_id`, `user_id`, `seller_id`, `course_name`, `course_fee`, `created_at`, `updated_at`) VALUES
(3, 4, 3, 39, 39, 'First Course3', 56, '2022-03-25 05:51:29', '2022-03-25 05:51:29'),
(4, 5, 4, 43, 42, 'First Course78', 100, '2022-03-25 06:32:40', '2022-03-25 06:32:40'),
(5, 5, 3, 43, 39, 'First Course3', 56, '2022-03-25 06:32:41', '2022-03-25 06:32:41'),
(6, 6, 3, 43, 39, 'First Course3', 56, '2022-04-08 10:32:13', '2022-04-08 10:32:13'),
(9, 11, 5, 43, 42, 'Alef-Bet Chart', 56, '2022-05-09 05:30:33', '2022-05-09 05:30:33');

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` bigint(20) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `card_number` int(255) NOT NULL,
  `cart_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exp_month` int(191) NOT NULL,
  `exp_year` int(255) NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `affiliation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `submission` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulty` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_gallery` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `product_name`, `price`, `category_name`, `language`, `subject`, `grade`, `affiliation`, `submission`, `difficulty`, `description`, `product_image`, `product_gallery`, `created_at`, `updated_at`) VALUES
(1, 42, 'Single product new', 120, 'acadmy', 'Eng', 'hebrew', 'pre-k', 'day school', '[\'activity\',\'project\']', 'none', 'this is test', '1651131412priyanshu.pdf', '1651131412Brachot Poster.pdf', '2022-04-28 02:06:52', '2022-04-28 07:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `resourse`
--

CREATE TABLE `resourse` (
  `id` bigint(20) NOT NULL,
  `resourse_title` varchar(255) NOT NULL,
  `resourse_description` text NOT NULL,
  `price` int(200) NOT NULL,
  `seller_id` bigint(20) NOT NULL,
  `resourse_content` varchar(4000) NOT NULL,
  `verify` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resourse`
--

INSERT INTO `resourse` (`id`, `resourse_title`, `resourse_description`, `price`, `seller_id`, `resourse_content`, `verify`, `created_at`, `updated_at`) VALUES
(1, 'Alphabet Chart', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has su', 1, 41, 'alef_bet_chart.pdf', 1, '2022-03-15 18:05:05', '2022-03-15 18:05:05'),
(2, 'Brachot Poster', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has su', 1, 41, 'brachot_poster.pdf', 1, '2022-03-15 18:07:57', '2022-03-15 18:07:57'),
(3, 'Unit', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has su', 7, 41, 'unit9.docx', 1, '2022-03-15 18:09:23', '2022-03-15 18:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `sellers_accounts`
--

CREATE TABLE `sellers_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `bankToken` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripeAccount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bankAccount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sellers_accounts`
--

INSERT INTO `sellers_accounts` (`id`, `user_id`, `bankToken`, `stripeAccount`, `bankAccount`, `status`, `created_at`, `updated_at`) VALUES
(1, 39, 'btok_1KgnYNDWukM2jk37JUag81x1', 'acct_1KgnYNRiQJNZ8GkF', 'ba_1KgnYSRiQJNZ8GkFbsOzf8wJ', NULL, '2022-03-24 04:36:12', '2022-03-24 04:36:12'),
(2, 42, 'btok_1Kh6ZcDWukM2jk37wGC5zHc6', 'acct_1Kh6ZcDFcZWTNiSu', 'ba_1Kh6ZgDFcZWTNiSu6JDscV3o', NULL, '2022-03-25 00:54:43', '2022-03-25 00:54:43');

-- --------------------------------------------------------

--
-- Table structure for table `testimonal`
--

CREATE TABLE `testimonal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonal`
--

INSERT INTO `testimonal` (`id`, `title`, `grade`, `school`, `location`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Test A', 'Test', 'A', 'Test', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '1650629733PANCH RAM CV-1 (1).docx', '2022-04-22 02:20:15', '2022-04-22 07:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `course_id` int(11) NOT NULL,
  `email` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `user_id`, `course_id`, `email`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 39, 3, 'panchram.svinfotech@gmail.com', 'txn_3Kh63XDWukM2jk371vNwpxtT', '2022-03-25 00:21:30', '2022-03-25 00:21:30'),
(2, 43, 4, 'suneel@spartanbots.com', 'txn_3Kh6hPDWukM2jk370Jmj0Cyg', '2022-03-25 01:02:41', '2022-03-25 01:02:41'),
(3, 43, 3, 'suneel@spartanbots.com', 'txn_3Kh6hPDWukM2jk370Jmj0Cyg', '2022-03-25 01:02:42', '2022-03-25 01:02:42'),
(4, 43, 3, 'suneel@spartanbots.com', 'txn_3KmF6tDWukM2jk370ajXTcGu', '2022-04-08 05:02:14', '2022-04-08 05:02:14'),
(7, 43, 5, 'suneel@spartanbots.com', 'txn_3KxPAyDWukM2jk373YB4YX3D', '2022-05-09 00:00:35', '2022-05-09 00:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferred_language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `i_am_a` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `affiliation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age_group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `talent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sample_content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ref_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ref_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ref_phonenumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ref_two_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ref_two_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ref_two_phonenumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_one_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_one_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_one_phonenumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_two_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_two_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resourse_two_phonenumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT 0,
  `token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user','seller') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by_admin` int(11) DEFAULT NULL,
  `createdDate` time DEFAULT NULL,
  `user_status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `stripe_publish_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `user_name`, `user_email`, `description`, `password`, `phone`, `gender`, `location`, `preferred_language`, `i_am_a`, `affiliation`, `subject`, `age_group`, `talent`, `sample_content`, `organization`, `seller_ref_name`, `seller_ref_email`, `seller_ref_phonenumber`, `seller_ref_two_name`, `seller_ref_two_email`, `seller_ref_two_phonenumber`, `resourse_name`, `resourse_one_name`, `resourse_one_email`, `resourse_one_phonenumber`, `resourse_two_name`, `resourse_two_email`, `resourse_two_phonenumber`, `api_token`, `verified`, `token`, `role`, `approved_by_admin`, `createdDate`, `user_status`, `stripe_publish_key`, `stripe_secret_key`, `created_at`, `updated_at`) VALUES
(5, 'kumarmamtesh', 'kumarmamtesh', 'kumarmamtesh@yahoo.com', NULL, '$2y$10$EbJexa7p9xGtzKRqTWwqYefbBvhP3aAlawBHAqv1lZVnCsrxcBMwO', '0', '', 'Uttrakhand', 'Hindi/English', 'Teacher', 'I dont no', 'Physics', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'user', NULL, NULL, '1', '', NULL, '2022-01-03 10:35:11', '2022-01-03 11:45:24'),
(6, 'kumarmam', 'kumarmam', 'kumarmamcitpl@gmail.com', NULL, '$2y$10$4ftwtu4C/dprP.iMMGrl3.Kj3CqqkHztfB.gk6FAEjdyslEBnmXr.', '0', '', 'Uttrakhand', 'Hindi/English', 'Teacher', 'I dont no', 'Physics', '18', 'more talented', NULL, 'Physics', 'hh', 'dd@gmail.com', '333243442344', 'fhgfhgfhg', 'fjhgfhjg@gmail.com', '654545665656', 'hdhh j', 'kgkk', 'tyry@gmail.com', '565456478', 'jgjgvv', 'yufghfgh@gmail.com', '6545646546', NULL, 1, 'tmFTQ2P9hosMpUYChCOJMi0jPVsZcaU1j8yngcLkHgEKGwO5k3', 'user', NULL, '07:13:29', '1', '', NULL, '2022-01-03 11:56:48', '2022-01-03 12:21:59'),
(37, 'panchram', 'panchram', 'panchram007@gmail.com', 'This is description', '$2y$10$zdd07JTSUq6p5w3Wa5L8JubTCx2uN.3hfLxrqa7ohCYhR8MICALWe', '0', '', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, 'test', 'vk', 'vk@gmail.co', '1234567890', 'vktest', 'vktest@mail.co', '0987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '55373zaxbnhyihh55373dgrfewskgfiskfjfk1643643532', 'user', NULL, '15:38:52', '1', '', NULL, '2022-01-28 09:42:27', '2022-01-31 11:05:48'),
(39, 'panchest', 'panchtest', 'panchram.svinfotech@gmail.com', NULL, '$2y$10$WxyDpXm9/lbKkCCdh2xzNuZBr5t8Lsjs9EP.FyLGmH/vV3lRWwJQC', '0', '', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, 'test', 'vk', 'vk@gmail.co', '1234567890', 'vktest', 'vktest@mail.co', '0987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'daatt13484token1645543260', 'seller', 1, '15:21:01', '1', '', NULL, '2022-02-22 09:51:01', '2022-04-12 02:07:46'),
(41, 'admin', 'admin', 'admin@gmail.com', NULL, '$2y$10$5PxZ6dZwQigEHbq/yTiKwunp0z2q7qJmcooldyBSg1Z78GSGypqvO', '0', '', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, 'test', 'vk', 'vk@gmail.co', '1234567890', 'vktest', 'vktest@mail.co', '0987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'daatt38430token1645633357', 'admin', NULL, '16:22:37', '1', '', NULL, '2022-02-23 10:52:37', '2022-02-23 10:52:37'),
(42, 'lovepreet', 'lovepreet', 'lovepreet@spartanbots.com', NULL, '$2y$10$6tja2oZ1JJ0Pmm8uo8//c.k/GcDNjTu3mnY5DeLeL7A3Svr/4pW4u', '0', '', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, 'test', 'vk', 'vk@gmail.co', '1234567890', 'vktest', 'vktest@mail.co', '0987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'daatt99896token1648188915', 'seller', 1, '06:15:15', '1', NULL, NULL, '2022-03-25 00:45:15', '2022-03-25 00:45:15'),
(43, 'suneel', 'suneel', 'suneel@spartanbots.com', NULL, '$2y$10$2LnrDep4jiB2iH0D7KWRaO/3jCBhz0Z3PEVBq.ExCBemMOAQENF2q', '0', '', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, 'test', 'vk', 'vk@gmail.co', '1234567890', 'vktest', 'vktest@mail.co', '0987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'daatt28084token1648189100', 'user', NULL, '06:18:20', '1', NULL, NULL, '2022-03-25 00:48:20', '2022-03-25 00:48:20'),
(44, 'abhishak', 'abhishak', 'abhishak@spartanbots.com', NULL, '$2y$10$aJ81qg5FX/wRLU2tWPB4Putl4V4HDqRxn10PkoOAnlpqxCUWj9mem', '0', '', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, 'test', 'vk', 'vk@gmail.co', '1234567890', 'vktest', 'vktest@mail.co', '0987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'daatt16654token1649230840', 'seller', 0, '07:40:40', '1', NULL, NULL, '2022-04-06 02:10:40', '2022-04-06 04:15:30'),
(46, 'asmen', 'asmen', 'asmen@spartanbots.com', NULL, '$2y$10$qhMfZe1B0IgjMt613Qv7Q.Jx/jjWtBsZ90iHJqgfWsixHU9fMh1HK', '9876543210', 'male', 'mohali', 'english', 'abc', 'ddhdhd', 'mdsdasd', 'pre', 'art', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'daatt97185token1649936145', 'seller', 0, '11:35:45', '1', NULL, NULL, '2022-04-14 06:05:45', '2022-04-14 06:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_basicinfos`
--

CREATE TABLE `user_basicinfos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_basicinfos`
--

INSERT INTO `user_basicinfos` (`id`, `first_name`, `last_name`, `user_type`, `change_password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Amit kumar mal', 'Pandeys', 'Profeser/Teacher', '$2y$10$Z7FVLoZjxiZ.XD01UV.jkuIi.nc1QDPrZLJ60KqJeSZim75zgr.1K', '0', '2021-12-16 03:48:22', '2021-12-16 07:49:49'),
(2, 'Amit kumar', 'Pandey', 'Profeser', '$2y$10$voFxoRLcFQsCNhYIPdoxJuY4CKkYHSr80jzs56KoPHMjeinH3cNVC', '0', '2021-12-16 03:49:13', '2021-12-16 03:58:48'),
(3, 'Amit kumar', 'Pandey', 'Profeser', '$2y$10$SocUPn.cc3W7rMVC9ry69uaKhYgG2X8KdEBigbwTpUgKrjQa8.luK', '1', '2021-12-16 03:49:16', '2021-12-16 03:49:16'),
(4, 'Amit  kumar sharma', 'Sharma', 'Sharma', '$2y$10$hBrcSpCfdSiGpM6iVNryO.QXHcVNLCgEyV1W/ox7X.YWFabY8.5zm', '1', '2021-12-16 07:13:29', '2021-12-16 07:13:29'),
(5, 'mmm', 'hh', 'uu', '$2y$10$gRR4bOQH1NTy8f36KfTS/ekoeaiRmXUhufkT6QFBC/wwF2nBd3CgG', '0', '2021-12-27 17:30:36', '2021-12-27 17:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `verify_users`
--

CREATE TABLE `verify_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdDate` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verify_users`
--

INSERT INTO `verify_users` (`id`, `user_id`, `token`, `createdDate`, `created_at`, `updated_at`) VALUES
(4, 4, '898d73abed0a62085d3f1abf52dea52b4b19e199', '10:13:24', '2021-12-31 00:34:36', '2021-12-31 04:43:24'),
(5, 5, 'd57f875c93a2950c60080096b4ac7378c9841ffe', '06:44:02', '2021-12-31 00:36:23', '2022-01-03 11:44:02'),
(6, 1, '42e1508b07cc7db554633ae5083bc6d6954cdfa6', '00:00:00', '2021-12-31 01:25:22', '2021-12-31 01:25:22'),
(8, 4, '898d73abed0a62085d3f1abf52dea52b4b19e199', '10:13:24', '2021-12-31 03:50:21', '2021-12-31 04:43:24'),
(9, 5, 'd57f875c93a2950c60080096b4ac7378c9841ffe', '06:44:02', '2022-01-03 10:35:11', '2022-01-03 11:44:02'),
(10, 6, 'f707727a19448ee1a0c1fabc61d963b2d5b12581', '14:38:01', '2022-01-03 11:56:48', '2022-01-03 19:38:01'),
(11, 7, 'ea7fc53f2cd50e4dad54abf198778a6c10943ca2', '15:12:12', '2022-01-13 09:42:12', '2022-01-13 09:42:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basic_information`
--
ALTER TABLE `basic_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course-manage` (`course_id`),
  ADD KEY `user-cart-manage` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_foreign` (`category_id`),
  ADD KEY `language_fk` (`language_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user-enrol` (`user_id`),
  ADD KEY `course-enrol` (`course_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user-order` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product-order-item` (`course_id`),
  ADD KEY `order-item` (`order_id`),
  ADD KEY `user-order-itm` (`user_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resourse`
--
ALTER TABLE `resourse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller-fk` (`seller_id`);

--
-- Indexes for table `sellers_accounts`
--
ALTER TABLE `sellers_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid_fk` (`user_id`);

--
-- Indexes for table `testimonal`
--
ALTER TABLE `testimonal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userinfo` (`user_id`),
  ADD KEY `courseinfo` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_email_unique` (`user_email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- Indexes for table `user_basicinfos`
--
ALTER TABLE `user_basicinfos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verify_users`
--
ALTER TABLE `verify_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basic_information`
--
ALTER TABLE `basic_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resourse`
--
ALTER TABLE `resourse`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sellers_accounts`
--
ALTER TABLE `sellers_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonal`
--
ALTER TABLE `testimonal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user_basicinfos`
--
ALTER TABLE `user_basicinfos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `verify_users`
--
ALTER TABLE `verify_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `course-manage` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `user-cart-manage` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `cat_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `language_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `course-enrol` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `user-enrol` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `user-order` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order-item` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `product-order-item` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `user-order-itm` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `resourse`
--
ALTER TABLE `resourse`
  ADD CONSTRAINT `seller-fk` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sellers_accounts`
--
ALTER TABLE `sellers_accounts`
  ADD CONSTRAINT `userid_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `courseinfo` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `userinfo` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
