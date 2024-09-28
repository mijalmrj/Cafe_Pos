-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2024 at 03:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";


-
-- --------------------------------------------------------
--
-- TABLE structure for TABLE `categories`
-- 
CREATE TABLE `categories` (
    `category_id` int(11) NOT NULL,
    `category_name` varchar(50) NOT NULL,
    `Description` text NOT NULL,
    `image` longblob DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for TABLE `categories`
-- 
INSERT INTO
    `categories` (
        `category_id`,
        `category_name`,
        `Description`,
        `image`
    )
VALUES
    (
        1,
        'tea',
        'A hot beverage made by steeping cured or fresh tea leaves.',
        NULL
    ),
    (
        2,
        'coffee',
        'A hot beverage made from roasted coffee beans.',
        NULL
    ),
    (
        3,
        'soft drinks',
        'A non-alcoholic, carbonated beverage.',
        NULL
    ),
    (
        4,
        'ice drink',
        'A cold beverage served with ice.',
        NULL
    );

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `order`
-- 
CREATE TABLE `order` (
    `order_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` int(11) NOT NULL,
    `total_amount` decimal(10, 2) NOT NULL,
    `shipping_method` enum('pick up', 'deliver', 'dine in', '') NOT NULL,
    `shipping_time` datetime NOT NULL,
    `shipping_location` varchar(100) NOT NULL,
    `order_status` enum('Confirmed', 'Canceled', 'Pending', '') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for TABLE `order`
-- 
INSERT INTO
    `order` (
        `order_id`,
        `user_id`,
        `total_amount`,
        `shipping_method`,
        `shipping_time`,
        `shipping_location`,
        `order_status`
    )
VALUES
    (
        0,
        2,
        0.00,
        'pick up',
        '2024-09-14 13:59:13',
        'N/A',
        'Confirmed'
    ),
    (
        0,
        2,
        0.00,
        'pick up',
        '2024-09-14 13:59:56',
        'N/A',
        'Confirmed'
    ),
    (
        0,
        2,
        0.00,
        'pick up',
        '2024-09-15 09:16:33',
        'N/A',
        'Confirmed'
    ),
    (
        0,
        2,
        0.00,
        'pick up',
        '2024-09-15 22:00:00',
        'In store',
        'Confirmed'
    ),
    (
        0,
        2,
        0.00,
        'deliver',
        '2024-09-15 23:00:00',
        'dnc md',
        'Confirmed'
    );

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `order_detail`
-- 
CREATE TABLE `order_detail` (
    `order_detail_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `order_type` varchar(50) NOT NULL,
    `order_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `size` enum('small', 'medium', 'large') NOT NULL,
    `quantity` decimal(10, 2) NOT NULL,
    `Customize_flavor` enum('Sweetness', 'milk') NOT NULL,
    FOREIGN KEY (order_id) REFERENCES `order`(order_id) ,
    FOREIGN KEY (product_id) REFERENCES `products`(product_id) 
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `password_resets`
-- 
CREATE TABLE `password_resets` (
    `id` int(11) NOT NULL,
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for TABLE `password_resets`
-- 
INSERT INTO
    `password_resets` (`id`, `email`, `token`, `created_at`)
VALUES
    (
        1,
        'smritishrestha579@gmail.com',
        'bce8fbcc32d32df24521e9d0e4e1ecaf4f4ba0afe6cc1de86d67ce39a8c4bdd356caded582a7c3eeb1c6dfbf49e852e27774',
        '2024-09-15 09:43:05'
    ),
    (
        2,
        'smritishrestha579@gmail.com',
        'bb8bcad6b43ff3bef9391151ee9530e48cb034413154fa481e24fd66c71814f680a350bd8e0c6314fbfdf8b664cce0ad4826',
        '2024-09-15 09:53:15'
    );

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `products`
-- 
CREATE TABLE `products` (
    `product_id` int(11) NOT NULL PRIMARY KEY,
    `product_name` varchar(100) NOT NULL,
    `Description` text NOT NULL,
    `Price` decimal(10, 2) NOT NULL,
    `category_id` int(11) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for TABLE `products`
-- 
INSERT INTO
    `products` (
        `product_id`,
        `product_name`,
        `Description`,
        `Price`,
        `category_id`
    )
VALUES
    (
        1,
        'Herbal Tea',
        'A soothing herbal tea blend.',
        5.00,
        1
    ),
    (
        2,
        'Black Tea',
        'Classic black tea with a robust flavor.',
        4.50,
        1
    ),
    (
        3,
        'Green Tea',
        'Refreshing green tea with a hint of citrus.',
        4.75,
        1
    ),
    (
        4,
        'Milk Tea',
        'Sweet and creamy milk tea.',
        5.25,
        1
    ),
    (
        5,
        'Latte',
        'Smooth and creamy latte with a rich coffee flavor.',
        5.50,
        2
    ),
    (
        6,
        'Americano',
        'Bold and strong Americano coffee.',
        4.75,
        2
    ),
    (
        7,
        'Espresso',
        'Rich and intense espresso shot.',
        3.50,
        
    ),
    (
        8,
        'Cappuccino',
        'Classic cappuccino with a frothy top.',
        5.25,
        2
    ),
    (
        9,
        'Juices',
        'Refreshing fruit juices in various flavors.',
        3.00,
        3
    ),
    (
        10,
        'Sodas',
        'Carbonated soft drinks in multiple flavors.',
        2.50,
        3
    ),
    (
        11,
        'Bottled Water',
        'Pure and clean bottled water.',
        1.50,
        3
    ),
    (
        12,
        'Kombucha',
        'Fermented tea with a tangy flavor.',
        4.00,
        3
    ),
    (
        13,
        'Iced Latte',
        'Chilled latte with a touch of ice.',
        5.75,
        4
    ),
    (
        14,
        'Iced Tea',
        'Refreshing iced tea with lemon.',
        4.00,
        4
    ),
    (
        15,
        'Iced Matcha',
        'Iced matcha with a hint of sweetness.',
        5.50,
        4
    ),
    (
        16,
        'Hot Chocolate',
        'Rich and creamy hot chocolate.',
        4.75,
        4
    );

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `sales`
-- 
CREATE TABLE `sales` (
    `sale_id` int(11) NOT NULL AUTO_INCREMENT,
    `sale_date` date NOT NULL,
    `total_amount` date NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `sale_detail`
-- 
CREATE TABLE `sale_detail` (
    `sale_detail` int(11) NOT NULL AUTO_INCREMENT,
    `quantity` int(11) NOT NULL,
    `price` decimal(10, 2) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- TABLE structure for TABLE `users`
-- 
CREATE TABLE `users` (
    `user_id` int(11) NOT NULL PRIMARY KEY,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` enum('Admin', 'Staff', 'Cashier', 'customer') NOT NULL,
    `Email` varchar(100) NOT NULL,
    `Contact_number` int(11) NOT NULL,
    `iv` varchar(32) DEFAULT NULL -- IV for email encryption

) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for TABLE `users`
-- 
INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `Email`, `Contact_number`, `iv`) VALUES
(1, 'Smriti', '$2y$10$z/NT6WJeevYv.t7Kb2bISOtxbfnsNn5UKUuxiURhognrvrOVnnCaC', 'Admin', 'Smritishrestha579@gmail.com', 412512512, NULL),
(2, 'Mijal', '2mijal@', 'Admin', 'mijalmrj231@gmail.com', 421212121, NULL),
(3, 'John', '3john@', 'Staff', 'johnjohn3@gmail.com', 452314448, NULL),
(4, 'Eva', '4eva@', 'Staff', 'evaeva223@gmail.com', 45289637, NULL),
(5, 'Anup', '5anup@', 'Cashier', 'anupgreen23@gmail.com', 45232145, NULL),
(7, 'customer', 'customer', 'customer', 'customer@gmail.com', 452163788, NULL),
(8, '22', '123456789', 'Admin', 'Aasthastha143@gmail.com', 222, NULL),
(0, '8', '$2y$10$e.JVUHAT0ho5rMj.CbN5VeuXHgUeOGptZzI2C91Xro9h0h1W6rJEK', 'Admin', 'roshanikc4164@gmail.com', 4341, NULL),
(0, 'Smriti Shrestha', 'k210259', 'Admin', 'smritishrestha579@gmail.com', 406807637, NULL),
(0, 'Smriti Shrestha', 'Smriti123', 'Admin', 'smritishrestha579@gmail.com', 406807637, NULL),
(0, 'Smriti', 'Smriti123', 'Admin', 'Smriti1@gmail.com', 406807637, NULL),
(0, 'customer', 'customer', 'Admin', 'Customer@gmail.com', 123, NULL),
(0, '', '$2y$10$oIxlq8m.srBSFwjYJPLauuRkzUfXZ7r1RYG8K1bEb8.0fc.4F.RrG', 'Admin', 'abc@gmail.com', 0, NULL),
(0, '', '$2y$10$mBVpAhT6BFQgCgqqgFNDw.Nsv63wHe1XiVIwYeVBRisUK8s1YzJbu', 'Admin', 'a@gmail.com', 0, NULL);

--- 
    CREATE TABLE transactions (
        transaction_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10, 2) NOT NULL,
        user_id INT NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
        FOREIGN KEY (user_id) REFERENCES user(user_id)
    );

-- Indexes for dumped tables
--
--
-- Indexes for TABLE `accounts`
-- 
ALTER TABLE
    `accounts`
ADD
    PRIMARY KEY (`account_id`);

--
-- Indexes for TABLE `categories`
-- 
ALTER TABLE
    `categories`
ADD
    PRIMARY KEY (`category_id`);

--
-- Indexes for TABLE `password_resets`
-- 
ALTER TABLE
    `password_resets`
ADD
    PRIMARY KEY (`id`);

--
-- Indexes for TABLE `products`
-- 
ALTER TABLE
    `products`
ADD
    PRIMARY KEY (`product_id`),
ADD
    KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for TABLE `categories`
-- 
ALTER TABLE
    `categories`
MODIFY
    `category_id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT for TABLE `password_resets`
-- 
ALTER TABLE
    `password_resets`
MODIFY
    `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT for TABLE `products`
-- 
ALTER TABLE
    `products`
MODIFY
    `product_id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 17;

--
-- Constraints for dumped tables
--
--
-- Constraints for TABLE `products`
-- 
ALTER TABLE
    `products`
ADD
    CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE
SET
    NULL ON UPDATE CASCADE;

COMMIT;

/*!40101
 
 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */
;

/*!40101
 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */
;

/*!40101
 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */
;