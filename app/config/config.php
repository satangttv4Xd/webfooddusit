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
 * ตรวจสอบ host และ protocol อัตโนมัติ (รองรับ Cloudflare Tunnel / Ngrok / Localhost / Production)
 */
$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? 80) == 443)
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
$protocol = $isHttps ? 'https://' : 'http://';

if (in_array($httpHost, ['localhost', '127.0.0.1']) || (php_sapi_name() === 'cli')) {
    define('BASE_URL', 'http://localhost/webfooddusit/');
} else {
    // ถ้าเข้าผ่าน Tunnel (เช่น trycloudflare.com, ngrok) หรือ IP LAN
    $subPath = (str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/webfooddusit/') || str_contains($_SERVER['REQUEST_URI'] ?? '', '/webfooddusit')) ? 'webfooddusit/' : '';
    define('BASE_URL', $protocol . $httpHost . '/' . $subPath);
}

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

// ---------- การอัปโหลดไฟล์ ----------
define('MAX_UPLOAD_SIZE', 20 * 1024 * 1024); // 20 MB
define('ALLOWED_IMAGE_TYPES', 'jpg,jpeg,png,webp,gif,svg');
define('ALLOWED_DOC_TYPES', 'pdf,doc,docx,xls,xlsx,ppt,pptx,zip');

// ---------- การแบ่งหน้า (Pagination) ----------
define('DEFAULT_PER_PAGE', 12);
define('ADMIN_PER_PAGE', 15);
