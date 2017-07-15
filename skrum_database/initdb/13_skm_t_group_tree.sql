-- MySQL dump 10.13  Distrib 5.7.12, for osx10.9 (x86_64)
--
-- Host: 127.0.0.1    Database: skm
-- ------------------------------------------------------
-- Server version	5.7.18

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
INSERT INTO `t_group_tree` VALUES (1,1,'1/','株式会社Skrum/','2017-04-28 17:32:16','2017-04-28 17:55:54',NULL),(2,2,'1/2/','株式会社Skrum/コーポレート本部/','2017-04-28 18:07:50','2017-06-27 00:25:28',NULL),(3,3,'1/3/','株式会社Skrum/グループ１−２/','2017-04-28 18:08:02','2017-04-28 18:08:02',NULL),(4,4,'1/2/4/','株式会社Skrum/コーポレート本部/グループ２−１/','2017-04-28 18:08:31','2017-06-27 00:25:28',NULL),(5,5,'1/2/5/','株式会社Skrum/コーポレート本部/グループ２−２/','2017-04-28 18:08:35','2017-06-27 00:25:28',NULL),(6,6,'1/3/6/','株式会社Skrum/グループ１−２/人事部/','2017-04-28 18:08:45','2017-06-27 00:25:36',NULL),(7,7,'1/3/7/','株式会社Skrum/グループ１−２/人材開発部/','2017-04-28 18:08:49','2017-06-27 00:25:51',NULL),(8,8,'1/2/4/8/','株式会社Skrum/コーポレート本部/グループ２−１/グループ３−１/','2017-04-28 18:09:26','2017-06-27 00:25:28',NULL),(9,9,'1/2/4/9/','株式会社Skrum/コーポレート本部/グループ２−１/グループ３−２/','2017-04-28 18:09:30','2017-06-27 00:25:28',NULL),(10,10,'1/2/5/10/','株式会社Skrum/コーポレート本部/グループ２−２/グループ３−３/','2017-04-28 18:09:40','2017-06-27 00:25:28',NULL),(11,11,'1/2/5/11/','株式会社Skrum/コーポレート本部/グループ２−２/グループ３−４/','2017-04-28 18:09:45','2017-06-27 00:25:28',NULL),(12,12,'1/3/6/12/','株式会社Skrum/グループ１−２/人事部/採用グループ/','2017-04-28 18:09:55','2017-06-27 00:26:00',NULL),(13,13,'1/3/6/13/','株式会社Skrum/グループ１−２/人事部/管理グループ/','2017-04-28 18:09:59','2017-06-27 00:26:44',NULL),(14,14,'1/3/7/14/','株式会社Skrum/グループ１−２/人材開発部/グループ３−７/','2017-04-28 18:10:04','2017-06-27 00:25:51',NULL),(15,15,'1/3/7/15/','株式会社Skrum/グループ１−２/人材開発部/グループ３−８/','2017-04-28 18:10:10','2017-06-27 00:25:51',NULL),(16,10,'1/2/4/9/10/','株式会社Skrum/コーポレート本部/グループ２−１/グループ３−２/グループ３−３/','2017-04-28 18:16:18','2017-06-27 00:25:28',NULL),(17,11,'1/2/5/10/11/','株式会社Skrum/コーポレート本部/グループ２−２/グループ３−３/グループ３−４/','2017-04-28 18:16:39','2017-06-27 00:25:28',NULL),(18,12,'1/2/5/11/12/','株式会社Skrum/コーポレート本部/グループ２−２/グループ３−４/採用グループ/','2017-04-28 18:16:45','2017-06-27 00:26:00',NULL),(19,13,'1/3/6/12/13/','株式会社Skrum/グループ１−２/人事部/採用グループ/管理グループ/','2017-04-28 18:16:56','2017-06-27 00:26:44',NULL),(20,14,'1/3/7/15/14/','株式会社Skrum/グループ１−２/人材開発部/グループ３−８/グループ３−７/','2017-04-28 18:17:03','2017-06-27 00:25:51',NULL);
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

-- Dump completed on 2017-06-27  0:52:16
