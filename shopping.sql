-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03 مايو 2026 الساعة 09:31
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopping`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(3, 'magedahmed@gmail.com', '$2y$10$2aInjBiZX5pmzyZTaCqXIOyk59JbPvCFLtjj3gW.EtG3KH3xOMUjO');

-- --------------------------------------------------------

--
-- بنية الجدول `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `name` varchar(500) NOT NULL,
  `price` varchar(500) NOT NULL,
  `img` varchar(500) NOT NULL,
  `quantity` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `session_id`, `name`, `price`, `img`, `quantity`) VALUES
(9, 1, 12, '', 'حقائب', '33', '2176_17.jpg', '3'),
(10, 1, 11, '', 'احذية', '99', '9701_8.jpg', '4'),
(11, 1, 20, '', 'هاتف', '7000', '4444_سامسنج.jpg', '2'),
(16, 1, 25, '', 'احذية', '88.7', '2923_جزمة سوداء.jpg', '1'),
(31, 5, 13, '', 'ادوات تجميل', '14', '888_9.jpg', '1'),
(74, 8, 8, '', 'عطور', '90', '1225_7.jpg', '1');

-- --------------------------------------------------------

--
-- بنية الجدول `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `comment` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `comments`
--

INSERT INTO `comments` (`id`, `username`, `comment`, `product_id`) VALUES
(8, 'maged', 'EXelant', 12),
(9, 'ali', 'ممتاز', 12),
(10, 'kaled', 'كاميرا احترافية', 20),
(11, '', 'جيد', 20),
(12, '', 'خفيفه ولون جذاب', 23),
(13, '', 'متانة', 23),
(14, '', 'قوة', 23),
(15, '', 'انيق', 20),
(16, '', 'رياضي', 11),
(17, '', 'شبابي', 11),
(18, '', 'لون جذاب', 12),
(19, '', 'قوي', 12),
(20, '', 'مرطب ممتاز', 13),
(21, '', 'عطر رجالي مناسب', 8),
(22, '', 'جزمة قوة القوة', 25),
(23, '', 'صناعة جيدة', 25),
(24, 'زائر', 'هاتف روعه', 20),
(25, 'اسماعيل', 'قوة القوة', 11);

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(256) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `country` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'cash',
  `payment_status` varchar(20) DEFAULT 'pending',
  `payment_receipt` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `email`, `phone`, `country`, `address`, `total_price`, `payment_method`, `payment_status`, `payment_receipt`, `status`, `order_date`) VALUES
(28, 3, 'maged', 'maged@gmail.com', '0562458449', 'السعودية', 'الرياض', 90.00, 'cod', 'pending', '', 0, '2026-05-02 16:12:39'),
(38, 7, 'خالد ماجد احمد', 'maged@gmail.com', '+967738266628', 'السعودية', 'الرياض', 90.00, 'cod', 'pending', '', 0, '2026-05-02 16:36:57'),
(39, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'قطر', 'الدوحة', 99.00, 'cod', 'pending', '', 0, '2026-05-02 16:52:29'),
(40, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'قطر', 'الدوحة', 7000.00, 'cod', 'pending', '', 0, '2026-05-02 16:53:14'),
(41, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'قطر', 'الدوحة', 353.50, 'cod', 'pending', '', 0, '2026-05-02 16:54:01'),
(42, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'السعودية', 'الدوحة', 9000.00, 'cod', 'pending', '', 0, '2026-05-02 16:54:38'),
(43, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'السعودية', 'الدوحة', 353.50, 'cod', 'pending', '', 0, '2026-05-02 16:55:15'),
(44, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'السعودية', 'الدوحة', 3000.00, 'cod', 'pending', '', 0, '2026-05-02 16:55:49'),
(45, 8, 'رعد ماجد احمد', 'maged@gmail.com', '+967738266628', 'السعودية', 'الدوحة', 88.70, 'cod', 'pending', '', 0, '2026-05-02 16:56:24');

-- --------------------------------------------------------

--
-- بنية الجدول `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(256) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_img` varchar(256) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `product_img`, `quantity`) VALUES
(39, 28, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(50, 38, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(51, 39, 11, 'احذية', 99.00, '9701_8.jpg', 1),
(52, 40, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 1),
(53, 41, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(54, 42, 17, 'هاتف', 9000.00, '1452_ايفون.jpg', 1),
(55, 43, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(56, 44, 19, 'هاتف', 3000.00, '1596_ردمي.jpg', 1),
(57, 45, 25, 'احذية', 88.70, '2923_جزمة سوداء.jpg', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `proname` varchar(200) NOT NULL,
  `proimg` varchar(200) NOT NULL,
  `proprice` varchar(200) NOT NULL,
  `prosection` varchar(200) NOT NULL,
  `prodescrip` varchar(1000) NOT NULL,
  `prosize` varchar(1000) NOT NULL,
  `prounv` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `product`
--

INSERT INTO `product` (`id`, `proname`, `proimg`, `proprice`, `prosection`, `prodescrip`, `prosize`, `prounv`, `quantity`) VALUES
(8, 'عطور', '1225_7.jpg', '90', 'عطور', 'عطر رجالي اسود', '40م', 'متوفر', 94),
(9, 'حقائب', '6361_شنطة بيضاء.jpeg', '77', 'حقائب', 'حقيبة بيضاء', 'وسط', 'متوفر', 95),
(11, 'احذية', '9701_8.jpg', '99', 'احذية', 'حذاء طبي', '40', 'متوفر', 96),
(12, 'حقائب', '2176_17.jpg', '33', 'احذية', 'حقيبة نسائيه', '40', 'متوفر', 98),
(13, 'ادوات تجميل', '888_9.jpg', '14', 'ادوات تجميل', 'مرطب شفائف', '40م', 'متوفر', 99),
(17, 'هاتف', '1452_ايفون.jpg', '9000', 'هواتف', 'تلفون ايفون', '1', 'متوفر', 98),
(19, 'هاتف', '1596_ردمي.jpg', '3000', 'هواتف', 'تلفون ردمي', '1', 'متوفر', -3),
(20, 'هاتف', '4444_سامسنج.jpg', '7000', 'هواتف', 'تلفون سامسنج', '1', 'متوفر', 90),
(23, 'احذية', '7189_جزمة حمراء.jpg', '353.5', 'احذية', 'جزمة رياضي حمرا', '38و40و42و', 'متوفر', 88),
(24, 'كريم للوجه', '1083_11.jpg', '100', 'ادوات تجميل', 'كريم تبييض', '40م', '', 97),
(25, 'احذية', '2923_جزمة سوداء.jpg', '88.7', 'احذية', 'حذا اسود ابو رقبه', '40و41و42', '', 97);

-- --------------------------------------------------------

--
-- بنية الجدول `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` datetime NOT NULL,
  `has_prize` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_phone` varchar(20) DEFAULT '777000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `sales`
