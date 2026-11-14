-- ============================================================
-- Migration: เพิ่มตารางและสิทธิ์สำหรับระบบ "ปฏิทินงาน" (Schedules)
-- ============================================================

CREATE TABLE IF NOT EXISTS `schedules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL COMMENT 'ชื่องาน / ภารกิจ',
  `description` TEXT DEFAULT NULL COMMENT 'รายละเอียดงาน',
  `start_date` DATETIME NOT NULL COMMENT 'วันเวลาเริ่มต้น',
  `end_date` DATETIME DEFAULT NULL COMMENT 'วันเวลาสิ้นสุด',
  `location` VARCHAR(255) DEFAULT NULL COMMENT 'สถานที่ / ลิงก์ห้องประชุม',
  `color` VARCHAR(20) DEFAULT '#0d6efd' COMMENT 'สีแสดงบนปฏิทิน',
  `assigned_to` VARCHAR(255) DEFAULT NULL COMMENT 'ผู้รับผิดชอบ / หน่วยงาน',
  `status` ENUM('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_schedule_start` (`start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่ม Permission สำหรับจัดการปฏิทินงาน
INSERT IGNORE INTO `permissions` (`name`, `slug`, `module`, `status`, `created_at`)
VALUES ('จัดการปฏิทินงาน', 'schedules.manage', 'schedules', 'active', NOW());

-- มอบสิทธิ์ให้ Super Admin (role_id = 1) และ Admin (role_id = 2)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`)
SELECT 1, id, NOW() FROM `permissions` WHERE `slug` = 'schedules.manage';

INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`)
SELECT 2, id, NOW() FROM `permissions` WHERE `slug` = 'schedules.manage';
