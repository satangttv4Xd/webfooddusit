-- ============================================================
--  ฐานข้อมูลเว็บไซต์ โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต
--  ไฟล์: schema.sql  (โครงสร้างตารางทั้งหมด)
--  Engine: InnoDB  |  Charset: utf8mb4  (รองรับภาษาไทย + emoji)
-- ------------------------------------------------------------
--  วิธีใช้: import ไฟล์นี้ผ่าน phpMyAdmin หรือ
--    mysql -u root fooddusit_db < schema.sql
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = '+07:00';

CREATE DATABASE IF NOT EXISTS `fooddusit_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `fooddusit_db`;

-- ============================================================
--  1) roles : บทบาทผู้ใช้งาน
-- ============================================================
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL COMMENT 'ชื่อบทบาท เช่น ผู้ดูแลระบบสูงสุด',
  `slug`        VARCHAR(50)  NOT NULL COMMENT 'รหัสบทบาท เช่น super_admin',
  `description` VARCHAR(255) DEFAULT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  2) permissions : สิทธิ์การใช้งานย่อย
-- ============================================================
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL COMMENT 'ชื่อสิทธิ์ เช่น เพิ่มข่าว',
  `slug`       VARCHAR(100) NOT NULL COMMENT 'รหัสสิทธิ์ เช่น news.create',
  `module`     VARCHAR(50)  NOT NULL COMMENT 'โมดูล เช่น news',
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permissions_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  3) role_permissions : ตารางเชื่อม role กับ permission (M:N)
-- ============================================================
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id`       INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  `created_at`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_perm` (`role_id`, `permission_id`),
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  4) users : ผู้ใช้งานระบบหลังบ้าน
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id`        INT UNSIGNED NOT NULL,
  `username`       VARCHAR(100) NOT NULL,
  `email`          VARCHAR(150) NOT NULL,
  `password`       VARCHAR(255) NOT NULL COMMENT 'เก็บเป็น hash เท่านั้น',
  `full_name`      VARCHAR(150) NOT NULL,
  `phone`          VARCHAR(30)  DEFAULT NULL,
  `avatar`         VARCHAR(255) DEFAULT NULL,
  `position`       VARCHAR(150) DEFAULT NULL COMMENT 'ตำแหน่ง',
  `remember_token` VARCHAR(255) DEFAULT NULL,
  `last_login_at`  DATETIME DEFAULT NULL,
  `last_login_ip`  VARCHAR(45) DEFAULT NULL,
  `login_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `locked_until`   DATETIME DEFAULT NULL,
  `status`         ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`     DATETIME DEFAULT NULL,
  `updated_at`     DATETIME DEFAULT NULL,
  `deleted_at`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  5) news_category : หมวดหมู่ข่าว
-- ============================================================
DROP TABLE IF EXISTS `news_category`;
CREATE TABLE `news_category` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(150) NOT NULL,
  `slug`       VARCHAR(180) NOT NULL,
  `description`VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_newscat_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  6) news : ข่าวประชาสัมพันธ์
-- ============================================================
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id`   INT UNSIGNED NOT NULL,
  `user_id`       INT UNSIGNED DEFAULT NULL COMMENT 'ผู้เขียน',
  `title`         VARCHAR(255) NOT NULL,
  `slug`          VARCHAR(280) NOT NULL,
  `excerpt`       VARCHAR(500) DEFAULT NULL COMMENT 'สรุปย่อ',
  `content`       LONGTEXT NOT NULL,
  `cover_image`   VARCHAR(255) DEFAULT NULL,
  `tags`          VARCHAR(255) DEFAULT NULL COMMENT 'คั่นด้วยจุลภาค',
  `views`         INT UNSIGNED NOT NULL DEFAULT 0,
  `is_featured`   TINYINT(1) NOT NULL DEFAULT 0,
  `allow_comment` TINYINT(1) NOT NULL DEFAULT 1,
  `meta_title`    VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `published_at`  DATETIME DEFAULT NULL,
  `status`        ENUM('published','draft','pending','inactive') NOT NULL DEFAULT 'draft',
  `created_at`    DATETIME DEFAULT NULL,
  `updated_at`    DATETIME DEFAULT NULL,
  `deleted_at`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_news_slug` (`slug`),
  KEY `idx_news_cat` (`category_id`),
  KEY `idx_news_status` (`status`),
  KEY `idx_news_featured` (`is_featured`),
  FULLTEXT KEY `ft_news` (`title`, `excerpt`, `content`),
  CONSTRAINT `fk_news_cat` FOREIGN KEY (`category_id`) REFERENCES `news_category`(`id`),
  CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  7) news_comments : ความคิดเห็นข่าว
