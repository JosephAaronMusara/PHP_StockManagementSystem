-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 08, 2024 at 02:13 PM
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
-- Database: `stockmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alert_type` enum('low_stock','transaction') NOT NULL,
  `stock_item_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Books'),
(3, 'Clothing'),
(1, 'Electronics'),
(2, 'Furniture'),
(5, 'Stationery');

-- --------------------------------------------------------

--
-- Table structure for table `profit_loss`
--

CREATE TABLE `profit_loss` (
  `id` int(11) NOT NULL,
  `stock_item_id` int(11) NOT NULL,
  `sales_total` decimal(10,2) NOT NULL,
  `purchase_total` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) GENERATED ALWAYS AS (`sales_total` - `purchase_total`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `received_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `supplier_id`, `user_id`, `total_amount`, `received_at`, `created_at`) VALUES
(11, 9, 6, 150.00, '2024-09-27 06:31:44', '2024-10-04 13:04:29');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `stock_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_details`
--

INSERT INTO `purchase_order_details` (`id`, `purchase_order_id`, `stock_item_id`, `quantity`, `unit_price`) VALUES
(6, 11, 20, 10, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `total_amount`, `created_at`) VALUES
(6, 7, 5000.00, '2024-09-26 13:21:50'),
(10, 6, 120.00, '2024-09-27 06:32:55'),
(12, 6, 5000.00, '2024-09-27 14:15:41'),
(13, 6, 750.00, '2024-10-02 12:25:02'),
(15, 6, 4000.00, '2024-10-02 12:48:42'),
(19, 6, 1700.00, '2024-10-03 13:11:14'),
(20, 6, 1700.00, '2024-10-04 13:05:18'),
(21, 6, 40.00, '2024-10-04 13:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

CREATE TABLE `sale_details` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `stock_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `sale_id`, `stock_item_id`, `quantity`, `unit_price`) VALUES
(3, 6, 5, 2, 2500.00),
(5, 10, 2, 3, 40.00),
(7, 12, 17, 25, 200.00),
(8, 13, 2, 10, 75.00),
(10, 15, 1, 4, 1000.00),
(14, 19, 15, 2, 850.00),
(15, 20, 15, 2, 850.00),
(16, 21, 20, 2, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_items`
--

CREATE TABLE `stock_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latest_update` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_items`
--

INSERT INTO `stock_items` (`id`, `name`, `category_id`, `supplier_id`, `purchase_price`, `selling_price`, `quantity`, `created_at`, `latest_update`) VALUES
(1, 'Laptop', 1, 1, 750.00, 1000.00, 50, '2024-09-20 07:31:15', '2024-10-03 16:30:17'),
(2, 'Office Chair', 2, 2, 45.00, 75.00, 200, '2024-09-20 07:31:15', '2024-10-03 16:30:17'),
(4, 'Novel', 4, 1, 5.00, 10.00, 500, '2024-09-20 07:31:15', '2024-10-03 16:30:17'),
(5, 'Notebook', 5, 2, 1.00, 2.00, 1000, '2024-09-20 07:31:15', '2024-10-03 16:30:17'),
(14, 'jnvkc', 1, 3, 56.00, 258.00, 2, '2024-09-26 14:23:05', '2024-10-03 16:30:17'),
(15, 'PS5', 1, 3, 600.00, 850.00, 7, '2024-09-27 13:27:17', '2024-10-03 16:30:17'),
(16, 'Phone', 1, 2, 1000.00, 1200.00, 12, '2024-09-27 14:06:03', '2024-10-03 16:30:17'),
(17, 'Pen', 1, 6, 150.00, 200.00, 30, '2024-09-27 14:06:56', '2024-10-03 16:30:17'),
(18, 'Gaming Headsets', 1, 9, 50.00, 75.00, 20, '2024-10-02 15:01:13', '2024-10-03 16:30:17'),
(19, 'Amin Test', 2, 9, 2000.00, 2200.00, 30, '2024-10-04 07:39:44', '2024-10-04 09:39:44'),
(20, 'test Review asdfgh', 2, 9, 15.00, 20.00, 12, '2024-10-04 13:02:59', '2024-10-04 15:02:59');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `stock_item_id` int(11) NOT NULL,
  `movement_type` enum('addition','removal','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `stock_item_id`, `movement_type`, `quantity`, `user_id`, `created_at`) VALUES
(1, 4, 'removal', 10, 6, '2024-10-02 14:42:52'),
(2, 18, 'addition', 20, 6, '2024-10-02 15:01:13'),
(3, 15, 'removal', 2, 6, '2024-10-03 13:11:14'),
(4, 19, 'addition', 20, 8, '2024-10-04 07:39:44'),
(5, 20, 'addition', 12, 6, '2024-10-04 13:02:59'),
(6, 15, 'removal', 2, 6, '2024-10-04 13:05:18'),
(7, 20, 'removal', 2, 6, '2024-10-04 13:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `postal_address` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_info`, `postal_address`, `created_at`) VALUES
(1, 'ABC Suppliers', 'Alice Johnson', 'alice@abc.com 555-1234', '2024-09-20 07:30:47'),
(2, 'XYZ Distributors', 'Bob Smith', 'bob@xyz.com 555-5678', '2024-09-20 07:30:47'),
(3, 'Global Traders', 'Charlie Davis', 'charlie@global.com 555-9876', '2024-09-20 07:30:47'),
(4, 'TARIROYASHE MUSARA', 'Classified', '3672 WEST RD', '2024-09-20 13:26:32'),
(6, 'JOSEPH AARON', 'Not necesarry lol', '455 MLEMI DRIVE', '2024-09-20 13:42:50'),
(8, 'Anthony', '263776168474', 'Shineplus residential estate', '2024-09-30 13:26:12'),
(9, 'Rivers Inc', 'info@riversinc.com', 'Santon, JHB, ZA', '2024-09-30 13:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_type` enum('sale','purchase') NOT NULL,
  `stock_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_type`, `stock_item_id`, `user_id`, `quantity`, `transaction_date`) VALUES
