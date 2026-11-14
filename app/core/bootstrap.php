<?php
/**
 * ============================================================
 *  Bootstrap
 * ------------------------------------------------------------
 *  จุดเริ่มต้นของระบบ: โหลด config, ตั้งค่า error, session ที่ปลอดภัย,
 *  autoload คลาส core/helper และตั้งค่า security header
 * ============================================================
 */

// ---------- โหลด config ----------
require_once __DIR__ . '/../config/config.php';

// ---------- ตั้งค่าการแสดง error ตามโหมด ----------
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');
}

// ---------- โซนเวลา ----------
date_default_timezone_set('Asia/Bangkok');
mb_internal_encoding('UTF-8');

// ---------- Security Headers ----------
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-XSS-Protection: 1; mode=block');
// Content Security Policy (ผ่อนปรนให้ใช้ CDN ที่จำเป็น)
header("Content-Security-Policy: default-src 'self'; connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
    . "img-src 'self' data: blob: https:; "
    . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; "
    . "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; "
    . "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com https://unpkg.com; "
    . "worker-src 'self' blob: https://cdnjs.cloudflare.com; "
    . "frame-src 'self' https://www.google.com https://www.youtube.com;");

// ---------- ตั้งค่า Session อย่างปลอดภัย ----------
session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,          // กัน JS อ่าน cookie (กัน XSS ขโมย session)
    'samesite' => 'Lax',         // กัน CSRF ระดับ cookie
]);
session_start();

// ---------- Autoload คลาสหลัก ----------
spl_autoload_register(function ($class) {
    // ตัด namespace ออก (เช่น Admin\NewsController -> NewsController)
    $short = str_contains($class, '\\') ? substr(strrchr($class, '\\'), 1) : $class;

    $paths = [
        APP_PATH . '/core/' . $short . '.php',
        APP_PATH . '/helpers/' . $short . '.php',
        APP_PATH . '/models/' . $short . '.php',
        APP_PATH . '/middleware/' . $short . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ---------- โหลด helper functions (global) ----------
require_once APP_PATH . '/helpers/functions.php';

// ---------- ตรวจ session timeout อัตโนมัติ ----------
if (Auth::check() && Auth::isSessionExpired()) {
    Auth::logout();
    $_SESSION['flash']['warning'] = 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่';
}
if (Auth::check()) {
    Auth::touch();
}