-- ============================================================
DROP TABLE IF EXISTS `news_comments`;
CREATE TABLE `news_comments` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `news_id`    INT UNSIGNED NOT NULL,
  `name`       VARCHAR(150) NOT NULL,
  `email`      VARCHAR(150) DEFAULT NULL,
  `comment`    TEXT NOT NULL,
  `status`     ENUM('approved','pending','inactive') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_comment_news` (`news_id`),
  CONSTRAINT `fk_comment_news` FOREIGN KEY (`news_id`) REFERENCES `news`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  8) departments : หน่วยงาน/สาขา
-- ============================================================
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(200) NOT NULL,
  `slug`       VARCHAR(220) NOT NULL,
  `description`TEXT DEFAULT NULL,
  `head_name`  VARCHAR(150) DEFAULT NULL COMMENT 'หัวหน้าหน่วยงาน',
  `image`      VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dept_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  9) programs : หลักสูตร
-- ============================================================
DROP TABLE IF EXISTS `programs`;
CREATE TABLE `programs` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` INT UNSIGNED DEFAULT NULL,
  `name`          VARCHAR(255) NOT NULL,
  `name_en`       VARCHAR(255) DEFAULT NULL,
  `slug`          VARCHAR(280) NOT NULL,
  `level`         ENUM('bachelor','master','doctoral','training') NOT NULL DEFAULT 'bachelor'
                  COMMENT 'ระดับ: ตรี/โท/เอก/อบรม',
  `degree`        VARCHAR(200) DEFAULT NULL COMMENT 'ชื่อปริญญา',
  `duration`      VARCHAR(100) DEFAULT NULL COMMENT 'ระยะเวลาเรียน',
  `credits`       VARCHAR(50)  DEFAULT NULL COMMENT 'หน่วยกิต',
  `tuition_fee`   VARCHAR(150) DEFAULT NULL COMMENT 'ค่าใช้จ่าย',
  `overview`      TEXT DEFAULT NULL COMMENT 'ภาพรวมหลักสูตร',
  `qualification` TEXT DEFAULT NULL COMMENT 'คุณสมบัติผู้สมัคร',
  `careers`       TEXT DEFAULT NULL COMMENT 'อาชีพหลังจบ',
  `content`       LONGTEXT DEFAULT NULL,
  `cover_image`   VARCHAR(255) DEFAULT NULL,
  `pdf_file`      VARCHAR(255) DEFAULT NULL COMMENT 'ไฟล์รายละเอียดหลักสูตร',
  `apply_url`     VARCHAR(255) DEFAULT NULL COMMENT 'ลิงก์สมัครเรียน',
  `is_featured`   TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`    INT NOT NULL DEFAULT 0,
  `meta_title`    VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `status`        ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`    DATETIME DEFAULT NULL,
  `updated_at`    DATETIME DEFAULT NULL,
  `deleted_at`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_program_slug` (`slug`),
  KEY `idx_program_level` (`level`),
  KEY `idx_program_dept` (`department_id`),
  CONSTRAINT `fk_program_dept` FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  10) curriculum : รายวิชาในหลักสูตร
-- ============================================================
DROP TABLE IF EXISTS `curriculum`;
CREATE TABLE `curriculum` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `program_id`  INT UNSIGNED NOT NULL,
  `year`        VARCHAR(20) DEFAULT NULL COMMENT 'ชั้นปี',
  `semester`    VARCHAR(20) DEFAULT NULL COMMENT 'ภาคเรียน',
  `course_code` VARCHAR(30) DEFAULT NULL,
  `course_name` VARCHAR(255) NOT NULL,
  `credits`     VARCHAR(20) DEFAULT NULL,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_curri_program` (`program_id`),
  CONSTRAINT `fk_curri_program` FOREIGN KEY (`program_id`) REFERENCES `programs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  11) events : กิจกรรม/ปฏิทิน
-- ============================================================
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`        VARCHAR(255) NOT NULL,
  `slug`         VARCHAR(280) NOT NULL,
  `description`  LONGTEXT DEFAULT NULL,
  `cover_image`  VARCHAR(255) DEFAULT NULL,
  `location`     VARCHAR(255) DEFAULT NULL,
  `start_date`   DATETIME NOT NULL,
  `end_date`     DATETIME DEFAULT NULL,
  `max_attendees`INT UNSIGNED DEFAULT NULL COMMENT 'จำนวนรับสูงสุด (NULL = ไม่จำกัด)',
  `allow_register` TINYINT(1) NOT NULL DEFAULT 0,
  `is_featured`  TINYINT(1) NOT NULL DEFAULT 0,
  `status`       ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`   DATETIME DEFAULT NULL,
  `updated_at`   DATETIME DEFAULT NULL,
  `deleted_at`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_event_slug` (`slug`),
  KEY `idx_event_start` (`start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  12) event_registrations : การลงทะเบียนเข้าร่วมกิจกรรม
-- ============================================================
DROP TABLE IF EXISTS `event_registrations`;
CREATE TABLE `event_registrations` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id`   INT UNSIGNED NOT NULL,
  `name`       VARCHAR(150) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `phone`      VARCHAR(30) DEFAULT NULL,
  `organization` VARCHAR(200) DEFAULT NULL,
  `note`       TEXT DEFAULT NULL,
  `status`     ENUM('confirmed','pending','cancelled','inactive') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reg_event` (`event_id`),
  CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  13) gallery_albums : อัลบั้มภาพ
-- ============================================================
DROP TABLE IF EXISTS `gallery_albums`;
CREATE TABLE `gallery_albums` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(200) NOT NULL,
  `slug`        VARCHAR(220) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `event_date`  DATE DEFAULT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_album_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  14) gallery : รูปภาพ/วิดีโอในอัลบั้ม
-- ============================================================
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_id`   INT UNSIGNED DEFAULT NULL,
  `title`      VARCHAR(200) DEFAULT NULL,
  `type`       ENUM('image','video') NOT NULL DEFAULT 'image',
  `file_path`  VARCHAR(255) DEFAULT NULL COMMENT 'พาธรูป',
  `video_url`  VARCHAR(255) DEFAULT NULL COMMENT 'ลิงก์วิดีโอ (YouTube)',
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_album` (`album_id`),
  CONSTRAINT `fk_gallery_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  15) banner : แบนเนอร์หน้าแรก (Hero Slider)
