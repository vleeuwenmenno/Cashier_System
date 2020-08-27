-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2020 at 03:14 PM
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
-- Database: `cashier_server`
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
(0, 'Kassa 1', '127.0.0.1', 'LoggedOff', NULL);

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
  `sendOrderNow` tinyint(1) NOT NULL,
  `directDebit` tinyint(4) NOT NULL DEFAULT 0,
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

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`nativeId`, `itemId`, `EAN`, `supplier`, `factoryId`, `itemName`, `itemCategory`, `itemStock`, `priceExclVat`, `priceModifier`, `manuallyInserted`) VALUES
(446295, '0001', '', 'Camping Schoonenberg', '0001', 'Volwassenen (dagen)', 'Dagen', 2147483647, '0.00', '+7', 1),
(446296, '0002', '', 'Camping Schoonenberg', '0002', 'Kinderen 1-12 jaar (dagen)', 'Dagen', 2147483647, '0.00', ' + 5', 1),
(446297, '0003', '', 'Camping Schoonenberg', '0003', 'Caravan/Tent', 'Overige', 2147483647, '0.00', ' + 7', 1),
(446298, '0004', '', 'Camping Schoonenberg', '0004', 'Elektra', 'Overige', 2147483647, '0.00', ' + 3', 1),
(446299, '0005', '', 'Camping Schoonenberg', '0005', 'Hond', 'Overige', 2147483647, '0.00', '+ 3.5', 1),
(446300, '0006', '', 'Camping Schoonenberg', '0006', 'Reseveringskosten', 'Overige', 2147483647, '0.00', ' + 20', 1),
(446301, '0007', '', 'Camping Schoonenberg', '0007', 'Natuurkampeerkaart', 'Overige', 2147483647, '0.00', ' + 20', 1),
(446302, '0008', '', 'Camping Schoonenberg', '0008', 'Kennemerduinenkaart', 'Overige', 2147483647, '0.00', ' + 20', 1),
(446303, '0009', '', 'Camping Schoonenberg', '0009', 'Fietsen', 'Overige', 2147483647, '0.00', '+ 9.5', 1),
(446304, '0010', '', 'Camping Schoonenberg', '0010', 'Toeristenbelasting', 'Overige', 2147483647, '0.00', ' + 1,50', 1),
(446305, '0011', '', 'Camping Schoonenberg', '0012', 'Milieubelasting', 'Overige', 2147483647, '0.00', ' + 0.25', 1),
(446306, '0012', '', 'Camping Schoonenberg', '0011', 'Aanbetaald', 'Overige', 2147483647, '0.00', ' + 0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `logId` int(11) NOT NULL,
  `contractId` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `customerId` int(11) NOT NULL,
  `receiverEmail` varchar(96) NOT NULL,
  `items` varchar(8192) NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `success` int(11) NOT NULL DEFAULT 0,
  `notes` varchar(1024) DEFAULT NULL
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
  `smtpPort` varchar(256) NOT NULL,
  `companyAddress` varchar(512) NOT NULL,
  `companyPhone` varchar(512) NOT NULL,
  `companyFax` varchar(512) NOT NULL,
  `companyEmail` varchar(512) NOT NULL,
  `companyWebsite` varchar(512) NOT NULL,
  `companyKvk` varchar(512) NOT NULL,
  `companyIBAN` varchar(512) NOT NULL,
  `companyVATNo` varchar(512) NOT NULL,
  `disclaimer` varchar(8192) NOT NULL,
  `invoiceExpireDays` int(11) NOT NULL DEFAULT 14,
  `VATText` varchar(128) NOT NULL,
  `showCustomerFieldsChk` tinyint(1) NOT NULL DEFAULT 0,
  `multiplierOnItemsChk` tinyint(1) NOT NULL DEFAULT 0,
  `contractSystemChk` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `companyName`, `vat`, `currency`, `smtpHost`, `smtpName`, `smtpUser`, `smtpPass`, `smtpSecure`, `smtpPort`, `companyAddress`, `companyPhone`, `companyFax`, `companyEmail`, `companyWebsite`, `companyKvk`, `companyIBAN`, `companyVATNo`, `disclaimer`, `invoiceExpireDays`, `VATText`, `showCustomerFieldsChk`, `multiplierOnItemsChk`, `contractSystemChk`) VALUES
(1, 'Camping Schoonenberg', '1.21', '&euro;', '', '', '', '', 'STARTTLS', '587', ' ', ' ', '', ' ', ' ', '', '', '', 'kampeerterrein Schoonenberg • Driehuizerkerkweg 15D • 1981 EH Velsen-Zuid<br/>tel: 0255-523998 • b.g.g: 06 54 72 86 99 • email: info@campingschoonenberg.nl<br/>Inschr. K.v.K. te Haarlem nr.: 34095591 • IBAN: NL64 RABO 0169938441 • NL28 INGB 0007832751', 14, 'BTW', 1, 1, 0);

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
  `roomNo` varchar(32) DEFAULT NULL,
  `arrival` varchar(128) DEFAULT NULL,
  `departure` varchar(128) DEFAULT NULL,
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
('QgH9CUCjoi2Nvx76fk7X3upcM8F7qh55', '1', '2020-08-27 15:11:03', '2020-08-28 01:59:11');

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
('1', 'admin', 'Admin', '4423D696B54809CB925E5B1BB93099DF40F58308222591C123031981E8716FC975556918F0D7A2525696AA7D055DCA76AC781A56A56C680E07316EA00258B05E', '1Pvtj4tttH0uQx5P8a46tCpFoZtLRtzA1ivxWCjc9ctTqKCG5FVsFthdiQpYJL6Lv5tfZk9SyXAldZspxQEcILWkVaWCoXHrjrgPY3bDwn4Ji7D6QmCwgsiY3sKhl3qW', 'Superhero', 1);

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
  ADD PRIMARY KEY (`logId`),
  ADD KEY `contractId` (`contractId`),
  ADD KEY `orderDate` (`orderDate`);

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
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `nativeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptId` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
