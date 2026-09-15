-- ============================================================
--  เพิ่มคอลัมน์ cover_image ในตาราง documents (รูปหน้าปกเอกสาร/รายงาน)
--  ------------------------------------------------------------
--  วิธีรัน:
--    mysql -u root --default-character-set=utf8mb4 fooddusit_db < database/sql/add_document_cover_image.sql
-- ============================================================

USE `fooddusit_db`;

ALTER TABLE `documents`
  ADD COLUMN IF NOT EXISTS `cover_image` VARCHAR(255) NULL COMMENT 'รูปหน้าปก/ภาพตัวอย่างของเอกสาร' AFTER `title`;
