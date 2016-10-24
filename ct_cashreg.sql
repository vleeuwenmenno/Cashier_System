-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2016 at 05:55 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ct_cashreg`
--

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` int(11) NOT NULL,
  `crName` varchar(255) NOT NULL,
  `crStaticIP` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `status` varchar(96) NOT NULL DEFAULT 'LoggedOff'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerId` int(11) NOT NULL,
  `initials` varchar(8) NOT NULL,
  `familyName` varchar(255) NOT NULL,
  `companyName` varchar(512) NOT NULL,
  `streetName` varchar(512) NOT NULL,
  `city` varchar(512) NOT NULL,
  `postalCode` varchar(8) NOT NULL,
  `phoneNumber` varchar(32) NOT NULL,
  `mobileNumber` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `receipts` varchar(4096) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerId`, `initials`, `familyName`, `companyName`, `streetName`, `city`, `postalCode`, `phoneNumber`, `mobileNumber`, `email`, `receipts`) VALUES
(1, 'M.C.', 'van Leeuwen', 'van Leeuwen Glas & Montage', 'Reguliersdwarsstraat 12A', 'Beverwijk', '1947 GG', '0251-241-255', '', 'info@vanleeuwenglasmontage.nl', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `nativeId` int(11) NOT NULL,
  `itemId` varchar(128) NOT NULL,
  `EAN` varchar(512) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `factoryId` varchar(255) NOT NULL,
  `itemName` varchar(4096) NOT NULL,
  `itemCategory` varchar(256) NOT NULL,
  `itemStock` int(11) NOT NULL,
  `priceExclVat` decimal(18,2) NOT NULL,
  `priceModifier` varchar(255) NOT NULL DEFAULT '* 1.575'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receiptId` bigint(20) NOT NULL,
  `creator` int(11) NOT NULL,
  `items` varchar(8192) NOT NULL,
  `createDt` varchar(128) NOT NULL,
  `paidDt` varchar(128) NOT NULL,
  `customerId` int(11) NOT NULL,
  `totalPaid` int(11) NOT NULL,
  `paymentMethod` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sessionId` varchar(256) NOT NULL,
  `userId` varchar(32) NOT NULL,
  `lastPing` datetime NOT NULL,
  `validUntil` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` varchar(32) NOT NULL,
  `username` varchar(64) NOT NULL,
  `nickName` varchar(256) NOT NULL,
  `hash` varchar(512) DEFAULT NULL,
  `salt` varchar(512) DEFAULT NULL,
  `managementUser` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `nickName`, `hash`, `salt`, `managementUser`) VALUES
('0', 'menno', 'Menno', '3ADB8B060DCE1F463452243AD1FE0082044523E71EE6DC20EB81F15855A47085230BBFB260861C5559443575FC8BF34B4C6A1C0444DA6BD650B62B91CBAEB405', 'hkjfhihg3no35giheawfuon4fwpislkdzgnhxn', 1),
('1', 'rob', 'Rob Mol', '3ADB8B060DCE1F463452243AD1FE0082044523E71EE6DC20EB81F15855A47085230BBFB260861C5559443575FC8BF34B4C6A1C0444DA6BD650B62B91CBAEB405', 'hkjfhihg3no35giheawfuon4fwpislkdzgnhxn', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`nativeId`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receiptId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `nativeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55886;
--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
