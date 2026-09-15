<?php
/**
 * ============================================================
 *  ไฟล์คอนฟิกหลักของระบบ (Global Configuration)
 *  โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต
 * ------------------------------------------------------------
 *  แก้ไขค่าต่าง ๆ ที่นี่เพื่อปรับให้เข้ากับเครื่อง/โฮสต์ของคุณ
 * ============================================================
 */

// ---------- โหมดการทำงาน ----------
// 'development' = แสดง error ทั้งหมด (ใช้ตอนพัฒนา)
// 'production'  = ซ่อน error (ใช้ตอนขึ้นจริง)
define('APP_ENV', 'development');

// ---------- ข้อมูลเว็บไซต์ ----------
define('APP_NAME', 'โรงเรียนการเรือน มหาวิทยาลัยสวนดุสิต');
define('APP_NAME_EN', 'School of Culinary Arts, Suan Dusit University');

/**
 * BASE_URL: ที่อยู่หลักของเว็บไซต์ (ต้องลงท้ายด้วย /)
 * - รันบน XAMPP ในโฟลเดอร์ htdocs/webfooddusit ให้ใช้:
 *     http://localhost/webfooddusit/
 * - ขึ้นโฮสต์จริงให้เปลี่ยนเป็นโดเมนจริง เช่น https://food.dusit.ac.th/
 */
define('BASE_URL', 'http://localhost/webfooddusit/');

// ---------- Path ต่าง ๆ ในเครื่อง ----------
define('ROOT_PATH', dirname(dirname(__DIR__)));           // โฟลเดอร์รากของโปรเจกต์
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEW_PATH', APP_PATH . '/views');

// ---------- ค่าเริ่มต้นของ Router ----------
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_METHOD', 'index');

// ---------- ความปลอดภัย / Session ----------
define('SESSION_NAME', 'FOODDUSIT_SESS');
define('SESSION_TIMEOUT', 3600);          // หมดอายุ session (วินาที) = 1 ชั่วโมง
define('CSRF_TOKEN_NAME', 'csrf_token');
define('LOGIN_MAX_ATTEMPTS', 5);          // ล็อกอินผิดได้กี่ครั้งก่อนโดนล็อก
define('LOGIN_LOCK_TIME', 600);           // ล็อกนานกี่วินาที (10 นาที)
define('REMEMBER_ME_DAYS', 30);

// ---------- การอัปโหลดไฟล์ ----------
define('MAX_UPLOAD_SIZE', 30 * 1024 * 1024); // 30 MB (รองรับไฟล์รายงาน PDF ที่มักมีขนาด 12-23 MB)
define('ALLOWED_IMAGE_TYPES', 'jpg,jpeg,png,webp,gif');
define('ALLOWED_DOC_TYPES', 'pdf,doc,docx,xls,xlsx,ppt,pptx');

// ---------- การแบ่งหน้า (Pagination) ----------
define('PER_PAGE', 12);
define('ADMIN_PER_PAGE', 15);

// ---------- ธีมสี (ใช้อ้างอิงใน CSS/PHP) ----------
define('COLOR_PRIMARY', '#0F4C81');
define('COLOR_SECONDARY', '#D4AF37');

// ---------- ภาษา ----------
define('DEFAULT_LANG', 'th');
