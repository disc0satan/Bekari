-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2025 at 07:58 AM
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
-- Database: `bekari`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch_transfers`
--

DROP TABLE IF EXISTS `batch_transfers`;
CREATE TABLE `batch_transfers` (
  `batch_transfer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ship_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `destination` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batch_transfers`
--

INSERT INTO `batch_transfers` (`batch_transfer_id`, `quantity`, `ship_time`, `destination`, `batch_id`) VALUES
(2, 3, '2025-12-28 16:00:27', 2, 22),
(3, 1, '2025-12-28 16:00:53', 2, 23),
(4, 8, '2025-12-28 17:16:03', 4, 25),
(5, 8, '2025-12-28 20:27:37', 4, 24);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `phone_number`, `email`, `points`) VALUES
(1, 'kusum', '01629286995', 'random2@gmail.com', 43),
(2, 'prionti', '01911992216', 'kkusum2612@gmail.com', 80),
(3, 'Farhan', '01531741884', 'random@gmail.com', 30),
(4, 'fatema', '01832922310', '', 4),
(6, 'faiyaz', '01783218244', 'random3@gmail.com', 27),
(7, 'abd', '01717875497', '', 4),
(8, 'Santa', '01915767621', 'kkusum2612@gmail.com', 22),
(9, 'kusum', '01938348191', '', 4),
(10, 'ZANNATUL FERDOUS', '01992473856', 'random@gmail.com', 25),
(11, 'Ria', '0138853899', '', 10),
(12, 'Shabana', '01832922315', '6july20th2021@gmail.com', 25),
(13, 'Amin', '01912223456', '', 25);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `role` enum('Admin','Branch Manager','Branch Worker','Hub Manager','Hub Worker') NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `first_name`, `last_name`, `role`, `location_id`, `email`, `password_hash`) VALUES
(24, 'Sabikun', 'Mubasshaira', 'Branch Manager', 2, 'ferdous0816@gmail.com', NULL),
(25, 'Audree', 'Autandrila', 'Branch Worker', 2, 'moinulk9@gmail.com', NULL),
(26, 'Fardin', 'Mahi', 'Admin', 1, 'jannatul0816@gmail.com', '$2y$10$Y.uCV0PnzvehGTOO7eAhUOMd11nEHAaplZ58psckO6QedYy/74Vtq'),
(27, 'Raiyan', 'Faiyaz', 'Hub Manager', 1, 'prionti0816@gmail.com', NULL),
(29, 'Mahpara', 'Zuhaina', 'Hub Worker', 1, 'mahpara.zuhaina.chowdhury@g.bracu.ac.bd', '$2y$10$3xxgd5F6gTtPXLjjQKBaW.D5yOt3NvZoRM6Bea4K3qCRJNP2RbMJy');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `current_stock` decimal(10,2) NOT NULL,
  `reorder_point` decimal(10,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `measure_unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`, `current_stock`, `reorder_point`, `last_updated`, `measure_unit`) VALUES
(1, 'Sugar', 5.00, 15.00, '2025-12-29 05:45:19', 'Kg'),
(2, 'Milk', 5.00, 10.00, '2025-12-29 05:46:04', 'L'),
(3, 'Butter', 8.00, 10.00, '2025-12-17 10:09:53', 'Kg'),
(4, 'Cheese', 5.00, 10.00, '2025-12-20 15:57:46', 'Kg'),
(6, 'Cocoa Powder', 30.00, 10.00, '2025-12-20 15:58:29', 'Kg'),
(7, 'Baking Powder', 40.00, 10.00, '2025-12-17 10:05:18', 'Kg'),
(8, 'Blueberry Jello', 3.00, 5.00, '2025-12-20 15:58:13', 'Kg'),
(11, 'Salt', 30.00, 5.00, '2025-12-17 08:51:57', 'Kg'),
(12, 'Eggs', 630.00, 50.00, '2025-12-20 15:57:57', 'Pcs'),
(14, 'Mango Syrup', 1.00, 2.00, '2025-12-29 08:40:21', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `location_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('hub','branch') NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `name`, `type`, `address`) VALUES
(1, 'Central Office', 'hub', 'Main Road, Dhaka'),
(2, 'Badda Branch', 'branch', '36/A North Badda, Dhaka'),
(4, 'Mirpur 12 Branch', 'branch', 'Harun Mollah Market, Mirpur 12, Dhaka');

