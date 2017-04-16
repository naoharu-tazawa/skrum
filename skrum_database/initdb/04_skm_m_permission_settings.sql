-- MySQL dump 10.13  Distrib 5.7.12, for osx10.9 (x86_64)
--
-- Host: 127.0.0.1    Database: skm
-- ------------------------------------------------------
-- Server version	5.7.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `m_permission_settings`
--

LOCK TABLES `m_permission_settings` WRITE;
/*!40000 ALTER TABLE `m_permission_settings` DISABLE KEYS */;
INSERT INTO `m_permission_settings` VALUES (1,'0001','00001','user_delete_self','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(2,'0002','00002','user_add','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(3,'0002','00003','user_delete_self','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(4,'0002','00004','user_delete','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(5,'0003','00005','user_permission_change','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(6,'0004','00006','password_reset','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(7,'0005','00007','company_profile_edit','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(8,'0006','00008','user_add','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(9,'0006','00009','user_delete','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(10,'0007','00010','user_permission_change','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(11,'0008','00011','password_reset','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(12,'0009','00012','timeframe_edit','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(13,'0010','00013','plan_edit','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL),(14,'0011','00014','payment_view','2017-04-03 12:11:50','2017-04-03 12:11:50',NULL);
/*!40000 ALTER TABLE `m_permission_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-03 12:15:53