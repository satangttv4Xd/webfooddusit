-- ============================================================
--  เพิ่มตาราง school_committee — คณะกรรมการประจำโรงเรียนการเรือน
--  ใช้จัดการผ่านหลังบ้าน (/admin/committee) แทนการฝังโค้ดตายตัว
--  ------------------------------------------------------------
--  วิธีรัน (กัน Thai เพี้ยนเป็น ????):
--    mysql -u root --default-character-set=utf8mb4 fooddusit_db < database/sql/add_school_committee.sql
-- ============================================================

USE `fooddusit_db`;

CREATE TABLE IF NOT EXISTS `school_committee` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name`   VARCHAR(191) NOT NULL COMMENT 'ชื่อ-นามสกุล พร้อมคำนำหน้า',
  `position`    VARCHAR(255) NOT NULL COMMENT 'ตำแหน่งงาน/หลักสูตรที่รับผิดชอบ',
  `group_name`  VARCHAR(100) NOT NULL COMMENT 'กลุ่มบทบาทในคณะกรรมการ',
  `photo`       VARCHAR(255) DEFAULT NULL,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  DATETIME DEFAULT NULL,
  `updated_at`  DATETIME DEFAULT NULL,
  `deleted_at`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่ม permission สำหรับจัดการคณะกรรมการ (ใช้สิทธิ์เดียวกับบุคลากร/คณบดี)
INSERT INTO `permissions` (`name`,`slug`,`module`,`status`,`created_at`)
SELECT 'จัดการคณะกรรมการ','committee.manage','staff','active',NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `slug` = 'committee.manage');

-- ผูกสิทธิ์ให้ role ที่มีสิทธิ์ staff.manage อยู่แล้ว (admin, super_admin เข้าได้ทุกทางอยู่แล้ว)
INSERT INTO `role_permissions` (`role_id`,`permission_id`,`created_at`)
SELECT rp.role_id, p2.id, NOW()
FROM `role_permissions` rp
JOIN `permissions` p1 ON p1.id = rp.permission_id AND p1.slug = 'staff.manage'
JOIN `permissions` p2 ON p2.slug = 'committee.manage'
WHERE NOT EXISTS (
  SELECT 1 FROM `role_permissions` rp2 WHERE rp2.role_id = rp.role_id AND rp2.permission_id = p2.id
);

-- ---------- seed ข้อมูลคณะกรรมการ (16 คน) ----------
INSERT INTO `school_committee` (`full_name`,`position`,`group_name`,`photo`,`sort_order`,`status`,`created_at`,`updated_at`) VALUES
('ผศ.ว่าที่ ร.ต. ดร.ธนพัฒน์ แสงรุ่งเรือง','คณบดี','ผู้บริหาร','committee/c01-thanapat.png',1,'active',NOW(),NOW()),
('อาจารย์ธิติมา แก้วมณี','รองคณบดีฝ่ายวิชาการและการวางแผน','ผู้บริหาร','committee/c02-thitima.png',2,'active',NOW(),NOW()),
('รศ.ดร.นราธิป ปุณเกษม','รองคณบดีฝ่ายบริหารและกิจการนักศึกษา','ผู้บริหาร','committee/c03-naratip.png',3,'active',NOW(),NOW()),

('รศ.ดร.วรรณา ตั้งเจริญชัย','ผู้ทรงคุณวุฒิ','ผู้ทรงคุณวุฒิ',NULL,1,'active',NOW(),NOW()),
('ดร.ลฎาภา มอร์เตโร','ผู้ทรงคุณวุฒิ','ผู้ทรงคุณวุฒิ',NULL,2,'active',NOW(),NOW()),
('คุณธามม์ ประวัติตรี','ผู้ทรงคุณวุฒิ','ผู้ทรงคุณวุฒิ',NULL,3,'active',NOW(),NOW()),

('ผศ.ฉัตรชนก บุญไชย','ประธานหลักสูตรวิทยาศาสตรบัณฑิต สาขาเทคโนโลยีการประกอบอาหารและการบริการ','ประธานหลักสูตร','committee/c07-chatchanok.png',1,'active',NOW(),NOW()),
('รศ.ดร.ภัทราทิพย์ รอดสำราญ','ประธานหลักสูตรนวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร','ประธานหลักสูตร','committee/c08-pattaratip.png',2,'active',NOW(),NOW()),
('รศ.ดร.ฐิตา ฟูเผ่า','ประธานหลักสูตรโภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย','ประธานหลักสูตร','committee/c09-thita.png',3,'active',NOW(),NOW()),
('รศ.ดร.อรรถ ขันสี','ประธานหลักสูตรเทคโนโลยีการประกอบอาหารและการบริการ ศูนย์ลำปาง','ประธานหลักสูตร','committee/c10-at.png',4,'active',NOW(),NOW()),
('ผศ.พรทวี ธนสัมบัณณ์','ประธานหลักสูตรเทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี','ประธานหลักสูตร','committee/c11-porntawee.png',5,'active',NOW(),NOW()),
('ผศ.ดุษฎี ทรัพย์บัว','ประธานหลักสูตรเทคโนโลยีการประกอบอาหารและการบริการ ศูนย์ตรัง','ประธานหลักสูตร','committee/c06-dutsadee.png',6,'active',NOW(),NOW()),
('ผศ. ดร.อัครพล ไวเชียงค้า','ประธานหลักสูตรศิลปศาสตรบัณฑิต สาขาคหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี','ประธานหลักสูตร','committee/c12-akaraphon.png',7,'active',NOW(),NOW()),

('ผศ.ดร.โสรัจจ์ วิสุทธิแพทย์','ผู้แทนจากคณาจารย์ประจำ','ผู้แทนจากคณาจารย์ประจำ','committee/c14-sorat.png',1,'active',NOW(),NOW()),
('อ.ปวิตรา ภาสุรกุล','ผู้แทนจากคณาจารย์ประจำ','ผู้แทนจากคณาจารย์ประจำ','committee/c15-pawitra.jpg',2,'active',NOW(),NOW()),

('นางสาวรณิดา ศรีธนาวรุณ','หัวหน้าสำนักงานโรงเรียนการเรือน','เลขานุการ','committee/c16-ranida.png',1,'active',NOW(),NOW());
