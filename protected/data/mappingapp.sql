-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.8-MariaDB


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema mappingapp
--

CREATE DATABASE IF NOT EXISTS mappingapp;
USE mappingapp;

--
-- Definition of table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(500) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `subscription_level` varchar(45) DEFAULT NULL,
  `renewal_date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`,`username`,`email`,`password`,`first_name`,`middle_name`,`last_name`,`address`,`subscription_level`,`renewal_date`) VALUES 
 (1,'palash','palash@c.m','$2a$10$1qAz2wSx3eDc4rFv5tGb5eTcmVIl3CiJjZOCZOkP4ZlIRJCKSnH1S','palash','sheemoul','islam','Dhaka','PowerUser','2015-11-27'),
 (2,'palash1','palash@c.m','$2a$10$1qAz2wSx3eDc4rFv5tGb5e2hWNWkZAwkLPh2BijKepfZ85zQ4szf6','','','','','',''),
 (3,'palash2','palash@c.m','$2a$10$1qAz2wSx3eDc4rFv5tGb5eprWkuXd.sGMPxStzeRB/KZ92jOLNvZa','','','','','',''),
 (4,'user1','user1@com.net','$2a$10$1qAz2wSx3eDc4rFv5tGb5eWTvINon9tqPVqA3RufYFM3ITI/AuNOy','','','','','','2015-11-25'),
 (5,'user2','user1@com.net','$2a$10$1qAz2wSx3eDc4rFv5tGb5eme2PNeqfGehxiTFVOPWeMC8QaIhypAC','','','','','',''),
 (6,'shaiful','kuvic16@gmail.com','$2a$10$1qAz2wSx3eDc4rFv5tGb5eprWkuXd.sGMPxStzeRB/KZ92jOLNvZa','Shaiful','Islam','Palash','Dhaka, Bangladesh','Free user','2015-11-26');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;


--
-- Definition of table `user_file`
--

DROP TABLE IF EXISTS `user_file`;
CREATE TABLE `user_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `physical_file_name` varchar(500) DEFAULT NULL,
  `creation_date` varchar(45) DEFAULT NULL,
  `last_modified_date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_file`
--

/*!40000 ALTER TABLE `user_file` DISABLE KEYS */;
INSERT INTO `user_file` (`id`,`username`,`file_name`,`physical_file_name`,`creation_date`,`last_modified_date`) VALUES 
 (1,'0','',NULL,NULL,NULL),
 (2,'0','',NULL,NULL,NULL),
 (3,'0','',NULL,NULL,NULL),
 (5,'palash','SpreadSheet Example.csv','palash-1448559689-SpreadSheet Example.csv','2015-11-26 06:11:29',NULL),
 (6,'palash1','SpreadSheet Example.csv','palash1-1448586160-SpreadSheet Example.csv','2015-11-27 02:11:40',NULL),
 (7,'palash','sse.csv','palash-1448586774-sse.csv','2015-11-27 02:11:54',NULL),
 (8,'palash','sse.csv','palash-1448906849-sse.csv','2015-11-30 07:11:29',NULL);
/*!40000 ALTER TABLE `user_file` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
