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
-- Table structure for table `m_company`
--

DROP TABLE IF EXISTS `m_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_company` (
  `company_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '会社ID',
  `company_name` varchar(255) DEFAULT NULL COMMENT '会社名',
  `vision` varchar(255) DEFAULT NULL COMMENT 'ヴィジョン',
  `mission` varchar(255) DEFAULT NULL COMMENT 'ミッション',
  `subdomain` varchar(45) NOT NULL COMMENT 'サブドメイン名',
  `default_disclosure_type` char(2) DEFAULT NULL COMMENT 'デフォルト公開種別',
  `image_version` int(11) NOT NULL DEFAULT '0' COMMENT '画像バージョン',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `ui_company_01` (`subdomain`),
  KEY `idx_company_01` (`company_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会社';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_company`
--

LOCK TABLES `m_company` WRITE;
/*!40000 ALTER TABLE `m_company` DISABLE KEYS */;
INSERT INTO `m_company` VALUES (1,'Skrum','文字、画像、映像などお客様の持つ情報を効率よく加工する事により、情報の伝達を促進させ、社会の繁栄、人類の進歩を飛躍的に加速させる','1. 楽しい職場、やりがいのある仕事、自己が成長できる環境を協力し合って創り上げる\n2. お客様に親しまれ、必要とされ、信頼される企業を創造する\n3. 社会の一員としての責任を果たし、積極的な姿勢で世の中に貢献する','company1','1',0,'2017-08-16 17:39:20','2017-09-23 16:13:56',NULL);
/*!40000 ALTER TABLE `m_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_group`
--

DROP TABLE IF EXISTS `m_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'グループID',
  `company_id` int(11) unsigned NOT NULL COMMENT '会社ID',
  `group_name` varchar(255) DEFAULT NULL COMMENT 'グループ名',
  `group_type` char(2) NOT NULL COMMENT 'グループ種別',
  `leader_user_id` int(11) unsigned DEFAULT NULL COMMENT 'リーダーユーザID',
  `mission` varchar(255) DEFAULT NULL COMMENT 'ミッション',
  `company_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会社フラグ',
  `image_version` int(11) NOT NULL DEFAULT '0' COMMENT '画像バージョン',
  `archived_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'アーカイブ済フラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`group_id`),
  KEY `idx_group_01` (`company_id`),
  KEY `idx_group_02` (`group_name`),
  KEY `idx_group_03` (`leader_user_id`),
  CONSTRAINT `fk_group_company_id` FOREIGN KEY (`company_id`) REFERENCES `m_company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='グループ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_group`
--

LOCK TABLES `m_group` WRITE;
/*!40000 ALTER TABLE `m_group` DISABLE KEYS */;
INSERT INTO `m_group` VALUES (1,1,'Skrum','3',NULL,NULL,1,0,0,'2017-08-16 17:39:20','2017-09-23 16:13:56',NULL),(2,1,'営業部門','1',NULL,NULL,0,0,0,'2017-08-16 17:52:49','2017-08-16 17:52:49',NULL),(3,1,'営業部','1',NULL,NULL,0,0,0,'2017-08-16 17:52:49','2017-08-16 17:52:49',NULL),(4,1,'営業推進第一グループ','1',NULL,NULL,0,0,0,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(5,1,'営業推進第二グループ','1',NULL,NULL,0,0,0,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(6,1,'営業企画部','1',NULL,NULL,0,0,0,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(7,1,'管理部門','1',NULL,NULL,0,0,0,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(8,1,'経理部','1',NULL,NULL,0,0,0,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(9,1,'人事部','1',NULL,NULL,0,0,0,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(10,1,'採用グループ','2',42,NULL,0,0,0,'2017-08-16 19:13:50','2017-08-16 19:13:50',NULL);
/*!40000 ALTER TABLE `m_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_normal_timeframe`
--

DROP TABLE IF EXISTS `m_normal_timeframe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_normal_timeframe` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '規定タイムフレーム名',
  `cycle_type` char(2) NOT NULL COMMENT 'サイクル種別',
  `display_order` tinyint(2) NOT NULL COMMENT '表示順',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_normal_timeframe_01` (`display_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='規定タイムフレーム';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_normal_timeframe`
--

LOCK TABLES `m_normal_timeframe` WRITE;
/*!40000 ALTER TABLE `m_normal_timeframe` DISABLE KEYS */;
INSERT INTO `m_normal_timeframe` VALUES (1,'１ヶ月毎','1',1,'2017-04-04 20:24:47','2017-04-04 20:24:47',NULL),(2,'４半期毎','2',2,'2017-04-04 20:24:47','2017-04-04 20:24:47',NULL),(3,'半年毎','3',3,'2017-04-04 20:24:47','2017-04-04 20:24:47',NULL),(4,'１年毎','4',4,'2017-04-04 20:24:47','2017-04-04 20:24:47',NULL);
/*!40000 ALTER TABLE `m_normal_timeframe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_permission_settings`
--

DROP TABLE IF EXISTS `m_permission_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_permission_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `permission_id` varchar(45) NOT NULL,
  `operation_id` varchar(45) NOT NULL COMMENT 'オペレーションID',
  `name` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_permission_settings_01` (`permission_id`,`operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='権限設定';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_permission_settings`
--

LOCK TABLES `m_permission_settings` WRITE;
/*!40000 ALTER TABLE `m_permission_settings` DISABLE KEYS */;
INSERT INTO `m_permission_settings` VALUES (1,'0001','00001','user_add','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(2,'0001','00002','user_delete','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(3,'0002','00003','user_permission_change','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(4,'0003','00004','password_reset','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(5,'0004','00005','user_add','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(6,'0004','00006','user_delete','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(7,'0005','00007','user_permission_change','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(8,'0006','00008','password_reset','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(9,'0007','00009','company_profile_edit','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(10,'0008','00010','timeframe_edit','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(11,'0009','00011','csv_upload','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL),(12,'0010','00012','plan_edit','2017-04-03 12:11:50','2017-07-21 22:08:50',NULL);
/*!40000 ALTER TABLE `m_permission_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_plan`
--

DROP TABLE IF EXISTS `m_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_plan` (
  `plan_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'プランID',
  `name` varchar(45) NOT NULL COMMENT 'プラン名',
  `price` decimal(15,0) NOT NULL COMMENT 'プラン価格',
  `price_type` char(2) NOT NULL COMMENT '価格種別',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='プラン';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_plan`
--

LOCK TABLES `m_plan` WRITE;
/*!40000 ALTER TABLE `m_plan` DISABLE KEYS */;
INSERT INTO `m_plan` VALUES (1,'お試しプラン',0,'1','2017-04-04 11:18:30','2017-04-04 11:18:30',NULL),(2,'スタンダードプラン',980,'2','2017-04-04 11:18:30','2017-04-04 11:18:30',NULL);
/*!40000 ALTER TABLE `m_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_role`
--

DROP TABLE IF EXISTS `m_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` varchar(45) NOT NULL COMMENT 'ロールID',
  `plan_id` int(11) unsigned NOT NULL COMMENT 'プランID',
  `name` varchar(45) NOT NULL COMMENT 'ロール名',
  `level` smallint(2) unsigned NOT NULL COMMENT 'ロールレベル',
  `description` varchar(255) NOT NULL COMMENT '説明',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ui_role_01` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='ロール';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_role`
--

LOCK TABLES `m_role` WRITE;
/*!40000 ALTER TABLE `m_role` DISABLE KEYS */;
INSERT INTO `m_role` VALUES (1,'A001',2,'一般ユーザ（スタンダードプラン）',1,'一般ユーザ（スタンダードプラン）','2017-04-03 11:57:25','2017-04-03 11:57:25',NULL),(2,'A002',2,'管理者ユーザ（スタンダードプラン）',4,'管理者ユーザ（スタンダードプラン）','2017-04-03 11:57:25','2017-04-03 11:57:25',NULL),(3,'A003',2,'スーパー管理者ユーザ（スタンダードプラン）',7,'スーパー管理者ユーザ（スタンダードプラン）','2017-04-03 11:57:25','2017-04-03 11:57:25',NULL);
/*!40000 ALTER TABLE `m_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_role_assignment`
--

DROP TABLE IF EXISTS `m_role_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_role_assignment` (
  `role_assignment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ロール割当ID',
  `role_id` varchar(45) NOT NULL COMMENT 'ロールID',
  `role_level` smallint(2) unsigned NOT NULL COMMENT 'ロールレベル',
  `company_id` int(11) unsigned NOT NULL COMMENT '会社ID',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`role_assignment_id`),
  KEY `idx_role_assignment_01` (`role_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='ロール割当';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_role_assignment`
--

LOCK TABLES `m_role_assignment` WRITE;
/*!40000 ALTER TABLE `m_role_assignment` DISABLE KEYS */;
INSERT INTO `m_role_assignment` VALUES (1,'A001',1,1,'2017-08-16 17:39:20','2017-08-16 17:39:20',NULL),(2,'A002',4,1,'2017-08-16 17:39:20','2017-08-16 17:39:20',NULL),(3,'A003',7,1,'2017-08-16 17:39:20','2017-08-16 17:39:20',NULL);
/*!40000 ALTER TABLE `m_role_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_role_permission`
--

DROP TABLE IF EXISTS `m_role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_role_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` varchar(45) NOT NULL COMMENT 'ロールID',
  `permission_id` varchar(45) NOT NULL COMMENT '権限ID',
  `name` varchar(45) NOT NULL COMMENT '権限名',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_role_permission_02` (`role_id`,`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='ロール権限';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_role_permission`
--

LOCK TABLES `m_role_permission` WRITE;
/*!40000 ALTER TABLE `m_role_permission` DISABLE KEYS */;
INSERT INTO `m_role_permission` VALUES (1,'A002','0001','user','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(2,'A002','0002','user_permission','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(3,'A002','0003','password','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(4,'A003','0004','user','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(5,'A003','0005','user_permission','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(6,'A003','0006','password','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(7,'A003','0007','company_profile','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(8,'A003','0008','timeframe','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(9,'A003','0009','csv','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL),(10,'A003','0010','plan','2017-04-03 12:04:32','2017-07-21 22:08:50',NULL);
/*!40000 ALTER TABLE `m_role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_user`
--

DROP TABLE IF EXISTS `m_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ユーザID',
  `company_id` int(11) unsigned NOT NULL COMMENT '会社ID',
  `last_name` varchar(255) DEFAULT NULL COMMENT '姓',
  `first_name` varchar(255) DEFAULT NULL COMMENT '名',
  `email_address` varchar(255) NOT NULL COMMENT 'Eメールアドレス',
  `password` varchar(255) NOT NULL COMMENT 'パスワード',
  `role_assignment_id` int(11) unsigned NOT NULL COMMENT 'ロール割当ID',
  `position` varchar(255) DEFAULT NULL COMMENT '役職',
  `phone_number` varchar(45) DEFAULT NULL COMMENT '電話番号',
  `image_version` int(11) NOT NULL DEFAULT '0' COMMENT '画像バージョン',
  `archived_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'アーカイブ済フラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`user_id`),
  KEY `idx_user_01` (`company_id`),
  KEY `idx_user_02` (`role_assignment_id`),
  KEY `idx_user_03` (`last_name`),
  KEY `idx_user_04` (`email_address`),
  CONSTRAINT `fk_user_company_id` FOREIGN KEY (`company_id`) REFERENCES `m_company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_role_assignment_id` FOREIGN KEY (`role_assignment_id`) REFERENCES `m_role_assignment` (`role_assignment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='ユーザ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_user`
--

LOCK TABLES `m_user` WRITE;
/*!40000 ALTER TABLE `m_user` DISABLE KEYS */;
INSERT INTO `m_user` VALUES (1,1,'田澤','尚治','sampleuser1@skrum.jp','$2y$12$Eu1.ymeVx7hqD5vFOj4z3.cXlwyqUD4xMFcOX2MTdkMr6LPPWBcBe',3,'社員','090-2323-2323',0,0,'2017-08-16 17:39:20','2017-08-16 17:45:28',NULL),(2,1,'田中','一郎','sampleuser101@skrum.jp','$2y$12$o5VqxST0uqB9kyLOlFmamu/aX92ZazPz4znS2sTkTw96z9UrD0gpG',2,'部長','090-4324-3242',0,0,'2017-08-16 17:52:49','2017-08-16 17:52:49',NULL),(3,1,'田中','二郎','sampleuser102@skrum.jp','$2y$12$blfzpcJ99XaTPCZWughFA.HSRKw.a2rJwGlVum5bpdgOYMUoBBNam',2,'副部長','080-3242-4234',0,0,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(4,1,'田中','五郎','sampleuser103@skrum.jp','$2y$12$yJEJS2hEwr2QRq5NrH8z/uPXZmNQWcLg9/8KlIeoquwDAHkqJok42',2,'課長','090-4234-2342',0,0,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(5,1,'田中','健','sampleuser104@skrum.jp','$2y$12$db28HUv7W7yBjcJIiIVGAOrKl0yLobM.hkQPbd68UJUR4/GoJ18Pa',1,'社員','090-6456-6454',0,0,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(6,1,'田中','悟','sampleuser105@skrum.jp','$2y$12$eOMP0PWNydAbs1EkCzv0aOKlZKZwx6XjKXoPVMKRPSLRxRCPUtrty',1,'社員','090-6456-6454',0,0,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(7,1,'田中','高広','sampleuser106@skrum.jp','$2y$12$KJxlyaidvlQYxU0LbHgVEuCYZ1Nw/CyXWpSE8NZIL5yyOYtPjvAym',1,'社員','090-6442-7546',0,0,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(8,1,'田中','弘','sampleuser107@skrum.jp','$2y$12$9C.ORx4181EP/KLAEoKDXOnqmM9BRbC4lgUG5PgfQqKuBWOfkO6ZW',1,'社員','080-3264-6345',0,0,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(9,1,'田中','純平','sampleuser108@skrum.jp','$2y$12$XlkX/mGWIwYOdmHeyfhfquGrkdBVJobciP3wKWUtfrrHdV8XQAAHq',1,'社員','090-2445-6666',0,0,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(10,1,'田中','健太','sampleuser109@skrum.jp','$2y$12$o32JU4WSZH9vycCpC4iNLuFx7dhiI/Y.tppZJ4xQlx2GIrBX6Sg0q',1,'社員','080-4344-6579',0,0,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(11,1,'田中','智史','sampleuser110@skrum.jp','$2y$12$YRYLh8FKk6qelarT2ORUcO28QHBTyGHRat.C2P6LqMN7cYVBa95K2',1,'社員','090-4324-3242',0,0,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(12,1,'佐藤','一郎','sampleuser111@skrum.jp','$2y$12$b5324Bp5Lu3cXOb/u4HfBuwHUnegs/isBIL2YaMo24LzDUtySIS/O',1,'社員','080-3242-4234',0,0,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(13,1,'佐藤','二郎','sampleuser112@skrum.jp','$2y$12$pOcI6aM4/nSz1QOSDgdLnufm7TpwM.hJylSOHk52Rxd1iYmLtwT8G',2,'課長','090-4234-2342',0,0,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(14,1,'佐藤','五郎','sampleuser113@skrum.jp','$2y$12$EFAvTYkcxN4nHjGcWwdIEe/zRWT5h37KRLlbQQuGunYlKQct8Bjc2',1,'社員','090-6456-6454',0,0,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(15,1,'佐藤','健','sampleuser114@skrum.jp','$2y$12$wU1//JiXSkWk6gtbCJhGq.fIvpspSRaRqXbGwgVt25u6BfQ5tnPP2',1,'社員','090-6456-6454',0,0,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(16,1,'佐藤','悟','sampleuser115@skrum.jp','$2y$12$1qseeUy0ZJ8dy5.Qb1Rriua2gjMeyoSAYNxICswlJoc8DEUYsHLeS',1,'社員','090-6442-7546',0,0,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(17,1,'佐藤','高広','sampleuser116@skrum.jp','$2y$12$kw9BTHf.c34lwaz3Cj63eeFD5Nz1kACGgFlZ5rkt2nwqjGzUjUEnK',1,'社員','080-3264-6345',0,0,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(18,1,'佐藤','弘','sampleuser117@skrum.jp','$2y$12$pOyQotl2QMVUGO6CLGsR4u7MhQQEsQXeSEZ/7grhHoajSR5nKHcZ2',1,'社員','090-2445-6666',0,0,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(19,1,'佐藤','純平','sampleuser118@skrum.jp','$2y$12$0S2WWX27tKYi1GxX5Y8UBu2mBrmojeYQhEL6.yZSWXLpeKatEUBT.',1,'社員','080-4344-6579',0,0,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(20,1,'佐藤','健太','sampleuser119@skrum.jp','$2y$12$u7J6/ecjhvoqPtqvnx4g7.MQVnY4DupEBD2LiGLJixAjU9S2SKXFO',1,'社員','090-4324-3242',0,0,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(21,1,'佐藤','智史','sampleuser120@skrum.jp','$2y$12$5drei4tCO2CtvbhKDnuWsepk1zsI3NpftVQWiiQFgdvvnvRLMMwqm',1,'社員','080-3242-4234',0,0,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(22,1,'高木','一郎','sampleuser121@skrum.jp','$2y$12$hoDRJqb4OmZS7x1OQPBXYuOtZpg0bZ6rVbeiJxEwmcusUwc//.VXm',2,'部長','090-4234-2342',0,0,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(23,1,'高木','二郎','sampleuser122@skrum.jp','$2y$12$bQtWiQVjulNoz4pGDfYza.5M1M6nsq7ilJpxD4UVnrLMYK4pmk2Hq',2,'副部長','090-6456-6454',0,0,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(24,1,'高木','五郎','sampleuser123@skrum.jp','$2y$12$p4XnqXTy9.SgN63Csfghueu1zcMqVYfiywElPPfSkRrGVyOSHlvfm',2,'課長','090-6456-6454',0,0,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(25,1,'高木','健','sampleuser124@skrum.jp','$2y$12$Rode7IgGZyUQbljzax/rYOd.SQ5K3Oq1UL/dAiyCzCr0paleTiRp2',1,'社員','090-6442-7546',0,0,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(26,1,'高木','悟','sampleuser125@skrum.jp','$2y$12$XP2nmcvSM.bEgimjnY951OCN62WprYndQXo0xR022X8FRoP5LElW6',1,'社員','080-3264-6345',0,0,'2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(27,1,'高木','高広','sampleuser126@skrum.jp','$2y$12$OCQVBIib1nwUfGnkPU2hZO/KLMAT7h.JuCsvZ0vBv9QODVtxI0Niq',1,'社員','090-2445-6666',0,0,'2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(28,1,'高木','弘','sampleuser127@skrum.jp','$2y$12$stQCsI9aensjuu/fwW2znO28QaLszEKCZ20p.rixKnjkhq2/fIUi2',1,'社員','080-4344-6579',0,0,'2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(29,1,'高木','純平','sampleuser128@skrum.jp','$2y$12$z6mTun4vuI0YQhB7hyOAq./HsXZHrMaCFz4nsPQ5JmcJ0F1yVYcYm',1,'社員','090-4324-3242',0,0,'2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(30,1,'高木','健太','sampleuser129@skrum.jp','$2y$12$6UChqdub4od6h2Pt9vZ6pO7tnoU6W4ZEhTsJQ2UQR7NrKo6uOoMLC',1,'社員','080-3242-4234',0,0,'2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(31,1,'高木','智史','sampleuser130@skrum.jp','$2y$12$BlQRBd3K9bBpj7I5C5D9K.ZaGjmtitk3zzsLocc1zyJgW98y/zJeq',1,'社員','090-4234-2342',0,0,'2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(32,1,'松本','一郎','sampleuser131@skrum.jp','$2y$12$PgIWXH8RX4EKBk/vtB8QhOF8Os8lBQGqzBno/plUiOUDVj6SU9wRq',2,'部長','090-6456-6454',0,0,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(33,1,'松本','二郎','sampleuser132@skrum.jp','$2y$12$YlN2IH1pWT3gbHIpnJwj9OTf.Nu4iNfKkYO3P/E9/IpkXqiQplc0O',2,'副部長','090-6456-6454',0,0,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(34,1,'松本','五郎','sampleuser133@skrum.jp','$2y$12$HZS4D/0oSJr4JM0jagbrI.8jW1ndZnWd3TAnA61pUHydtsDgUnxWO',2,'課長','090-6442-7546',0,0,'2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(35,1,'松本','健','sampleuser134@skrum.jp','$2y$12$gKYNky4gCY5nP.FaFSivuuVuZbQYwsQ2SOw4V1tmef8eaA.2.M8me',1,'社員','080-3264-6345',0,0,'2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(36,1,'松本','悟','sampleuser135@skrum.jp','$2y$12$ODoifzmmCkpyLv0QijMgA.SnLWTd3SjsDkAdLtBh648CO0QhHCEoW',1,'社員','090-2445-6666',0,0,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(37,1,'松本','高広','sampleuser136@skrum.jp','$2y$12$8zIA/ZcM6Rc3W5CPzzpAY.cm9DQQaG7u1.1DhsDdNWbJfPqG1l13W',1,'社員','080-4344-6579',0,0,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(38,1,'松本','弘','sampleuser137@skrum.jp','$2y$12$pEzWxfJLittr8bRoAkqj/uUTCOaeRHsJjCxNp8EKABGm3reImh4s2',1,'社員','090-4324-3242',0,0,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(39,1,'松本','純平','sampleuser138@skrum.jp','$2y$12$I3XP0Zlh0lPd0I5RRY5eCeUFFlVSwqFxZE1hwXiupgW/IAZdEzSmW',1,'社員','080-3242-4234',0,0,'2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(40,1,'松本','健太','sampleuser139@skrum.jp','$2y$12$xN3wMpaeG0C6WvDRkbqPn.tpUs2/ZWcYtyIG2ZPfAkU8K2RCtMbS.',1,'社員','090-4234-2342',0,0,'2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(41,1,'松本','智史','sampleuser140@skrum.jp','$2y$12$X3I5HynKtHHGQJUxf7ZKguz.u3.nXXj2Mnv9EDu.yZrIyNB33rk9i',1,'社員','090-6456-6454',0,0,'2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(42,1,'山田','一郎','sampleuser141@skrum.jp','$2y$12$vAkIZaTvJ2VgsPseVFlTa.wKBIPOlAilIh7MiaD7nAZ0iI3W8Mb8W',2,'部長','090-6456-6454',0,0,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(43,1,'山田','二郎','sampleuser142@skrum.jp','$2y$12$d5mLDrfFzymrkycM/asAIuHgRBFdY2PvOpXRJ3.spI0VkcYoHbHJC',2,'副部長','090-6442-7546',0,0,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(44,1,'山田','五郎','sampleuser143@skrum.jp','$2y$12$W7S/SnMckWzMhZbXFFhLK.ssS3LcrvTz6gjXCne9jpLsjE7X3Zab.',2,'課長','080-3264-6345',0,0,'2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(45,1,'山田','健','sampleuser144@skrum.jp','$2y$12$ZF8zwF8Cy8e8LcFfnrkhduLtUb2ctKirikvuureHjqkve31P2cfcm',1,'社員','090-2445-6666',0,0,'2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(46,1,'山田','悟','sampleuser145@skrum.jp','$2y$12$YceTGFncACSAx5544z/pOOpz../6NqZlPmj/tcnVN2F1QO0dxUkjK',1,'社員','080-4344-6579',0,0,'2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(47,1,'山田','高広','sampleuser146@skrum.jp','$2y$12$Ajicnc4breVX2kYfOBhqu.97nbowv0/Cmlpux58uKL7oSiuP/ouui',1,'社員','090-4324-3242',0,0,'2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(48,1,'山田','弘','sampleuser147@skrum.jp','$2y$12$1Xgtc5ALwV9Jintz/SBkOuY6S0FJ/cy9BF3I/BRykZxC21U.q0wpy',1,'社員','080-3242-4234',0,0,'2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(49,1,'山田','純平','sampleuser148@skrum.jp','$2y$12$Wh5WgDf2rd0mN9PyOeGcKetCbesCwujHt0akNXxfgWKnxZOP6hIgC',1,'社員','090-4234-2342',0,0,'2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(50,1,'山田','健太','sampleuser149@skrum.jp','$2y$12$18ijaPAzUxC96pWhYlk4Duini42Pzavp6vYca5QDTVRFcln4uyrOO',1,'社員','090-6456-6454',0,0,'2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(51,1,'山田','智史','sampleuser150@skrum.jp','$2y$12$zZVA7QnL.DS4KsR03J1Ox.YXu9.LYpjM..UQXIiIc7L7UIlkajvH.',1,'社員','090-6456-6454',0,0,'2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(52,1,'岡山','一郎','sampleuser151@skrum.jp','$2y$12$KHb/olW.wAIyZxJ4xh.pEes.Uhsp1dvsacsCYq/c9ssHalr3PaCA.',2,'課長','090-6442-7546',0,0,'2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(53,1,'岡山','二郎','sampleuser152@skrum.jp','$2y$12$iDKrnrPCGpGlQwm6wcovauBB27FUCd2wvnwvwPx.vk.zGfoVW8kDS',1,'社員','080-3264-6345',0,0,'2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(54,1,'岡山','五郎','sampleuser153@skrum.jp','$2y$12$xcCX3TvxfHoz3W0sPd8kauFF.Q3SJjeZE1KN09abv/ozQ32h/0Ewq',1,'社員','090-2445-6666',0,0,'2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(55,1,'岡山','健','sampleuser154@skrum.jp','$2y$12$oS9EBmvlXAWQlbKrgGQ5YOL2v/da0DGTkUVOSf5ryJF1kgNSHW75a',1,'社員','080-4344-6579',0,0,'2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(56,1,'岡山','悟','sampleuser155@skrum.jp','$2y$12$eRNQ8lTnH7kEx5GwrBxw2O3nG9uZuF/0PFGoU4/YGACsGP6R9ZQ8q',1,'社員','090-4324-3242',0,0,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(57,1,'岡山','高広','sampleuser156@skrum.jp','$2y$12$OOO12t20rECvRGZXj3y04.z3ND9yRrGwHOF3oxzK6UQoP4ooFx4Ky',1,'社員','080-3242-4234',0,0,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(58,1,'岡山','弘','sampleuser157@skrum.jp','$2y$12$jPv6JYz2fYIVpSoeJkY6vOEoiaE3apHfeXLp/dhFimwmtae.j.U7.',1,'社員','090-4234-2342',0,0,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(59,1,'岡山','純平','sampleuser158@skrum.jp','$2y$12$Xc5KWWB5KiXJ.IjLiIjXI.nKUrK.mrq8gKeSotzUk8g.aKHCIUKUq',1,'社員','090-6456-6454',0,0,'2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(60,1,'岡山','健太','sampleuser159@skrum.jp','$2y$12$0iEWNp2r7viCzmcAfMYQve2A2k.lBTxxlyscFHcNbcKL9Xf1IhVMe',1,'社員','090-6456-6454',0,0,'2017-08-16 17:53:17','2017-08-16 17:53:17',NULL);
/*!40000 ALTER TABLE `m_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_group_tree_path_id`
--

DROP TABLE IF EXISTS `s_group_tree_path_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_group_tree_path_id` (
  `current_value` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '現在値',
  PRIMARY KEY (`current_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='グループツリーパスIDシーケンス';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_group_tree_path_id`
--

LOCK TABLES `s_group_tree_path_id` WRITE;
/*!40000 ALTER TABLE `s_group_tree_path_id` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_group_tree_path_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_achievement_rate_log`
--

DROP TABLE IF EXISTS `t_achievement_rate_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_achievement_rate_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `owner_type` char(20) NOT NULL COMMENT 'オーナー種別',
  `owner_user_id` int(11) unsigned DEFAULT NULL COMMENT 'オーナーユーザID',
  `owner_group_id` int(11) unsigned DEFAULT NULL COMMENT 'オーナーグループID',
  `owner_company_id` int(11) unsigned DEFAULT NULL COMMENT 'オーナー会社ID',
  `timeframe_id` int(11) unsigned NOT NULL COMMENT 'タイムフレームID',
  `achievement_rate` decimal(15,1) NOT NULL COMMENT '達成率',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='目標進捗達成率ログ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_achievement_rate_log`
--

LOCK TABLES `t_achievement_rate_log` WRITE;
/*!40000 ALTER TABLE `t_achievement_rate_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_achievement_rate_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_authorization`
--

DROP TABLE IF EXISTS `t_authorization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_authorization` (
  `authorization_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '認可ID',
  `company_id` int(11) NOT NULL COMMENT '会社ID',
  `plan_id` int(11) NOT NULL COMMENT 'プランID',
  `contract_id` int(11) DEFAULT NULL COMMENT '契約ID',
  `authorization_start_datetime` datetime NOT NULL COMMENT '認可開始日時',
  `authorization_end_datetime` datetime NOT NULL COMMENT '認可終了日時',
  `authorization_stop_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '認可停止フラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`authorization_id`),
  KEY `idx_authorization_01` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_authorization`
--

LOCK TABLES `t_authorization` WRITE;
/*!40000 ALTER TABLE `t_authorization` DISABLE KEYS */;
INSERT INTO `t_authorization` VALUES (1,1,1,NULL,'2017-08-16 17:39:20','2030-08-30 23:59:59',0,'2017-08-16 17:39:20','2017-08-16 17:39:20',NULL);
/*!40000 ALTER TABLE `t_authorization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_contract`
--

DROP TABLE IF EXISTS `t_contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_contract` (
  `contract_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '契約ID',
  `company_id` int(11) unsigned NOT NULL COMMENT '会社ID',
  `plan_id` int(11) unsigned NOT NULL COMMENT 'プランID',
  `price` decimal(15,0) DEFAULT NULL COMMENT 'プラン価格',
  `price_type` char(2) DEFAULT NULL COMMENT '価格種別',
  `plan_start_date` date DEFAULT NULL COMMENT 'プラン利用開始日',
  `plan_end_date` date DEFAULT NULL COMMENT 'プラン利用終了日',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`contract_id`),
  KEY `idx_contract_01` (`company_id`),
  KEY `idx_contract_02` (`plan_id`),
  KEY `idx_contract_03` (`plan_start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='契約';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_contract`
--

LOCK TABLES `t_contract` WRITE;
/*!40000 ALTER TABLE `t_contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_email_reservation`
--

DROP TABLE IF EXISTS `t_email_reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_email_reservation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `to_email_address` varchar(255) NOT NULL COMMENT 'TOアドレス',
  `title` varchar(255) NOT NULL COMMENT 'タイトル',
  `body` text NOT NULL COMMENT '本文',
  `reception_datetime` datetime NOT NULL COMMENT '受付日時',
  `sending_reservation_datetime` datetime NOT NULL COMMENT '送信予約日時',
  `sending_datetime` datetime DEFAULT NULL COMMENT '送信日時',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='メール送信予約';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_email_reservation`
--

LOCK TABLES `t_email_reservation` WRITE;
/*!40000 ALTER TABLE `t_email_reservation` DISABLE KEYS */;
INSERT INTO `t_email_reservation` VALUES (1,'sampleuser1@skrum.jp','【Skrum】CSVファイル登録成功データ','田澤 尚治 さん\n\n登録されたCSVファイルについて以下の行データの登録に成功しました。\n\n\n1,田中,一郎,2,部長,sampleuser101@skrum.jp,090-4324-3242,営業部門,営業部,,,\n----- 仮パスワード：vk25UM536sD877R\n2,田中,二郎,2,副部長,sampleuser102@skrum.jp,080-3242-4234,営業部門,営業部,,,\n----- 仮パスワード：H8gGsySuHK7y52T\n3,田中,五郎,2,課長,sampleuser103@skrum.jp,090-4234-2342,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：782phguy48386bc\n4,田中,健,1,社員,sampleuser104@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：En6g45XYEHXk8NB\n5,田中,悟,1,社員,sampleuser105@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：3LJ3EtVw2478D74\n6,田中,高広,1,社員,sampleuser106@skrum.jp,090-6442-7546,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：R5737pUEC3v727T\n7,田中,弘,1,社員,sampleuser107@skrum.jp,080-3264-6345,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：54gsD7pLWAb2VJd\n8,田中,純平,1,社員,sampleuser108@skrum.jp,090-2445-6666,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：agEt4T3477waT2L\n9,田中,健太,1,社員,sampleuser109@skrum.jp,080-4344-6579,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：2mm7R7v3E5Z27E7\n10,田中,智史,1,社員,sampleuser110@skrum.jp,090-4324-3242,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：hB5p7r5yT34x363\n11,佐藤,一郎,1,社員,sampleuser111@skrum.jp,080-3242-4234,営業部門,営業部,営業推進第一グループ,,\n----- 仮パスワード：75N6xFy6uf68w2z\n12,佐藤,二郎,2,課長,sampleuser112@skrum.jp,090-4234-2342,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：8wc76n662cmkswS\n13,佐藤,五郎,1,社員,sampleuser113@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：zYp2362ven6N53G\n14,佐藤,健,1,社員,sampleuser114@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：8xX8M3374zD52P5\n15,佐藤,悟,1,社員,sampleuser115@skrum.jp,090-6442-7546,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：5tf4E46B34f7862\n16,佐藤,高広,1,社員,sampleuser116@skrum.jp,080-3264-6345,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：bL2JB4BW5552X85\n17,佐藤,弘,1,社員,sampleuser117@skrum.jp,090-2445-6666,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：yLv63Fn8f377y6h\n18,佐藤,純平,1,社員,sampleuser118@skrum.jp,080-4344-6579,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：33DnR53mBgam2X8\n19,佐藤,健太,1,社員,sampleuser119@skrum.jp,090-4324-3242,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：T342ce4f6W268J6\n20,佐藤,智史,1,社員,sampleuser120@skrum.jp,080-3242-4234,営業部門,営業部,営業推進第二グループ,,\n----- 仮パスワード：ty86aSFXFpUk255\n21,高木,一郎,2,部長,sampleuser121@skrum.jp,090-4234-2342,営業部門,営業企画部,,,\n----- 仮パスワード：SE63Y8a4g4ZNSdU\n22,高木,二郎,2,副部長,sampleuser122@skrum.jp,090-6456-6454,営業部門,営業企画部,,,\n----- 仮パスワード：cr45ZRX687eZ68s\n23,高木,五郎,2,課長,sampleuser123@skrum.jp,090-6456-6454,営業部門,営業企画部,,,\n----- 仮パスワード：L2287LT6hf35Y6m\n24,高木,健,1,社員,sampleuser124@skrum.jp,090-6442-7546,営業部門,営業企画部,,,\n----- 仮パスワード：747EZ6853D457G7\n25,高木,悟,1,社員,sampleuser125@skrum.jp,080-3264-6345,営業部門,営業企画部,,,\n----- 仮パスワード：W5hN8exw46c77s7\n26,高木,高広,1,社員,sampleuser126@skrum.jp,090-2445-6666,営業部門,営業企画部,,,\n----- 仮パスワード：gTBAHPDvWvS8FpN\n27,高木,弘,1,社員,sampleuser127@skrum.jp,080-4344-6579,営業部門,営業企画部,,,\n----- 仮パスワード：T3nmwER5366K83e\n28,高木,純平,1,社員,sampleuser128@skrum.jp,090-4324-3242,営業部門,営業企画部,,,\n----- 仮パスワード：3E4A33A258L6733\n29,高木,健太,1,社員,sampleuser129@skrum.jp,080-3242-4234,営業部門,営業企画部,,,\n----- 仮パスワード：Z236N3eK4evG3e8\n30,高木,智史,1,社員,sampleuser130@skrum.jp,090-4234-2342,営業部門,営業企画部,,,\n----- 仮パスワード：n52v33G32K4E5V7\n31,松本,一郎,2,部長,sampleuser131@skrum.jp,090-6456-6454,管理部門,経理部,,,\n----- 仮パスワード：xLZ328eMbP337r6\n32,松本,二郎,2,副部長,sampleuser132@skrum.jp,090-6456-6454,管理部門,経理部,,,\n----- 仮パスワード：x2YhFC7K45d5t24\n33,松本,五郎,2,課長,sampleuser133@skrum.jp,090-6442-7546,管理部門,経理部,,,\n----- 仮パスワード：8gL87h85VX5g2p3\n34,松本,健,1,社員,sampleuser134@skrum.jp,080-3264-6345,管理部門,経理部,,,\n----- 仮パスワード：L4mS655fxZ35668\n35,松本,悟,1,社員,sampleuser135@skrum.jp,090-2445-6666,管理部門,経理部,,,\n----- 仮パスワード：Amsmkvy3X24pa62\n36,松本,高広,1,社員,sampleuser136@skrum.jp,080-4344-6579,管理部門,経理部,,,\n----- 仮パスワード：USb75E64zJwpN3e\n37,松本,弘,1,社員,sampleuser137@skrum.jp,090-4324-3242,管理部門,経理部,,,\n----- 仮パスワード：63D253ZB4T6ZXFS\n38,松本,純平,1,社員,sampleuser138@skrum.jp,080-3242-4234,管理部門,経理部,,,\n----- 仮パスワード：Tz5HL463z4Lk2K6\n39,松本,健太,1,社員,sampleuser139@skrum.jp,090-4234-2342,管理部門,経理部,,,\n----- 仮パスワード：e35XLs28c54466s\n40,松本,智史,1,社員,sampleuser140@skrum.jp,090-6456-6454,管理部門,経理部,,,\n----- 仮パスワード：vH8AMamp2Dfh2WU\n41,山田,一郎,2,部長,sampleuser141@skrum.jp,090-6456-6454,管理部門,人事部,,,\n----- 仮パスワード：687C57yP7y77rD8\n42,山田,二郎,2,副部長,sampleuser142@skrum.jp,090-6442-7546,管理部門,人事部,,,\n----- 仮パスワード：dS23m752tJzMY42\n43,山田,五郎,2,課長,sampleuser143@skrum.jp,080-3264-6345,管理部門,人事部,,,\n----- 仮パスワード：mu36FmKN2JD7t5B\n44,山田,健,1,社員,sampleuser144@skrum.jp,090-2445-6666,管理部門,人事部,,,\n----- 仮パスワード：2h3y7XY8r7444w7\n45,山田,悟,1,社員,sampleuser145@skrum.jp,080-4344-6579,管理部門,人事部,,,\n----- 仮パスワード：wYh7G35r53tyTv4\n46,山田,高広,1,社員,sampleuser146@skrum.jp,090-4324-3242,管理部門,人事部,,,\n----- 仮パスワード：K6Y682h67HwLeF2\n47,山田,弘,1,社員,sampleuser147@skrum.jp,080-3242-4234,管理部門,人事部,,,\n----- 仮パスワード：tW4R3534N8M5CY2\n48,山田,純平,1,社員,sampleuser148@skrum.jp,090-4234-2342,管理部門,人事部,,,\n----- 仮パスワード：6pUEc3H5h4G8TxX\n49,山田,健太,1,社員,sampleuser149@skrum.jp,090-6456-6454,管理部門,人事部,,,\n----- 仮パスワード：Cy2hpU287876eS7\n50,山田,智史,1,社員,sampleuser150@skrum.jp,090-6456-6454,管理部門,人事部,,,\n----- 仮パスワード：54462WwtH8b25bN\n51,岡山,一郎,2,課長,sampleuser151@skrum.jp,090-6442-7546,管理部門,人事部,,,\n----- 仮パスワード：s6fP62hND3R5852\n52,岡山,二郎,1,社員,sampleuser152@skrum.jp,080-3264-6345,管理部門,人事部,,,\n----- 仮パスワード：LVHd6a732asDV73\n53,岡山,五郎,1,社員,sampleuser153@skrum.jp,090-2445-6666,管理部門,人事部,,,\n----- 仮パスワード：RDK3t575T52G38L\n54,岡山,健,1,社員,sampleuser154@skrum.jp,080-4344-6579,管理部門,人事部,,,\n----- 仮パスワード：8z6CNrvJ8u53546\n55,岡山,悟,1,社員,sampleuser155@skrum.jp,090-4324-3242,管理部門,人事部,,,\n----- 仮パスワード：kZ6rgkZ4G7t6cv5\n56,岡山,高広,1,社員,sampleuser156@skrum.jp,080-3242-4234,管理部門,人事部,,,\n----- 仮パスワード：W27raR277388yA7\n57,岡山,弘,1,社員,sampleuser157@skrum.jp,090-4234-2342,管理部門,人事部,,,\n----- 仮パスワード：2uPwy7pvZtLZB42\n58,岡山,純平,1,社員,sampleuser158@skrum.jp,090-6456-6454,管理部門,人事部,,,\n----- 仮パスワード：8DE54Ntca682338\n59,岡山,健太,1,社員,sampleuser159@skrum.jp,090-6456-6454,管理部門,人事部,,,\n----- 仮パスワード：83z6C78J2YB75c4\n\n\n※従業員には通知メールが送られていません。','2017-08-16 17:53:18','2017-08-16 17:53:18',NULL,'2017-08-16 17:53:18','2017-08-16 17:53:18',NULL);
/*!40000 ALTER TABLE `t_email_reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_email_settings`
--

DROP TABLE IF EXISTS `t_email_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_email_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL COMMENT 'ユーザID',
  `okr_achievement` char(2) NOT NULL DEFAULT '1' COMMENT '達成率通知メール設定',
  `okr_timeline` char(2) NOT NULL DEFAULT '1' COMMENT '投稿通知メール設定',
  `okr_reminder` char(2) NOT NULL DEFAULT '1' COMMENT '進捗登録リマインドメール設定',
  `report_member_achievement` char(2) NOT NULL DEFAULT '1' COMMENT 'メンバー進捗状況レポートメール設定',
  `report_group_achievement` char(2) NOT NULL DEFAULT '1' COMMENT 'グループ進捗状況レポートメール設定',
  `report_feedback_target` char(2) NOT NULL DEFAULT '1' COMMENT 'フィードバック対象者通知メール設定',
  `service_notification` char(2) NOT NULL DEFAULT '1' COMMENT 'サービスお知らせメール設定',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='メール通知設定';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_email_settings`
--

LOCK TABLES `t_email_settings` WRITE;
/*!40000 ALTER TABLE `t_email_settings` DISABLE KEYS */;
INSERT INTO `t_email_settings` VALUES (1,1,'1','1','1','1','0','1','1','2017-08-16 17:39:20','2017-08-16 17:39:20',NULL),(2,2,'1','1','1','1','0','1','1','2017-08-16 17:52:49','2017-08-16 17:52:49',NULL),(3,3,'1','1','1','1','0','1','1','2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(4,4,'1','1','1','1','0','1','1','2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(5,5,'1','1','1','0','1','0','1','2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(6,6,'1','1','1','0','1','0','1','2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(7,7,'1','1','1','0','1','0','1','2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(8,8,'1','1','1','0','1','0','1','2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(9,9,'1','1','1','0','1','0','1','2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(10,10,'1','1','1','0','1','0','1','2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(11,11,'1','1','1','0','1','0','1','2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(12,12,'1','1','1','0','1','0','1','2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(13,13,'1','1','1','1','0','1','1','2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(14,14,'1','1','1','0','1','0','1','2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(15,15,'1','1','1','0','1','0','1','2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(16,16,'1','1','1','0','1','0','1','2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(17,17,'1','1','1','0','1','0','1','2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(18,18,'1','1','1','0','1','0','1','2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(19,19,'1','1','1','0','1','0','1','2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(20,20,'1','1','1','0','1','0','1','2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(21,21,'1','1','1','0','1','0','1','2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(22,22,'1','1','1','1','0','1','1','2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(23,23,'1','1','1','1','0','1','1','2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(24,24,'1','1','1','1','0','1','1','2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(25,25,'1','1','1','0','1','0','1','2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(26,26,'1','1','1','0','1','0','1','2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(27,27,'1','1','1','0','1','0','1','2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(28,28,'1','1','1','0','1','0','1','2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(29,29,'1','1','1','0','1','0','1','2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(30,30,'1','1','1','0','1','0','1','2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(31,31,'1','1','1','0','1','0','1','2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(32,32,'1','1','1','1','0','1','1','2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(33,33,'1','1','1','1','0','1','1','2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(34,34,'1','1','1','1','0','1','1','2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(35,35,'1','1','1','0','1','0','1','2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(36,36,'1','1','1','0','1','0','1','2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(37,37,'1','1','1','0','1','0','1','2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(38,38,'1','1','1','0','1','0','1','2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(39,39,'1','1','1','0','1','0','1','2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(40,40,'1','1','1','0','1','0','1','2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(41,41,'1','1','1','0','1','0','1','2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(42,42,'1','1','1','1','0','1','1','2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(43,43,'1','1','1','1','0','1','1','2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(44,44,'1','1','1','1','0','1','1','2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(45,45,'1','1','1','0','1','0','1','2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(46,46,'1','1','1','0','1','0','1','2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(47,47,'1','1','1','0','1','0','1','2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(48,48,'1','1','1','0','1','0','1','2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(49,49,'1','1','1','0','1','0','1','2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(50,50,'1','1','1','0','1','0','1','2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(51,51,'1','1','1','0','1','0','1','2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(52,52,'1','1','1','1','0','1','1','2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(53,53,'1','1','1','0','1','0','1','2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(54,54,'1','1','1','0','1','0','1','2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(55,55,'1','1','1','0','1','0','1','2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(56,56,'1','1','1','0','1','0','1','2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(57,57,'1','1','1','0','1','0','1','2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(58,58,'1','1','1','0','1','0','1','2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(59,59,'1','1','1','0','1','0','1','2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(60,60,'1','1','1','0','1','0','1','2017-08-16 17:53:17','2017-08-16 17:53:17',NULL);
/*!40000 ALTER TABLE `t_email_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_group_member`
--

DROP TABLE IF EXISTS `t_group_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_group_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(11) unsigned NOT NULL COMMENT 'グループID',
  `user_id` int(11) unsigned NOT NULL COMMENT 'ユーザID',
  `post_share_flg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '投稿シェアフラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_group_member_01` (`group_id`),
  KEY `idx_group_member_02` (`user_id`),
  CONSTRAINT `fk_group_member_group_id` FOREIGN KEY (`group_id`) REFERENCES `m_group` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_member_user_id` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8 COMMENT='グループメンバー';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_group_member`
--

LOCK TABLES `t_group_member` WRITE;
/*!40000 ALTER TABLE `t_group_member` DISABLE KEYS */;
INSERT INTO `t_group_member` VALUES (1,2,2,1,'2017-08-16 17:52:49','2017-08-16 17:52:49',NULL),(2,3,2,1,'2017-08-16 17:52:49','2017-08-16 17:52:49',NULL),(3,2,3,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(4,3,3,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(5,2,4,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(6,3,4,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(7,4,4,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(8,2,5,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(9,3,5,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(10,4,5,1,'2017-08-16 17:52:50','2017-08-16 17:52:50',NULL),(11,2,6,1,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(12,3,6,1,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(13,4,6,1,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(14,2,7,1,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(15,3,7,1,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(16,4,7,1,'2017-08-16 17:52:51','2017-08-16 17:52:51',NULL),(17,2,8,1,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(18,3,8,1,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(19,4,8,1,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(20,2,9,1,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(21,3,9,1,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(22,4,9,1,'2017-08-16 17:52:52','2017-08-16 17:52:52',NULL),(23,2,10,1,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(24,3,10,1,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(25,4,10,1,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(26,2,11,1,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(27,3,11,1,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(28,4,11,1,'2017-08-16 17:52:53','2017-08-16 17:52:53',NULL),(29,2,12,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(30,3,12,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(31,4,12,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(32,2,13,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(33,3,13,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(34,5,13,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(35,2,14,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(36,3,14,1,'2017-08-16 17:52:54','2017-08-16 17:52:54',NULL),(37,5,14,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(38,2,15,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(39,3,15,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(40,5,15,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(41,2,16,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(42,3,16,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(43,5,16,1,'2017-08-16 17:52:55','2017-08-16 17:52:55',NULL),(44,2,17,1,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(45,3,17,1,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(46,5,17,1,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(47,2,18,1,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(48,3,18,1,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(49,5,18,1,'2017-08-16 17:52:56','2017-08-16 17:52:56',NULL),(50,2,19,1,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(51,3,19,1,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(52,5,19,1,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(53,2,20,1,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(54,3,20,1,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(55,5,20,1,'2017-08-16 17:52:57','2017-08-16 17:52:57',NULL),(56,2,21,1,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(57,3,21,1,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(58,5,21,1,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(59,2,22,1,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(60,6,22,1,'2017-08-16 17:52:58','2017-08-16 17:52:58',NULL),(61,2,23,1,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(62,6,23,1,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(63,2,24,1,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(64,6,24,1,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(65,2,25,1,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(66,6,25,1,'2017-08-16 17:52:59','2017-08-16 17:52:59',NULL),(67,2,26,1,'2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(68,6,26,1,'2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(69,2,27,1,'2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(70,6,27,1,'2017-08-16 17:53:00','2017-08-16 17:53:00',NULL),(71,2,28,1,'2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(72,6,28,1,'2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(73,2,29,1,'2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(74,6,29,1,'2017-08-16 17:53:01','2017-08-16 17:53:01',NULL),(75,2,30,1,'2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(76,6,30,1,'2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(77,2,31,1,'2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(78,6,31,1,'2017-08-16 17:53:02','2017-08-16 17:53:02',NULL),(79,7,32,1,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(80,8,32,1,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(81,7,33,1,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(82,8,33,1,'2017-08-16 17:53:03','2017-08-16 17:53:03',NULL),(83,7,34,1,'2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(84,8,34,1,'2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(85,7,35,1,'2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(86,8,35,1,'2017-08-16 17:53:04','2017-08-16 17:53:04',NULL),(87,7,36,1,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(88,8,36,1,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(89,7,37,1,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(90,8,37,1,'2017-08-16 17:53:05','2017-08-16 17:53:05',NULL),(91,7,38,1,'2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(92,8,38,1,'2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(93,7,39,1,'2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(94,8,39,1,'2017-08-16 17:53:06','2017-08-16 17:53:06',NULL),(95,7,40,1,'2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(96,8,40,1,'2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(97,7,41,1,'2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(98,8,41,1,'2017-08-16 17:53:07','2017-08-16 17:53:07',NULL),(99,7,42,1,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(100,9,42,1,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(101,7,43,1,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(102,9,43,1,'2017-08-16 17:53:08','2017-08-16 17:53:08',NULL),(103,7,44,1,'2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(104,9,44,1,'2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(105,7,45,1,'2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(106,9,45,1,'2017-08-16 17:53:09','2017-08-16 17:53:09',NULL),(107,7,46,1,'2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(108,9,46,1,'2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(109,7,47,1,'2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(110,9,47,1,'2017-08-16 17:53:10','2017-08-16 17:53:10',NULL),(111,7,48,1,'2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(112,9,48,1,'2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(113,7,49,1,'2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(114,9,49,1,'2017-08-16 17:53:11','2017-08-16 17:53:11',NULL),(115,7,50,1,'2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(116,9,50,1,'2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(117,7,51,1,'2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(118,9,51,1,'2017-08-16 17:53:12','2017-08-16 17:53:12',NULL),(119,7,52,1,'2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(120,9,52,1,'2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(121,7,53,1,'2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(122,9,53,1,'2017-08-16 17:53:13','2017-08-16 17:53:13',NULL),(123,7,54,1,'2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(124,9,54,1,'2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(125,7,55,1,'2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(126,9,55,1,'2017-08-16 17:53:14','2017-08-16 17:53:14',NULL),(127,7,56,1,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(128,9,56,1,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(129,7,57,1,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(130,9,57,1,'2017-08-16 17:53:15','2017-08-16 17:53:15',NULL),(131,7,58,1,'2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(132,9,58,1,'2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(133,7,59,1,'2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(134,9,59,1,'2017-08-16 17:53:16','2017-08-16 17:53:16',NULL),(135,7,60,1,'2017-08-16 17:53:17','2017-08-16 17:53:17',NULL),(136,9,60,1,'2017-08-16 17:53:17','2017-08-16 17:53:17',NULL),(137,2,1,1,'2017-08-16 17:55:33','2017-08-16 17:55:33',NULL),(138,3,1,1,'2017-08-16 17:56:02','2017-08-16 17:56:02',NULL),(139,4,1,1,'2017-08-16 17:56:25','2017-08-16 17:56:25',NULL),(140,10,52,1,'2017-08-16 19:15:43','2017-08-16 19:15:43',NULL),(141,10,53,1,'2017-08-16 19:16:19','2017-08-16 19:16:19',NULL),(142,10,54,1,'2017-08-16 19:16:37','2017-08-16 19:16:37',NULL),(143,10,55,1,'2017-08-16 19:16:54','2017-08-16 19:16:54',NULL),(144,10,56,1,'2017-08-16 19:17:16','2017-08-16 19:17:16',NULL),(145,10,57,1,'2017-08-16 19:17:31','2017-08-16 19:17:31',NULL),(146,10,58,1,'2017-08-16 19:17:44','2017-08-16 19:17:44',NULL),(147,10,59,1,'2017-08-16 19:17:59','2017-08-16 19:17:59',NULL),(148,10,60,1,'2017-08-16 19:18:21','2017-08-16 19:18:21',NULL);
/*!40000 ALTER TABLE `t_group_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_group_tree`
--

DROP TABLE IF EXISTS `t_group_tree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_group_tree` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(11) unsigned NOT NULL COMMENT 'グループID',
  `group_tree_path` varchar(3072) CHARACTER SET latin1 NOT NULL COMMENT 'グループパス(経路列挙モデル)',
  `group_tree_path_name` varchar(3072) DEFAULT NULL COMMENT 'グループパス名',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ui_group_tree_01` (`group_tree_path`),
  KEY `idx_group_tree_01` (`group_id`),
  CONSTRAINT `fk_group_tree_group_id` FOREIGN KEY (`group_id`) REFERENCES `m_group` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='グループツリー';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_group_tree`
--

LOCK TABLES `t_group_tree` WRITE;
/*!40000 ALTER TABLE `t_group_tree` DISABLE KEYS */;
INSERT INTO `t_group_tree` VALUES (1,1,'1/','Skrum/','2017-08-16 17:39:20','2017-09-23 16:13:56',NULL),(2,2,'1/2/','Skrum/営業部門/','2017-08-16 19:00:29','2017-09-23 16:13:56',NULL),(3,3,'1/2/3/','Skrum/営業部門/営業部/','2017-08-16 19:04:32','2017-09-23 16:13:56',NULL),(4,4,'1/2/3/4/','Skrum/営業部門/営業部/営業推進第一グループ/','2017-08-16 19:04:59','2017-09-23 16:13:56',NULL),(5,5,'1/2/3/5/','Skrum/営業部門/営業部/営業推進第二グループ/','2017-08-16 19:07:40','2017-09-23 16:13:56',NULL),(6,6,'1/2/6/','Skrum/営業部門/営業企画部/','2017-08-16 19:09:29','2017-09-23 16:13:56',NULL),(7,7,'1/7/','Skrum/管理部門/','2017-08-16 19:11:04','2017-09-23 16:13:56',NULL),(8,8,'1/7/8/','Skrum/管理部門/経理部/','2017-08-16 19:11:26','2017-09-23 16:13:56',NULL),(9,9,'1/7/9/','Skrum/管理部門/人事部/','2017-08-16 19:12:51','2017-09-23 16:13:56',NULL),(10,10,'1/7/9/10/','Skrum/管理部門/人事部/採用グループ/','2017-08-16 19:13:50','2017-09-23 16:13:56',NULL);
/*!40000 ALTER TABLE `t_group_tree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_like`
--

DROP TABLE IF EXISTS `t_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_like` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL COMMENT 'ユーザID',
  `post_id` bigint(20) unsigned NOT NULL COMMENT '投稿ID',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_like_01` (`user_id`),
  KEY `idx_like_02` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='いいね';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_like`
--

LOCK TABLES `t_like` WRITE;
/*!40000 ALTER TABLE `t_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_login`
--

DROP TABLE IF EXISTS `t_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_login` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL COMMENT 'ユーザID',
  `login_datetime` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_login_01` (`user_id`),
  KEY `idx_login_02` (`login_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='ログイン';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_login`
--

LOCK TABLES `t_login` WRITE;
/*!40000 ALTER TABLE `t_login` DISABLE KEYS */;
INSERT INTO `t_login` VALUES (1,1,'2017-08-16 17:39:20','2017-08-16 17:39:20','2017-08-16 17:39:20',NULL),(2,1,'2017-08-16 17:45:56','2017-08-16 17:45:56','2017-08-16 17:45:56',NULL),(3,13,'2017-08-16 19:07:10','2017-08-16 19:07:10','2017-08-16 19:07:10',NULL),(4,22,'2017-08-16 19:08:42','2017-08-16 19:08:42','2017-08-16 19:08:42',NULL),(5,32,'2017-08-16 19:10:30','2017-08-16 19:10:30','2017-08-16 19:10:30',NULL),(6,42,'2017-08-16 19:12:17','2017-08-16 19:12:17','2017-08-16 19:12:17',NULL),(7,52,'2017-08-16 19:15:13','2017-08-16 19:15:13','2017-08-16 19:15:13',NULL),(8,1,'2017-08-16 19:19:03','2017-08-16 19:19:03','2017-08-16 19:19:03',NULL),(9,1,'2017-09-23 16:13:16','2017-09-23 16:13:16','2017-09-23 16:13:16',NULL);
/*!40000 ALTER TABLE `t_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_okr`
--

DROP TABLE IF EXISTS `t_okr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_okr` (
  `okr_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'OKRID',
  `timeframe_id` int(11) unsigned NOT NULL COMMENT 'タイムフレームID',
  `parent_okr_id` int(11) unsigned DEFAULT NULL COMMENT '親OKRID',
  `type` char(2) NOT NULL COMMENT '種別',
  `name` varchar(255) NOT NULL COMMENT 'OKR名',
  `detail` varchar(255) DEFAULT NULL COMMENT 'OKR詳細',
  `target_value` bigint(20) unsigned NOT NULL DEFAULT '100' COMMENT '目標値',
  `achieved_value` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '達成値',
  `achievement_rate` decimal(15,1) NOT NULL COMMENT '達成率',
  `tree_left` double(15,14) DEFAULT NULL COMMENT '左値(入れ子区間モデル)',
  `tree_right` double(15,14) DEFAULT NULL COMMENT '右値(入れ子区間モデル)',
  `unit` varchar(45) NOT NULL DEFAULT '％' COMMENT '単位',
  `owner_type` char(2) NOT NULL COMMENT 'オーナー種別',
  `owner_user_id` int(11) unsigned DEFAULT NULL COMMENT 'オーナーユーザID',
  `owner_group_id` int(11) unsigned DEFAULT NULL COMMENT 'オーナーグループID',
  `owner_company_id` int(11) unsigned DEFAULT NULL COMMENT 'オーナー会社ID',
  `start_date` date DEFAULT NULL COMMENT '開始日',
  `end_date` date DEFAULT NULL COMMENT '終了日',
  `status` char(2) NOT NULL COMMENT 'ステータス',
  `disclosure_type` char(2) NOT NULL COMMENT '公開種別',
  `weighted_average_ratio` decimal(4,1) DEFAULT NULL COMMENT '加重平均比率',
  `ratio_locked_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '持分比率ロックフラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`okr_id`),
  KEY `idx_okr_01` (`timeframe_id`),
  KEY `idx_okr_02` (`parent_okr_id`),
  KEY `idx_okr_04` (`owner_group_id`),
  KEY `idx_okr_05` (`owner_user_id`),
  KEY `idx_okr_06` (`tree_left`,`tree_right`),
  KEY `idx_okr_07` (`name`),
  CONSTRAINT `fk_okr_owner_group_id` FOREIGN KEY (`owner_group_id`) REFERENCES `m_group` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_okr_owner_user_id` FOREIGN KEY (`owner_user_id`) REFERENCES `m_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_okr_parent_okr_id` FOREIGN KEY (`parent_okr_id`) REFERENCES `t_okr` (`okr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_okr_timeframe_id` FOREIGN KEY (`timeframe_id`) REFERENCES `t_timeframe` (`timeframe_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='OKR';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_okr`
--

LOCK TABLES `t_okr` WRITE;
/*!40000 ALTER TABLE `t_okr` DISABLE KEYS */;
INSERT INTO `t_okr` VALUES (1,1,NULL,'3','ROOT_NODE',NULL,100,0,0.0,0.00000000000000,1.00000000000000,'％','4',NULL,NULL,NULL,NULL,NULL,'1','1',NULL,0,'2017-08-16 19:45:49','2017-08-16 19:45:49',NULL),(2,1,1,'1','利益率の向上','',100,0,7.1,0.33333333333333,0.66666666666667,'％','3',NULL,NULL,1,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 19:45:49','2017-08-16 22:44:11',NULL),(3,1,2,'2','経常利益3億5000万円達成','前年同期比20%増',350000000,25000000,7.1,0.44444444444444,0.55555555555556,'円','3',NULL,NULL,1,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 19:49:01','2017-08-16 22:44:11',NULL),(4,1,3,'1','売上の増大','',100,0,7.1,0.48148148148148,0.51851851851852,'％','2',NULL,2,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 19:51:12','2017-08-16 22:42:49',NULL),(5,1,3,'1','コストの削減','',100,0,58.0,0.45679012345679,0.46913580246913,'％','2',NULL,2,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 19:52:11','2017-08-16 22:42:06',NULL),(6,1,4,'2','売上高320億円達成','前年同期比10%アップ',32000000000,2299999633,7.1,0.49382716049383,0.50617283950617,'円','2',NULL,2,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 19:55:42','2017-08-16 22:42:49',NULL),(7,1,5,'2','顧客獲得コストを1億円削減する','前年同期比10%ダウン',100000000,58000000,58.0,0.46090534979424,0.46502057613168,'円','2',NULL,2,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 19:59:13','2017-08-16 22:42:06',NULL),(8,1,6,'1','新規顧客の獲得','',100,0,45.0,0.49794238683128,0.50205761316872,'％','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:01:12','2017-08-16 22:35:43',NULL),(9,1,6,'1','既存顧客の満足度改善','',100,0,83.7,0.50342935528120,0.50480109739369,'％','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:02:36','2017-08-16 22:36:03',NULL),(10,1,7,'1','採算製品の受注比率の改善','',100,0,8.6,0.46095615505767,0.46100696032109,'％','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:03:55','2017-08-16 22:36:21',NULL),(11,1,7,'1','不採算製品の採算性の改善','',100,0,1.9,0.46105776558452,0.46121018137479,'％','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:04:49','2017-08-16 22:36:37',NULL),(12,1,7,'1','代金回収率の改善','',100,25,25.0,0.46136259716507,0.46181984453589,'%','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:05:45','2017-08-16 22:37:39',NULL),(13,1,8,'2','新規顧客120社獲得','',120,54,45.0,0.49931412894376,0.50068587105624,'社','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:07:21','2017-08-16 22:35:43',NULL),(14,1,9,'1','顧客満足度アンケート80%達成','',100,0,0.0,0.50388660265203,0.50434385002286,'％','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:09:13','2017-08-16 20:54:24','2017-08-16 21:06:23'),(15,1,9,'1','顧客満足度アンケート80%達成','',80,0,0.0,0.50358177107148,0.50373418686175,'%','2',NULL,2,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 20:09:23','2017-08-16 20:11:46','2017-08-16 20:11:52'),(16,1,9,'1','顧客満足度アンケート80%達成','',80,0,0.0,0.50348016054463,0.50353096580805,'%','2',NULL,2,NULL,'2017-09-01','2017-12-31','1','1',33.3,0,'2017-08-16 20:11:22','2017-08-16 20:11:22','2017-08-16 20:11:46'),(17,1,10,'1','採算製品受注件数3,000件','前年同期比10%アップ',100,0,0.0,0.61042524005487,0.61179698216736,'％','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:14:21','2017-08-16 20:54:53','2017-08-16 21:06:09'),(18,1,11,'2','不採算製品の受注件数1,000件','全同期比20%アップ',1000,19,1.9,0.46110857084794,0.46115937611137,'件','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:16:29','2017-08-16 22:36:37',NULL),(19,1,13,'1','ビジネス支援サービス顧客の獲得','',100,0,18.7,0.49977137631459,0.50022862368541,'％','2',NULL,4,NULL,'2017-09-01','2017-11-30','1','1',NULL,0,'2017-08-16 20:17:42','2017-08-16 22:34:35',NULL),(20,1,14,'1','納期遅れの低減','',100,0,0.0,0.50403901844231,0.50419143423258,'％','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:19:20','2017-08-16 20:29:35','2017-08-16 21:06:23'),(21,1,17,'1','アクリル製品顧客の獲得','',100,0,0.0,0.61088248742570,0.61133973479653,'％','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 20:20:52','2017-08-16 20:34:25','2017-08-16 21:06:09'),(22,1,17,'1','納期遅れの低減','',100,0,0.0,0.61057765584515,0.61073007163542,'％','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 20:21:55','2017-08-16 20:33:53','2017-08-16 21:06:09'),(23,1,18,'1','新規抱き合わせ製品顧客の獲得','',100,0,0.0,0.46112550593575,0.46114244102356,'％','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:23:28','2017-08-16 21:39:47',NULL),(24,1,21,'2','新規顧客50社確保','',50,0,0.0,0.61103490321598,0.61118731900625,'社','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:26:23','2017-08-16 20:26:23','2017-08-16 21:06:09'),(25,1,19,'2','新規顧客80社獲得','',80,15,18.7,0.49992379210486,0.50007620789514,'社','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:27:43','2017-08-16 22:34:35',NULL),(26,1,20,'2','納期遅れ件数10社削減','',10,0,0.0,0.50408982370573,0.50414062896916,'社','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:29:35','2017-08-16 20:29:35','2017-08-16 21:06:23'),(27,1,22,'2','納期遅れ件数15件削減','',15,0,0.0,0.61062846110857,0.61067926637200,'件','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:31:02','2017-08-16 20:31:03','2017-08-16 21:06:09'),(28,1,23,'2','新規顧客50社','',50,0,0.0,0.46113115096502,0.46113679599429,'社','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 20:32:01','2017-08-16 21:39:47',NULL),(29,1,6,'1','新製品市場への進出','',100,0,0.0,0.50525834476452,0.50571559213534,'％','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:47:53','2017-08-16 21:31:25',NULL),(30,1,6,'1','採算製品のシェア拡大','',100,0,0.0,0.50586800792562,0.50602042371589,'％','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 20:49:44','2017-08-16 21:34:29',NULL),(31,1,9,'2','顧客満足度アンケート80%達成','',80,67,83.7,0.50388660265203,0.50434385002286,'%','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:10:21','2017-08-16 22:36:03',NULL),(32,1,10,'2','採算製品の受注件数3000件','前年同期比10%増',3000,260,8.6,0.46097309014548,0.46099002523328,'件','2',NULL,3,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:12:04','2017-08-16 22:36:21',NULL),(33,1,31,'1','納期遅れの低減','',100,0,13.3,0.50403901844231,0.50419143423258,'％','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:13:24','2017-08-16 22:35:04',NULL),(34,1,33,'2','納期遅れ件数15件削減','前年同期比5%ダウン',15,2,13.3,0.50408982370573,0.50414062896916,'件','2',NULL,4,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:14:28','2017-08-16 22:35:04',NULL),(35,1,32,'1','アクリル製品顧客の獲得','',100,0,0.0,0.46097497182190,0.46097685349833,'％','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:17:14','2017-08-16 21:39:47',NULL),(36,1,32,'1','納期遅れの低減','',100,0,0.0,0.46097873517475,0.46098438020401,'％','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:18:45','2017-08-16 21:39:47',NULL),(37,1,35,'2','新規顧客500社獲得','',500,0,0.0,0.46097559904738,0.46097622627285,'社','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:21:05','2017-08-16 21:39:47',NULL),(38,1,36,'2','納期遅れ件数10件削減','前年同期比5%ダウン',10,0,0.0,0.46098061685117,0.46098249852759,'件','2',NULL,5,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:22:45','2017-08-16 21:39:47',NULL),(39,1,7,'1','販売経費の低減','',100,0,0.0,0.46410608139003,0.46456332876085,'％','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:24:23','2017-08-16 21:39:47',NULL),(40,1,7,'1','不採算製品の採算化','',100,0,0.0,0.46227709190672,0.46364883401920,'%','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:25:28','2017-08-16 21:39:47',NULL),(41,1,29,'2','11月末までに販売ルートの確定','',100,0,0.0,0.50541076055479,0.50556317634507,'%','2',NULL,6,NULL,'2017-09-01','2017-11-30','1','1',33.3,0,'2017-08-16 21:27:32','2017-08-16 21:31:25',NULL),(42,1,29,'2','大口代理店5社確保','',5,0,0.0,0.50530915002794,0.50535995529137,'社','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',33.3,0,'2017-08-16 21:29:04','2017-08-16 21:31:25',NULL),(43,1,29,'2','新規店舗20店出店','',20,0,0.0,0.50561398160849,0.50566478687192,'店','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',33.3,0,'2017-08-16 21:31:25','2017-08-16 21:31:25',NULL),(44,1,30,'2','採算製品のシェア35%','前年同期比7%アップ',35,0,0.0,0.50591881318904,0.50596961845247,'%','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:34:29','2017-08-16 21:34:29',NULL),(45,1,39,'2','販売経費を3,000万円削減','',30000000,0,0.0,0.46425849718030,0.46441091297058,'円','2',NULL,6,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:36:53','2017-08-16 21:39:47',NULL),(46,1,3,'1','コストの削減','',100,0,0.0,0.53086419753087,0.54320987654321,'％','2',NULL,7,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:42:14','2017-08-16 21:47:25',NULL),(47,1,3,'1','就業環境の改善','',100,0,0.0,0.44855967078189,0.45267489711934,'％','2',NULL,7,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:44:04','2017-08-16 21:48:57',NULL),(48,1,46,'2','管理費5,000万円削減','前年同期比5%ダウン',50000000,0,0.0,0.53497942386832,0.53909465020576,'円','2',NULL,7,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:47:24','2017-08-16 21:47:25',NULL),(49,1,47,'2','従業員満足度80%達成','前期比5%増',80,0,0.0,0.44993141289437,0.45130315500686,'%','2',NULL,7,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:48:57','2017-08-16 21:48:57',NULL),(50,1,48,'1','月次決算の処理期間の短縮','',100,0,0.0,0.53635116598080,0.53772290809328,'％','2',NULL,8,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:50:29','2017-08-16 21:56:03',NULL),(51,1,48,'1','伝票処理のミスの低減','',100,0,0.0,0.53543667123915,0.53589391860997,'％','2',NULL,8,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:51:27','2017-08-16 21:57:30',NULL),(52,1,48,'1','コストの削減','',100,0,0.0,0.53513183965860,0.53528425544887,'％','2',NULL,9,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:53:25','2017-08-16 22:10:33',NULL),(53,1,49,'1','労働意識の改善','',100,0,0.0,0.45038866026520,0.45084590763603,'％','2',NULL,9,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 21:54:36','2017-08-16 22:02:14',NULL),(54,1,50,'2','5日間短縮','',5,0,0.0,0.53680841335163,0.53726566072245,'日','2',NULL,8,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:56:03','2017-08-16 21:56:03',NULL),(55,1,51,'2','伝票処理ミス数30件削減','',30,0,0.0,0.53558908702942,0.53574150281970,'件','2',NULL,8,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 21:57:30','2017-08-16 21:57:30',NULL),(56,1,52,'2','残業時間1人10時間削減/月','',10,0,0.0,0.53518264492202,0.53523345018545,'時間','2',NULL,9,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:00:15','2017-08-16 22:10:33',NULL),(57,1,53,'2','期末評価対前年比8%アップ','',8,0,0.0,0.45054107605548,0.45069349184575,'%','2',NULL,9,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 22:02:14','2017-08-16 22:02:14',NULL),(58,1,52,'1','採用費の削減','',100,0,0.0,0.53525038527326,0.53526732036106,'％','2',NULL,10,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:03:42','2017-08-16 22:10:33',NULL),(59,1,57,'1','新規戦力の獲得','',100,0,0.0,0.45059188131890,0.45064268658233,'％','2',NULL,10,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 22:06:30','2017-08-16 22:13:37',NULL),(60,1,58,'2','採用活動期間30日間短縮','',30,0,0.0,0.53525603030253,0.53526167533179,'日','2',NULL,10,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:09:04','2017-08-16 22:10:33',NULL),(61,1,58,'2','採用マージンの低い人材紹介会社へ利用転換','',100,0,0.0,0.53526355700821,0.53526543868464,'%','2',NULL,10,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:10:33','2017-08-16 22:10:33',NULL),(62,1,59,'2','新卒社員30名採用','',30,0,0.0,0.45060881640671,0.45062575149452,'人','2',NULL,10,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:12:32','2017-08-16 22:13:37',NULL),(63,1,59,'2','中途社員10名採用','',10,0,0.0,0.45063139652379,0.45063704155306,'人','2',NULL,10,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:13:36','2017-08-16 22:13:49',NULL),(64,1,25,'1','売上アップ','',100,0,15.8,0.49997459736829,0.50002540263171,'％','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 22:17:53','2017-08-16 22:32:29',NULL),(65,1,34,'1','顧客満足度の向上','',100,0,16.0,0.50410675879354,0.50412369388135,'％','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',NULL,0,'2017-08-16 22:19:56','2017-08-16 22:33:11',NULL),(66,1,64,'2','既存顧客の売上8,000万円','前期比1,000万円アップ',80000000,9200000,11.5,0.49999153245610,0.50000846754390,'円','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:21:40','2017-08-16 22:32:29',NULL),(67,1,64,'2','新規顧客開拓10社','',10,2,20.0,0.50001411257317,0.50001975760244,'社','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:22:44','2017-08-16 22:32:09',NULL),(68,1,65,'2','既存顧客への訪問件数累計50件','',50,8,16.0,0.50411240382281,0.50411804885208,'件','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',100.0,0,'2017-08-16 22:24:43','2017-08-16 22:33:11',NULL),(69,1,NULL,'1','営業スキルの向上','',100,0,36.7,NULL,NULL,'％','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','3',NULL,0,'2017-08-16 22:25:47','2017-08-16 22:33:50',NULL),(70,1,69,'2','営業スキルに関する本を5冊読む','',5,2,40.0,NULL,NULL,'冊','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:26:33','2017-08-16 22:33:40',NULL),(71,1,69,'2','社内営業研修に3回参加する','',3,1,33.3,NULL,NULL,'回','1',1,NULL,NULL,'2017-09-01','2017-12-31','1','1',50.0,0,'2017-08-16 22:27:32','2017-08-16 22:33:50',NULL);
/*!40000 ALTER TABLE `t_okr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_okr_activity`
--

DROP TABLE IF EXISTS `t_okr_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_okr_activity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `okr_id` int(11) unsigned NOT NULL COMMENT 'OKRID',
  `type` char(2) NOT NULL COMMENT '種別',
  `activity_datetime` datetime NOT NULL COMMENT 'アクティビティ日時',
  `target_value` bigint(20) unsigned DEFAULT NULL COMMENT '目標値',
  `achieved_value` bigint(20) unsigned DEFAULT NULL COMMENT '達成値',
  `achievement_rate` decimal(15,1) unsigned DEFAULT NULL COMMENT '達成率',
  `changed_percentage` decimal(15,1) DEFAULT NULL COMMENT '増加パーセンテージ',
  `new_parent_okr_id` int(11) unsigned DEFAULT NULL COMMENT '新親OKRID',
  `previous_parent_okr_id` int(11) unsigned DEFAULT NULL COMMENT '旧親OKRID',
  `new_timeframe_id` int(11) unsigned DEFAULT NULL COMMENT '新タイムフレームID',
  `previous_timeframe_id` int(11) unsigned DEFAULT NULL COMMENT '旧タイムフレームID',
  `new_owner_user_id` int(11) unsigned DEFAULT NULL COMMENT '新オーナーユーザID',
  `previous_owner_user_id` int(11) unsigned DEFAULT NULL COMMENT '旧オーナーユーザID',
  `new_owner_group_id` int(11) unsigned DEFAULT NULL COMMENT '新オーナーグループID',
  `previous_owner_group_id` int(11) unsigned DEFAULT NULL COMMENT '旧オーナーグループID',
  `new_owner_company_id` int(11) unsigned DEFAULT NULL COMMENT '新オーナー会社ID',
  `previous_owner_company_id` int(11) unsigned DEFAULT NULL COMMENT '旧オーナー会社ID',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_okr_activity_01` (`okr_id`),
  KEY `idx_okr_activity_02` (`activity_datetime`),
  CONSTRAINT `fk_okr_activity_okr_id` FOREIGN KEY (`okr_id`) REFERENCES `t_okr` (`okr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8 COMMENT='OKRアクティビティ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_okr_activity`
--

LOCK TABLES `t_okr_activity` WRITE;
/*!40000 ALTER TABLE `t_okr_activity` DISABLE KEYS */;
INSERT INTO `t_okr_activity` VALUES (1,2,'1','2017-08-16 19:45:49',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:45:49','2017-08-16 19:45:49',NULL),(2,3,'1','2017-08-16 19:49:01',350000000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:49:01','2017-08-16 19:49:01',NULL),(3,3,'2','2017-08-16 19:49:01',NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:49:01','2017-08-16 19:49:01',NULL),(4,2,'5','2017-08-16 19:49:02',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:49:02','2017-08-16 19:49:02',NULL),(5,4,'1','2017-08-16 19:51:12',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:51:12','2017-08-16 19:51:12',NULL),(6,4,'2','2017-08-16 19:51:12',NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:51:12','2017-08-16 19:51:12',NULL),(7,5,'1','2017-08-16 19:52:11',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:52:11','2017-08-16 19:52:11',NULL),(8,5,'2','2017-08-16 19:52:11',NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:52:11','2017-08-16 19:52:11',NULL),(9,2,'5','2017-08-16 19:52:12',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:52:12','2017-08-16 19:52:12',NULL),(10,6,'1','2017-08-16 19:55:42',32000000000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:55:42','2017-08-16 19:55:42',NULL),(11,6,'2','2017-08-16 19:55:42',NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:55:42','2017-08-16 19:55:42',NULL),(12,4,'5','2017-08-16 19:55:42',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:55:42','2017-08-16 19:55:42',NULL),(13,7,'1','2017-08-16 19:59:13',100000000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:59:13','2017-08-16 19:59:13',NULL),(14,7,'2','2017-08-16 19:59:13',NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:59:13','2017-08-16 19:59:13',NULL),(15,5,'5','2017-08-16 19:59:13',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:59:13','2017-08-16 19:59:13',NULL),(16,2,'5','2017-08-16 19:59:13',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 19:59:13','2017-08-16 19:59:13',NULL),(17,8,'1','2017-08-16 20:01:12',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:01:12','2017-08-16 20:01:12',NULL),(18,8,'2','2017-08-16 20:01:12',NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:01:12','2017-08-16 20:01:12',NULL),(19,9,'1','2017-08-16 20:02:36',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:02:36','2017-08-16 20:02:36',NULL),(20,9,'2','2017-08-16 20:02:37',NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:02:37','2017-08-16 20:02:37',NULL),(21,10,'1','2017-08-16 20:03:55',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:03:55','2017-08-16 20:03:55',NULL),(22,10,'2','2017-08-16 20:03:55',NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:03:55','2017-08-16 20:03:55',NULL),(23,11,'1','2017-08-16 20:04:49',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:04:49','2017-08-16 20:04:49',NULL),(24,11,'2','2017-08-16 20:04:49',NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:04:49','2017-08-16 20:04:49',NULL),(25,12,'1','2017-08-16 20:05:45',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:05:45','2017-08-16 20:05:45',NULL),(26,12,'2','2017-08-16 20:05:45',NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:05:45','2017-08-16 20:05:45',NULL),(27,13,'1','2017-08-16 20:07:21',120,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:07:21','2017-08-16 20:07:21',NULL),(28,13,'2','2017-08-16 20:07:21',NULL,NULL,NULL,NULL,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:07:21','2017-08-16 20:07:21',NULL),(29,8,'5','2017-08-16 20:07:21',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:07:21','2017-08-16 20:07:21',NULL),(30,14,'1','2017-08-16 20:09:13',80,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:09:13','2017-08-16 20:09:13','2017-08-16 21:06:23'),(31,14,'2','2017-08-16 20:09:13',NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:09:13','2017-08-16 20:09:13','2017-08-16 21:06:23'),(32,9,'5','2017-08-16 20:09:13',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:09:13','2017-08-16 20:09:13',NULL),(33,15,'1','2017-08-16 20:09:23',80,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:09:23','2017-08-16 20:09:23','2017-08-16 20:11:52'),(34,15,'2','2017-08-16 20:09:23',NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:09:23','2017-08-16 20:09:23','2017-08-16 20:11:52'),(35,9,'5','2017-08-16 20:09:23',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:09:23','2017-08-16 20:09:23',NULL),(36,16,'1','2017-08-16 20:11:22',80,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:11:22','2017-08-16 20:11:22','2017-08-16 20:11:46'),(37,16,'2','2017-08-16 20:11:22',NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:11:22','2017-08-16 20:11:22','2017-08-16 20:11:46'),(38,9,'5','2017-08-16 20:11:22',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:11:22','2017-08-16 20:11:22',NULL),(39,9,'5','2017-08-16 20:11:46',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:11:46','2017-08-16 20:11:46',NULL),(40,9,'5','2017-08-16 20:11:52',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:11:52','2017-08-16 20:11:52',NULL),(41,17,'1','2017-08-16 20:14:21',3000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:14:21','2017-08-16 20:14:21','2017-08-16 21:06:09'),(42,17,'2','2017-08-16 20:14:21',NULL,NULL,NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:14:21','2017-08-16 20:14:21','2017-08-16 21:06:09'),(43,10,'5','2017-08-16 20:14:21',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:14:21','2017-08-16 20:14:21',NULL),(44,18,'1','2017-08-16 20:16:29',1000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:16:29','2017-08-16 20:16:29',NULL),(45,18,'2','2017-08-16 20:16:29',NULL,NULL,NULL,NULL,11,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:16:29','2017-08-16 20:16:29',NULL),(46,11,'5','2017-08-16 20:16:29',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:16:29','2017-08-16 20:16:29',NULL),(47,19,'1','2017-08-16 20:17:42',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:17:42','2017-08-16 20:17:42',NULL),(48,19,'2','2017-08-16 20:17:42',NULL,NULL,NULL,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:17:42','2017-08-16 20:17:42',NULL),(49,20,'1','2017-08-16 20:19:20',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:19:20','2017-08-16 20:19:20','2017-08-16 21:06:23'),(50,20,'2','2017-08-16 20:19:20',NULL,NULL,NULL,NULL,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:19:20','2017-08-16 20:19:20','2017-08-16 21:06:23'),(51,14,'5','2017-08-16 20:19:20',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:19:20','2017-08-16 20:19:20','2017-08-16 21:06:23'),(52,9,'5','2017-08-16 20:19:20',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:19:20','2017-08-16 20:19:20',NULL),(53,21,'1','2017-08-16 20:20:52',50,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:20:52','2017-08-16 20:20:52','2017-08-16 21:06:09'),(54,21,'2','2017-08-16 20:20:52',NULL,NULL,NULL,NULL,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:20:52','2017-08-16 20:20:52','2017-08-16 21:06:09'),(55,17,'5','2017-08-16 20:20:52',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:20:52','2017-08-16 20:20:52','2017-08-16 21:06:09'),(56,10,'5','2017-08-16 20:20:52',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:20:52','2017-08-16 20:20:52',NULL),(57,22,'1','2017-08-16 20:21:55',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:21:55','2017-08-16 20:21:55','2017-08-16 21:06:09'),(58,22,'2','2017-08-16 20:21:55',NULL,NULL,NULL,NULL,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:21:55','2017-08-16 20:21:55','2017-08-16 21:06:09'),(59,17,'5','2017-08-16 20:21:55',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:21:55','2017-08-16 20:21:55','2017-08-16 21:06:09'),(60,10,'5','2017-08-16 20:21:55',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:21:55','2017-08-16 20:21:55',NULL),(61,23,'1','2017-08-16 20:23:28',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:23:28','2017-08-16 20:23:28',NULL),(62,23,'2','2017-08-16 20:23:28',NULL,NULL,NULL,NULL,18,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:23:28','2017-08-16 20:23:28',NULL),(63,24,'1','2017-08-16 20:26:23',50,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:26:23','2017-08-16 20:26:23','2017-08-16 21:06:09'),(64,24,'2','2017-08-16 20:26:23',NULL,NULL,NULL,NULL,21,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:26:23','2017-08-16 20:26:23','2017-08-16 21:06:09'),(65,21,'5','2017-08-16 20:26:23',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:26:23','2017-08-16 20:26:23','2017-08-16 21:06:09'),(66,17,'5','2017-08-16 20:26:23',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:26:23','2017-08-16 20:26:23','2017-08-16 21:06:09'),(67,10,'5','2017-08-16 20:26:23',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:26:23','2017-08-16 20:26:23',NULL),(68,25,'1','2017-08-16 20:27:43',80,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:27:43','2017-08-16 20:27:43',NULL),(69,25,'2','2017-08-16 20:27:44',NULL,NULL,NULL,NULL,19,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:27:44','2017-08-16 20:27:44',NULL),(70,19,'5','2017-08-16 20:27:44',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:27:44','2017-08-16 20:27:44',NULL),(71,26,'1','2017-08-16 20:29:35',10,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:29:35','2017-08-16 20:29:35','2017-08-16 21:06:23'),(72,26,'2','2017-08-16 20:29:35',NULL,NULL,NULL,NULL,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:29:35','2017-08-16 20:29:35','2017-08-16 21:06:23'),(73,20,'5','2017-08-16 20:29:35',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:29:35','2017-08-16 20:29:35','2017-08-16 21:06:23'),(74,14,'5','2017-08-16 20:29:35',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:29:35','2017-08-16 20:29:35','2017-08-16 21:06:23'),(75,9,'5','2017-08-16 20:29:35',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:29:35','2017-08-16 20:29:35',NULL),(76,27,'1','2017-08-16 20:31:02',15,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:31:02','2017-08-16 20:31:02','2017-08-16 21:06:09'),(77,27,'2','2017-08-16 20:31:02',NULL,NULL,NULL,NULL,22,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:31:02','2017-08-16 20:31:02','2017-08-16 21:06:09'),(78,22,'5','2017-08-16 20:31:02',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:31:02','2017-08-16 20:31:02','2017-08-16 21:06:09'),(79,17,'5','2017-08-16 20:31:03',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:31:03','2017-08-16 20:31:03','2017-08-16 21:06:09'),(80,10,'5','2017-08-16 20:31:03',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:31:03','2017-08-16 20:31:03',NULL),(81,28,'1','2017-08-16 20:32:01',50,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:32:01','2017-08-16 20:32:01',NULL),(82,28,'2','2017-08-16 20:32:01',NULL,NULL,NULL,NULL,23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:32:01','2017-08-16 20:32:01',NULL),(83,23,'5','2017-08-16 20:32:01',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:32:01','2017-08-16 20:32:01',NULL),(84,29,'1','2017-08-16 20:47:53',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:47:53','2017-08-16 20:47:53',NULL),(85,29,'2','2017-08-16 20:47:53',NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:47:53','2017-08-16 20:47:53',NULL),(86,30,'1','2017-08-16 20:49:44',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:49:44','2017-08-16 20:49:44',NULL),(87,30,'2','2017-08-16 20:49:44',NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 20:49:44','2017-08-16 20:49:44',NULL),(88,31,'1','2017-08-16 21:10:21',80,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:10:21','2017-08-16 21:10:21',NULL),(89,31,'2','2017-08-16 21:10:21',NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:10:21','2017-08-16 21:10:21',NULL),(90,9,'5','2017-08-16 21:10:21',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:10:21','2017-08-16 21:10:21',NULL),(91,32,'1','2017-08-16 21:12:04',3000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:12:04','2017-08-16 21:12:04',NULL),(92,32,'2','2017-08-16 21:12:04',NULL,NULL,NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:12:04','2017-08-16 21:12:04',NULL),(93,10,'5','2017-08-16 21:12:04',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:12:04','2017-08-16 21:12:04',NULL),(94,33,'1','2017-08-16 21:13:24',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:13:24','2017-08-16 21:13:24',NULL),(95,33,'2','2017-08-16 21:13:24',NULL,NULL,NULL,NULL,31,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:13:24','2017-08-16 21:13:24',NULL),(96,34,'1','2017-08-16 21:14:28',15,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:14:28','2017-08-16 21:14:28',NULL),(97,34,'2','2017-08-16 21:14:28',NULL,NULL,NULL,NULL,33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:14:29','2017-08-16 21:14:28',NULL),(98,33,'5','2017-08-16 21:14:29',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:14:29','2017-08-16 21:14:29',NULL),(99,35,'1','2017-08-16 21:17:14',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:17:14','2017-08-16 21:17:14',NULL),(100,35,'2','2017-08-16 21:17:14',NULL,NULL,NULL,NULL,32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:17:14','2017-08-16 21:17:14',NULL),(101,36,'1','2017-08-16 21:18:45',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:18:45','2017-08-16 21:18:45',NULL),(102,36,'2','2017-08-16 21:18:45',NULL,NULL,NULL,NULL,32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:18:45','2017-08-16 21:18:45',NULL),(103,37,'1','2017-08-16 21:21:05',500,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:21:05','2017-08-16 21:21:05',NULL),(104,37,'2','2017-08-16 21:21:05',NULL,NULL,NULL,NULL,35,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:21:05','2017-08-16 21:21:05',NULL),(105,35,'5','2017-08-16 21:21:05',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:21:05','2017-08-16 21:21:05',NULL),(106,38,'1','2017-08-16 21:22:45',10,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:22:45','2017-08-16 21:22:45',NULL),(107,38,'2','2017-08-16 21:22:45',NULL,NULL,NULL,NULL,36,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:22:45','2017-08-16 21:22:45',NULL),(108,36,'5','2017-08-16 21:22:45',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:22:45','2017-08-16 21:22:45',NULL),(109,39,'1','2017-08-16 21:24:23',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:24:23','2017-08-16 21:24:23',NULL),(110,39,'2','2017-08-16 21:24:24',NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:24:24','2017-08-16 21:24:24',NULL),(111,40,'1','2017-08-16 21:25:28',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:25:28','2017-08-16 21:25:28',NULL),(112,40,'2','2017-08-16 21:25:28',NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:25:28','2017-08-16 21:25:28',NULL),(113,41,'1','2017-08-16 21:27:32',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:27:32','2017-08-16 21:27:32',NULL),(114,41,'2','2017-08-16 21:27:32',NULL,NULL,NULL,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:27:32','2017-08-16 21:27:32',NULL),(115,29,'5','2017-08-16 21:27:32',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:27:32','2017-08-16 21:27:32',NULL),(116,42,'1','2017-08-16 21:29:04',5,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:29:04','2017-08-16 21:29:04',NULL),(117,42,'2','2017-08-16 21:29:04',NULL,NULL,NULL,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:29:04','2017-08-16 21:29:04',NULL),(118,29,'5','2017-08-16 21:29:04',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:29:04','2017-08-16 21:29:04',NULL),(119,43,'1','2017-08-16 21:31:25',20,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:31:25','2017-08-16 21:31:25',NULL),(120,43,'2','2017-08-16 21:31:25',NULL,NULL,NULL,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:31:25','2017-08-16 21:31:25',NULL),(121,29,'5','2017-08-16 21:31:25',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:31:25','2017-08-16 21:31:25',NULL),(122,44,'1','2017-08-16 21:34:29',35,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:34:29','2017-08-16 21:34:29',NULL),(123,44,'2','2017-08-16 21:34:29',NULL,NULL,NULL,NULL,30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:34:29','2017-08-16 21:34:29',NULL),(124,30,'5','2017-08-16 21:34:29',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:34:29','2017-08-16 21:34:29',NULL),(125,45,'1','2017-08-16 21:36:53',30000000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:36:53','2017-08-16 21:36:53',NULL),(126,45,'2','2017-08-16 21:36:53',NULL,NULL,NULL,NULL,39,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:36:53','2017-08-16 21:36:53',NULL),(127,39,'5','2017-08-16 21:36:53',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:36:53','2017-08-16 21:36:53',NULL),(128,5,'3','2017-08-16 21:39:46',NULL,NULL,NULL,NULL,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:39:46','2017-08-16 21:39:46',NULL),(129,2,'5','2017-08-16 21:39:46',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:39:46','2017-08-16 21:39:46',NULL),(130,46,'1','2017-08-16 21:42:14',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:42:14','2017-08-16 21:42:14',NULL),(131,46,'2','2017-08-16 21:42:14',NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:42:14','2017-08-16 21:42:14',NULL),(132,47,'1','2017-08-16 21:44:04',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:44:04','2017-08-16 21:44:04',NULL),(133,47,'2','2017-08-16 21:44:04',NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:44:04','2017-08-16 21:44:04',NULL),(134,48,'1','2017-08-16 21:47:24',50000000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:47:24','2017-08-16 21:47:24',NULL),(135,48,'2','2017-08-16 21:47:25',NULL,NULL,NULL,NULL,46,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:47:25','2017-08-16 21:47:25',NULL),(136,46,'5','2017-08-16 21:47:25',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:47:25','2017-08-16 21:47:25',NULL),(137,49,'1','2017-08-16 21:48:57',80,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:48:57','2017-08-16 21:48:57',NULL),(138,49,'2','2017-08-16 21:48:57',NULL,NULL,NULL,NULL,47,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:48:57','2017-08-16 21:48:57',NULL),(139,47,'5','2017-08-16 21:48:57',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:48:57','2017-08-16 21:48:57',NULL),(140,50,'1','2017-08-16 21:50:29',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:50:29','2017-08-16 21:50:29',NULL),(141,50,'2','2017-08-16 21:50:29',NULL,NULL,NULL,NULL,48,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:50:29','2017-08-16 21:50:29',NULL),(142,51,'1','2017-08-16 21:51:27',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:51:27','2017-08-16 21:51:27',NULL),(143,51,'2','2017-08-16 21:51:27',NULL,NULL,NULL,NULL,48,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:51:27','2017-08-16 21:51:27',NULL),(144,52,'1','2017-08-16 21:53:25',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:53:25','2017-08-16 21:53:25',NULL),(145,52,'2','2017-08-16 21:53:25',NULL,NULL,NULL,NULL,48,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:53:25','2017-08-16 21:53:25',NULL),(146,53,'1','2017-08-16 21:54:36',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:54:36','2017-08-16 21:54:36',NULL),(147,53,'2','2017-08-16 21:54:37',NULL,NULL,NULL,NULL,49,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:54:37','2017-08-16 21:54:37',NULL),(148,54,'1','2017-08-16 21:56:03',5,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:56:03','2017-08-16 21:56:03',NULL),(149,54,'2','2017-08-16 21:56:03',NULL,NULL,NULL,NULL,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:56:03','2017-08-16 21:56:03',NULL),(150,50,'5','2017-08-16 21:56:03',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:56:03','2017-08-16 21:56:03',NULL),(151,55,'1','2017-08-16 21:57:30',30,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:57:30','2017-08-16 21:57:30',NULL),(152,55,'2','2017-08-16 21:57:30',NULL,NULL,NULL,NULL,51,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:57:30','2017-08-16 21:57:30',NULL),(153,51,'5','2017-08-16 21:57:30',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 21:57:30','2017-08-16 21:57:30',NULL),(154,56,'1','2017-08-16 22:00:15',10,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:00:15','2017-08-16 22:00:15',NULL),(155,56,'2','2017-08-16 22:00:15',NULL,NULL,NULL,NULL,52,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:00:15','2017-08-16 22:00:15',NULL),(156,52,'5','2017-08-16 22:00:15',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:00:15','2017-08-16 22:00:15',NULL),(157,57,'1','2017-08-16 22:02:14',8,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:02:14','2017-08-16 22:02:14',NULL),(158,57,'2','2017-08-16 22:02:14',NULL,NULL,NULL,NULL,53,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:02:14','2017-08-16 22:02:14',NULL),(159,53,'5','2017-08-16 22:02:14',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:02:14','2017-08-16 22:02:14',NULL),(160,58,'1','2017-08-16 22:03:42',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:03:42','2017-08-16 22:03:42',NULL),(161,58,'2','2017-08-16 22:03:42',NULL,NULL,NULL,NULL,52,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:03:42','2017-08-16 22:03:42',NULL),(162,52,'5','2017-08-16 22:03:42',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:03:42','2017-08-16 22:03:42',NULL),(163,59,'1','2017-08-16 22:06:30',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:06:30','2017-08-16 22:06:30',NULL),(164,59,'2','2017-08-16 22:06:30',NULL,NULL,NULL,NULL,57,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:06:30','2017-08-16 22:06:30',NULL),(165,60,'1','2017-08-16 22:09:04',30,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:09:04','2017-08-16 22:09:04',NULL),(166,60,'2','2017-08-16 22:09:04',NULL,NULL,NULL,NULL,58,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:09:04','2017-08-16 22:09:04',NULL),(167,58,'5','2017-08-16 22:09:04',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:09:04','2017-08-16 22:09:04',NULL),(168,52,'5','2017-08-16 22:09:04',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:09:04','2017-08-16 22:09:04',NULL),(169,61,'1','2017-08-16 22:10:33',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:10:33','2017-08-16 22:10:33',NULL),(170,61,'2','2017-08-16 22:10:33',NULL,NULL,NULL,NULL,58,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:10:33','2017-08-16 22:10:33',NULL),(171,58,'5','2017-08-16 22:10:33',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:10:33','2017-08-16 22:10:33',NULL),(172,52,'5','2017-08-16 22:10:33',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:10:33','2017-08-16 22:10:33',NULL),(173,62,'1','2017-08-16 22:12:32',30,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:12:32','2017-08-16 22:12:32',NULL),(174,62,'2','2017-08-16 22:12:32',NULL,NULL,NULL,NULL,59,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:12:32','2017-08-16 22:12:32',NULL),(175,59,'5','2017-08-16 22:12:32',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:12:32','2017-08-16 22:12:32',NULL),(176,63,'1','2017-08-16 22:13:36',10,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:13:36','2017-08-16 22:13:36',NULL),(177,63,'2','2017-08-16 22:13:37',NULL,NULL,NULL,NULL,59,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:13:37','2017-08-16 22:13:37',NULL),(178,59,'5','2017-08-16 22:13:37',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:13:37','2017-08-16 22:13:37',NULL),(179,64,'1','2017-08-16 22:17:53',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:17:53','2017-08-16 22:17:53',NULL),(180,64,'2','2017-08-16 22:17:53',NULL,NULL,NULL,NULL,25,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:17:53','2017-08-16 22:17:53',NULL),(181,65,'1','2017-08-16 22:19:56',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:19:56','2017-08-16 22:19:56',NULL),(182,65,'2','2017-08-16 22:19:56',NULL,NULL,NULL,NULL,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:19:56','2017-08-16 22:19:56',NULL),(183,66,'1','2017-08-16 22:21:40',1000,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:21:40','2017-08-16 22:21:40',NULL),(184,66,'2','2017-08-16 22:21:40',NULL,NULL,NULL,NULL,64,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:21:40','2017-08-16 22:21:40',NULL),(185,64,'5','2017-08-16 22:21:40',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:21:40','2017-08-16 22:21:40',NULL),(186,67,'1','2017-08-16 22:22:44',10,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:22:44','2017-08-16 22:22:44',NULL),(187,67,'2','2017-08-16 22:22:44',NULL,NULL,NULL,NULL,64,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:22:44','2017-08-16 22:22:44',NULL),(188,64,'5','2017-08-16 22:22:44',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:22:44','2017-08-16 22:22:44',NULL),(189,68,'1','2017-08-16 22:24:43',50,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:24:43','2017-08-16 22:24:43',NULL),(190,68,'2','2017-08-16 22:24:43',NULL,NULL,NULL,NULL,65,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:24:43','2017-08-16 22:24:43',NULL),(191,65,'5','2017-08-16 22:24:43',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:24:43','2017-08-16 22:24:43',NULL),(192,69,'1','2017-08-16 22:25:47',100,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:25:47','2017-08-16 22:25:47',NULL),(193,70,'1','2017-08-16 22:26:33',5,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:26:33','2017-08-16 22:26:33',NULL),(194,70,'2','2017-08-16 22:26:33',NULL,NULL,NULL,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:26:33','2017-08-16 22:26:33',NULL),(195,69,'5','2017-08-16 22:26:34',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:26:34','2017-08-16 22:26:34',NULL),(196,71,'1','2017-08-16 22:27:32',3,0,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:27:32','2017-08-16 22:27:32',NULL),(197,71,'2','2017-08-16 22:27:32',NULL,NULL,NULL,NULL,69,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:27:32','2017-08-16 22:27:32',NULL),(198,69,'5','2017-08-16 22:27:32',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:27:32','2017-08-16 22:27:32',NULL),(199,66,'5','2017-08-16 22:31:24',80000000,5800000,7.2,7.2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:31:24','2017-08-16 22:31:24',NULL),(200,64,'5','2017-08-16 22:31:24',NULL,NULL,3.6,3.6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:31:24','2017-08-16 22:31:24',NULL),(201,67,'5','2017-08-16 22:31:55',10,1,10.0,10.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:31:55','2017-08-16 22:31:55',NULL),(202,64,'5','2017-08-16 22:31:55',NULL,NULL,8.6,5.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:31:55','2017-08-16 22:31:55',NULL),(203,67,'5','2017-08-16 22:32:09',10,2,20.0,10.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:32:09','2017-08-16 22:32:09',NULL),(204,64,'5','2017-08-16 22:32:09',NULL,NULL,13.6,5.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:32:09','2017-08-16 22:32:09',NULL),(205,66,'5','2017-08-16 22:32:29',80000000,9200000,11.5,4.3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:32:29','2017-08-16 22:32:29',NULL),(206,64,'5','2017-08-16 22:32:29',NULL,NULL,15.8,2.2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:32:29','2017-08-16 22:32:29',NULL),(207,68,'5','2017-08-16 22:33:03',50,2,4.0,4.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:03','2017-08-16 22:33:03',NULL),(208,65,'5','2017-08-16 22:33:03',NULL,NULL,4.0,4.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:03','2017-08-16 22:33:03',NULL),(209,68,'5','2017-08-16 22:33:11',50,8,16.0,12.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:11','2017-08-16 22:33:11',NULL),(210,65,'5','2017-08-16 22:33:11',NULL,NULL,16.0,12.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:11','2017-08-16 22:33:11',NULL),(211,70,'5','2017-08-16 22:33:33',5,1,20.0,20.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:33','2017-08-16 22:33:33',NULL),(212,69,'5','2017-08-16 22:33:33',NULL,NULL,10.0,10.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:33','2017-08-16 22:33:33',NULL),(213,70,'5','2017-08-16 22:33:39',5,2,40.0,20.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:40','2017-08-16 22:33:40',NULL),(214,69,'5','2017-08-16 22:33:40',NULL,NULL,20.0,10.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:40','2017-08-16 22:33:40',NULL),(215,71,'5','2017-08-16 22:33:49',3,1,33.3,33.3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:49','2017-08-16 22:33:49',NULL),(216,69,'5','2017-08-16 22:33:50',NULL,NULL,36.7,16.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:33:50','2017-08-16 22:33:50',NULL),(217,25,'5','2017-08-16 22:34:26',80,7,8.7,8.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:34:26','2017-08-16 22:34:26',NULL),(218,19,'5','2017-08-16 22:34:26',NULL,NULL,8.7,8.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:34:26','2017-08-16 22:34:26',NULL),(219,25,'5','2017-08-16 22:34:35',80,15,18.7,10.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:34:35','2017-08-16 22:34:35',NULL),(220,19,'5','2017-08-16 22:34:35',NULL,NULL,18.7,10.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:34:35','2017-08-16 22:34:35',NULL),(221,34,'5','2017-08-16 22:34:57',15,1,6.6,6.6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:34:57','2017-08-16 22:34:57',NULL),(222,33,'5','2017-08-16 22:34:57',NULL,NULL,6.6,6.6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:34:57','2017-08-16 22:34:57',NULL),(223,34,'5','2017-08-16 22:35:04',15,2,13.3,6.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:35:04','2017-08-16 22:35:04',NULL),(224,33,'5','2017-08-16 22:35:04',NULL,NULL,13.3,6.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:35:04','2017-08-16 22:35:04',NULL),(225,13,'5','2017-08-16 22:35:43',120,54,45.0,45.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:35:43','2017-08-16 22:35:43',NULL),(226,8,'5','2017-08-16 22:35:43',NULL,NULL,45.0,45.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:35:43','2017-08-16 22:35:43',NULL),(227,31,'5','2017-08-16 22:36:03',80,67,83.7,83.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:36:03','2017-08-16 22:36:03',NULL),(228,9,'5','2017-08-16 22:36:03',NULL,NULL,83.7,83.7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:36:03','2017-08-16 22:36:03',NULL),(229,32,'5','2017-08-16 22:36:21',3000,260,8.6,8.6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:36:21','2017-08-16 22:36:21',NULL),(230,10,'5','2017-08-16 22:36:21',NULL,NULL,8.6,8.6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:36:21','2017-08-16 22:36:21',NULL),(231,18,'5','2017-08-16 22:36:37',1000,19,1.9,1.9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:36:37','2017-08-16 22:36:37',NULL),(232,11,'5','2017-08-16 22:36:37',NULL,NULL,1.9,1.9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:36:37','2017-08-16 22:36:37',NULL),(233,12,'5','2017-08-16 22:37:39',100,25,25.0,25.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:37:39','2017-08-16 22:37:39',NULL),(234,6,'5','2017-08-16 22:38:32',32000000000,230000,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:38:32','2017-08-16 22:38:32',NULL),(235,4,'5','2017-08-16 22:38:32',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:38:32','2017-08-16 22:38:32',NULL),(236,6,'5','2017-08-16 22:40:52',32000000000,230001,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:40:52','2017-08-16 22:40:52',NULL),(237,4,'5','2017-08-16 22:40:52',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:40:52','2017-08-16 22:40:52',NULL),(238,7,'5','2017-08-16 22:41:52',100000000,580000,0.5,0.5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:41:52','2017-08-16 22:41:52',NULL),(239,5,'5','2017-08-16 22:41:52',NULL,NULL,0.5,0.5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:41:52','2017-08-16 22:41:52',NULL),(240,7,'5','2017-08-16 22:42:06',100000000,58000000,58.0,57.5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:06','2017-08-16 22:42:06',NULL),(241,5,'5','2017-08-16 22:42:06',NULL,NULL,58.0,57.5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:06','2017-08-16 22:42:06',NULL),(242,6,'5','2017-08-16 22:42:23',32000000000,2300001,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:23','2017-08-16 22:42:23',NULL),(243,4,'5','2017-08-16 22:42:24',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:24','2017-08-16 22:42:24',NULL),(244,6,'5','2017-08-16 22:42:31',32000000000,22999996,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:31','2017-08-16 22:42:31',NULL),(245,4,'5','2017-08-16 22:42:32',NULL,NULL,0.0,0.0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:32','2017-08-16 22:42:32',NULL),(246,6,'5','2017-08-16 22:42:49',32000000000,2299999633,7.1,7.1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:49','2017-08-16 22:42:49',NULL),(247,4,'5','2017-08-16 22:42:49',NULL,NULL,7.1,7.1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:42:49','2017-08-16 22:42:49',NULL),(248,3,'5','2017-08-16 22:44:11',350000000,25000000,7.1,7.1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:44:11','2017-08-16 22:44:11',NULL),(249,2,'5','2017-08-16 22:44:11',NULL,NULL,7.1,7.1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2017-08-16 22:44:11','2017-08-16 22:44:11',NULL);
/*!40000 ALTER TABLE `t_okr_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_payment`
--

DROP TABLE IF EXISTS `t_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_payment` (
  `payment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '請求ID',
  `contract_id` int(11) unsigned NOT NULL COMMENT '契約ID',
  `payment_date` date NOT NULL COMMENT '請求日',
  `status` char(2) NOT NULL COMMENT '請求ステータス',
  `charge_amount` decimal(15,0) NOT NULL COMMENT '請求金額',
  `settlement_amount` decimal(15,0) DEFAULT NULL COMMENT '約定金額',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `idx_payment_01` (`contract_id`),
  CONSTRAINT `fk_payment_contract_id` FOREIGN KEY (`contract_id`) REFERENCES `t_contract` (`contract_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='請求';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_payment`
--

LOCK TABLES `t_payment` WRITE;
/*!40000 ALTER TABLE `t_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_post`
--

DROP TABLE IF EXISTS `t_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `poster_type` char(2) NOT NULL COMMENT '投稿者種別',
  `poster_user_id` int(11) unsigned DEFAULT NULL COMMENT '投稿ユーザID',
  `poster_group_id` int(11) unsigned DEFAULT NULL COMMENT '投稿グループID',
  `poster_company_id` int(11) unsigned DEFAULT NULL COMMENT '投稿会社ID',
  `post` varchar(3072) DEFAULT NULL COMMENT '投稿',
  `auto_post` varchar(3072) DEFAULT NULL COMMENT '自動投稿',
  `posted_datetime` datetime NOT NULL COMMENT '投稿日時',
  `okr_activity_id` bigint(20) unsigned DEFAULT NULL COMMENT 'OKRアクティビティID',
  `parent_id` bigint(20) unsigned DEFAULT NULL COMMENT '親タイムラインID',
  `disclosure_type` char(2) DEFAULT NULL COMMENT '公開種別',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_post_01` (`okr_activity_id`),
  KEY `idx_post_02` (`parent_id`),
  CONSTRAINT `fk_post_okr_activity_id` FOREIGN KEY (`okr_activity_id`) REFERENCES `t_okr_activity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `t_post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投稿';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_post`
--

LOCK TABLES `t_post` WRITE;
/*!40000 ALTER TABLE `t_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_post_to`
--

DROP TABLE IF EXISTS `t_post_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_post_to` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `post_id` bigint(20) unsigned NOT NULL COMMENT '投稿ID',
  `timeline_owner_group_id` int(11) unsigned NOT NULL COMMENT 'タイムラインオーナーグループID',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `fk_post_to_post_id_idx` (`post_id`),
  CONSTRAINT `fk_post_to_post_id` FOREIGN KEY (`post_id`) REFERENCES `t_post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投稿先';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_post_to`
--

LOCK TABLES `t_post_to` WRITE;
/*!40000 ALTER TABLE `t_post_to` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_post_to` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_pre_user`
--

DROP TABLE IF EXISTS `t_pre_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_pre_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `urltoken` varchar(128) NOT NULL COMMENT 'URLトークン',
  `email_address` varchar(255) NOT NULL COMMENT 'Eメールアドレス',
  `subdomain` varchar(45) NOT NULL COMMENT 'サブドメイン',
  `company_id` int(11) unsigned DEFAULT NULL COMMENT '会社ID',
  `role_assignment_id` int(11) unsigned DEFAULT NULL COMMENT 'ロール割当ID',
  `initial_user_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '初期ユーザフラグ',
  `invalid_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '無効フラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_pre_user_01` (`urltoken`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='仮登録ユーザ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_pre_user`
--

LOCK TABLES `t_pre_user` WRITE;
/*!40000 ALTER TABLE `t_pre_user` DISABLE KEYS */;
INSERT INTO `t_pre_user` VALUES (1,'a8382e5508636c48de34b07be0fb1f22548af80182896d2d74af10a4d5137450','sampleuser1@skrum.jp','accea',NULL,NULL,1,1,'2017-08-16 17:37:37','2017-08-16 17:39:20',NULL);
/*!40000 ALTER TABLE `t_pre_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_timeframe`
--

DROP TABLE IF EXISTS `t_timeframe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_timeframe` (
  `timeframe_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'タイムフレームID',
  `company_id` int(11) unsigned NOT NULL COMMENT '会社ID',
  `timeframe_name` varchar(255) NOT NULL COMMENT 'タイムフレーム名',
  `start_date` date NOT NULL COMMENT '開始日',
  `end_date` date NOT NULL COMMENT '終了日',
  `default_flg` tinyint(1) DEFAULT NULL COMMENT 'デフォルトフラグ',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`timeframe_id`),
  KEY `idx_timeframe_01` (`company_id`),
  CONSTRAINT `fk_timeframe_company_id` FOREIGN KEY (`company_id`) REFERENCES `m_company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='タイムフレーム';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_timeframe`
--

LOCK TABLES `t_timeframe` WRITE;
/*!40000 ALTER TABLE `t_timeframe` DISABLE KEYS */;
INSERT INTO `t_timeframe` VALUES (1,1,'2017/09 - 2017/11','2017-09-01','2017-11-30',1,'2017-08-16 17:45:28','2017-08-16 17:45:28',NULL),(2,1,'2017/12 - 2018/02','2017-12-01','2018-02-28',NULL,'2017-08-16 17:45:28','2017-08-16 17:45:28',NULL),(3,1,'2018/03 - 2018/05','2018-03-01','2018-05-31',NULL,'2017-08-16 17:45:28','2017-08-16 17:45:28',NULL),(4,1,'2018/06 - 2018/08','2018-06-01','2018-08-31',NULL,'2017-08-16 17:45:28','2017-08-16 17:45:28',NULL);
/*!40000 ALTER TABLE `t_timeframe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_upload`
--

DROP TABLE IF EXISTS `t_upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'アップロードID',
  `upload_control_id` int(11) unsigned NOT NULL COMMENT 'アップロード管理ID',
  `line_number` int(11) unsigned NOT NULL COMMENT '行番号',
  `line_data` varchar(255) DEFAULT NULL COMMENT '行データ',
  `batch_execution_status` char(2) DEFAULT NULL COMMENT 'アップロード後バッチ実行ステータス',
  `result` tinyint(1) DEFAULT NULL COMMENT '実行結果',
  `message` varchar(255) DEFAULT NULL COMMENT 'メッセージ',
  `temporary_password` varchar(255) DEFAULT NULL COMMENT '仮パスワード',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_upload_01` (`upload_control_id`),
  CONSTRAINT `fk_upload_id` FOREIGN KEY (`upload_control_id`) REFERENCES `t_upload_control` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COMMENT='アップロード';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_upload`
--

LOCK TABLES `t_upload` WRITE;
/*!40000 ALTER TABLE `t_upload` DISABLE KEYS */;
INSERT INTO `t_upload` VALUES (1,1,1,'1,田中,一郎,2,部長,sampleuser101@skrum.jp,090-4324-3242,営業部門,営業部,,,','1',0,NULL,NULL,'2017-08-16 17:51:55','2017-08-16 17:52:49','2017-08-16 17:53:16'),(2,1,2,'2,田中,二郎,2,副部長,sampleuser102@skrum.jp,080-3242-4234,営業部門,営業部,,,','1',0,NULL,NULL,'2017-08-16 17:51:55','2017-08-16 17:52:50','2017-08-16 17:53:16'),(3,1,3,'3,田中,五郎,2,課長,sampleuser103@skrum.jp,090-4234-2342,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:55','2017-08-16 17:52:50','2017-08-16 17:53:16'),(4,1,4,'4,田中,健,1,社員,sampleuser104@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:55','2017-08-16 17:52:50','2017-08-16 17:53:16'),(5,1,5,'5,田中,悟,1,社員,sampleuser105@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:55','2017-08-16 17:52:51','2017-08-16 17:53:16'),(6,1,6,'6,田中,高広,1,社員,sampleuser106@skrum.jp,090-6442-7546,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:55','2017-08-16 17:52:51','2017-08-16 17:53:16'),(7,1,7,'7,田中,弘,1,社員,sampleuser107@skrum.jp,080-3264-6345,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:52','2017-08-16 17:53:16'),(8,1,8,'8,田中,純平,1,社員,sampleuser108@skrum.jp,090-2445-6666,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:52','2017-08-16 17:53:16'),(9,1,9,'9,田中,健太,1,社員,sampleuser109@skrum.jp,080-4344-6579,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:53','2017-08-16 17:53:16'),(10,1,10,'10,田中,智史,1,社員,sampleuser110@skrum.jp,090-4324-3242,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:53','2017-08-16 17:53:16'),(11,1,11,'11,佐藤,一郎,1,社員,sampleuser111@skrum.jp,080-3242-4234,営業部門,営業部,営業推進第一グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:54','2017-08-16 17:53:16'),(12,1,12,'12,佐藤,二郎,2,課長,sampleuser112@skrum.jp,090-4234-2342,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:54','2017-08-16 17:53:16'),(13,1,13,'13,佐藤,五郎,1,社員,sampleuser113@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:55','2017-08-16 17:53:16'),(14,1,14,'14,佐藤,健,1,社員,sampleuser114@skrum.jp,090-6456-6454,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:55','2017-08-16 17:53:16'),(15,1,15,'15,佐藤,悟,1,社員,sampleuser115@skrum.jp,090-6442-7546,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:55','2017-08-16 17:53:16'),(16,1,16,'16,佐藤,高広,1,社員,sampleuser116@skrum.jp,080-3264-6345,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:56','2017-08-16 17:53:16'),(17,1,17,'17,佐藤,弘,1,社員,sampleuser117@skrum.jp,090-2445-6666,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:56','2017-08-16 17:53:16'),(18,1,18,'18,佐藤,純平,1,社員,sampleuser118@skrum.jp,080-4344-6579,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:57','2017-08-16 17:53:16'),(19,1,19,'19,佐藤,健太,1,社員,sampleuser119@skrum.jp,090-4324-3242,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:57','2017-08-16 17:53:16'),(20,1,20,'20,佐藤,智史,1,社員,sampleuser120@skrum.jp,080-3242-4234,営業部門,営業部,営業推進第二グループ,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:58','2017-08-16 17:53:16'),(21,1,21,'21,高木,一郎,2,部長,sampleuser121@skrum.jp,090-4234-2342,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:58','2017-08-16 17:53:16'),(22,1,22,'22,高木,二郎,2,副部長,sampleuser122@skrum.jp,090-6456-6454,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:59','2017-08-16 17:53:16'),(23,1,23,'23,高木,五郎,2,課長,sampleuser123@skrum.jp,090-6456-6454,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:52:59','2017-08-16 17:53:16'),(24,1,24,'24,高木,健,1,社員,sampleuser124@skrum.jp,090-6442-7546,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:00','2017-08-16 17:53:16'),(25,1,25,'25,高木,悟,1,社員,sampleuser125@skrum.jp,080-3264-6345,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:00','2017-08-16 17:53:16'),(26,1,26,'26,高木,高広,1,社員,sampleuser126@skrum.jp,090-2445-6666,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:00','2017-08-16 17:53:16'),(27,1,27,'27,高木,弘,1,社員,sampleuser127@skrum.jp,080-4344-6579,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:01','2017-08-16 17:53:16'),(28,1,28,'28,高木,純平,1,社員,sampleuser128@skrum.jp,090-4324-3242,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:01','2017-08-16 17:53:16'),(29,1,29,'29,高木,健太,1,社員,sampleuser129@skrum.jp,080-3242-4234,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:02','2017-08-16 17:53:16'),(30,1,30,'30,高木,智史,1,社員,sampleuser130@skrum.jp,090-4234-2342,営業部門,営業企画部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:02','2017-08-16 17:53:16'),(31,1,31,'31,松本,一郎,2,部長,sampleuser131@skrum.jp,090-6456-6454,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:03','2017-08-16 17:53:16'),(32,1,32,'32,松本,二郎,2,副部長,sampleuser132@skrum.jp,090-6456-6454,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:03','2017-08-16 17:53:16'),(33,1,33,'33,松本,五郎,2,課長,sampleuser133@skrum.jp,090-6442-7546,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:04','2017-08-16 17:53:16'),(34,1,34,'34,松本,健,1,社員,sampleuser134@skrum.jp,080-3264-6345,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:04','2017-08-16 17:53:16'),(35,1,35,'35,松本,悟,1,社員,sampleuser135@skrum.jp,090-2445-6666,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:05','2017-08-16 17:53:16'),(36,1,36,'36,松本,高広,1,社員,sampleuser136@skrum.jp,080-4344-6579,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:05','2017-08-16 17:53:16'),(37,1,37,'37,松本,弘,1,社員,sampleuser137@skrum.jp,090-4324-3242,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:06','2017-08-16 17:53:16'),(38,1,38,'38,松本,純平,1,社員,sampleuser138@skrum.jp,080-3242-4234,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:06','2017-08-16 17:53:16'),(39,1,39,'39,松本,健太,1,社員,sampleuser139@skrum.jp,090-4234-2342,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:07','2017-08-16 17:53:16'),(40,1,40,'40,松本,智史,1,社員,sampleuser140@skrum.jp,090-6456-6454,管理部門,経理部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:07','2017-08-16 17:53:16'),(41,1,41,'41,山田,一郎,2,部長,sampleuser141@skrum.jp,090-6456-6454,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:08','2017-08-16 17:53:16'),(42,1,42,'42,山田,二郎,2,副部長,sampleuser142@skrum.jp,090-6442-7546,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:08','2017-08-16 17:53:16'),(43,1,43,'43,山田,五郎,2,課長,sampleuser143@skrum.jp,080-3264-6345,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:09','2017-08-16 17:53:16'),(44,1,44,'44,山田,健,1,社員,sampleuser144@skrum.jp,090-2445-6666,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:09','2017-08-16 17:53:16'),(45,1,45,'45,山田,悟,1,社員,sampleuser145@skrum.jp,080-4344-6579,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:10','2017-08-16 17:53:16'),(46,1,46,'46,山田,高広,1,社員,sampleuser146@skrum.jp,090-4324-3242,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:10','2017-08-16 17:53:16'),(47,1,47,'47,山田,弘,1,社員,sampleuser147@skrum.jp,080-3242-4234,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:11','2017-08-16 17:53:16'),(48,1,48,'48,山田,純平,1,社員,sampleuser148@skrum.jp,090-4234-2342,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:11','2017-08-16 17:53:16'),(49,1,49,'49,山田,健太,1,社員,sampleuser149@skrum.jp,090-6456-6454,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:12','2017-08-16 17:53:16'),(50,1,50,'50,山田,智史,1,社員,sampleuser150@skrum.jp,090-6456-6454,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:12','2017-08-16 17:53:16'),(51,1,51,'51,岡山,一郎,2,課長,sampleuser151@skrum.jp,090-6442-7546,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:13','2017-08-16 17:53:16'),(52,1,52,'52,岡山,二郎,1,社員,sampleuser152@skrum.jp,080-3264-6345,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:13','2017-08-16 17:53:16'),(53,1,53,'53,岡山,五郎,1,社員,sampleuser153@skrum.jp,090-2445-6666,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:14','2017-08-16 17:53:16'),(54,1,54,'54,岡山,健,1,社員,sampleuser154@skrum.jp,080-4344-6579,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:14','2017-08-16 17:53:16'),(55,1,55,'55,岡山,悟,1,社員,sampleuser155@skrum.jp,090-4324-3242,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:15','2017-08-16 17:53:16'),(56,1,56,'56,岡山,高広,1,社員,sampleuser156@skrum.jp,080-3242-4234,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:15','2017-08-16 17:53:16'),(57,1,57,'57,岡山,弘,1,社員,sampleuser157@skrum.jp,090-4234-2342,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:16','2017-08-16 17:53:16'),(58,1,58,'58,岡山,純平,1,社員,sampleuser158@skrum.jp,090-6456-6454,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:16','2017-08-16 17:53:16'),(59,1,59,'59,岡山,健太,1,社員,sampleuser159@skrum.jp,090-6456-6454,管理部門,人事部,,,','1',0,NULL,NULL,'2017-08-16 17:51:56','2017-08-16 17:53:17','2017-08-16 17:53:16');
/*!40000 ALTER TABLE `t_upload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_upload_control`
--

DROP TABLE IF EXISTS `t_upload_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_upload_control` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'アップロード管理ID',
  `company_id` int(11) unsigned NOT NULL COMMENT '会社ID',
  `upload_type` char(2) NOT NULL COMMENT 'アップロード種別',
  `count` int(11) unsigned NOT NULL COMMENT '件数',
  `upload_user_id` int(11) unsigned NOT NULL COMMENT 'アップロード登録者',
  `created_at` datetime DEFAULT NULL COMMENT '登録日時',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted_at` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `idx_upload_control_01` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='アップロード管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_upload_control`
--

LOCK TABLES `t_upload_control` WRITE;
/*!40000 ALTER TABLE `t_upload_control` DISABLE KEYS */;
INSERT INTO `t_upload_control` VALUES (1,1,'1',59,1,'2017-08-16 17:51:55','2017-08-16 17:51:55','2017-08-16 17:53:18');
/*!40000 ALTER TABLE `t_upload_control` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-09-23 16:16:23