--

INSERT INTO `sales` (`id`, `order_id`, `user_id`, `full_name`, `phone`, `email`, `country`, `address`, `total_price`, `order_date`, `has_prize`, `delivered_date`, `delivery_phone`) VALUES
(1, 1, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 6579.20, '2026-04-30 14:01:21', 0, '2026-04-30 11:36:29', '777000000'),
(2, 2, 5, 'اصيل المحرقي', '0565656565', 'maged@gmail.com', 'السعودية', 'الرياض', 562.50, '2026-04-30 16:34:12', 0, '2026-05-02 10:24:48', '777000000'),
(3, 4, 6, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 28456.20, '2026-05-02 13:23:10', 0, '2026-05-02 10:25:51', '777000000'),
(5, 6, 6, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 453.50, '2026-05-02 13:36:26', 0, '2026-05-02 10:50:57', '777000000'),
(6, 5, 6, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 453.50, '2026-05-02 13:32:49', 0, '2026-05-02 10:51:31', '777000000'),
(7, 7, 6, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 14.00, '2026-05-02 13:52:29', 0, '2026-05-02 10:53:10', '777000000'),
(8, 9, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 7000.00, '2026-05-02 16:51:43', 0, '2026-05-02 13:57:16', '777000000'),
(9, 10, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 6000.00, '2026-05-02 16:52:53', 0, '2026-05-02 14:40:58', '777000000'),
(10, 12, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 33.00, '2026-05-02 16:54:13', 0, '2026-05-02 14:41:13', '777000000'),
(11, 8, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 88.70, '2026-05-02 16:50:12', 0, '2026-05-02 15:09:40', '777000000'),
(12, 11, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 353.50, '2026-05-02 16:53:39', 0, '2026-05-02 15:09:52', '777000000'),
(13, 13, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 99.00, '2026-05-02 17:33:58', 0, '2026-05-02 15:10:01', '777000000'),
(14, 14, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 154.00, '2026-05-02 17:58:41', 0, '2026-05-02 15:11:34', '777000000'),
(15, 15, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 353.50, '2026-05-02 18:11:04', 0, '2026-05-02 15:11:56', '777000000'),
(16, 16, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 353.50, '2026-05-02 18:20:07', 0, '2026-05-02 15:32:19', '777000000'),
(17, 17, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 90.00, '2026-05-02 18:20:40', 0, '2026-05-02 15:32:31', '777000000'),
(18, 18, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 9000.00, '2026-05-02 18:21:08', 0, '2026-05-02 15:32:43', '777000000'),
(19, 19, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 353.50, '2026-05-02 18:21:38', 0, '2026-05-02 15:32:53', '777000000'),
(20, 20, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 7000.00, '2026-05-02 18:22:06', 0, '2026-05-02 15:35:38', '777000000'),
(21, 21, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 353.50, '2026-05-02 18:33:48', 0, '2026-05-02 15:35:46', '777000000'),
(22, 22, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 100.00, '2026-05-02 18:34:13', 0, '2026-05-02 15:35:52', '777000000'),
(23, 23, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 77.00, '2026-05-02 18:34:38', 0, '2026-05-02 15:35:58', '777000000'),
(24, 24, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 7000.00, '2026-05-02 18:35:08', 0, '2026-05-02 16:13:02', '777000000'),
(25, 25, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 90.00, '2026-05-02 19:10:59', 0, '2026-05-02 16:13:12', '777000000'),
(26, 26, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 707.00, '2026-05-02 19:11:29', 0, '2026-05-02 16:13:26', '777000000'),
(27, 27, 3, 'maged', '0562458449', 'maged@gmail.com', 'السعودية', 'الرياض', 77.00, '2026-05-02 19:12:06', 0, '2026-05-02 16:13:36', '777000000'),
(28, 29, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'اليمن', 'تعز', 132.00, '2026-05-02 19:21:36', 0, '2026-05-02 16:25:52', '777000000'),
(29, 30, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'اليمن', 'تعز', 7000.00, '2026-05-02 19:23:33', 0, '2026-05-02 16:26:05', '777000000'),
(30, 31, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 353.50, '2026-05-02 19:24:27', 0, '2026-05-02 16:26:16', '777000000'),
(31, 32, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 90.00, '2026-05-02 19:25:24', 1, '2026-05-02 16:28:23', '777000000'),
(32, 33, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 90.00, '2026-05-02 19:27:56', 0, '2026-05-02 16:35:35', '777000000'),
(33, 34, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 100.00, '2026-05-02 19:30:45', 0, '2026-05-02 16:35:46', '777000000'),
(34, 35, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 7000.00, '2026-05-02 19:31:59', 0, '2026-05-02 16:35:59', '777000000'),
(35, 36, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 77.00, '2026-05-02 19:32:40', 1, '2026-05-02 16:37:26', '777000000'),
(36, 37, 7, 'خالد ماجد احمد', '+967738266628', 'maged@gmail.com', 'السعودية', 'الرياض', 99.00, '2026-05-02 19:34:39', 1, '2026-05-02 16:44:14', '777000000');

