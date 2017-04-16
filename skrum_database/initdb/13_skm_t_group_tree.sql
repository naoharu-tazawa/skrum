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
-- Dumping data for table `t_group_tree`
--

LOCK TABLES `t_group_tree` WRITE;
/*!40000 ALTER TABLE `t_group_tree` DISABLE KEYS */;
INSERT INTO `t_group_tree` VALUES (1,1,'1/','会社名/','2017-04-08 15:59:53','2017-04-08 17:04:34',NULL),(2,2,'2/','会社名/','2017-04-08 16:00:44','2017-04-08 17:05:17',NULL),(3,3,'3/','会社名/','2017-04-08 16:01:28','2017-04-08 17:05:36',NULL),(4,4,'4/','会社名/','2017-04-08 16:02:09','2017-04-08 17:05:55',NULL),(5,5,'5/','会社名/','2017-04-08 16:02:39','2017-04-08 17:06:25',NULL),(6,6,'1/6/','会社名/グループ名/','2017-04-08 18:12:36','2017-04-08 18:12:36',NULL),(7,7,'1/6/7/','会社名/グループ名/グループ名/','2017-04-08 18:13:06','2017-04-08 18:13:06',NULL),(8,9,'2/9/','会社名/グループ名/','2017-04-08 18:14:14','2017-04-08 18:14:14',NULL),(9,12,'2/9/12/','会社名/グループ名/グループ名/','2017-04-08 18:14:53','2017-04-08 18:14:53',NULL),(10,14,'3/14/','会社名/グループ名/','2017-04-08 18:15:37','2017-04-08 18:15:37',NULL),(11,15,'3/14/15/','会社名/グループ名/グループ名/','2017-04-08 18:15:46','2017-04-08 18:15:46',NULL),(12,17,'4/17/','会社名/グループ名/','2017-04-08 18:16:28','2017-04-08 18:16:28',NULL),(13,18,'4/17/18/','会社名/グループ名/グループ名/','2017-04-08 18:16:34','2017-04-08 18:16:34',NULL),(14,20,'5/20/','会社名/グループ名/','2017-04-08 18:17:12','2017-04-08 18:17:12',NULL),(15,21,'5/20/21/','会社名/グループ名/グループ名/','2017-04-08 18:17:22','2017-04-08 18:17:22',NULL);
/*!40000 ALTER TABLE `t_group_tree` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-08 18:56:34