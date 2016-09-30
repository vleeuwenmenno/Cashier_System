-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2016 at 03:44 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.5.38

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
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` int(11) NOT NULL,
  `crName` varchar(255) NOT NULL,
  `crStaticIP` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `status` varchar(96) NOT NULL DEFAULT 'LoggedOff'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cash_registers`
--

INSERT INTO `cash_registers` (`id`, `crName`, `crStaticIP`, `status`) VALUES
(1, 'Kassa 1', '127.0.0.1', 'LoggedOff');

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
  `hash` varchar(512) DEFAULT NULL,
  `salt` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `hash`, `salt`) VALUES
('4656C64E531A6FD938BC62A20CC313A0', 'rob', 'B9D3FF1350DB22B53B7AA8FD37941A7E3E37132E5C1E634E83CD0F0F276ADF26844DE1689BE9C56689FA0CCDD504FDC9395B6260870B43378149913D50995436', 'isrW35yM32Mo5xeunkV73fCCcnvOwydP');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userId` (`userId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
