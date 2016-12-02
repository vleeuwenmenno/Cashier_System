-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2016 at 12:39 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ct_kassa`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashsession`
--

CREATE TABLE `cashsession` (
  `cashSessionId` int(11) NOT NULL,
  `cashRegisterId` int(11) NOT NULL,
  `openedBy` int(11) NOT NULL,
  `cashIn` decimal(18,2) NOT NULL,
  `cashOut` decimal(18,2) NOT NULL,
  `grossTurnover` decimal(18,2) NOT NULL,
  `netTurnover` decimal(18,2) NOT NULL,
  `cash` decimal(18,2) NOT NULL,
  `pin` decimal(18,2) NOT NULL,
  `bankTransfer` decimal(18,2) NOT NULL,
  `margin` decimal(18,2) NOT NULL,
  `openDate` varchar(255) NOT NULL,
  `closeDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` int(11) NOT NULL,
  `crName` varchar(255) NOT NULL,
  `crStaticIP` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `status` varchar(96) NOT NULL DEFAULT 'LoggedOff',
  `currentSession` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cash_registers`
--

INSERT INTO `cash_registers` (`id`, `crName`, `crStaticIP`, `status`, `currentSession`) VALUES
(0, 'Kassa 1', '::1', 'LoggedOff', NULL);

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
(2, 'M.C.', 'van Leeuwen', 'van Leeuwen Glas & Montage', 'Reguliersdwarsstraat 12A', 'Beverwijk', '1947GG', '0251-241-255', '06 49 93 51 79', '', NULL);

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
  `priceModifier` varchar(255) NOT NULL DEFAULT '* 1.375'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receiptId` bigint(20) NOT NULL,
  `creator` int(11) NOT NULL,
  `parentSession` int(11) NOT NULL,
  `items` varchar(8192) NOT NULL,
  `createDt` varchar(128) NOT NULL,
  `paidDt` varchar(128) DEFAULT NULL,
  `customerId` int(11) NOT NULL,
  `paymentMethod` varchar(128) DEFAULT NULL
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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sessionId`, `userId`, `lastPing`, `validUntil`) VALUES
('mITezUlwc13olhTPAobi0ALLez0HkzJ3', '0', '2016-12-03 00:37:07', '2016-12-03 01:36:58');

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
('1', 'rob', 'Rob Mol', '401FD1F85B3F53F7286A9A6D4DE402C5DAF38A379C09AF706EEBD2913BBC7B3BFF3060ED56155EFE9272F34F69CD7FC88371C494BA961268AAB92E8EE102E8AA', 'oiefjow389ru32hfi23fn', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashsession`
--
ALTER TABLE `cashsession`
  ADD PRIMARY KEY (`cashSessionId`),
  ADD UNIQUE KEY `cashSessionId` (`cashSessionId`);

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
-- AUTO_INCREMENT for table `cashsession`
--
ALTER TABLE `cashsession`
  MODIFY `cashSessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `nativeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9082;
--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1165362041;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
