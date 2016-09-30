-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	4.1.11-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema `comtoday`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `comtoday`;
USE `comtoday`;

--
-- Table structure for table `comtoday`.`artikel`
--

DROP TABLE IF EXISTS `artikel`;
CREATE TABLE `artikel` (
  `id` int(11) NOT NULL auto_increment,
  `categorie` char(30) default NULL,
  `merk` char(30) default NULL,
  `type` char(50) default NULL,
  `inkoop` int(11) default NULL,
  `marge` int(11) default NULL,
  `prijs` int(11) default NULL,
  `omschrijving` char(100) default NULL,
  `voorraad` int(11) default NULL,
  `demo` int(11) default NULL,
  `rma` int(11) default NULL,
  `retour` int(11) default NULL,
  `eol` char(4) default NULL,
  PRIMARY KEY  (`id`),
  KEY `categorie` (`categorie`),
  KEY `merk` (`merk`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`artikel`
--

/*!40000 ALTER TABLE `artikel` DISABLE KEYS */;
INSERT INTO `artikel` (`id`,`categorie`,`merk`,`type`,`inkoop`,`marge`,`prijs`,`omschrijving`,`voorraad`,`demo`,`rma`,`retour`,`eol`) VALUES 
 (6567,'','','',0,0,0,'',0,0,0,0,'Nee'),
 (6568,'','','',0,0,0,'',0,0,0,0,'Nee'),
 (6569,'','','',0,0,0,'',0,0,0,0,'Nee'),
 (6570,'Telefoon','Apple','59849 Home Flex cable 4',395,40,499,'',2,0,0,0,'Nee'),
 (6571,'Telefoon','Apple','92484 LCD digitizer 4S zwart',2900,31,4521,'',2,0,0,0,'Nee'),
 (6572,'Telefoon','Apple','92485 LCD digitizer 4S wit',2900,31,4521,'',2,0,0,0,'Nee'),
 (6573,'Telefoon','Apple','58225 LCD digitizer 4 zwart',2900,31,4521,'',2,0,0,0,'Nee'),
 (6574,'Telefoon','Apple','90495 LCD digitizer 4 wit',2900,31,4521,'',2,0,0,0,'Nee'),
 (6575,'Telefoon','Apple','113242 LCD digitizer 5 wit',8900,31,13874,'',2,0,0,0,'Nee'),
 (6576,'Telefoon','Apple','113241 LCD digitizer 5 zwart',8900,31,13874,'',2,0,0,0,'Nee'),
 (6577,'Telefoon','Samsung','128551 glassscherm s3 zwart',1495,31,2182,'',2,0,0,0,'Nee'),
 (6578,'Telefoon','HTC','119839 Rhyme bruin/beige',5702,31,8886,'',1,0,0,0,'Nee'),
 (6579,'Telefoon','Samsung','S2 i9100 glasscherm wit',1221,31,1871,'',1,0,0,0,'Nee');
INSERT INTO `artikel` (`id`,`categorie`,`merk`,`type`,`inkoop`,`marge`,`prijs`,`omschrijving`,`voorraad`,`demo`,`rma`,`retour`,`eol`) VALUES 
 (6580,'Telefoon','Samsung','136736 LCD digitizer S4 zwart I9500',12500,31,19486,'',1,0,0,0,'Nee'),
 (6581,'Tablet','Samsung','Galaxy Tab 3 (7.0) SM-T210 glasscherm wit',3546,31,5456,'',1,0,0,0,'Nee'),
 (6582,'Tablet','Apple','103933 glasscherm Ipad 3 zwart',3900,31,6080,'',2,0,0,0,'Nee'),
 (6583,'Tablet','Apple','104890 glasscherm ipad 3 wit',3995,31,6080,'',2,0,0,0,'Nee'),
 (6584,'Tablet','Apple','59709 glasscherm Ipad 2 wit',3995,31,6080,'',2,0,0,0,'Nee'),
 (6585,'','','',0,0,0,'',0,0,0,0,'Nee'),
 (6586,'Tablet','Apple','59716 glasscherm Ipad 2 zwart',3900,31,6080,'',1,0,0,0,'Nee'),
 (6587,'Telefoon','Samsung','134144 S3 Glasscherm wit',2484,31,3741,'',1,0,0,0,'Nee'),
 (6588,'Tablet','Apple','Antenne 3G Ipad 2 Rechter antenne',2190,31,3274,'',1,0,0,0,'Nee'),
 (6589,'Telefoon','Samsung','S4 Battery cover wit',2259,31,3430,'',1,0,0,0,'Nee'),
 (6590,'Tablet','Apple','Ipad mini glasscherm wit',8744,31,13562,'',0,0,0,0,'Nee');
/*!40000 ALTER TABLE `artikel` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`betaalwijze`
--

DROP TABLE IF EXISTS `betaalwijze`;
CREATE TABLE `betaalwijze` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`betaalwijze`
--

/*!40000 ALTER TABLE `betaalwijze` DISABLE KEYS */;
INSERT INTO `betaalwijze` (`id`,`naam`) VALUES 
 (1,'pin'),
 (2,'kontant'),
 (3,'rekening'),
 (4,'pin en kontant');
/*!40000 ALTER TABLE `betaalwijze` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`bon`
--

DROP TABLE IF EXISTS `bon`;
CREATE TABLE `bon` (
  `id` int(11) NOT NULL auto_increment,
  `klantid` int(11) default NULL,
  `naam` char(40) default NULL,
  `aanmaakdatum` date default NULL,
  `aanmaaktijd` time default NULL,
  `datum` date default NULL,
  `tijd` time default NULL,
  `status` char(15) default NULL,
  `betaalwijze` char(15) default NULL,
  `totaal` int(11) default NULL,
  `pin` int(11) default NULL,
  `kontant` int(11) default NULL,
  `rekening` int(11) default NULL,
  `userid` int(11) default NULL,
  `moduleid` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`bon`
--

/*!40000 ALTER TABLE `bon` DISABLE KEYS */;
INSERT INTO `bon` (`id`,`klantid`,`naam`,`aanmaakdatum`,`aanmaaktijd`,`datum`,`tijd`,`status`,`betaalwijze`,`totaal`,`pin`,`kontant`,`rekening`,`userid`,`moduleid`) VALUES 
 (1,-1,'','2013-12-19','16:44:55','0000-00-00','00:00:00','wacht','kontant',0,0,0,0,11,1),
 (2,-1,'C. Portegies','2013-12-24','16:30:37','2013-12-24','16:30:57','betaald','pin',9500,9500,0,0,1,1),
 (3,-1,'','2013-12-31','16:39:41','2013-12-31','16:40:19','betaald','pin',14000,14000,0,0,11,1),
 (4,-1,'','2013-12-31','16:45:33','0000-00-00','00:00:00','wacht','kontant',0,0,0,0,11,1);
/*!40000 ALTER TABLE `bon` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`bonarchive`
--

DROP TABLE IF EXISTS `bonarchive`;
CREATE TABLE `bonarchive` (
  `id` int(11) NOT NULL auto_increment,
  `klantid` int(11) default NULL,
  `naam` char(40) default NULL,
  `aanmaakdatum` date default NULL,
  `aanmaaktijd` time default NULL,
  `datum` date default NULL,
  `tijd` time default NULL,
  `status` char(15) default NULL,
  `betaalwijze` char(15) default NULL,
  `totaal` int(11) default NULL,
  `pin` int(11) default NULL,
  `kontant` int(11) default NULL,
  `rekening` int(11) default NULL,
  `userid` int(11) default NULL,
  `moduleid` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`bonarchive`
--

/*!40000 ALTER TABLE `bonarchive` DISABLE KEYS */;
/*!40000 ALTER TABLE `bonarchive` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`bonstatus`
--

DROP TABLE IF EXISTS `bonstatus`;
CREATE TABLE `bonstatus` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`bonstatus`
--

/*!40000 ALTER TABLE `bonstatus` DISABLE KEYS */;
INSERT INTO `bonstatus` (`id`,`naam`) VALUES 
 (1,'wacht'),
 (2,'betaald');
/*!40000 ALTER TABLE `bonstatus` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`categorie`
--

/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` (`id`,`naam`) VALUES 
 (1,'Telefoon'),
 (2,'Tablet'),
 (3,'Accessoires'),
 (4,'');
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL auto_increment,
  `bonid` int(11) default NULL,
  `artikelid` int(11) default NULL,
  `aantal` int(11) default NULL,
  `prijs` int(11) default NULL,
  `categorie` char(30) default NULL,
  `merk` char(30) default NULL,
  `type` char(50) default NULL,
  `omschrijving` char(100) default NULL,
  `demo` int(11) default NULL,
  `transactie` char(15) default NULL,
  `totaal` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`item`
--

/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` (`id`,`bonid`,`artikelid`,`aantal`,`prijs`,`categorie`,`merk`,`type`,`omschrijving`,`demo`,`transactie`,`totaal`) VALUES 
 (1,2,6586,1,6080,'Tablet','Apple','59716 glasscherm Ipad 2 zwart','',0,'verkoop',9500),
 (2,3,6590,1,13562,'Tablet','Apple','Ipad mini glasscherm wit','',0,'verkoop',14000);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`kassalog`
--

DROP TABLE IF EXISTS `kassalog`;
CREATE TABLE `kassalog` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `moduleid` int(11) default NULL,
  `status` int(11) default NULL,
  `kasin` int(11) default NULL,
  `kasuit` int(11) default NULL,
  `kasgeld` int(11) default NULL,
  `afromen` int(11) default NULL,
  `pinbon` int(11) default NULL,
  `oprekening` int(11) default NULL,
  `kasverschil` int(11) default NULL,
  `controle` int(11) default NULL,
  `commentaar` char(100) default NULL,
  `datum` date default NULL,
  `tijd` time default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`kassalog`
--

/*!40000 ALTER TABLE `kassalog` DISABLE KEYS */;
INSERT INTO `kassalog` (`id`,`userid`,`moduleid`,`status`,`kasin`,`kasuit`,`kasgeld`,`afromen`,`pinbon`,`oprekening`,`kasverschil`,`controle`,`commentaar`,`datum`,`tijd`) VALUES 
 (1,1,1,1,0,0,0,0,0,0,0,1,'','2005-04-17','17:20:12'),
 (2,1,1,2,0,0,0,0,0,0,0,1,'','2005-04-17','17:20:13'),
 (3,1,2,1,0,0,0,0,0,0,0,1,'','2005-04-18','18:20:12'),
 (4,1,2,2,0,0,0,0,0,0,0,1,'','2005-04-18','18:20:13'),
 (5,1,1,1,0,0,0,0,0,0,0,0,'','2005-05-27','17:22:43'),
 (6,1,1,2,0,0,0,0,1700,108500,0,0,'','2005-05-27','17:30:01'),
 (7,1,1,1,29200,0,0,0,0,0,0,0,'','2005-05-28','09:26:24'),
 (8,1,1,2,29200,33900,33900,0,122050,67500,0,0,'','2005-05-28','20:07:33'),
 (9,1,1,1,33900,0,0,0,0,0,0,0,'','2005-05-30','09:48:57'),
 (10,1,1,2,33900,41700,41700,0,70095,0,0,0,'','2005-05-30','18:23:07'),
 (11,1,1,1,41700,0,0,0,0,0,0,1,'','2005-05-31','09:19:45'),
 (12,1,1,2,41700,34705,53105,18400,34225,0,0,1,'','2005-05-31','18:28:15'),
 (13,1,1,1,34705,0,0,0,0,0,0,1,'','2005-06-01','09:30:24'),
 (14,1,1,2,34705,28025,43025,15000,75995,0,0,1,'','2005-06-01','18:03:14'),
 (15,1,1,1,28025,0,0,0,0,0,0,1,'','2005-06-02','09:19:04');
/*!40000 ALTER TABLE `kassalog` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`kassastatus`
--

DROP TABLE IF EXISTS `kassastatus`;
CREATE TABLE `kassastatus` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`kassastatus`
--

/*!40000 ALTER TABLE `kassastatus` DISABLE KEYS */;
INSERT INTO `kassastatus` (`id`,`naam`) VALUES 
 (1,'geopend'),
 (2,'gesloten');
/*!40000 ALTER TABLE `kassastatus` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`klant`
--

DROP TABLE IF EXISTS `klant`;
CREATE TABLE `klant` (
  `id` int(11) NOT NULL auto_increment,
  `voorletters` char(10) default NULL,
  `tussenvoegsel` char(10) default NULL,
  `achternaam` char(30) default NULL,
  `bedrijfsnaam` char(30) default NULL,
  `straat` char(30) default NULL,
  `huisnr` char(10) default NULL,
  `postcode` char(10) default NULL,
  `woonplaats` char(30) default NULL,
  `telefoon` char(20) default NULL,
  `email` char(20) default NULL,
  `debiteur` char(4) default NULL,
  `eol` char(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`klant`
--

/*!40000 ALTER TABLE `klant` DISABLE KEYS */;
INSERT INTO `klant` (`id`,`voorletters`,`tussenvoegsel`,`achternaam`,`bedrijfsnaam`,`straat`,`huisnr`,`postcode`,`woonplaats`,`telefoon`,`email`,`debiteur`,`eol`) VALUES 
 (1,'','','','','','','','','','','0','0'),
 (2,'R.J.','','Mol','','Vleugeltjesbloem','24','1902 GJ','Castricum','0251-653333','h.mol@weel.nl','Ja',''),
 (3,'','','Klinkenberg','Jelline','Tormentil','3','1902 JM','Castricum','0251-655481','','Ja',''),
 (4,'','','Hopster','Q-Works','Ardennenlaan','9','1966 RS','Heemskerk','','','Nee',''),
 (5,'','','Biesbroek','','Luxemburglaan','879','1966 MV','Heemskerk','06-15118206','jutter879@wanadoo.nl','Nee',''),
 (6,'','','','','','','','','','','0','0'),
 (7,'M & J','','Grasmeijer - Woudt','','Paulingstraat','6','1902 CX','Castricum','0251-652861','','',''),
 (8,'','','','J.P. Lute Beheer BV','','','','','','','',''),
 (9,'','','O. Karres','','Ossemarkt','20','1981LX','Weesp','','','',''),
 (10,'','','van Schie','','','','','Heemskerk','','','Nee',''),
 (11,'','','Meijer','','Bachstraat','52','1962 BD','Heemskerk','0651266942','','',''),
 (12,'','','','','','','','','','','0','0');
/*!40000 ALTER TABLE `klant` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`merk`
--

DROP TABLE IF EXISTS `merk`;
CREATE TABLE `merk` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`merk`
--

/*!40000 ALTER TABLE `merk` DISABLE KEYS */;
INSERT INTO `merk` (`id`,`naam`) VALUES 
 (208,'Apple'),
 (209,'Samsung'),
 (210,'HTC'),
 (211,'');
/*!40000 ALTER TABLE `merk` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `id` int(11) NOT NULL auto_increment,
  `type` char(10) default NULL,
  `naam` char(20) default NULL,
  `ipaddress` char(16) default NULL,
  `printer` char(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`module`
--



--
-- Table structure for table `comtoday`.`systeem`
--

DROP TABLE IF EXISTS `systeem`;
CREATE TABLE `systeem` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(30) default NULL,
  `totaal` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`systeem`
--

/*!40000 ALTER TABLE `systeem` DISABLE KEYS */;
INSERT INTO `systeem` (`id`,`naam`,`totaal`) VALUES 
 (1,'Nieuw',0),
 (2,'Nieuw',0);
/*!40000 ALTER TABLE `systeem` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`systeemitem`
--

DROP TABLE IF EXISTS `systeemitem`;
CREATE TABLE `systeemitem` (
  `id` int(11) NOT NULL auto_increment,
  `systeemid` int(11) default NULL,
  `artikelid` int(11) default NULL,
  `categorie` char(30) default NULL,
  `merk` char(30) default NULL,
  `type` char(50) default NULL,
  `omschrijving` char(100) default NULL,
  `aantal` int(11) default NULL,
  `totaal` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`systeemitem`
--

/*!40000 ALTER TABLE `systeemitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `systeemitem` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`transactie`
--

DROP TABLE IF EXISTS `transactie`;
CREATE TABLE `transactie` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`transactie`
--

/*!40000 ALTER TABLE `transactie` DISABLE KEYS */;
INSERT INTO `transactie` (`id`,`naam`) VALUES 
 (1,'verkoop'),
 (2,'retour'),
 (3,'rma');
/*!40000 ALTER TABLE `transactie` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(20) default NULL,
  `password` char(20) default NULL,
  `role` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`,`naam`,`password`,`role`) VALUES 
 (1,'rob','wachtwoord','Beheerder');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`userrole`
--

DROP TABLE IF EXISTS `userrole`;
CREATE TABLE `userrole` (
  `id` int(11) NOT NULL auto_increment,
  `naam` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`userrole`
--

/*!40000 ALTER TABLE `userrole` DISABLE KEYS */;
INSERT INTO `userrole` (`id`,`naam`) VALUES 
 (1,'beheerder'),
 (2,'medewerker');
/*!40000 ALTER TABLE `userrole` ENABLE KEYS */;


--
-- Table structure for table `comtoday`.`voorraadlog`
--

DROP TABLE IF EXISTS `voorraadlog`;
CREATE TABLE `voorraadlog` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `moduleid` int(11) default NULL,
  `artikelid` int(11) default NULL,
  `inkoop` int(11) default NULL,
  `prijs` int(11) default NULL,
  `transactie` char(20) default NULL,
  `aantal` int(11) default NULL,
  `totaal` int(11) default NULL,
  `datum` date default NULL,
  `tijd` time default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comtoday`.`voorraadlog`
--

/*!40000 ALTER TABLE `voorraadlog` DISABLE KEYS */;
INSERT INTO `voorraadlog` (`id`,`userid`,`moduleid`,`artikelid`,`inkoop`,`prijs`,`transactie`,`aantal`,`totaal`,`datum`,`tijd`) VALUES 
 (1,11,4,6570,395,499,'voorraad',2,790,'2013-12-19','16:10:34'),
 (2,11,4,6571,2900,4521,'voorraad',2,5800,'2013-12-19','16:15:01'),
 (3,11,4,6572,2900,4521,'voorraad',2,5800,'2013-12-19','16:16:14'),
 (4,11,4,6573,2900,4521,'voorraad',2,5800,'2013-12-19','16:19:07'),
 (5,11,4,6574,2900,4521,'voorraad',2,5800,'2013-12-19','16:20:08'),
 (6,11,4,6575,8900,13874,'voorraad',2,17800,'2013-12-19','16:21:53'),
 (7,11,4,6576,8900,13874,'voorraad',2,17800,'2013-12-19','16:24:45'),
 (8,11,4,6577,1495,2182,'voorraad',2,2990,'2013-12-19','16:28:49'),
 (9,11,4,6578,5702,8886,'voorraad',1,5702,'2013-12-19','16:30:08'),
 (10,11,4,6579,1221,1871,'voorraad',1,1221,'2013-12-19','16:31:28'),
 (11,11,4,6579,1221,1871,'voorraad',1,1221,'2013-12-19','16:33:00'),
 (12,11,4,6579,1221,1871,'voorraad',-1,-1221,'2013-12-19','16:33:24'),
 (13,11,4,6581,3546,5456,'voorraad',1,3546,'2013-12-19','16:35:03'),
 (14,11,4,6582,3900,6080,'voorraad',2,7800,'2013-12-19','16:36:26');
INSERT INTO `voorraadlog` (`id`,`userid`,`moduleid`,`artikelid`,`inkoop`,`prijs`,`transactie`,`aantal`,`totaal`,`datum`,`tijd`) VALUES 
 (15,11,4,6583,3995,6080,'voorraad',2,7990,'2013-12-19','16:37:52'),
 (16,11,4,6584,3995,6080,'voorraad',2,7990,'2013-12-19','16:40:38'),
 (17,11,4,6586,3900,6080,'voorraad',2,7800,'2013-12-19','16:42:58'),
 (18,11,4,6580,12500,19486,'voorraad',1,12500,'2013-12-20','15:11:07'),
 (19,11,4,6587,2484,3741,'voorraad',1,2484,'2013-12-23','11:10:43'),
 (20,11,4,6588,2190,3274,'voorraad',1,2190,'2013-12-24','16:49:20'),
 (21,11,4,6589,2259,3430,'voorraad',1,2259,'2013-12-31','14:31:44'),
 (22,11,4,6590,8744,13562,'voorraad',1,8744,'2013-12-31','14:34:15');
/*!40000 ALTER TABLE `voorraadlog` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