--
-- Triggers `location`
--
DROP TRIGGER IF EXISTS `prevent_hub_delete`;
DELIMITER $$
CREATE TRIGGER `prevent_hub_delete` BEFORE DELETE ON `location` FOR EACH ROW BEGIN
    IF OLD.type = 'hub' THEN
        IF (SELECT COUNT(*) FROM Location WHERE type = 'hub') = 1 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot delete the only hub';
        END IF;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `prevent_multiple_hubs_insert`;
DELIMITER $$
CREATE TRIGGER `prevent_multiple_hubs_insert` BEFORE INSERT ON `location` FOR EACH ROW BEGIN
    IF NEW.type = 'hub' THEN
        IF (SELECT COUNT(*) FROM Location WHERE type = 'hub') >= 1 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Only one hub is allowed';
        END IF;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `prevent_multiple_hubs_update`;
DELIMITER $$
CREATE TRIGGER `prevent_multiple_hubs_update` BEFORE UPDATE ON `location` FOR EACH ROW BEGIN
    IF NEW.type = 'hub' AND OLD.type <> 'hub' THEN
        IF (SELECT COUNT(*) FROM Location WHERE type = 'hub') >= 1 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Only one hub is allowed';
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `role` varchar(50) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `read_status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `type`, `message`, `role`, `branch_id`, `read_status`, `created_at`) VALUES
(1, 'ingredient', 'Ingredient Flour has reached reorder point', 'hub_worker', NULL, 0, '2025-12-29 00:46:21'),
(2, 'ingredient', 'Ingredient Flour has reached reorder point', 'hub_worker', NULL, 0, '2025-12-29 00:51:34'),
(3, 'ingredient', 'Ingredient check has reached reorder point', 'hub_worker', NULL, 0, '2025-12-29 00:51:42'),
(4, 'ingredient', 'Ingredient check has reached reorder point', 'hub_worker', NULL, 0, '2025-12-29 00:55:42'),
(5, 'ingredient', 'Ingredient check has reached reorder point', 'hub_worker', NULL, 0, '2025-12-29 00:56:56'),
(14, 'batch_transfer', 'Batch #2 (Vanilla Cupcake) to be transferred to Badda Branch at 2025-12-29 05:20:42', 'Hub Manager', 1, 0, '2025-12-29 10:20:42'),
(17, 'ingredient', 'Ingredient Milk has reached reorder point', 'Hub Manager', 1, 0, '2025-12-29 11:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `order_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_status`, `customer_id`, `location_id`, `order_time`) VALUES
(1, 'Completed', 1, 1, '2025-12-21 17:00:13'),
(2, 'Completed', 1, 1, '2025-12-21 17:06:55'),
(3, 'Completed', 1, 1, '2025-12-21 17:14:35'),
(4, 'Completed', 2, 1, '2025-12-21 18:22:16'),
(5, 'Completed', 2, 1, '2025-12-21 18:23:58'),
(6, 'Completed', 3, 1, '2025-12-22 05:50:13'),
(7, 'Completed', 3, 1, '2025-12-22 05:50:26'),
(8, 'Completed', 3, 1, '2025-12-22 05:58:45'),
(9, 'Completed', 1, 1, '2025-12-22 06:31:49'),
(10, 'Completed', 1, 1, '2025-12-22 06:32:14'),
(11, 'Completed', 4, 1, '2025-12-22 07:15:30'),
(12, 'Completed', 1, 1, '2025-12-22 07:35:19'),
(13, 'Completed', 6, 1, '2025-12-22 07:37:29'),
(14, 'Completed', 6, 1, '2025-12-22 07:43:33'),
(15, 'Completed', 6, 1, '2025-12-22 07:54:38'),
(16, 'Completed', 7, 1, '2025-12-22 07:57:13'),
(17, 'Completed', 8, 1, '2025-12-22 08:47:23'),
(18, 'Completed', 9, 1, '2025-12-22 09:04:09'),
(19, 'Completed', 1, 1, '2025-12-24 16:14:49'),
(20, 'Completed', 10, 1, '2025-12-24 16:17:03'),
(21, 'Completed', 10, 1, '2025-12-24 17:21:19'),
(22, 'Completed', 11, 1, '2025-12-24 17:33:42'),
(23, 'Completed', 12, 1, '2025-12-24 18:16:17'),
(24, 'Completed', 6, 1, '2025-12-25 19:33:18'),
(25, 'Completed', 8, 2, '2025-12-28 23:18:03'),
(26, 'Completed', 11, 2, '2025-12-28 23:18:59'),
(27, 'Completed', 13, 2, '2025-12-29 08:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `quantity_sold`, `unit_price`, `order_id`, `batch_id`) VALUES
(1, 1, 500.00, 1, 1),
(2, 2, 500.00, 2, 1),
(3, 1, 300.00, 2, 2),
(4, 1, 500.00, 3, 1),
(5, 1, 400.00, 4, 2),
(6, 1, 400.00, 5, 2),
(7, 1, 400.00, 6, 4),
(8, 1, 400.00, 7, 4),
(9, 2, 500.00, 8, 3),
(10, 1, 500.00, 9, 3),
(11, 1, 500.00, 10, 3),
(12, 1, 450.00, 11, 3),
(13, 2, 400.00, 12, 4),
(14, 1, 400.00, 13, 4),
(15, 2, 500.00, 13, 3),
(16, 1, 500.00, 14, 3),
(17, 1, 500.00, 15, 3),
(18, 1, 400.00, 16, 4),
(19, 1, 500.00, 17, 3),
(20, 2, 400.00, 17, 4),
(21, 1, 400.00, 18, 4),
(22, 1, 500.00, 19, 3),
(23, 2, 400.00, 19, 4),
(24, 1, 500.00, 20, 3),
(25, 10, 100.00, 21, 7),
(26, 5, 200.00, 21, 5),
(27, 3, 200.00, 22, 5),
(28, 10, 100.00, 23, 6),
(29, 3, 500.00, 23, 3),
(30, 1, 200.00, 24, 5),
(31, 1, 100.00, 24, 6),
(32, 2, 200.00, 25, 8),
(33, 5, 100.00, 25, 6),
(34, 2, 200.00, 26, 8),
(35, 1, 1500.00, 27, 23),
(36, 1, 1000.00, 27, 22);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `shelf_life` int(11) NOT NULL,
  `markdown_hours` int(11) NOT NULL,
  `discount_pct` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `base_price`, `shelf_life`, `markdown_hours`, `discount_pct`) VALUES
(2, 'Mud Cake 500g', 800.00, 2, 30, 50.00),
(3, 'Blueberry Cake 500g', 1000.00, 2, 28, 25.00),
(4, 'Cheese Cake 800g', 1500.00, 2, 24, 30.00),
(10, 'Chocolate Croissant', 100.00, 3, 12, 30.00),
(21, 'Chocolate Brownie', 120.00, 2, 12, 20.00),
(22, 'Mango Cake 800g', 1100.00, 2, 10, 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_batches`
--

