-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2019 at 05:21 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `card`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `branch_id` int(5) NOT NULL,
  `status` int(1) NOT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `trans_limit` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `user_id`, `branch_id`, `status`, `balance`, `trans_limit`) VALUES
(2, 1, 2, 1, 21300, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `block_history`
--

CREATE TABLE `block_history` (
  `id` int(5) NOT NULL,
  `account_id` int(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `branch_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `block_history`
--

INSERT INTO `block_history` (`id`, `account_id`, `created_at`, `branch_id`) VALUES
(1, 2, '2017-03-29 17:39:59', 1),
(2, 2, '2017-03-29 18:34:17', 4);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` int(5) NOT NULL,
  `name` varchar(32) NOT NULL,
  `location` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `location`) VALUES
(1, 'Unity Bank', '148 Aka Road, Uyo, State.'),
(2, 'Keystone Bank', '132 Abak Road, Uyo, Akwa Ibom State.'),
(3, 'Access Bank', '45 Abak Road, Uyo, Akwa Ibom State.'),
(4, 'Zenith Bank', '62 Abak Road, Uyo, Akwa Ibom State.'),
(5, 'GTBank PLC', '144 Udoudoma Avenue, Uyo, Akwa Ibom State.'),
(6, 'First Bank Plc', '63 Aka Road, Uyo');

-- --------------------------------------------------------

--
-- Table structure for table `credit_card`
--

CREATE TABLE `credit_card` (
  `id` int(5) NOT NULL,
  `allowed_branches` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ac_number` bigint(20) NOT NULL,
  `pin` int(4) NOT NULL,
  `account_id` int(5) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `credit_card`
--

INSERT INTO `credit_card` (`id`, `allowed_branches`, `ac_number`, `pin`, `account_id`, `status`) VALUES
(1, '[ 3 6 5]', 5531886652142950, 3310, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(5) NOT NULL,
  `account_id` int(5) NOT NULL,
  `branch_id` int(5) NOT NULL,
  `amount` int(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `account_id`, `branch_id`, `amount`, `created_at`) VALUES
(15, 2, 0, 100, '2017-03-25 04:54:43'),
(16, 2, 0, 2000, '2017-03-25 04:56:19'),
(17, 2, 0, 500, '2017-03-29 15:51:21'),
(18, 2, 5, 200, '2017-03-29 16:07:09'),
(19, 2, 3, 300, '2017-03-29 16:07:57'),
(20, 2, 6, 500, '2017-03-29 18:35:52'),
(21, 2, 1, 200, '2019-11-07 10:58:19'),
(22, 2, 1, 1000, '2019-11-07 10:59:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`) VALUES
(1, 'Mr Tester', 'tester@gmail.com', '12345', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `block_history`
--
ALTER TABLE `block_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_card`
--
ALTER TABLE `credit_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `block_history`
--
ALTER TABLE `block_history`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `credit_card`
--
ALTER TABLE `credit_card`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