-- ============================================================
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(200) DEFAULT NULL,
  `subtitle`    VARCHAR(300) DEFAULT NULL,
  `image`       VARCHAR(255) NOT NULL,
  `link_url`    VARCHAR(255) DEFAULT NULL,
  `button_text` VARCHAR(100) DEFAULT NULL,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  16) downloads : ไฟล์ดาวน์โหลด
-- ============================================================
DROP TABLE IF EXISTS `downloads`;
CREATE TABLE `downloads` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(255) NOT NULL,
  `description`    VARCHAR(500) DEFAULT NULL,
  `category`       VARCHAR(100) DEFAULT NULL,
  `file_path`      VARCHAR(255) NOT NULL,
  `file_type`      VARCHAR(20) DEFAULT NULL,
  `file_size`      INT UNSIGNED DEFAULT NULL COMMENT 'ขนาดไบต์',
  `download_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `status`         ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`     DATETIME DEFAULT NULL,
  `updated_at`     DATETIME DEFAULT NULL,
  `deleted_at`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_download_cat` (`category`),
  FULLTEXT KEY `ft_download` (`title`, `description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  17) contact : ข้อความติดต่อจากผู้ใช้
-- ============================================================
DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(150) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `phone`      VARCHAR(30) DEFAULT NULL,
  `subject`    VARCHAR(255) DEFAULT NULL,
  `message`    TEXT NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `is_read`    TINYINT(1) NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_contact_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  18) faq : คำถามที่พบบ่อย
-- ============================================================
DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question`   VARCHAR(500) NOT NULL,
  `answer`     TEXT NOT NULL,
  `category`   VARCHAR(100) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_faq` (`question`, `answer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  19) announcements : ประกาศ
-- ============================================================
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(255) NOT NULL,
  `content`    TEXT DEFAULT NULL,
  `file_path`  VARCHAR(255) DEFAULT NULL,
  `is_pinned`  TINYINT(1) NOT NULL DEFAULT 0,
  `published_at` DATETIME DEFAULT NULL,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  20) pages : หน้าเนื้อหาแบบยืดหยุ่น (About, ประวัติ ฯลฯ)
-- ============================================================
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(280) NOT NULL,
  `content`     LONGTEXT DEFAULT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `template`    VARCHAR(50) DEFAULT 'default',
  `meta_title`  VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `status`      ENUM('published','draft','inactive') NOT NULL DEFAULT 'published',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_slug` (`slug`),
  FULLTEXT KEY `ft_page` (`title`, `content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  21) menu : เมนูนำทาง (จัดการได้จากหลังบ้าน)
-- ============================================================
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id`  INT UNSIGNED DEFAULT NULL,
  `title`      VARCHAR(150) NOT NULL,
  `url`        VARCHAR(255) NOT NULL,
  `icon`       VARCHAR(50) DEFAULT NULL,
  `position`   ENUM('header','footer') NOT NULL DEFAULT 'header',
  `target`     ENUM('_self','_blank') NOT NULL DEFAULT '_self',
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_menu_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  22) settings : ตั้งค่าเว็บไซต์ (key-value)
-- ============================================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`        VARCHAR(100) NOT NULL,
  `value`      LONGTEXT DEFAULT NULL,
  `group`      VARCHAR(50) DEFAULT 'general',
  `type`       VARCHAR(30) DEFAULT 'text' COMMENT 'text/textarea/image/boolean',
  `label`      VARCHAR(150) DEFAULT NULL,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_setting_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  23) alumni : ศิษย์เก่า
-- ============================================================
DROP TABLE IF EXISTS `alumni`;
CREATE TABLE `alumni` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name`   VARCHAR(200) NOT NULL,
  `graduate_year` VARCHAR(10) DEFAULT NULL,
  `program`     VARCHAR(200) DEFAULT NULL,
  `current_job` VARCHAR(255) DEFAULT NULL,
  `organization`VARCHAR(255) DEFAULT NULL,
  `photo`       VARCHAR(255) DEFAULT NULL,
  `quote`       TEXT DEFAULT NULL COMMENT 'คำบอกเล่า',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  24) staff : บุคลากร/ผู้บริหาร/อาจารย์