DROP TABLE IF EXISTS `product_batches`;
CREATE TABLE `product_batches` (
  `batch_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `initial_quantity` int(11) NOT NULL,
  `current_quantity` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `made_timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_batches`
--

INSERT INTO `product_batches` (`batch_id`, `product_id`, `initial_quantity`, `current_quantity`, `status`, `made_timestamp`) VALUES
(1, 2, 5, 5, 2, '2025-12-21 22:15:05'),
(2, 21, 5, 5, 2, '2025-12-21 10:38:00'),
(3, 22, 1, 1, 2, '2025-12-19 22:51:00'),
(4, 3, 2, 2, 2, '2025-12-18 22:52:00'),
(5, 4, 3, 3, 2, '2025-12-21 23:03:44'),
(7, 3, 5, 2, 2, '2025-12-21 23:27:45'),
(8, 2, 10, 10, 2, '2025-12-20 12:31:00'),
(9, 21, 10, 10, 2, '2025-12-20 12:33:00'),
(10, 21, 5, 5, 2, '2025-12-22 13:33:01'),
(11, 2, 5, 5, 2, '2025-12-20 13:34:00'),
(12, 22, 3, 3, 2, '2025-12-22 14:33:14'),
(13, 4, 5, 5, 2, '2025-12-22 15:08:12'),
(14, 10, 2, 2, 2, '2025-12-19 10:39:00'),
(15, 10, 3, 3, 2, '2025-12-20 10:38:00'),
(16, 22, 1, 1, 2, '2025-12-20 10:41:00'),
(17, 22, 1, 1, 2, '2025-12-21 08:43:00'),
(18, 22, 1, 1, 2, '2025-12-22 22:43:18'),
(19, 4, 1, 1, 2, '2025-12-21 22:46:00'),
(20, 4, 3, 3, 2, '2025-12-24 23:24:13'),
(22, 3, 10, 7, 0, '2025-12-28 21:49:28'),
(23, 4, 12, 11, 0, '2025-12-28 21:49:35'),
(24, 2, 15, 7, 0, '2025-12-28 21:49:43'),
(25, 21, 10, 2, 2, '2025-12-27 10:46:00'),
(26, 2, 8, 8, 0, '2025-12-29 02:25:15');

-- --------------------------------------------------------

--
-- Table structure for table `used_in`
--

DROP TABLE IF EXISTS `used_in`;
CREATE TABLE `used_in` (
  `product_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `used_in`
--

INSERT INTO `used_in` (`product_id`, `ingredient_id`, `quantity`) VALUES
(2, 1, 0.5000),
(2, 2, 1.0000),
(2, 3, 0.2500),
(2, 6, 0.5000),
(2, 12, 8.0000),
(3, 1, 0.5000),
(3, 2, 1.0000),
(3, 7, 0.5000),
(3, 8, 0.2500),
(3, 11, 0.2500),
(4, 1, 0.5000),
(4, 2, 2.0000),
(4, 3, 0.2500),
(4, 4, 0.5000),
(10, 1, 0.0500),
(10, 2, 0.2000),
(10, 3, 0.0500),
(10, 6, 0.0500),
(10, 7, 0.2000),
(21, 1, 0.5000),
(21, 2, 2.0000),
(21, 7, 0.5000),
(21, 12, 8.0000),
(22, 2, 1.0000),
(22, 7, 0.5000),
(22, 12, 6.0000),
(22, 14, 0.3000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch_transfers`
--
ALTER TABLE `batch_transfers`
  ADD PRIMARY KEY (`batch_transfer_id`),
  ADD KEY `fk_transfer_destination` (`destination`),
  ADD KEY `fk_transfer_batch` (`batch_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `product_batches`
--
ALTER TABLE `product_batches`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `used_in`
--
ALTER TABLE `used_in`
  ADD PRIMARY KEY (`product_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch_transfers`
--
ALTER TABLE `batch_transfers`
  MODIFY `batch_transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `product_batches`
--
ALTER TABLE `product_batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch_transfers`
--
ALTER TABLE `batch_transfers`
  ADD CONSTRAINT `fk_transfer_batch` FOREIGN KEY (`batch_id`) REFERENCES `product_batches` (`batch_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfer_destination` FOREIGN KEY (`destination`) REFERENCES `location` (`location_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `product_batches`
--
ALTER TABLE `product_batches`
  ADD CONSTRAINT `product_batches_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `used_in`
--
ALTER TABLE `used_in`
  ADD CONSTRAINT `used_in_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `used_in_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
