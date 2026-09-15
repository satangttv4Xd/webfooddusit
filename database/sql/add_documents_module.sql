-- ============================================================
--  เพิ่มสิทธิ์จัดการเอกสาร/รายงาน (ตาราง documents มีอยู่แล้วในสคีมา แต่ยังไม่มีระบบใช้งาน)
--  ------------------------------------------------------------
--  วิธีรัน:
--    mysql -u root --default-character-set=utf8mb4 fooddusit_db < database/sql/add_documents_module.sql
-- ============================================================

USE `fooddusit_db`;

INSERT INTO `permissions` (`name`,`slug`,`module`,`status`,`created_at`)
SELECT 'จัดการเอกสาร/รายงาน','documents.manage','pages','active',NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `slug` = 'documents.manage');

-- ผูกสิทธิ์ให้ role ที่มีสิทธิ์ pages.manage อยู่แล้ว (admin/super_admin เข้าได้ทุกทางอยู่แล้ว)
INSERT INTO `role_permissions` (`role_id`,`permission_id`,`created_at`)
SELECT rp.role_id, p2.id, NOW()
FROM `role_permissions` rp
JOIN `permissions` p1 ON p1.id = rp.permission_id AND p1.slug = 'pages.manage'
JOIN `permissions` p2 ON p2.slug = 'documents.manage'
WHERE NOT EXISTS (
  SELECT 1 FROM `role_permissions` rp2 WHERE rp2.role_id = rp.role_id AND rp2.permission_id = p2.id
);