-- ============================================================
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` INT UNSIGNED DEFAULT NULL,
  `full_name`     VARCHAR(200) NOT NULL,
  `position`      VARCHAR(200) DEFAULT NULL,
  `type`          ENUM('executive','teacher','staff') NOT NULL DEFAULT 'teacher'
                  COMMENT 'ผู้บริหาร/อาจารย์/เจ้าหน้าที่',
  `email`         VARCHAR(150) DEFAULT NULL,
  `phone`         VARCHAR(30) DEFAULT NULL,
  `photo`         VARCHAR(255) DEFAULT NULL,
  `bio`           TEXT DEFAULT NULL,
  `expertise`     VARCHAR(500) DEFAULT NULL COMMENT 'ความเชี่ยวชาญ',
  `sort_order`    INT NOT NULL DEFAULT 0,
  `status`        ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`    DATETIME DEFAULT NULL,
  `updated_at`    DATETIME DEFAULT NULL,
  `deleted_at`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_staff_dept` (`department_id`),
  KEY `idx_staff_type` (`type`),
  CONSTRAINT `fk_staff_dept` FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  25) research : ผลงานวิจัย
-- ============================================================
DROP TABLE IF EXISTS `research`;
CREATE TABLE `research` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(500) NOT NULL,
  `authors`     VARCHAR(500) DEFAULT NULL,
  `abstract`    TEXT DEFAULT NULL,
  `year`        VARCHAR(10) DEFAULT NULL,
  `category`    VARCHAR(150) DEFAULT NULL,
  `file_path`   VARCHAR(255) DEFAULT NULL,
  `external_url`VARCHAR(255) DEFAULT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_research` (`title`, `abstract`, `authors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  26) admissions : ข้อมูลการรับสมัคร
-- ============================================================
DROP TABLE IF EXISTS `admissions`;
CREATE TABLE `admissions` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `program_id`  INT UNSIGNED DEFAULT NULL,
  `round_name`  VARCHAR(150) DEFAULT NULL COMMENT 'รอบการรับสมัคร',
  `content`     LONGTEXT DEFAULT NULL,
  `open_date`   DATE DEFAULT NULL,
  `close_date`  DATE DEFAULT NULL,
  `apply_url`   VARCHAR(255) DEFAULT NULL,
  `file_path`   VARCHAR(255) DEFAULT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_adm_program` (`program_id`),
  CONSTRAINT `fk_adm_program` FOREIGN KEY (`program_id`) REFERENCES `programs`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  27) documents : เอกสารดาวน์โหลด/เอกสารราชการ
-- ============================================================
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `doc_number`  VARCHAR(100) DEFAULT NULL,
  `category`    VARCHAR(150) DEFAULT NULL,
  `file_path`   VARCHAR(255) DEFAULT NULL,
  `doc_date`    DATE DEFAULT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  28) partners : พันธมิตร/ผู้สนับสนุน
-- ============================================================
DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(200) NOT NULL,
  `logo`       VARCHAR(255) DEFAULT NULL,
  `website`    VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  29) visitor_logs : บันทึกผู้เข้าชม (สถิติ)
-- ============================================================
DROP TABLE IF EXISTS `visitor_logs`;
CREATE TABLE `visitor_logs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `url`        VARCHAR(255) DEFAULT NULL,
  `referer`    VARCHAR(255) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `visit_date` DATE DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_visit_date` (`visit_date`),
  KEY `idx_visit_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  30) audit_logs : บันทึกการกระทำในระบบหลังบ้าน
-- ============================================================
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED DEFAULT NULL,
  `action`     VARCHAR(50) NOT NULL,
  `module`     VARCHAR(50) DEFAULT NULL,
  `detail`     TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_audit_user` (`user_id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  31) newsletter_subscribers : สมาชิกรับข่าวสาร
-- ============================================================
DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(150) NOT NULL,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subscriber_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
-- ============================================================
--  จบไฟล์ schema.sql
-- ============================================================
