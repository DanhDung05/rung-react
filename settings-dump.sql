-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: rungadventure
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` enum('string','text','boolean','number','json','color','image','select') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (34,'site_name','Rừng Adventure','general','string','Tên website','2026-10-13 22:01:08','2026-10-14 02:42:34',NULL),(35,'site_description','Khám phá những hành trình trekking tuyệt vời.','general','text','Mô tả ngắn','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(36,'site_logo',NULL,'general','string','Logo','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(37,'site_favicon',NULL,'general','string','Favicon','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(38,'contact_email','rungadventure@gmail.com','contact','string','Email hiển thị','2026-10-13 22:01:08','2026-10-18 21:04:23',NULL),(39,'contact_phone','0376 344 375','contact','string','Số điện thoại','2026-10-13 22:01:08','2026-10-14 02:43:27',NULL),(40,'contact_address','Số 627 Vũ Tông Phan, Khương Đình, Thanh Xuân, Hà Nội','contact','string','Địa chỉ','2026-10-13 22:01:08','2026-10-14 02:43:27',NULL),(41,'social_facebook','https://www.facebook.com/rungadventure','social','string','Facebook','2026-10-13 22:01:08','2026-10-14 02:47:09',NULL),(42,'social_instagram','https://www.instagram.com/rungadventure','social','string','Instagram','2026-10-13 22:01:08','2026-10-14 02:47:09',NULL),(43,'social_youtube',NULL,'social','string','YouTube','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(44,'social_tiktok','https://www.tiktok.com/@rungadventure','social','string','TikTok','2026-10-13 22:01:08','2026-10-14 02:47:09',NULL),(45,'seo_default_title','Rung Adventure — Khám phá Việt Nam','seo','string','Meta title mặc định','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(46,'seo_default_description','Khám phá những hành trình trekking tuyệt vời cùng Rung Adventure.','seo','text','Meta description mặc định','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(47,'seo_keywords',NULL,'seo','string','Từ khóa SEO','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(48,'google_analytics_id',NULL,'seo','string','Google Analytics ID','2026-10-13 22:01:08','2026-10-13 22:01:08',NULL),(49,'theme_primary_color','#307a55','theme','color','Màu chính','2026-10-13 22:01:08','2026-10-13 22:05:57',NULL),(50,'theme_secondary_color','#f97316','theme','color','Màu phụ','2026-10-13 22:01:08','2026-10-13 22:05:57',NULL),(51,'theme_h1_color','#307a55','theme','color','H1 — Màu chữ','2026-10-13 22:01:08','2026-10-13 22:30:34',NULL),(52,'theme_h1_font','Plus Jakarta Sans','theme','select','H1 — Font chữ','2026-10-13 22:01:08','2026-10-13 22:05:57',NULL),(53,'theme_h2_color','#307a55','theme','color','H2 — Màu chữ','2026-10-13 22:01:08','2026-10-14 00:53:15',NULL),(54,'theme_h2_font','Plus Jakarta Sans','theme','select','H2 — Font chữ','2026-10-13 22:01:08','2026-10-13 22:05:57',NULL),(55,'theme_h3_color','#0f172a','theme','color','H3 — Màu chữ','2026-10-13 22:01:08','2026-10-13 22:05:57',NULL),(56,'theme_h3_font','Plus Jakarta Sans','theme','select','H3 — Font chữ','2026-10-13 22:01:08','2026-10-13 22:05:57',NULL),(57,'theme_h4_color','#0f172a','theme','color','H4 — Màu chữ','2026-10-13 22:01:09','2026-10-13 22:05:57',NULL),(58,'theme_h4_font','Plus Jakarta Sans','theme','select','H4 — Font chữ','2026-10-13 22:01:09','2026-10-13 22:05:57',NULL),(59,'theme_h5_color','#0f172a','theme','color','H5 — Màu chữ','2026-10-13 22:01:09','2026-10-13 22:05:57',NULL),(60,'theme_h5_font','Plus Jakarta Sans','theme','select','H5 — Font chữ','2026-10-13 22:01:09','2026-10-13 22:05:57',NULL),(61,'theme_h6_color','#0f172a','theme','color','H6 — Màu chữ','2026-10-13 22:01:09','2026-10-13 22:05:57',NULL),(62,'theme_h6_font','Plus Jakarta Sans','theme','select','H6 — Font chữ','2026-10-13 22:01:09','2026-10-13 22:05:57',NULL),(63,'about_hero_title','Về Rừng Adventure','about','string','Hero — Tiêu đề','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(64,'about_hero_subtitle','Hành trình khám phá thiên nhiên Việt Nam','about','text','Hero — Phụ đề','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(65,'about_story_title','Câu chuyện của chúng tôi','about','string','Câu chuyện — Tiêu đề','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(66,'about_mission_title','Sứ mệnh','about','string','Sứ mệnh — Tiêu đề','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(67,'about_mission_content','Mang đến những trải nghiệm du lịch bền vững, tôn trọng thiên nhiên và văn hóa địa phương.','about','text','Sứ mệnh — Nội dung','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(68,'about_vision_title','Tầm nhìn','about','string','Tầm nhìn — Tiêu đề','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(69,'about_vision_content','Trở thành đơn vị tổ chức du lịch trekking hàng đầu Việt Nam vào năm 2030.','about','text','Tầm nhìn — Nội dung','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(70,'about_values_title','Giá trị cốt lõi','about','string','Giá trị cốt lõi — Tiêu đề','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(71,'about_values_content','An toàn — Bền vững — Tận tâm — Chuyên nghiệp','about','text','Giá trị cốt lõi — Nội dung','2026-10-14 01:15:57','2026-10-14 01:15:57',NULL),(72,'contact_notification_emails','nguyendanhdung479@gmail.com,ddungx479@gmail.com','contact','text','Email nhận thông báo','2026-10-16 22:15:27','2026-10-16 22:15:27',NULL),(73,'hero_image_blog',NULL,'hero','string','Blog — Ảnh hero','2026-10-23 04:36:58','2026-10-23 04:36:58',NULL),(74,'hero_image_tour',NULL,'hero','string','Tour — Ảnh hero','2026-10-23 04:36:58','2026-10-23 04:36:58',NULL),(75,'hero_image_destination','media/settings/2026/10/itVsmyyLATADLZi4Q24X.webp','hero','string','Điểm đến — Ảnh hero','2026-10-23 04:36:59','2026-10-23 04:36:59',NULL),(76,'hero_image_about',NULL,'hero','string','Giới thiệu — Ảnh hero','2026-10-23 04:36:59','2026-10-23 04:36:59',NULL),(77,'hero_image_contact',NULL,'hero','string','Liên hệ — Ảnh hero','2026-10-23 04:36:59','2026-10-23 04:36:59',NULL),(78,'hero_image_faq',NULL,'hero','string','FAQ — Ảnh hero','2026-10-23 04:36:59','2026-10-23 04:36:59',NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-27 17:04:57
