-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: fooddusit_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admissions`
--

DROP TABLE IF EXISTS `admissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `program_id` int(10) unsigned DEFAULT NULL,
  `round_name` varchar(150) DEFAULT NULL COMMENT 'รอบการรับสมัคร',
  `content` longtext DEFAULT NULL,
  `open_date` date DEFAULT NULL,
  `close_date` date DEFAULT NULL,
  `apply_url` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_adm_program` (`program_id`),
  CONSTRAINT `fk_adm_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admissions`
--

LOCK TABLES `admissions` WRITE;
/*!40000 ALTER TABLE `admissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni`
--

DROP TABLE IF EXISTS `alumni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(200) NOT NULL,
  `graduate_year` varchar(10) DEFAULT NULL,
  `program` varchar(200) DEFAULT NULL,
  `current_job` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `quote` text DEFAULT NULL COMMENT 'คำบอกเล่า',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni`
--

LOCK TABLES `alumni` WRITE;
/*!40000 ALTER TABLE `alumni` DISABLE KEYS */;
INSERT INTO `alumni` VALUES (1,'ศิษย์เก่าดีเด่น 1','2560','เทคโนโลยีการประกอบอาหาร','Executive Chef','โรงแรมระดับ 5 ดาว',NULL,'โรงเรียนการเรือนให้ทั้งความรู้และโอกาส',1,0,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'ศิษย์เก่าดีเด่น 2','2562','คหกรรมศาสตร์','เจ้าของกิจการ','ร้านเบเกอรีชื่อดัง',NULL,'ทุกวันนี้ประสบความสำเร็จได้เพราะพื้นฐานที่แข็งแรง',1,0,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `alumni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_audit_user` (`user_id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,3,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 16:43:58'),(2,3,'logout','auth','ออกจากระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 16:50:23'),(3,3,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 16:51:30'),(4,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 16:54:25'),(5,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 16:55:01'),(6,1,'create','staff','เพิ่มบุคลากร','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 16:55:01'),(7,3,'logout','auth','ออกจากระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 16:56:37'),(8,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 16:56:44'),(9,1,'logout','auth','ออกจากระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 17:14:34'),(10,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 17:44:44'),(11,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 22:11:57'),(12,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 22:26:46'),(13,1,'create','deans','เพิ่มคณบดี','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 22:26:46'),(14,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 22:27:14'),(15,1,'create','deans','เพิ่มคณบดี','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22 22:27:14'),(16,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23 11:34:59'),(17,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-24 12:54:03'),(18,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 15:59:42'),(19,1,'create','staff','เพิ่มบุคลากร','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 16:16:21'),(20,1,'update','staff','แก้ไขบุคลากร #5','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 16:16:32'),(21,1,'delete','staff','ลบบุคลากร #5','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 16:17:14'),(22,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 23:02:40'),(23,3,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 08:46:34'),(24,3,'logout','auth','ออกจากระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 08:49:15'),(25,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 08:49:28'),(26,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 23:11:09'),(27,1,'create','deans','เพิ่มคณบดี','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 23:11:54'),(28,1,'delete','deans','ลบคณบดี #5','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 23:12:42'),(29,1,'create','news','เพิ่มข่าว #5: วัว','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 23:13:25'),(30,1,'delete','news','ลบข่าว #5','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 23:13:44'),(31,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-03 15:16:59'),(32,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04 14:37:47'),(33,1,'login','auth','เข้าสู่ระบบ','::1','curl/8.16.0','2026-08-04 16:41:05'),(34,1,'create','school_committee','เพิ่มกรรมการ ????? ????????????','::1','curl/8.16.0','2026-08-04 16:41:50'),(35,1,'delete','school_committee','ลบกรรมการ #17','::1','curl/8.16.0','2026-08-04 16:41:50'),(36,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04 23:02:34'),(37,1,'login','auth','เข้าสู่ระบบ','::1','curl/8.16.0','2026-08-04 23:12:29'),(38,1,'delete','staff','ลบบุคลากร #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04 23:31:07'),(39,1,'delete','staff','ลบบุคลากร #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04 23:31:10'),(40,1,'login','auth','เข้าสู่ระบบ','::1','curl/8.16.0','2026-08-04 23:51:02'),(41,1,'login','auth','เข้าสู่ระบบ','::1','curl/8.16.0','2026-08-04 23:54:00'),(42,1,'login','auth','เข้าสู่ระบบ','::1','curl/8.16.0','2026-08-05 00:01:42'),(43,1,'login','auth','เข้าสู่ระบบ','::1','curl/8.16.0','2026-08-05 00:12:53'),(44,1,'create','documents','เพิ่มเอกสาร TestUpload','::1','curl/8.16.0','2026-08-05 00:14:42'),(45,1,'delete','documents','ลบเอกสาร #1','::1','curl/8.16.0','2026-08-05 00:16:51'),(46,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 01:11:52'),(47,1,'create','documents','เพิ่มเอกสาร TestBigUpload15MB','::1','curl/8.16.0','2026-08-05 01:14:39'),(48,1,'delete','documents','ลบเอกสาร #2','::1','curl/8.16.0','2026-08-05 01:14:51'),(49,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:24:47'),(50,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:25:21'),(51,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:25:45'),(52,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:26:02'),(53,1,'create','documents','เพิ่มเอกสาร __TEST__ pdf.js cover auto-gen QA','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:26:04'),(54,1,'create','documents','เพิ่มเอกสาร ตัวอย่าง','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 01:30:05'),(55,1,'create','documents','เพิ่มเอกสาร ตัวอย่างสอง','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 01:33:59'),(56,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:37:09'),(57,1,'create','documents','เพิ่มเอกสาร __TEST__ newsletter QA','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:37:12'),(58,1,'create','documents','เพิ่มเอกสาร เทส','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 01:40:12'),(59,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:42:16'),(60,1,'create','documents','เพิ่มเอกสาร เทส','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 01:43:37'),(61,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 01:46:53'),(62,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 08:33:28'),(63,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 08:39:34'),(64,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 09:16:06'),(65,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 12:01:19'),(66,1,'update','deans','แก้ไขคณบดี #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 12:01:21'),(67,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 12:01:44'),(68,1,'update','deans','แก้ไขคณบดี #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 12:01:46'),(69,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05 12:04:18'),(70,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 12:09:36'),(71,1,'update','school_committee','แก้ไขกรรมการ #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05 12:09:38'),(72,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 11:52:11'),(73,1,'update','gallery','แก้ไขอัลบั้ม #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 11:53:16'),(74,1,'update','gallery','แก้ไขอัลบั้ม #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 11:56:23'),(75,1,'login','auth','เข้าสู่ระบบ','::1','','2026-08-06 12:02:47'),(76,1,'create','faq','เพิ่มคำถาม','::1','','2026-08-06 12:02:48'),(77,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:03:59'),(78,1,'create','faq','เพิ่มคำถาม','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:04:01'),(79,1,'update','gallery','แก้ไขอัลบั้ม #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:06:26'),(80,1,'update','gallery','แก้ไขอัลบั้ม #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:06:57'),(81,1,'update','gallery','แก้ไขอัลบั้ม #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:07:14'),(82,1,'update','gallery','แก้ไขอัลบั้ม #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:08:02'),(83,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:14:40'),(84,1,'create','deans','เพิ่มคณบดี','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:14:42'),(85,1,'update','gallery','แก้ไขอัลบั้ม #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:15:23'),(86,1,'update','gallery','แก้ไขอัลบั้ม #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:19:06'),(87,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:22:44'),(88,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:23:42'),(89,1,'create','gallery','เพิ่มรูป 1 รายการในอัลบั้ม #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:23:44'),(90,1,'update','gallery','แก้ไขคำอธิบายรูป #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06 12:23:57'),(91,1,'create','gallery','เพิ่มรูป 1 รายการในอัลบั้ม #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:33:26'),(92,1,'update','gallery','แก้ไขอัลบั้ม #2','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:34:04'),(93,1,'update','gallery','แก้ไขอัลบั้ม #1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:34:16'),(94,1,'update','settings','แก้ไขการตั้งค่าเว็บไซต์','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 12:43:48'),(95,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 21:36:25'),(96,1,'logout','auth','ออกจากระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06 21:36:37'),(97,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07 11:33:29'),(98,1,'logout','auth','ออกจากระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07 12:39:24'),(99,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07 12:39:34'),(100,1,'create','gallery','เพิ่มอัลบั้ม #3','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07 12:40:19'),(101,1,'delete','gallery','ลบอัลบั้ม #3','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07 12:40:40'),(102,1,'login','auth','เข้าสู่ระบบ','::1','','2026-08-07 12:46:11'),(103,1,'create','gallery','เพิ่มอัลบั้ม #6','::1','','2026-08-07 12:46:12'),(104,1,'login','auth','เข้าสู่ระบบ','::1','','2026-08-07 12:46:53'),(105,1,'create','gallery','เพิ่มอัลบั้ม #7','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07 12:55:12'),(106,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-17 13:37:52'),(107,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 13:10:51'),(108,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18 14:34:54'),(109,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-08-31 20:16:32'),(110,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 11:34:59'),(111,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 12:46:58'),(112,1,'create','banner','เพิ่มแบนเนอร์','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 12:48:54'),(113,1,'delete','banner','ลบแบนเนอร์ #3','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 12:49:12'),(114,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 13:08:44'),(115,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 14:41:57'),(116,1,'create','banner','เพิ่มแบนเนอร์','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01 14:42:15'),(117,1,'login','auth','เข้าสู่ระบบ','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','2026-09-15 14:14:40');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(300) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner`
--

LOCK TABLES `banner` WRITE;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
INSERT INTO `banner` VALUES (1,'โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต','ผู้นำด้านศาสตร์การประกอบอาหารและการบริการของประเทศไทย','banner/hero-1.svg','programs','ดูหลักสูตรทั้งหมด',1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'เปิดรับสมัครนักศึกษาใหม่ 2569','เรียนกับเชฟมืออาชีพในครัวมาตรฐานสากล','banner/hero-2.svg','news/detail/open-admission-2569','สมัครเรียนเลย',2,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,'','','banner/20260901_144215_ee4bd971bd99.jpg','','',0,'active','2026-09-01 14:42:15','2026-09-01 14:42:15',NULL);
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_contact_read` (`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (2,'test','test@gmail.com','test','test','testtesttest','::1',1,'active','2026-08-05 01:48:04','2026-08-05 01:48:04',NULL),(3,'d','test@gmail.com','d','d','ddddddddddddd','::1',1,'active','2026-08-17 13:37:38','2026-08-17 13:37:38',NULL);
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curriculum`
--

DROP TABLE IF EXISTS `curriculum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `curriculum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` int(10) unsigned NOT NULL,
  `year` varchar(20) DEFAULT NULL COMMENT 'ชั้นปี',
  `semester` varchar(20) DEFAULT NULL COMMENT 'ภาคเรียน',
  `course_code` varchar(30) DEFAULT NULL,
  `course_name` varchar(255) NOT NULL,
  `credits` varchar(20) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_curri_program` (`program_id`),
  CONSTRAINT `fk_curri_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curriculum`
--

LOCK TABLES `curriculum` WRITE;
/*!40000 ALTER TABLE `curriculum` DISABLE KEYS */;
/*!40000 ALTER TABLE `curriculum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deans`
--

DROP TABLE IF EXISTS `deans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) NOT NULL COMMENT 'ชื่อ-นามสกุล พร้อมคำนำหน้า',
  `position` varchar(100) NOT NULL DEFAULT 'คณบดี',
  `term` varchar(191) DEFAULT NULL COMMENT 'ช่วงเวลาการดำรงตำแหน่ง',
  `bio` text DEFAULT NULL COMMENT '???????/?????',
  `photo` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deans`
--

LOCK TABLES `deans` WRITE;
/*!40000 ALTER TABLE `deans` DISABLE KEYS */;
INSERT INTO `deans` VALUES (1,'ผศ.ดร.กนกกานต์ วีระกุล','คณบดี','30 เมษายน 2553 – 25 มิถุนายน 2562',NULL,'deans/dean-1.png',1,'active','2026-07-22 22:21:22','2026-07-22 22:21:22',NULL),(2,'ผศ. ว่าที่ ร.ต. ดร.ธนพัฒน์ แสงรุ่งเรือง','คณบดี','28 มิถุนายน 2562 – ปัจจุบัน',NULL,'deans/dean-2.png',2,'active','2026-07-22 22:21:22','2026-08-05 12:01:46',NULL);
/*!40000 ALTER TABLE `deans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `description` text DEFAULT NULL,
  `head_name` varchar(150) DEFAULT NULL COMMENT 'หัวหน้าหน่วยงาน',
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dept_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'สาขาวิชาเทคโนโลยีการประกอบอาหารและการบริการ','culinary-tech','หลักสูตรด้านการประกอบอาหารและการบริการมืออาชีพ','ผศ.ดร. ก. ครัวทอง',NULL,1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'สาขาวิชาคหกรรมศาสตร์','home-economics','ศาสตร์แห่งการจัดการครัวเรือนและงานประดิษฐ์',' ผศ. ข. เรือนไทย',NULL,2,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,'สาขาวิชาโภชนาการและการประกอบอาหารเพื่อการส่งเสริมสมรรถภาพและการชะลอวัย','nutrition','โภชนาการเชิงสุขภาพและการชะลอวัย','ดร. ค. โภชนา',NULL,3,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL COMMENT 'รูปหน้าปก/ภาพตัวอย่างของเอกสาร',
  `doc_number` varchar(100) DEFAULT NULL,
  `category` varchar(150) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `doc_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (4,'ตัวอย่าง','documents/20260805_013005_0ebc23402da7.png','','รายงานผลการดำเนินงาน','documents/20260805_013005_c3a4e74fa1df.pdf',NULL,'active','2026-08-05 01:30:05','2026-08-05 01:30:05',NULL),(5,'ตัวอย่างสอง','documents/20260805_013359_05d40f2b3c16.png','','รายงานผลการดำเนินงาน','documents/20260805_013359_a95baa7f5fa4.pdf',NULL,'active','2026-08-05 01:33:59','2026-08-05 01:33:59',NULL),(7,'เทส','documents/20260805_014012_2bffb012da05.png','','จดหมายข่าว','documents/20260805_014012_4aba0f4fd71c.pdf',NULL,'active','2026-08-05 01:40:12','2026-08-05 01:40:12',NULL),(8,'เทส','documents/20260805_014337_f3bcc137efc3.png','','การจัดการความรู้ (KM)','documents/20260805_014337_e37e05c11423.pdf',NULL,'active','2026-08-05 01:43:37','2026-08-05 01:43:37',NULL);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(20) DEFAULT NULL,
  `file_size` int(10) unsigned DEFAULT NULL COMMENT 'ขนาดไบต์',
  `download_count` int(10) unsigned NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_download_cat` (`category`),
  FULLTEXT KEY `ft_download` (`title`,`description`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloads`
--

LOCK TABLES `downloads` WRITE;
/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
INSERT INTO `downloads` VALUES (1,'ใบสมัครเข้าศึกษา ปีการศึกษา 2569','แบบฟอร์มใบสมัครสำหรับผู้สนใจเข้าศึกษา','ใบสมัคร','downloads/sample-application.pdf','pdf',102400,58,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'คู่มือนักศึกษาใหม่','ข้อมูลที่นักศึกษาใหม่ควรทราบ','คู่มือ','downloads/sample-handbook.pdf','pdf',204800,120,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_registrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `organization` varchar(200) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` enum('confirmed','pending','cancelled','inactive') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reg_event` (`event_id`),
  CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_registrations`
--

LOCK TABLES `event_registrations` WRITE;
/*!40000 ALTER TABLE `event_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `description` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `max_attendees` int(10) unsigned DEFAULT NULL COMMENT 'จำนวนรับสูงสุด (NULL = ไม่จำกัด)',
  `allow_register` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_event_slug` (`slug`),
  KEY `idx_event_start` (`start_date`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'เทศกาลอาหารนานาชาติ Suan Dusit Food Fair','food-fair-2569','งานแสดงและจำหน่ายอาหารจากฝีมือนักศึกษาและคณาจารย์',NULL,'อาคารโรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต','2026-08-20 09:00:00','2026-08-22 17:00:00',NULL,1,1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'เวิร์กช็อปการแกะสลักผักและผลไม้','carving-workshop','เรียนรู้ศิลปะการแกะสลักผักและผลไม้แบบไทย',NULL,'ห้องปฏิบัติการคหกรรม','2026-09-05 13:00:00','2026-09-05 16:00:00',NULL,1,0,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,'สัมมนาวิชาการด้านโภชนาการเพื่อสุขภาพ','nutrition-seminar','สัมมนาโดยผู้เชี่ยวชาญด้านโภชนาการระดับประเทศ',NULL,'หอประชุมมหาวิทยาลัยสวนดุสิต','2026-10-10 08:30:00','2026-10-10 16:30:00',NULL,1,0,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_faq` (`question`,`answer`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'เปิดรับสมัครนักศึกษาช่วงไหน?','โดยทั่วไปเปิดรับสมัครช่วงเดือนพฤศจิกายนถึงพฤษภาคมของทุกปี โปรดติดตามประกาศจากทางเว็บไซต์','การรับสมัคร',1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'มีหอพักสำหรับนักศึกษาหรือไม่?','มหาวิทยาลัยมีหอพักและมีเครือข่ายหอพักเอกชนใกล้เคียงให้เลือก','ทั่วไป',2,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,'เรียนจบแล้วประกอบอาชีพอะไรได้บ้าง?','เชฟ ผู้ประกอบการร้านอาหาร นักโภชนาการ นักพัฒนาผลิตภัณฑ์อาหาร และอื่น ๆ อีกมากมาย','หลักสูตร',3,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `file_path` varchar(255) DEFAULT NULL COMMENT 'พาธรูป',
  `video_url` varchar(255) DEFAULT NULL COMMENT 'ลิงก์วิดีโอ (YouTube)',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_album` (`album_id`),
  CONSTRAINT `fk_gallery_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (2,2,'เทส','image','gallery/20260806_123326_7b9f016ef960.jpg',NULL,0,'active','2026-08-06 12:33:26','2026-08-06 12:33:26',NULL);
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_album_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_albums`
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES (1,'ตัวอย่าง2','ตวอยาง2','บรรยากาศพิธีรับปริญญาบัตร','gallery/20260806_121523_70d09b9899fd.jpg','2025-11-15','active','2026-07-15 19:38:42','2026-08-06 12:34:16',NULL),(2,'ตัวอย่าง1','ตวอยาง1','ภาพการแข่งขันทำอาหารของนักศึกษา','gallery/20260806_121906_34644b7b8edd.jpg','2026-02-14','active','2026-07-15 19:38:42','2026-08-06 12:34:04',NULL),(3,'test','test','','gallery/20260807_124019_495377a9b6c5.jpg',NULL,'inactive','2026-08-07 12:40:19','2026-08-07 12:40:19','2026-08-07 12:40:40'),(7,'test','test-2','','gallery/20260807_125512_2a985d760ae5.jpg',NULL,'active','2026-08-07 12:55:12','2026-08-07 12:55:12',NULL);
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `position` enum('header','footer') NOT NULL DEFAULT 'header',
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_menu_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,NULL,'หน้าแรก','',NULL,'header','_self',1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,NULL,'เกี่ยวกับเรา','about',NULL,'header','_self',2,'inactive','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,NULL,'หลักสูตร','curriculum',NULL,'header','_self',3,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,NULL,'ข่าวสาร','https://tcas.dusit.ac.th/',NULL,'header','_blank',4,'inactive','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(5,NULL,'กิจกรรม','journal',NULL,'header','_self',5,'inactive','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(6,NULL,'แกลเลอรี','https://wbsc.dusit.ac.th/my/',NULL,'header','_blank',6,'inactive','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(7,NULL,'ดาวน์โหลด','https://repository.dusit.ac.th/home',NULL,'header','_blank',7,'inactive','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(8,NULL,'ติดต่อเรา','contact',NULL,'header','_self',8,'inactive','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(13,NULL,'การรับสมัครเรียน','https://tcas.dusit.ac.th/',NULL,'header','_blank',4,'active','2026-07-22 18:02:19',NULL,NULL),(14,NULL,'วารสารวัฒนธรรมอาหารไทย','journal',NULL,'header','_self',5,'active','2026-07-22 18:02:19',NULL,NULL),(15,NULL,'WBSC-LMS','https://wbsc.dusit.ac.th/my/',NULL,'header','_blank',6,'active','2026-07-22 18:02:19',NULL,NULL),(16,NULL,'คลังข้อมูลดิจิทัล','https://repository.dusit.ac.th/home',NULL,'header','_blank',7,'active','2026-07-22 18:02:19',NULL,NULL),(17,NULL,'รอบรั้วโรงเรียนการเรือน','#',NULL,'header','_self',2,'active','2026-07-22 18:19:54',NULL,NULL),(18,17,'ประวัติ วิสัยทัศน์ พันธกิจ','history',NULL,'header','_self',1,'active','2026-07-22 18:19:54',NULL,NULL),(19,17,'SDU Goal : 2568-2571 | The Power of SDU','sdugoal',NULL,'header','_self',2,'active','2026-07-22 18:19:54',NULL,NULL),(20,17,'โครงสร้างการบริหารงาน','orgstructure',NULL,'header','_blank',3,'active','2026-07-22 18:19:54',NULL,NULL),(21,17,'ทำเนียบคณบดี','deans',NULL,'header','_blank',4,'active','2026-07-22 18:19:54',NULL,NULL),(22,17,'คณะผู้บริหารและบุคลากร','#',NULL,'header','_self',5,'active','2026-07-22 18:19:54',NULL,NULL),(23,17,'รายงานผลการดำเนินงาน','#',NULL,'header','_self',6,'active','2026-07-22 18:19:54',NULL,NULL),(24,3,'วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ','curriculum/bsc-culinary-tech',NULL,'header','_self',1,'active','2026-07-22 18:25:26',NULL,NULL),(25,3,'สาขาวิชาเทคโนโลยีการประกอบอาหารและการบริการ','curriculum/culinary-tech-service',NULL,'header','_self',2,'active','2026-07-22 18:25:26',NULL,NULL),(26,3,'สาขาวิชาคหกรรมศาสตร์','curriculum/home-economics',NULL,'header','_self',3,'active','2026-07-22 18:25:26',NULL,NULL),(27,3,'สาขาวิชานวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร (หลักสูตรปรับปรุง พ.ศ. 2568)','curriculum/food-innovation-2568',NULL,'header','_self',4,'active','2026-07-22 18:25:26',NULL,NULL),(28,3,'สาขาวิชาโภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย (หลักสูตรปรับปรุง พ.ศ. 2568)','curriculum/nutrition-health-2568',NULL,'header','_self',5,'active','2026-07-22 18:25:26',NULL,NULL),(29,3,'สาขาวิชาอุตสาหกรรมการประกอบอาหาร','curriculum/food-industry',NULL,'header','_self',6,'active','2026-07-22 18:25:26',NULL,NULL),(30,3,'สาขาวิชาโภชนาการและการประกอบอาหารเพื่อการสร้างเสริมสมรรถภาพและการชะลอวัย','curriculum/nutrition-fitness',NULL,'header','_self',7,'active','2026-07-22 18:25:26',NULL,NULL),(31,3,'สาขาวิชาการจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร (หลักสูตรพัฒนาใหม่ พ.ศ. 2569)','curriculum/food-business-culinary-arts-2569',NULL,'header','_self',8,'active','2026-07-22 18:25:26',NULL,NULL),(32,22,'คณะกรรมการประจำโรงเรียนการเรือน','info/school-committee',NULL,'header','_self',1,'active','2026-07-22 22:36:53',NULL,NULL),(33,22,'บุคลากรสายวิชาการ','info/staff-academic',NULL,'header','_self',2,'active','2026-07-22 22:36:53',NULL,NULL),(34,22,'บุคลากรสายสนับสนุน','info/staff-support',NULL,'header','_self',3,'active','2026-07-22 22:36:53',NULL,NULL),(35,23,'รายงานผลการดำเนินงาน','info/annual-report',NULL,'header','_self',1,'active','2026-07-22 22:39:51',NULL,NULL),(36,23,'จดหมายข่าว','info/newsletter',NULL,'header','_self',2,'active','2026-07-22 22:39:51',NULL,NULL),(37,23,'การจัดการความรู้ (KM)','info/km',NULL,'header','_self',3,'active','2026-07-22 22:39:51',NULL,NULL),(38,NULL,'ศิษย์เก่า','alumni',NULL,'header','_self',8,'inactive','2026-07-24 12:53:38',NULL,NULL);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'ผู้เขียน',
  `title` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL COMMENT 'สรุปย่อ',
  `content` longtext NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL COMMENT 'คั่นด้วยจุลภาค',
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `allow_comment` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('published','draft','pending','inactive') NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_news_slug` (`slug`),
  KEY `idx_news_cat` (`category_id`),
  KEY `idx_news_status` (`status`),
  KEY `idx_news_featured` (`is_featured`),
  KEY `fk_news_user` (`user_id`),
  FULLTEXT KEY `ft_news` (`title`,`excerpt`,`content`),
  CONSTRAINT `fk_news_cat` FOREIGN KEY (`category_id`) REFERENCES `news_category` (`id`),
  CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,2,3,'เปิดรับสมัครนักศึกษาใหม่ ปีการศึกษา 2569','open-admission-2569','โรงเรียนการเรือน เปิดรับสมัครนักศึกษาใหม่ระดับปริญญาตรี ประจำปีการศึกษา 2569 แล้ววันนี้','<p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต เปิดรับสมัครนักศึกษาใหม่ระดับปริญญาตรี ประจำปีการศึกษา 2569 ในหลากหลายสาขาวิชา ผู้สนใจสามารถสมัครได้ทางระบบออนไลน์ตั้งแต่บัดนี้เป็นต้นไป</p><p>สอบถามข้อมูลเพิ่มเติมได้ที่สำนักงานโรงเรียนการเรือน</p>',NULL,'รับสมัคร,ปริญญาตรี,2569',246,1,1,NULL,NULL,'2026-07-15 19:38:42','published','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,3,3,'นักศึกษาคว้ารางวัลชนะเลิศการแข่งขันทำอาหารระดับประเทศ','win-cooking-contest','ขอแสดงความยินดีกับนักศึกษาที่คว้ารางวัลชนะเลิศจากการแข่งขันทำอาหารระดับประเทศ','<p>นักศึกษาโรงเรียนการเรือน สร้างชื่อเสียงคว้ารางวัลชนะเลิศจากการแข่งขันทำอาหารระดับประเทศ ตอกย้ำคุณภาพการเรียนการสอนด้านการประกอบอาหารระดับแนวหน้าของประเทศ</p>',NULL,'รางวัล,แข่งขัน,ทำอาหาร',182,1,1,NULL,NULL,'2026-07-15 19:38:42','published','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,1,3,'โครงการอบรมระยะสั้น หลักสูตรเบเกอรีมืออาชีพ','short-course-bakery','เปิดอบรมหลักสูตรเบเกอรีสำหรับบุคคลทั่วไป เรียนกับเชฟมืออาชีพ','<p>โรงเรียนการเรือน จัดโครงการอบรมระยะสั้น หลักสูตรเบเกอรีมืออาชีพ สำหรับบุคคลทั่วไปที่สนใจ เรียนกับคณาจารย์และเชฟมืออาชีพ พร้อมรับใบประกาศนียบัตร</p>',NULL,'อบรม,เบเกอรี,ระยะสั้น',95,0,1,NULL,NULL,'2026-07-15 19:38:42','published','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,1,3,'พิธีลงนามความร่วมมือทางวิชาการกับโรงแรมชั้นนำ','mou-hotel','โรงเรียนการเรือนลงนาม MOU กับโรงแรมชั้นนำเพื่อพัฒนานักศึกษาสู่มืออาชีพ','<p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต ลงนามบันทึกข้อตกลงความร่วมมือทางวิชาการกับโรงแรมชั้นนำ เพื่อเปิดโอกาสให้นักศึกษาได้ฝึกประสบการณ์วิชาชีพในสถานประกอบการจริง</p>',NULL,'MOU,ความร่วมมือ',78,0,1,NULL,NULL,'2026-07-15 19:38:42','published','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(5,3,1,'วัว','วว','','วัว ทาม ทาม','news/20260726_231325_4248be7f65de.jpg','',0,0,1,'','','2026-07-26 23:13:25','inactive','2026-07-26 23:13:25','2026-07-26 23:13:25','2026-07-26 23:13:44');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_category`
--

DROP TABLE IF EXISTS `news_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_newscat_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_category`
--

LOCK TABLES `news_category` WRITE;
/*!40000 ALTER TABLE `news_category` DISABLE KEYS */;
INSERT INTO `news_category` VALUES (1,'ข่าวประชาสัมพันธ์','announcement','ข่าวสารทั่วไปของโรงเรียนการเรือน',1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'ข่าวรับสมัคร','admission','ข่าวการรับสมัครนักศึกษาและอบรม',2,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,'ข่าวกิจกรรม','activity','ภาพและข่าวกิจกรรมต่าง ๆ',3,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,'ข่าวรางวัลและความภาคภูมิใจ','award','รางวัลของนักศึกษาและบุคลากร',4,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `news_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_comments`
--

DROP TABLE IF EXISTS `news_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `comment` text NOT NULL,
  `status` enum('approved','pending','inactive') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_comment_news` (`news_id`),
  CONSTRAINT `fk_comment_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_comments`
--

LOCK TABLES `news_comments` WRITE;
/*!40000 ALTER TABLE `news_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subscriber_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `content` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `template` varchar(50) DEFAULT 'default',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `status` enum('published','draft','inactive') NOT NULL DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_slug` (`slug`),
  FULLTEXT KEY `ft_page` (`title`,`content`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'ประวัติความเป็นมา','history','<p>โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต มีรากฐานยาวนานด้านการเรียนการสอนศาสตร์การประกอบอาหารและคหกรรมศาสตร์ เป็นที่ยอมรับในระดับประเทศและนานาชาติ</p>',NULL,'default',NULL,NULL,'published','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'วิสัยทัศน์และพันธกิจ','vision-mission','<h3>วิสัยทัศน์</h3><p>เป็นผู้นำด้านศาสตร์การประกอบอาหารและการบริการของอาเซียน</p><h3>พันธกิจ</h3><ul><li>ผลิตบัณฑิตคุณภาพสูง</li><li>สร้างงานวิจัยและนวัตกรรมด้านอาหาร</li><li>บริการวิชาการสู่สังคม</li></ul>',NULL,'default',NULL,NULL,'published','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'โรงแรมพันธมิตร',NULL,'#',1,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'สมาคมเชฟแห่งประเทศไทย',NULL,'#',2,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,'บริษัทอาหารชั้นนำ',NULL,'#',3,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'ชื่อสิทธิ์ เช่น เพิ่มข่าว',
  `slug` varchar(100) NOT NULL COMMENT 'รหัสสิทธิ์ เช่น news.create',
  `module` varchar(50) NOT NULL COMMENT 'โมดูล เช่น news',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permissions_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'ดูข่าว','news.view','news','active','2026-07-15 19:38:42',NULL,NULL),(2,'เพิ่มข่าว','news.create','news','active','2026-07-15 19:38:42',NULL,NULL),(3,'แก้ไขข่าว','news.edit','news','active','2026-07-15 19:38:42',NULL,NULL),(4,'ลบข่าว','news.delete','news','active','2026-07-15 19:38:42',NULL,NULL),(5,'จัดการหลักสูตร','programs.manage','programs','active','2026-07-15 19:38:42',NULL,NULL),(6,'จัดการกิจกรรม','events.manage','events','active','2026-07-15 19:38:42',NULL,NULL),(7,'จัดการแกลเลอรี','gallery.manage','gallery','active','2026-07-15 19:38:42',NULL,NULL),(8,'จัดการดาวน์โหลด','downloads.manage','downloads','active','2026-07-15 19:38:42',NULL,NULL),(9,'จัดการแบนเนอร์','banner.manage','banner','active','2026-07-15 19:38:42',NULL,NULL),(10,'จัดการหน้าเนื้อหา','pages.manage','pages','active','2026-07-15 19:38:42',NULL,NULL),(11,'จัดการผู้ใช้','users.manage','users','active','2026-07-15 19:38:42',NULL,NULL),(12,'จัดการสิทธิ์','roles.manage','roles','active','2026-07-15 19:38:42',NULL,NULL),(13,'จัดการตั้งค่า','settings.manage','settings','active','2026-07-15 19:38:42',NULL,NULL),(14,'ดูข้อความติดต่อ','contact.view','contact','active','2026-07-15 19:38:42',NULL,NULL),(15,'?????????????/???????','staff.manage','staff','active','2026-07-22 16:49:54',NULL,NULL),(16,'จัดการคณะกรรมการ','committee.manage','staff','active','2026-08-04 16:33:33',NULL,NULL),(17,'จัดการเอกสาร/รายงาน','documents.manage','pages','active','2026-08-04 23:50:13',NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(280) NOT NULL,
  `level` enum('bachelor','master','doctoral','training') NOT NULL DEFAULT 'bachelor' COMMENT 'ระดับ: ตรี/โท/เอก/อบรม',
  `degree` varchar(200) DEFAULT NULL COMMENT 'ชื่อปริญญา',
  `duration` varchar(100) DEFAULT NULL COMMENT 'ระยะเวลาเรียน',
  `credits` varchar(50) DEFAULT NULL COMMENT 'หน่วยกิต',
  `tuition_fee` varchar(150) DEFAULT NULL COMMENT 'ค่าใช้จ่าย',
  `overview` text DEFAULT NULL COMMENT 'ภาพรวมหลักสูตร',
  `qualification` text DEFAULT NULL COMMENT 'คุณสมบัติผู้สมัคร',
  `careers` text DEFAULT NULL COMMENT 'อาชีพหลังจบ',
  `content` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL COMMENT 'ไฟล์รายละเอียดหลักสูตร',
  `apply_url` varchar(255) DEFAULT NULL COMMENT 'ลิงก์สมัครเรียน',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_program_slug` (`slug`),
  KEY `idx_program_level` (`level`),
  KEY `idx_program_dept` (`department_id`),
  CONSTRAINT `fk_program_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES (1,1,'เทคโนโลยีการประกอบอาหารและการบริการ','Culinary Technology and Services','bachelor-culinary','bachelor','วิทยาศาสตรบัณฑิต (วท.บ.)','4 ปี','ไม่น้อยกว่า 130 หน่วยกิต','ประมาณ 25,000 บาท/ภาคเรียน','หลักสูตรที่มุ่งผลิตบัณฑิตด้านการประกอบอาหารและการบริการระดับมืออาชีพ ผสานทฤษฎีและปฏิบัติในครัวมาตรฐานสากล','สำเร็จการศึกษาระดับมัธยมศึกษาตอนปลายหรือเทียบเท่า','เชฟ, ผู้จัดการครัว, ผู้ประกอบการร้านอาหาร, นักพัฒนาผลิตภัณฑ์อาหาร',NULL,NULL,NULL,NULL,1,1,NULL,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,2,'คหกรรมศาสตร์','Home Economics','bachelor-home-economics','bachelor','ศิลปศาสตรบัณฑิต (ศศ.บ.)','4 ปี','ไม่น้อยกว่า 129 หน่วยกิต','ประมาณ 23,000 บาท/ภาคเรียน','บูรณาการศาสตร์ด้านอาหาร ผ้าและเครื่องแต่งกาย งานประดิษฐ์ และการจัดการครัวเรือน','สำเร็จการศึกษาระดับมัธยมศึกษาตอนปลายหรือเทียบเท่า','นักคหกรรมศาสตร์, ครู, ผู้ประกอบการ, นักออกแบบ',NULL,NULL,NULL,NULL,1,2,NULL,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,3,'โภชนาการและการประกอบอาหารเพื่อการชะลอวัย','Nutrition and Culinary for Anti-Aging','bachelor-nutrition','bachelor','วิทยาศาสตรบัณฑิต (วท.บ.)','4 ปี','ไม่น้อยกว่า 132 หน่วยกิต','ประมาณ 26,000 บาท/ภาคเรียน','เน้นองค์ความรู้ด้านโภชนาการเชิงสุขภาพและการประกอบอาหารเพื่อส่งเสริมสมรรถภาพและการชะลอวัย','สำเร็จการศึกษาระดับมัธยมศึกษาตอนปลาย สายวิทย์-คณิต','นักโภชนาการ, ที่ปรึกษาด้านสุขภาพ, ผู้ประกอบการอาหารสุขภาพ',NULL,NULL,NULL,NULL,1,3,NULL,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,1,'อบรมเบเกอรีและเพสตรีมืออาชีพ','Professional Bakery & Pastry','training-bakery','training','ประกาศนียบัตร','40 ชั่วโมง','-','9,900 บาท','หลักสูตรอบรมระยะสั้นสำหรับผู้สนใจเริ่มต้นอาชีพเบเกอรี','บุคคลทั่วไป อายุ 15 ปีขึ้นไป','เจ้าของร้านเบเกอรี, ผู้ช่วยเชฟเพสตรี',NULL,NULL,NULL,NULL,0,4,NULL,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `research`
--

DROP TABLE IF EXISTS `research`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `research` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `authors` varchar(500) DEFAULT NULL,
  `abstract` text DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `category` varchar(150) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `external_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_research` (`title`,`abstract`,`authors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `research`
--

LOCK TABLES `research` WRITE;
/*!40000 ALTER TABLE `research` DISABLE KEYS */;
/*!40000 ALTER TABLE `research` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_perm` (`role_id`,`permission_id`),
  KEY `fk_rp_perm` (`permission_id`),
  CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,3,6,'2026-07-15 19:38:42'),(2,3,7,'2026-07-15 19:38:42'),(3,3,2,'2026-07-15 19:38:42'),(4,3,4,'2026-07-15 19:38:42'),(5,3,3,'2026-07-15 19:38:42'),(6,3,1,'2026-07-15 19:38:42'),(8,4,1,'2026-07-15 19:38:42'),(9,4,5,'2026-07-15 19:38:42'),(11,5,14,'2026-07-15 19:38:42'),(12,5,8,'2026-07-15 19:38:42'),(14,2,9,'2026-07-15 19:38:42'),(15,2,14,'2026-07-15 19:38:42'),(16,2,8,'2026-07-15 19:38:42'),(17,2,6,'2026-07-15 19:38:42'),(18,2,7,'2026-07-15 19:38:42'),(19,2,2,'2026-07-15 19:38:42'),(20,2,4,'2026-07-15 19:38:42'),(21,2,3,'2026-07-15 19:38:42'),(22,2,1,'2026-07-15 19:38:42'),(23,2,10,'2026-07-15 19:38:42'),(24,2,5,'2026-07-15 19:38:42'),(25,2,13,'2026-07-15 19:38:42'),(26,2,11,'2026-07-15 19:38:42'),(27,2,15,'2026-07-22 16:49:54'),(28,2,16,'2026-08-04 16:33:33'),(29,2,17,'2026-08-04 23:50:13');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'ชื่อบทบาท เช่น ผู้ดูแลระบบสูงสุด',
  `slug` varchar(50) NOT NULL COMMENT 'รหัสบทบาท เช่น super_admin',
  `description` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ผู้ดูแลระบบสูงสุด','super_admin','เข้าถึงและจัดการได้ทุกส่วน','active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(2,'ผู้ดูแลระบบ','admin','จัดการเนื้อหาและผู้ใช้ส่วนใหญ่','active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,'บรรณาธิการ','editor','จัดการข่าว/บทความ/กิจกรรม','active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,'อาจารย์','teacher','จัดการหลักสูตร/งานวิจัย','active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(5,'เจ้าหน้าที่','staff','จัดการเอกสาร/ดาวน์โหลด/ติดต่อ','active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_committee`
--

DROP TABLE IF EXISTS `school_committee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_committee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) NOT NULL COMMENT 'ชื่อ-นามสกุล พร้อมคำนำหน้า',
  `position` varchar(255) NOT NULL COMMENT 'ตำแหน่งงาน/หลักสูตรที่รับผิดชอบ',
  `bio` text DEFAULT NULL COMMENT '???????/?????',
  `group_name` varchar(100) NOT NULL COMMENT 'กลุ่มบทบาทในคณะกรรมการ',
  `photo` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_committee`
--

LOCK TABLES `school_committee` WRITE;
/*!40000 ALTER TABLE `school_committee` DISABLE KEYS */;
INSERT INTO `school_committee` VALUES (1,'ผศ.ว่าที่ ร.ต. ดร.ธนพัฒน์ แสงรุ่งเรือง','คณบดี',NULL,'ผู้บริหาร','committee/c01-thanapat.png',1,'active','2026-08-04 16:33:33','2026-08-05 12:09:38',NULL),(2,'อาจารย์ธิติมา แก้วมณี','รองคณบดีฝ่ายวิชาการและการวางแผน',NULL,'ผู้บริหาร','committee/c02-thitima.png',2,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(3,'รศ.ดร.นราธิป ปุณเกษม','รองคณบดีฝ่ายบริหารและกิจการนักศึกษา',NULL,'ผู้บริหาร','committee/c03-naratip.png',3,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(4,'รศ.ดร.วรรณา ตั้งเจริญชัย','ผู้ทรงคุณวุฒิ',NULL,'ผู้ทรงคุณวุฒิ',NULL,1,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(5,'ดร.ลฎาภา มอร์เตโร','ผู้ทรงคุณวุฒิ',NULL,'ผู้ทรงคุณวุฒิ',NULL,2,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(6,'คุณธามม์ ประวัติตรี','ผู้ทรงคุณวุฒิ',NULL,'ผู้ทรงคุณวุฒิ',NULL,3,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(7,'ผศ.ฉัตรชนก บุญไชย','ประธานหลักสูตรวิทยาศาสตรบัณฑิต สาขาเทคโนโลยีการประกอบอาหารและการบริการ',NULL,'ประธานหลักสูตร','committee/c07-chatchanok.png',1,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(8,'รศ.ดร.ภัทราทิพย์ รอดสำราญ','ประธานหลักสูตรนวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร',NULL,'ประธานหลักสูตร','committee/c08-pattaratip.png',2,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(9,'รศ.ดร.ฐิตา ฟูเผ่า','ประธานหลักสูตรโภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย',NULL,'ประธานหลักสูตร','committee/c09-thita.png',3,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(10,'รศ.ดร.อรรถ ขันสี','ประธานหลักสูตรเทคโนโลยีการประกอบอาหารและการบริการ ศูนย์ลำปาง',NULL,'ประธานหลักสูตร','committee/c10-at.png',4,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(11,'ผศ.พรทวี ธนสัมบัณณ์','ประธานหลักสูตรเทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี',NULL,'ประธานหลักสูตร','committee/c11-porntawee.png',5,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(12,'ผศ.ดุษฎี ทรัพย์บัว','ประธานหลักสูตรเทคโนโลยีการประกอบอาหารและการบริการ ศูนย์ตรัง',NULL,'ประธานหลักสูตร','committee/c06-dutsadee.png',6,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(13,'ผศ. ดร.อัครพล ไวเชียงค้า','ประธานหลักสูตรศิลปศาสตรบัณฑิต สาขาคหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี',NULL,'ประธานหลักสูตร','committee/c12-akaraphon.png',7,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(14,'ผศ.ดร.โสรัจจ์ วิสุทธิแพทย์','ผู้แทนจากคณาจารย์ประจำ',NULL,'ผู้แทนจากคณาจารย์ประจำ','committee/c14-sorat.png',1,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(15,'อ.ปวิตรา ภาสุรกุล','ผู้แทนจากคณาจารย์ประจำ',NULL,'ผู้แทนจากคณาจารย์ประจำ','committee/c15-pawitra.jpg',2,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL),(16,'นางสาวรณิดา ศรีธนาวรุณ','หัวหน้าสำนักงานโรงเรียนการเรือน',NULL,'เลขานุการ','committee/c16-ranida.png',1,'active','2026-08-04 16:33:33','2026-08-04 16:33:33',NULL);
/*!40000 ALTER TABLE `school_committee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group` varchar(50) DEFAULT 'general',
  `type` varchar(30) DEFAULT 'text' COMMENT 'text/textarea/image/boolean',
  `label` varchar(150) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_setting_key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต','general','text','ชื่อเว็บไซต์','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(2,'site_description','ผู้นำด้านศาสตร์การประกอบอาหารและการบริการของประเทศไทย','general','textarea','คำอธิบายเว็บไซต์','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(3,'site_email','info@fooddusit.ac.th','contact','text','อีเมลติดต่อ','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(4,'site_phone','0-2244-5000','contact','text','โทรศัพท์','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(5,'site_address','295 ถนนนครราชสีมา เขตดุสิต กรุงเทพมหานคร 10300','contact','textarea','ที่อยู่','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(6,'facebook_url','https://www.facebook.com/SchoolofCulinaryArtsSDU/?locale=th_TH','social','text','Facebook','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(7,'youtube_url','https://youtube.com/','social','text','YouTube','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(8,'line_url','#','social','text','Line','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(9,'google_map','<iframe src=\"https://www.google.com/maps?q=%E0%B8%A1%E0%B8%AB%E0%B8%B2%E0%B8%A7%E0%B8%B4%E0%B8%97%E0%B8%A2%E0%B8%B2%E0%B8%A5%E0%B8%B1%E0%B8%A2%E0%B8%AA%E0%B8%A7%E0%B8%99%E0%B8%94%E0%B8%B8%E0%B8%B4%E0%B8%95&hl=th&z=16&output=embed\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\" title=\"แผนที่มหาวิทยาลัยสวนดุสิต\"></iframe>','contact','textarea','Google Map','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL),(10,'copyright','© 2569 โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต. สงวนลิขสิทธิ์.','general','text','ข้อความลิขสิทธิ์','active','2026-07-15 19:38:42','2026-08-06 12:43:48',NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(10) unsigned DEFAULT NULL,
  `full_name` varchar(200) NOT NULL,
  `position` varchar(200) DEFAULT NULL,
  `group_name` varchar(191) DEFAULT NULL COMMENT 'กลุ่ม/หลักสูตร/วิทยาเขต สำหรับจัดหมวดหน้าบุคลากรสายวิชาการ',
  `type` enum('executive','teacher','staff') NOT NULL DEFAULT 'teacher' COMMENT 'ผู้บริหาร/อาจารย์/เจ้าหน้าที่',
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `expertise` varchar(500) DEFAULT NULL COMMENT 'ความเชี่ยวชาญ',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_staff_dept` (`department_id`),
  KEY `idx_staff_type` (`type`),
  CONSTRAINT `fk_staff_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (3,1,'อาจารย์ประจำสาขา','อาจารย์',NULL,'teacher',NULL,NULL,NULL,NULL,NULL,3,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(6,NULL,'ผศ. ดร.ยศพร พลายโถ','ประธานหลักสูตร','วท.ม. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s01.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(7,NULL,'รศ. ดร.นราธิป ปุณเกษม','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.ม. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s02.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(8,NULL,'ดร.ภาคภูมิ คูประเสริฐยิ่ง','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.ม. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s03.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(9,NULL,'อ.ธิติมา แก้วมณี','ประธานหลักสูตร','ศศ.บ. คหกรรมศาสตร์','teacher',NULL,NULL,'staff/s04.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(10,NULL,'ผศ.จารุณี วิเทศ','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์','teacher',NULL,NULL,'staff/s05.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(11,NULL,'ผศ. ดร.นุจิรา รัศมีไพบูลย์','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์','teacher',NULL,NULL,'staff/s06.jpg',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(12,NULL,'อ.ชมนาท ชื่นฉ่ำ','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์','teacher',NULL,NULL,'staff/s07.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(13,NULL,'อ.ทศพร ธีรวงศ์กาญจนา','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์','teacher',NULL,NULL,'staff/s08.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(14,NULL,'ผศ.ฉัตรชนก บุญไชย','ประธานหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s09.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(15,NULL,'รศ. ดร.ธีรนุช ฉายศิริโชติ','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s10.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(16,NULL,'อ.เป็นเอก ทรัพย์สิน','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s11.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(17,NULL,'อ.ณัจยา เมฆราวี','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s12.jpg',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(18,NULL,'ผศ. ดร.โสรัจจ์ วิสุทธิแพทย์','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ','teacher',NULL,NULL,'staff/s13.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(19,NULL,'รศ. ดร.ภัทราทิพย์ รอดสำราญ','ประธานหลักสูตร','วท.บ. นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร','teacher',NULL,NULL,'staff/s14.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(20,NULL,'รศ. ดร.ปัญญภัสก์ ปิ่นแก้ว','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร','teacher',NULL,NULL,'staff/s15.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(21,NULL,'ดร.พรรณรพี เอี่ยมทวีเจริญ','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร','teacher',NULL,NULL,'staff/s16.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(22,NULL,'ดร.จันทร์จนา ศิริพันธ์วัฒนา','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร','teacher',NULL,NULL,'staff/s17.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(23,NULL,'อ.วนิดา คงคาโชติ','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร','teacher',NULL,NULL,'staff/s18.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(24,NULL,'รศ. ดร.ฐิตา ฟูเผ่า','ประธานหลักสูตร','วท.บ. โภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย','teacher',NULL,NULL,'staff/s19.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(25,NULL,'ผศ.กันต์กนิษฐ์ จงรัตนวิทย์','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. โภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย','teacher',NULL,NULL,'staff/s20.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(26,NULL,'อ.ธัญลักษณ์ อุ่นสุข','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. โภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย','teacher',NULL,NULL,'staff/s21.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(27,NULL,'ผศ.ยศสินี หัวดง','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. โภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย','teacher',NULL,NULL,'staff/s22.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(28,NULL,'อ.สุนิทรา ชัยภัย','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. โภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย','teacher',NULL,NULL,'staff/s23.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(29,NULL,'ผศ. ว่าที่ ร.ต. ดร.ธนพัฒน์ แสงรุ่งเรือง','ประธานหลักสูตร','ศศ.บ. การจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร','teacher',NULL,NULL,'staff/s24.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(30,NULL,'ผศ. ดร.ณัชนก นุกิจ','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. การจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร','teacher',NULL,NULL,'staff/s25.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(31,NULL,'อ.ทรงพล วิธานวัฒนา','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. การจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร','teacher',NULL,NULL,'staff/s26.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(32,NULL,'อ.สุปรียา ถาวรรักษ์','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. การจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร','teacher',NULL,NULL,'staff/s27.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(33,NULL,'อ.หทัยรัตน์ แซ่ตั้ง','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. การจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร','teacher',NULL,NULL,'staff/s28.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(34,NULL,'ผศ. ดร.อัครพล ไวเชียงค้า','ประธานหลักสูตร','ศศ.บ. คหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s29.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(35,NULL,'ผศ.กาญจนา เฟื่องศรี','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s30.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(36,NULL,'ดร.ณัชฌา พันธุ์วงษ์','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s31.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(37,NULL,'ดร.อิทธิพล อเนกธนทรัพย์','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s32.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(38,NULL,'อ.ชลธิชา ศิริประยงค์','อาจารย์ผู้รับผิดชอบหลักสูตร','ศศ.บ. คหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s33.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(39,NULL,'ผศ.พรทวี ธนสัมบัณณ์','ประธานหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s34.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(40,NULL,'ผศ.บุญญาพร เชื่อมสมพงษ์','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s35.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(41,NULL,'ผศ. ดร.มนฤทัย ศรีทองเกิด','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s36.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(42,NULL,'อ.บุษราคัม สีดาเหลือง','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s37.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(43,NULL,'ผศ. ดร.อรอนงค์ ทองมี','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี','teacher',NULL,NULL,'staff/s38.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(44,NULL,'รศ. ดร.อรรถ ขันสี','ประธานหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s39.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(45,NULL,'ผศ. ดร.อานง ใจแน่น','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s40.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(46,NULL,'อ.ฐิติวรฎา ใยสำลี','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s41.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(47,NULL,'ผศ. ดร.สังวาลย์ ชมภูจา','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s42.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(48,NULL,'ผศ.ราตรี เมฆวิลัย','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s43.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(49,NULL,'ผศ.ดุษฎี ทรัพย์บัว','ประธานหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s44.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(50,NULL,'ผศ.สาวิตรี ณุวงศ์ศรี','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s45.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(51,NULL,'ผศ.ทิพย์พิกา ธรฤทธิ์','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s46.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(52,NULL,'ผศ.เสาวพรรณ ปาละสุวรรณ','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s47.png',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(53,NULL,'อ.สิรินทร์ทิพย์ สุตตาพงค์','อาจารย์ผู้รับผิดชอบหลักสูตร','วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s48.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(54,NULL,'ผศ. ดร.กนกกานต์ วีระกุล','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s49.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(55,NULL,'ผศ. ดร.นฤมล นันทรักษ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s50.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(56,NULL,'ดร.วีระ พุ่มเกิด','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s51.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(57,NULL,'ผศ. ดร.พรยุพรรณ พรสุขสวัสดิ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s52.jpg',NULL,NULL,4,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(58,NULL,'ดร.นิพัทธ์ชนก นาจพินิจ','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s53.png',NULL,NULL,5,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(59,NULL,'รศ. ดร.สุวรรณา พิชัยยงค์วงศ์ดี','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s54.png',NULL,NULL,6,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(60,NULL,'ผศ. ดร.ชุติปภา สุวรรณกนิษฐ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s55.png',NULL,NULL,7,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(61,NULL,'ผศ. ดร.สายบังอร ปานพรม','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s56.png',NULL,NULL,8,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(62,NULL,'ผศ. ดร.วราภรณ์ วิทยาภรณ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s57.png',NULL,NULL,9,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(63,NULL,'ผศ.กิตติศักดิ์ วสันติวงศ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s58.png',NULL,NULL,10,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(64,NULL,'อ.นันทพร รุจิขจร','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s59.png',NULL,NULL,11,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(65,NULL,'อ.จารึก ศรีอรุณ','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s74_jaruek.png',NULL,NULL,12,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(66,NULL,'ผศ. ดร.ศวรรญา ปั่นดลสุข','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s60.png',NULL,NULL,13,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(67,NULL,'อ.ปวิตรา ภาสุรกุล','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s61.jpg',NULL,NULL,14,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(68,NULL,'อ.ภูริตา พรอนันต์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s62.png',NULL,NULL,15,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(69,NULL,'อ.นฤนาท ลิ้มอุทัยรัตน์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s63.png',NULL,NULL,16,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(70,NULL,'อ.พัชร์ศศิ ศรีพิพัฒน์พรกุล','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s64.png',NULL,NULL,17,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(71,NULL,'อ.พัชราพร ก้องภักดีสุข','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s65.png',NULL,NULL,18,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(72,NULL,'อ.ชุติกาญจน์ พิลึก','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s66.jpg',NULL,NULL,19,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(73,NULL,'อ.นราธิป อุปไชย','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s67.png',NULL,NULL,20,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(74,NULL,'อ.ทัศนีย์ อินทร์วิมล','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)','teacher',NULL,NULL,'staff/s68.png',NULL,NULL,21,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(75,NULL,'ผศ.สุภาวดี นาคบรรพ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s69.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(76,NULL,'อ.ปัทมา กาญจนรักษ์','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ตรัง','teacher',NULL,NULL,'staff/s70.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(77,NULL,'ผศ.จรรยา โท๊ะนาบุตร','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s71.png',NULL,NULL,1,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(78,NULL,'ผศ. ดร.อัศพงษ์ อุประวรรณา','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s72.png',NULL,NULL,2,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(79,NULL,'อ.เสาวลักษณ์ กันจินะ','อาจารย์ประจำ','อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ลำปาง','teacher',NULL,NULL,'staff/s73.png',NULL,NULL,3,'active','2026-08-04 23:09:12','2026-08-04 23:09:12',NULL),(80,NULL,'นางสาวรณิดา ศรีธนาวรุณ','หัวหน้าสำนักงาน','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup01.png',NULL,NULL,1,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(81,NULL,'นางสาวฐานิช วงศ์เมือง','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) รับผิดชอบงานสารบรรณ และจัดซื้อ-จัดจ้าง','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup02.png',NULL,NULL,2,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(82,NULL,'นางดวงสุดา บุญพบ','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) รับผิดชอบประชาสัมพันธ์และกิจการนักศึกษา','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup03.png',NULL,NULL,3,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(83,NULL,'นางสาวปรียานิตย์ ตั้งธานาภักดี','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) รับผิดชอบงานด้านวิชาการ','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup04.png',NULL,NULL,4,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(84,NULL,'นางสาวกัญจน์ณัฏฐ์ ไวยคูนา','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) รับผิดชอบฝึกประสบการณ์วิชาชีพและวารสาร','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup05.png',NULL,NULL,5,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(85,NULL,'นางสาวพรทิพย์ บำรุงแคว้น','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) ผู้ประสานงานหลักสูตรสาขาอุตสาหกรรมการประกอบอาหาร','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup06.png',NULL,NULL,6,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(86,NULL,'นางสาวกนกกร ถึงพุ่ม','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) ผู้ประสานงานหลักสูตร วท.ม. เทคโนโลยีการประกอบอาหารและการบริการ','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup07.png',NULL,NULL,7,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(87,NULL,'นางสาวทิพวรรณ ศรีสาคร','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) ผู้ประสานงานหลักสูตร วท.บ. สาขาเทคโนโลยีการประกอบอาหาร','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup08.png',NULL,NULL,8,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL),(88,NULL,'นายธนกฤต บัวประเสริฐ','เจ้าหน้าที่สำนักงาน (บริหารงานทั่วไป) ผู้ประสานงานหลักสูตรศิลปศาสตรบัณฑิต','สำนักงานโรงเรียนการเรือน','staff',NULL,NULL,'staff/sup09.png',NULL,NULL,9,'active','2026-08-04 23:34:36','2026-08-04 23:34:36',NULL);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'เก็บเป็น hash เท่านั้น',
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `position` varchar(150) DEFAULT NULL COMMENT 'ตำแหน่ง',
  `remember_token` varchar(255) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `login_attempts` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'superadmin','superadmin@dusit.ac.th','$2y$10$usChmr50aNrgurcuAISrneOKM.1sP7n7kY6Hwsil7.5qVyLkX4y06','ผู้ดูแลระบบสูงสุด','021234567',NULL,'ผู้ดูแลระบบ','950ab43d41b5359c82cca0c05da7b3413db1cdf7898590dab52573b41df6122c','2026-09-15 14:14:40','::1',0,NULL,'active','2026-07-15 19:38:42','2026-08-03 15:16:59',NULL),(2,2,'admin','admin@dusit.ac.th','$2y$10$usChmr50aNrgurcuAISrneOKM.1sP7n7kY6Hwsil7.5qVyLkX4y06','ผู้ดูแลระบบ','021234568',NULL,'ผู้ดูแลระบบ',NULL,NULL,NULL,0,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(3,3,'editor','editor@dusit.ac.th','$2y$10$SoAi7uIesAo9GeaUDK98Uevt2NEyAClL9fNhSw7nwoSG4EuARU2D2','บรรณาธิการข่าว','021234569',NULL,'บรรณาธิการ',NULL,'2026-07-25 08:46:34','::1',0,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(4,4,'teacher','teacher@dusit.ac.th','$2y$10$RCfOOb1/vtIVas3amOUvced9wlMD5s8YSAzXCb5eWTpVXg/Z089W.','อาจารย์ประจำ','021234570',NULL,'อาจารย์',NULL,NULL,NULL,0,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL),(5,5,'staff','staff@dusit.ac.th','$2y$10$MvtAHIDjd.U0oOiazrOoXuwgAqg.makUEM5dZZ4KTm7udR9twzA0G','เจ้าหน้าที่ธุรการ','021234571',NULL,'เจ้าหน้าที่',NULL,NULL,NULL,1,NULL,'active','2026-07-15 19:38:42','2026-07-15 19:38:42',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitor_logs`
--

DROP TABLE IF EXISTS `visitor_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitor_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_visit_date` (`visit_date`),
  KEY `idx_visit_ip` (`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitor_logs`
--

LOCK TABLES `visitor_logs` WRITE;
/*!40000 ALTER TABLE `visitor_logs` DISABLE KEYS */;
INSERT INTO `visitor_logs` VALUES (1,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-15','2026-07-15 19:51:04'),(2,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-15','2026-07-15 19:51:21'),(3,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-15','2026-07-15 19:51:38'),(4,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-15','2026-07-15 19:52:22'),(5,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 19:53:36'),(6,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-15','2026-07-15 19:54:05'),(7,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 19:54:40'),(8,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 19:54:49'),(9,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 19:54:51'),(10,'::1','/','http://localhost/webfooddusit/events','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 19:54:51'),(11,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-15','2026-07-15 23:16:57'),(12,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.1 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36','2026-07-15','2026-07-15 23:17:09'),(13,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 23:17:20'),(14,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 23:18:15'),(15,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 23:18:17'),(16,'::1','/','http://localhost/webfooddusit/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 23:18:19'),(17,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 23:18:21'),(18,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-15','2026-07-15 23:19:24'),(19,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.1 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36','2026-07-16','2026-07-16 20:19:32'),(20,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16','2026-07-16 20:19:42'),(21,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16','2026-07-16 20:20:41'),(22,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.129.1 Chrome/148.0.7778.280 Electron/42.6.0 Safari/537.36','2026-07-21','2026-07-21 14:39:19'),(23,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:39:25'),(24,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:40:11'),(25,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:40:13'),(26,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:40:16'),(27,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:46:21'),(28,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:46:26'),(29,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:46:39'),(30,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 14:46:51'),(31,'::1','/','','curl/8.16.0','2026-07-21','2026-07-21 15:16:49'),(32,'::1','/','','curl/8.16.0','2026-07-21','2026-07-21 15:17:13'),(33,'::1','/','','curl/8.16.0','2026-07-21','2026-07-21 15:21:36'),(34,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:22:20'),(35,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:22:25'),(36,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.129.1 Chrome/148.0.7778.280 Electron/42.6.0 Safari/537.36','2026-07-21','2026-07-21 15:23:33'),(37,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:23:40'),(38,'::1','/','','curl/8.16.0','2026-07-21','2026-07-21 15:36:21'),(39,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:37:39'),(40,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:37:56'),(41,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:43:45'),(42,'::1','/','http://localhost/webfooddusit/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:43:51'),(43,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:52:51'),(44,'::1','/','http://localhost/webfooddusit/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:53:58'),(45,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:54:00'),(46,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 15:54:01'),(47,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-21','2026-07-21 16:10:37'),(48,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.129.1 Chrome/148.0.7778.280 Electron/42.6.0 Safari/537.36','2026-07-22','2026-07-22 16:30:01'),(49,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:30:13'),(50,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:30:17'),(51,'::1','/','http://localhost/webfooddusit/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:30:32'),(52,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:31:16'),(53,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:32:55'),(54,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:33:00'),(55,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 16:40:05'),(56,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:40:27'),(57,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 16:42:54'),(58,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:43:40'),(59,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:44:45'),(60,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:44:46'),(61,'::1','/','http://localhost/webfooddusit/news/detail/open-admission-2569','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:54:46'),(62,'::1','/','http://localhost/webfooddusit/news/detail/open-admission-2569','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:54:48'),(63,'::1','/','http://localhost/webfooddusit/news/detail/open-admission-2569','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:54:49'),(64,'::1','/','http://localhost/webfooddusit/news/detail/open-admission-2569','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:54:49'),(65,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 16:55:02'),(66,'::1','/','http://localhost/webfooddusit/admin/news/edit/1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:57:35'),(67,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 16:58:06'),(68,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 17:09:05'),(69,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 17:09:20'),(70,'::1','/','http://localhost/webfooddusit/admin/news/edit/1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:09:56'),(71,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 17:11:23'),(72,'::1','/','http://localhost/webfooddusit/admin/news/edit/1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:12:14'),(73,'::1','/','http://localhost/webfooddusit/admin','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:14:18'),(74,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 17:14:50'),(75,'::1','/','http://localhost/webfooddusit/admin','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:44:47'),(76,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:46:55'),(77,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:47:28'),(78,'::1','/','http://localhost/webfooddusit/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:47:33'),(79,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:47:35'),(80,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:47:38'),(81,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:47:40'),(82,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:47:42'),(83,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:48:00'),(84,'::1','/','http://localhost/webfooddusit/events','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:48:07'),(85,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:48:15'),(86,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:48:38'),(87,'::1','/','http://localhost/webfooddusit/news','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:48:54'),(88,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:49:05'),(89,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 17:51:29'),(90,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 18:01:48'),(91,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 18:02:31'),(92,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:03:42'),(93,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:05:25'),(94,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 18:08:34'),(95,'::1','/','http://localhost/webfooddusit/about','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:10:28'),(96,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 18:15:24'),(97,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 18:20:17'),(98,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:23:35'),(99,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 18:25:43'),(100,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:26:10'),(101,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:29:28'),(102,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:39:40'),(103,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 18:55:07'),(104,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:11:42'),(105,'::1','/','http://localhost/webfooddusit/sdugoal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:30:15'),(106,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:30:21'),(107,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 22:36:54'),(108,'::1','/','http://localhost/webfooddusit/info/annual-report','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:38:43'),(109,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 22:39:51'),(110,'::1','/','http://localhost/webfooddusit/info/annual-report','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:40:50'),(111,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 22:42:37'),(112,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 22:46:56'),(113,'::1','/','http://localhost/webfooddusit/info/annual-report','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:52:13'),(114,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B8%A7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:52:24'),(115,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%AA%E0%B8%A1%E0%B8%B1%E0%B8%84%E0%B8%A3','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 22:52:34'),(116,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-22','2026-07-22 22:57:56'),(117,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%AA%E0%B8%A1%E0%B8%B1%E0%B8%84%E0%B8%A3','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 23:07:38'),(118,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22','2026-07-22 23:23:09'),(119,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 00:02:49'),(120,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 00:02:50'),(121,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 00:03:39'),(122,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 00:08:18'),(123,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 00:56:37'),(124,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 01:03:48'),(125,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 01:04:07'),(126,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 11:34:50'),(127,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 11:35:03'),(128,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 11:37:44'),(129,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 11:37:45'),(130,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 11:37:45'),(131,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 11:37:45'),(132,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 11:39:14'),(133,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 12:20:42'),(134,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 12:22:41'),(135,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 12:24:13'),(136,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-23','2026-07-23 12:30:56'),(137,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23','2026-07-23 12:31:57'),(138,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 12:44:57'),(139,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 12:47:18'),(140,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 12:47:23'),(141,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 12:47:56'),(142,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-24','2026-07-24 12:53:39'),(143,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 12:55:00'),(144,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 13:00:29'),(145,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-24','2026-07-24 13:02:44'),(146,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-24','2026-07-24 13:05:20'),(147,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-24','2026-07-24 13:06:13'),(148,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875','2026-07-24','2026-07-24 13:06:43'),(149,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%99%E0%B8%B1%E0%B8%81','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 13:07:42'),(150,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 15:58:58'),(151,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 16:07:46'),(152,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 16:07:51'),(153,'::1','/','http://localhost/webfooddusit/news/detail/win-cooking-contest','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 16:10:13'),(154,'::1','/','http://localhost/webfooddusit/news/detail/win-cooking-contest','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 16:12:48'),(155,'::1','/','http://localhost/webfooddusit/news/detail/win-cooking-contest','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 16:17:20'),(156,'::1','/','http://localhost/webfooddusit/curriculum/bsc-culinary-tech','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 16:28:47'),(157,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 23:02:27'),(158,'::1','/','http://localhost/webfooddusit/page/privacy-policy','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 23:03:47'),(159,'::1','/','http://localhost/webfooddusit/page/privacy-policy','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 23:03:57'),(160,'::1','/','http://localhost/webfooddusit/history','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 23:04:11'),(161,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24','2026-07-24 23:34:20'),(162,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25','2026-07-25 08:28:30'),(163,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%AA%E0%B8%B2%E0%B8%82%E0%B8%B2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25','2026-07-25 10:03:15'),(164,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 08:39:08'),(165,'::1','/','http://localhost/webfooddusit/curriculum/culinary-tech-service','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 09:34:47'),(166,'::1','/','','curl/8.16.0','2026-07-26','2026-07-26 10:37:25'),(167,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%AA%E0%B8%B2%E0%B8%82%E0%B8%B2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 10:41:35'),(168,'::1','/','','curl/8.16.0','2026-07-26','2026-07-26 12:08:29'),(169,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%AA%E0%B8%B2%E0%B8%82%E0%B8%B2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 12:08:57'),(170,'::1','/','http://localhost/webfooddusit/curriculum/home-economics','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 12:09:37'),(171,'::1','/','http://localhost/webfooddusit/curriculum/bsc-culinary-tech','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 12:10:44'),(172,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 12:12:12'),(173,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%97%E0%B8%B8%E0%B8%99','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 12:15:34'),(174,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%97%E0%B8%B8%E0%B8%99','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1','2026-07-26','2026-07-26 12:16:49'),(175,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1','2026-07-26','2026-07-26 12:16:51'),(176,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 12:21:04'),(177,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 13:48:17'),(178,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 23:10:11'),(179,'::1','/','http://localhost/webfooddusit/curriculum','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 23:11:25'),(180,'::1','/','http://localhost/webfooddusit/deans','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 23:13:29'),(181,'::1','/','http://localhost/webfooddusit/news/detail/%E0%B8%A7%E0%B8%A7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 23:13:35'),(182,'::1','/','http://localhost/webfooddusit/news/detail/%E0%B8%A7%E0%B8%A7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26','2026-07-26 23:13:38'),(183,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-29','2026-07-29 14:08:34'),(184,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30','2026-07-30 01:33:01'),(185,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30','2026-07-30 16:10:39'),(186,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-03','2026-08-03 15:16:47'),(187,'::1','/','http://localhost/webfooddusit/curriculum/bsc-culinary-tech','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-03','2026-08-03 15:20:39'),(188,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-03','2026-08-03 15:27:46'),(189,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 11:00:18'),(190,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 11:00:21'),(191,'::1','/','http://localhost/webfooddusit/curriculum/nutrition-fitness','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 13:52:16'),(192,'::1','/','http://localhost/webfooddusit/curriculum/food-business-culinary-arts-2569','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 14:28:54'),(193,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%84%E0%B8%AD%E0%B8%A1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 16:41:11'),(194,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 23:02:19'),(195,'::1','/','http://localhost/webfooddusit/info/school-committee','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 23:13:20'),(196,'::1','/','http://localhost/webfooddusit/info/school-committee','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 23:22:56'),(197,'::1','/','http://localhost/webfooddusit/info/annual-report','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-04','2026-08-04 23:39:40'),(198,'::1','/','','curl/8.16.0','2026-08-04','2026-08-04 23:52:38'),(199,'::1','/','','curl/8.16.0','2026-08-04','2026-08-04 23:54:47'),(200,'::1','/','','curl/8.16.0','2026-08-04','2026-08-04 23:54:47'),(201,'::1','/','','curl/8.16.0','2026-08-04','2026-08-04 23:54:47'),(202,'::1','/','','curl/8.16.0','2026-08-04','2026-08-04 23:54:48'),(203,'::1','/','','curl/8.16.0','2026-08-04','2026-08-04 23:54:48'),(204,'::1','/','http://localhost/webfooddusit/info/km','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:44:25'),(205,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:48:18'),(206,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:48:18'),(207,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:48:33'),(208,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:48:33'),(209,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:48:34'),(210,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:48:34'),(211,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:52:00'),(212,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:52:48'),(213,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:53:12'),(214,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:53:38'),(215,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:53:55'),(216,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:54:18'),(217,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 01:54:39'),(218,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 07:27:42'),(219,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 07:28:45'),(220,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 07:28:52'),(221,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 07:28:55'),(222,'::1','/','http://localhost/webfooddusit/info/newsletter','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 07:59:50'),(223,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 08:33:16'),(224,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 08:38:35'),(225,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 08:38:38'),(226,'::1','/','http://localhost/webfooddusit/curriculum/bsc-culinary-tech','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 08:39:36'),(227,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 08:39:54'),(228,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 08:40:16'),(229,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 08:41:22'),(230,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B8%A7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 08:42:26'),(231,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 09:06:22'),(232,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 09:11:25'),(233,'::1','/','http://localhost/webfooddusit/programs','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 09:12:03'),(234,'::1','/','http://localhost/webfooddusit/orgstructure','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 09:17:47'),(235,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 11:51:24'),(236,'::1','/','http://localhost/webfooddusit/info/staff-academic','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 12:11:58'),(237,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 12:17:05'),(238,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 13:00:40'),(239,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 13:00:49'),(240,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:02:31'),(241,'::1','/','http://localhost/webfooddusit/curriculum','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:02:51'),(242,'::1','/','http://localhost/webfooddusit/search?q=%E0%B9%80%E0%B8%97%E0%B8%84','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:04:48'),(243,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:16:49'),(244,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:17:12'),(245,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:18:06'),(246,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:18:44'),(247,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:19:35'),(248,'::1','/','http://localhost/webfooddusit/info/school-committee','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:20:16'),(249,'::1','/','http://localhost/webfooddusit/info/school-committee','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:20:18'),(250,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 13:23:46'),(251,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 13:24:33'),(252,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 13:24:45'),(253,'::1','/','','','2026-08-05','2026-08-05 13:25:24'),(254,'::1','/','','','2026-08-05','2026-08-05 13:26:39'),(255,'::1','/','','','2026-08-05','2026-08-05 13:26:57'),(256,'::1','/','','curl/8.16.0','2026-08-05','2026-08-05 13:27:06'),(257,'::1','/','http://localhost/webfooddusit/info/school-committee','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 13:27:56'),(258,'::1','/','','','2026-08-05','2026-08-05 13:29:19'),(259,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-05','2026-08-05 15:34:53'),(260,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 11:50:35'),(261,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 11:53:20'),(262,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 11:56:27'),(263,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:03:53'),(264,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:04:03'),(265,'::1','/','','curl/8.16.0','2026-08-06','2026-08-06 12:04:37'),(266,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:06:43'),(267,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:07:03'),(268,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:07:16'),(269,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:08:04'),(270,'::1','/','http://localhost/webfooddusit/admin/gallery','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:13:02'),(271,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:14:37'),(272,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:14:45'),(273,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:15:04'),(274,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:18:44'),(275,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:19:15'),(276,'::1','/','http://localhost/webfooddusit/gallery/album/internal-cooking','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:33:42'),(277,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:34:14'),(278,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 12:34:46'),(279,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-08-06','2026-08-06 21:35:28'),(280,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 11:32:08'),(281,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 11:38:41'),(282,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 11:39:22'),(283,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 11:39:49'),(284,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 11:42:25'),(285,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:38:42'),(286,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:39:17'),(287,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:39:26'),(288,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:39:27'),(289,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:39:35'),(290,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:39:55'),(291,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:40:28'),(292,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:40:54'),(293,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:47:04'),(294,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-07','2026-08-07 12:55:28'),(295,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09','2026-08-09 10:51:43'),(296,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-09','2026-08-09 10:51:47'),(297,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-17','2026-08-17 13:32:47'),(298,'::1','/','http://localhost/webfooddusit/search?q=%E0%B8%97%E0%B8%B8%E0%B8%99','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-17','2026-08-17 13:33:09'),(299,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-17','2026-08-17 13:36:29'),(300,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18','2026-08-18 13:09:35'),(301,'::1','/','http://localhost/webfooddusit/admin','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-18','2026-08-18 14:34:58'),(302,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-08-31','2026-08-31 20:15:29'),(303,'::1','/','http://localhost/webfooddusit/journal','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-08-31','2026-08-31 20:16:03'),(304,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:33:32'),(305,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:33:38'),(306,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:33:46'),(307,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:33:50'),(308,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:33:51'),(309,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:34:44'),(310,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:35:28'),(311,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:35:41'),(312,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:36:00'),(313,'::1','/','http://localhost/webfooddusit/orgstructure','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:37:04'),(314,'::1','/','http://localhost/webfooddusit/curriculum/food-business-culinary-arts-2569','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:37:16'),(315,'::1','/','http://localhost/webfooddusit/contact','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 11:38:21'),(316,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 12:46:44'),(317,'::1','/','http://localhost/webfooddusit/curriculum/nutrition-fitness','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 12:47:11'),(318,'::1','/','http://localhost/webfooddusit/curriculum/nutrition-fitness','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 12:49:00'),(319,'::1','/','http://localhost/webfooddusit/curriculum/nutrition-fitness','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 12:49:15'),(320,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:08:32'),(321,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:15:31'),(322,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:15:41'),(323,'::1','/','http://localhost/webfooddusit/curriculum/bsc-culinary-tech','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:17:56'),(324,'::1','/','http://localhost/webfooddusit/news/detail/win-cooking-contest','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:18:15'),(325,'::1','/','http://localhost/webfooddusit/news/detail/win-cooking-contest','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:18:57'),(326,'::1','/','http://localhost/webfooddusit/curriculum/bsc-culinary-tech','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:23:12'),(327,'::1','/','http://localhost/webfooddusit/curriculum/courses/nutrition-health-2568','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:28:52'),(328,'::1','/','http://localhost/webfooddusit/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 13:33:01'),(329,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 14:35:07'),(330,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 14:42:32'),(331,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-01','2026-09-01 15:20:40'),(332,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','2026-09-14','2026-09-14 23:38:28'),(333,'::1','/','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','2026-09-15','2026-09-15 14:13:56'),(334,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','2026-09-15','2026-09-15 14:14:17'),(335,'::1','/','','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','2026-09-15','2026-09-15 14:14:38');
/*!40000 ALTER TABLE `visitor_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-15 14:24:12
