-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2020 at 10:35 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cashier`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashsession`
--

CREATE TABLE `cashsession` (
  `cashSessionId` int(11) NOT NULL,
  `cashRegisterId` int(11) NOT NULL,
  `openedBy` int(11) NOT NULL,
  `closedBy` int(11) NOT NULL,
  `cashIn` decimal(18,2) NOT NULL,
  `cashOut` decimal(18,2) NOT NULL,
  `cutOut` decimal(18,2) NOT NULL,
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
(0, 'Kassa 1', '127.0.0.1', 'LoggedOn', 298);

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `contractId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `startDate` varchar(128) NOT NULL,
  `planningPeriod` varchar(64) NOT NULL,
  `planningDay` int(11) NOT NULL,
  `items` varchar(8192) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `priceModifier` varchar(255) NOT NULL DEFAULT '* 1.375',
  `manuallyInserted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `logId` int(11) NOT NULL,
  `contractId` int(11) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `customerId` int(11) NOT NULL,
  `receiverEmail` varchar(96) NOT NULL,
  `items` varchar(8192) NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `success` int(11) NOT NULL,
  `notes` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `vat` decimal(10,2) NOT NULL,
  `currency` varchar(32) NOT NULL,
  `smtpHost` varchar(256) NOT NULL,
  `smtpName` varchar(256) NOT NULL,
  `smtpUser` varchar(256) NOT NULL,
  `smtpPass` varchar(256) NOT NULL,
  `smtpSecure` varchar(256) NOT NULL,
  `smtpPort` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `companyName`, `vat`, `currency`, `smtpHost`, `smtpName`, `smtpUser`, `smtpPass`, `smtpSecure`, `smtpPort`) VALUES
(1, 'Com Today', '1.21', '&euro;', 'smtp02.hostnet.nl', 'facturen@comtoday.nl', 'smtp@comforttoday.nl', 'Maerelaan26!', 'STARTTLS', '587');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receiptId` bigint(20) NOT NULL,
  `creator` int(11) NOT NULL,
  `parentSession` int(11) NOT NULL,
  `items` varchar(8192) NOT NULL,
  `receiptDesc` varchar(4096) DEFAULT NULL,
  `createDt` varchar(128) NOT NULL,
  `paidDt` varchar(128) DEFAULT NULL,
  `customerId` int(11) NOT NULL,
  `cashValue` decimal(18,2) NOT NULL,
  `pinValue` decimal(18,2) NOT NULL,
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
  `userTheme` varchar(255) NOT NULL DEFAULT 'Default',
  `managementUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `nickName`, `hash`, `salt`, `userTheme`, `managementUser`) VALUES
('1', 'menno', 'Menno', '3ADB8B060DCE1F463452243AD1FE0082044523E71EE6DC20EB81F15855A47085230BBFB260861C5559443575FC8BF34B4C6A1C0444DA6BD650B62B91CBAEB405', 'hkjfhihg3no35giheawfuon4fwpislkdzgnhxn', 'Superhero', 1);

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
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`contractId`);

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
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`logId`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `cashSessionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `contractId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `nativeId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptId` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