(1, 'sale', 1, 6, 4, '2024-10-02 12:48:42'),
(2, 'purchase', 4, 6, 10, '2024-10-02 12:52:40'),
(3, 'sale', 2, 6, 6, '2024-10-02 14:41:25'),
(4, 'sale', 2, 6, 6, '2024-10-02 14:41:54'),
(5, 'sale', 4, 6, 10, '2024-10-02 14:42:52'),
(6, 'sale', 15, 6, 2, '2024-10-03 13:11:14'),
(7, 'purchase', 20, 6, 10, '2024-10-04 13:04:29'),
(8, 'sale', 15, 6, 2, '2024-10-04 13:05:18'),
(9, 'sale', 20, 6, 2, '2024-10-04 13:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `pwd`, `role`, `created_at`) VALUES
(3, 'mark_smith', 'mark@example.com', 'hashedpassword3', 'user', '2024-09-20 07:27:45'),
(4, 'Jonzo', 'joe@joerter.com', '$2y$10$cA7u2Ctq4lixA2p3Albyiump5OKZBW5i00kh5W.Nn6PPVJCuxk8IC', 'user', '2024-09-24 07:43:29'),
(5, 'Tariroyashe', 'tariroyashe@joerter.com', '$2y$10$4EDHXfDqNNIdasDQ2Tys5u2Lo3q3ovjmdz7h.JYiIWttTugyE4/Tq', 'admin', '2024-09-25 14:07:37'),
(6, 'babaT', 'babat@gmail.com', '$2y$10$hEgWklSW27fUKFlKwy5nhufENop0mcJ/xO4hTM1uqqtvQwsshsHWK', 'user', '2024-09-26 07:27:55'),
(7, 'JosephAaron', 'joe@aaron.com', '$2y$10$LRaQVx1Q0F6DnOd6F4rXE.DKM9FMUypG.XfnxuxvxNH0OefTvBpoi', NULL, '2024-09-26 07:34:50'),
(8, 'Anthony', 'anthony@uncle.com', '$2y$10$nQ5qv6rTnWMdutKlt4dCKumG0ua7dUQBUFFjVrHJZj3ZDHZuYSjki', 'admin', '2024-09-26 13:50:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `stock_item_id` (`stock_item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `profit_loss`
--
ALTER TABLE `profit_loss`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_item_id` (`stock_item_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_item_id` (`stock_item_id`),
  ADD KEY `fk_purchase_order` (`purchase_order_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_details_ibfk_2` (`stock_item_id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `stock_items`
--
ALTER TABLE `stock_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_item_id` (`stock_item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_item_id` (`stock_item_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profit_loss`
--
ALTER TABLE `profit_loss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sale_details`
--
ALTER TABLE `sale_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stock_items`
--
ALTER TABLE `stock_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`);

--
-- Constraints for table `profit_loss`
--
ALTER TABLE `profit_loss`
  ADD CONSTRAINT `profit_loss_ibfk_1` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD CONSTRAINT `fk_purchase_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_details_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `purchase_order_details_ibfk_2` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD CONSTRAINT `sale_details_ibfk_2` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_details_ibfk_3` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_items`
--
ALTER TABLE `stock_items`
  ADD CONSTRAINT `stock_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `stock_items_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`),
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