-- --------------------------------------------------------

--
-- بنية الجدول `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_img` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `sales_items`
--

INSERT INTO `sales_items` (`id`, `sale_id`, `order_id`, `product_id`, `product_name`, `product_price`, `product_img`, `quantity`) VALUES
(1, 1, 1, 12, 'حقائب', 33.00, '2176_17.jpg', 1),
(2, 1, 1, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(3, 1, 1, 13, 'ادوات تجميل', 14.00, '888_9.jpg', 1),
(4, 1, 1, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(5, 1, 1, 25, 'احذية', 88.70, '2923_جزمة سوداء.jpg', 1),
(6, 1, 1, 19, 'هاتف', 3000.00, '1596_ردمي.jpg', 2),
(7, 2, 2, 12, 'حقائب', 33.00, '2176_17.jpg', 4),
(8, 2, 2, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(9, 2, 2, 9, 'حقائب', 77.00, '6361_شنطة بيضاء.jpeg', 1),
(10, 3, 4, 13, 'ادوات تجميل', 14.00, '888_9.jpg', 1),
(11, 3, 4, 25, 'احذية', 88.70, '2923_جزمة سوداء.jpg', 1),
(12, 3, 4, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 4),
(13, 3, 4, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(15, 5, 6, 24, 'كريم للوجه', 100.00, '1083_11.jpg', 1),
(16, 5, 6, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(17, 6, 5, 24, 'كريم للوجه', 100.00, '1083_11.jpg', 1),
(18, 7, 7, 13, 'ادوات تجميل', 14.00, '888_9.jpg', 1),
(19, 8, 9, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 1),
(20, 9, 10, 19, 'هاتف', 3000.00, '1596_ردمي.jpg', 2),
(21, 10, 12, 12, 'حقائب', 33.00, '2176_17.jpg', 1),
(22, 11, 8, 25, 'احذية', 88.70, '2923_جزمة سوداء.jpg', 1),
(23, 12, 11, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(24, 13, 13, 11, 'احذية', 99.00, '9701_8.jpg', 1),
(25, 14, 14, 9, 'حقائب', 77.00, '6361_شنطة بيضاء.jpeg', 2),
(26, 15, 15, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(27, 16, 16, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(28, 17, 17, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(29, 18, 18, 17, 'هاتف', 9000.00, '1452_ايفون.jpg', 1),
(30, 19, 19, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(31, 20, 20, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 1),
(32, 21, 21, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(33, 22, 22, 24, 'كريم للوجه', 100.00, '1083_11.jpg', 1),
(34, 23, 23, 9, 'حقائب', 77.00, '6361_شنطة بيضاء.jpeg', 1),
(35, 24, 24, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 1),
(36, 25, 25, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(37, 26, 26, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 2),
(38, 27, 27, 9, 'حقائب', 77.00, '6361_شنطة بيضاء.jpeg', 1),
(39, 28, 29, 12, 'حقائب', 33.00, '2176_17.jpg', 1),
(40, 28, 29, 11, 'احذية', 99.00, '9701_8.jpg', 1),
(41, 29, 30, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 1),
(42, 30, 31, 23, 'احذية', 353.50, '7189_جزمة حمراء.jpg', 1),
(43, 31, 32, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(44, 32, 33, 8, 'عطور', 90.00, '1225_7.jpg', 1),
(45, 33, 34, 24, 'كريم للوجه', 100.00, '1083_11.jpg', 1),
(46, 34, 35, 20, 'هاتف', 7000.00, '4444_سامسنج.jpg', 1),
(47, 35, 36, 9, 'حقائب', 77.00, '6361_شنطة بيضاء.jpeg', 1),
(48, 36, 37, 11, 'احذية', 99.00, '9701_8.jpg', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `m` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `section`
--

INSERT INTO `section` (`id`, `m`) VALUES
(11, 'ملابس'),
(12, 'عطور'),
(13, 'احذية'),
(15, 'هواتف'),
(18, 'حقائب'),
(19, 'ادوات تجميل');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `p` int(11) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created-at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `p`, `password`, `created-at`) VALUES
(1, 'maged', 'maged@gmail.com', 0, '123', '2026-04-28 14:17:57'),
(3, 'ماجد', 'maged@gmail.com', 0, '111', '2026-04-28 18:14:23'),
(5, 'اصيل', 'maged@gmail.com', 0, '12345', '2026-04-28 19:10:25'),
(6, 'اسماعيل', 'maged@gmail.com', 0, '123', '2026-04-29 16:08:25'),
(7, 'خالد ماجد احمد عماد', 'maged@gmail.com', 0, '123', '2026-05-01 04:14:54'),
(8, 'رعد ماجد احمد', 'maged@gmail.com', 0, '123', '2026-05-02 16:50:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
