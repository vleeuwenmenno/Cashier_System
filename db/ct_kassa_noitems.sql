-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 23 feb 2017 om 12:46
-- Serverversie: 10.1.19-MariaDB
-- PHP-versie: 7.0.13

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
-- Tabelstructuur voor tabel `cashsession`
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

--
-- Gegevens worden geëxporteerd voor tabel `cashsession`
--

INSERT INTO `cashsession` (`cashSessionId`, `cashRegisterId`, `openedBy`, `cashIn`, `cashOut`, `grossTurnover`, `netTurnover`, `cash`, `pin`, `bankTransfer`, `margin`, `openDate`, `closeDate`) VALUES
(1, 0, 0, '239.32', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '14-02-2017 09:30:18', NULL),
(2, 0, 1, '590.03', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '22-02-2017 10:16:17', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` int(11) NOT NULL,
  `crName` varchar(255) NOT NULL,
  `crStaticIP` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `status` varchar(96) NOT NULL DEFAULT 'LoggedOff',
  `currentSession` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `cash_registers`
--

INSERT INTO `cash_registers` (`id`, `crName`, `crStaticIP`, `status`, `currentSession`) VALUES
(0, 'Kassa 1', '::1', 'LoggedOn', 1),
(1, 'Kassa 1', '192.168.1.107', 'LoggedOn', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customers`
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
-- Gegevens worden geëxporteerd voor tabel `customers`
--

INSERT INTO `customers` (`customerId`, `initials`, `familyName`, `companyName`, `streetName`, `city`, `postalCode`, `phoneNumber`, `mobileNumber`, `email`, `receipts`) VALUES
(2, 'M.C.', 'van Leeuwen', '', 'Reguliersdwarsstraat 12A', 'Beverwijk', '1947GG', '0251-241-255', '06 49 93 51 79', '', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `receipt`
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

--
-- Gegevens worden geëxporteerd voor tabel `receipt`
--

INSERT INTO `receipt` (`receiptId`, `creator`, `parentSession`, `items`, `createDt`, `paidDt`, `customerId`, `paymentMethod`) VALUES
(1172399368, 1, 2, '%7B%22390657%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A12.87%2C%22priceModifier%22%3A%22%2A+1.375+%2B+2.59%22%7D%7D%7D', '11:24:30 22-02-2017', '11:28:05 22-02-2017', 0, 'PIN'),
(1172399397, 1, 2, '%7B%22441736%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A0%2C%22priceModifier%22%3A%22+%2B+137.94%22%7D%7D%7D', '11:29:19 22-02-2017', '11:30:35 22-02-2017', 0, 'BANK'),
(1172399672, 1, 2, '', '11:31:13 22-02-2017', NULL, 0, NULL),
(1172399943, 1, 2, '%7B%22389344%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A4.9%2C%22priceModifier%22%3A%22%2A+1.375+%2B6.80%22%7D%7D%7D', '11:35:00 22-02-2017', '11:38:25 22-02-2017', 0, 'PIN'),
(1172399959, 1, 2, '%7B%22390974%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A1.49%2C%22priceModifier%22%3A%22%2A+1.375+%2B5.47%22%7D%7D%7D', '11:38:33 22-02-2017', '11:40:17 22-02-2017', 0, 'CASH'),
(1172400453, 1, 2, '%7B%22441737%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A%2210.88%22%2C%22priceModifier%22%3A%22+%2A+1.375+-+0.1%22%7D%7D%2C%22390381%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A7.53%2C%22priceModifier%22%3A%22%2A+1.375+%2B5+-+0.53%22%7D%7D%2C%22390302%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A7.57%2C%22priceModifier%22%3A%22%2A+1.375+-1.6%22%7D%7D%2C%22390306%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A9.75%2C%22priceModifier%22%3A%22%2A+1.375+-.23%22%7D%7D%2C%22390385%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A7.53%2C%22priceModifier%22%3A%22%2A+1.375+%2B+4+-+.53%22%7D%7D%7D', '11:44:15 22-02-2017', '11:47:10 22-02-2017', 0, 'PIN'),
(1172400597, 1, 2, '%7B%22441736%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A0%2C%22priceModifier%22%3A%22+%2B+10+%2B+1.95%22%7D%7D%7D', '11:48:09 22-02-2017', '11:49:15 22-02-2017', 0, 'PIN'),
(1172400691, 1, 2, '%7B%22389794%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A3.5%2C%22priceModifier%22%3A%22%2A+1.375+%2B+2.12%22%7D%7D%7D', '11:50:01 22-02-2017', '11:50:39 22-02-2017', 0, 'PIN'),
(1172408142, 1, 2, '%7B%22392539%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A31.9%2C%22priceModifier%22%3A%22%2A+1.375+-+4+%2B+0.91%22%7D%7D%7D', '13:52:04 22-02-2017', '13:53:17 22-02-2017', 0, 'PIN'),
(1172411464, 1, 2, '%7B%22391584%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A0.99%2C%22priceModifier%22%3A%22%2A+1.375+%2B+6.30%22%7D%7D%2C%22391614%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A1.59%2C%22priceModifier%22%3A%22%2A+1.375+%2B+7.31%22%7D%7D%7D', '14:49:08 22-02-2017', '15:12:09 22-02-2017', 0, 'PIN'),
(1172413177, 1, 2, '%7B%22390936%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A2.5%2C%22priceModifier%22%3A%22%2A+1.375%2B+5.8+-+0.02%22%7D%7D%7D', '15:16:56 22-02-2017', '15:36:39 22-02-2017', 0, 'CASH'),
(1172413225, 1, 2, '%7B%22391750%22%3A%7B%22count%22%3A%22-1%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A%2247.30%22%2C%22priceModifier%22%3A%22%2A+1.375+-3.69%22%7D%7D%2C%22391850%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A%2236.79%22%2C%22priceModifier%22%3A%22%2A+1.375+%2B+0.78%22%7D%7D%7D', '15:15:55 22-02-2017', '15:16:24 22-02-2017', 0, 'CASH'),
(1172415244, 1, 2, '%7B%22390353%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A13.41%2C%22priceModifier%22%3A%22%2A+1.375+-+0.32%22%7D%7D%7D', '15:53:59 22-02-2017', '15:54:25 22-02-2017', 0, 'PIN'),
(1172415359, 1, 2, '%7B%22441738%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A10%2C%22priceModifier%22%3A%22+%2A+1.24+%2B+37%22%7D%7D%7D', '15:54:57 22-02-2017', '15:55:42 22-02-2017', 0, 'PIN'),
(1172423131, 1, 2, '%7B%22441738%22%3A%7B%22count%22%3A%221%22%2C%22priceAPiece%22%3A%7B%22priceExclVat%22%3A10%2C%22priceModifier%22%3A%22+%2A+1.24+%2B+4.95%22%7D%7D%7D', '18:04:04 22-02-2017', '18:04:24 22-02-2017', 0, 'PIN');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sessions`
--

CREATE TABLE `sessions` (
  `sessionId` varchar(256) NOT NULL,
  `userId` varchar(32) NOT NULL,
  `lastPing` datetime NOT NULL,
  `validUntil` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `userId` varchar(32) NOT NULL,
  `username` varchar(64) NOT NULL,
  `nickName` varchar(256) NOT NULL,
  `hash` varchar(512) DEFAULT NULL,
  `salt` varchar(512) DEFAULT NULL,
  `userTheme` varchar(255) NOT NULL DEFAULT 'Default',
  `managementUser` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`userId`, `username`, `nickName`, `hash`, `salt`, `userTheme`, `managementUser`) VALUES
('0', 'rob', 'Rob', '401FD1F85B3F53F7286A9A6D4DE402C5DAF38A379C09AF706EEBD2913BBC7B3BFF3060ED56155EFE9272F34F69CD7FC88371C494BA961268AAB92E8EE102E8AA', 'oiefjow389ru32hfi23fn', 'Yeti', 1),
('1', 'menno', 'Menno', '3ADB8B060DCE1F463452243AD1FE0082044523E71EE6DC20EB81F15855A47085230BBFB260861C5559443575FC8BF34B4C6A1C0444DA6BD650B62B91CBAEB405', 'hkjfhihg3no35giheawfuon4fwpislkdzgnhxn', 'Yeti', 1),
('1308', 'jens', 'Jens', 'FF0C4853A2B93952179D0A07C1A967AFC7D7AD62A24625DFA054A5699C086B8C8A018E3310D7430597830901F83B6D680910711CF542A4BBA290543F242334BA', 'qZMn18DjHIEB2tcK7C6HySJWrsTqm4YCpXMvcPFxMUuHNTzrj1MLU0r6I4prjV2fMO8uJp4DfXHEVbk1RiPGE1x8nzjlDFtKxwSmERRC5ThurmssNsguKU13oWA7khh8', 'Yeti', 0),
('1723', 'barry', 'Barry', '7D38091ADBE5E7333F2894A129982A4AD935FF41930687BD9709D14414AFCB236F89A2F554D8CD155DDEE586327E8CA1E0B278EE339CA6537B0048715D8CACAF', 'AbSAF64f95I51JxrJ8KafT4A25Hz5kosQGl4hMhMSGppB0sjVPEkNKHOHRPABLK1YyGWMOlGuEY74DgzZSqSeYdpe1PZ0AZXYNUa8eiYXZpcoEVcWj5OwABnExHKgN5h', 'Yeti', 1);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `cashsession`
--
ALTER TABLE `cashsession`
  ADD PRIMARY KEY (`cashSessionId`),
  ADD UNIQUE KEY `cashSessionId` (`cashSessionId`);

--
-- Indexen voor tabel `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerId`);

--
-- Indexen voor tabel `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receiptId`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `cashsession`
--
ALTER TABLE `cashsession`
  MODIFY `cashSessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1172423132;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
