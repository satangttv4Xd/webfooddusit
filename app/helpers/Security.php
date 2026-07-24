<?php
/**
 * ============================================================
 *  Security (Helper)
 * ------------------------------------------------------------
 *  รวมฟังก์ชันด้านความปลอดภัย: CSRF, XSS clean, hash password,
 *  ตรวจสอบ input, สร้าง token ฯลฯ
 * ============================================================
 */

class Security
{
    /**
     * สร้าง CSRF token เก็บใน session แล้วคืนค่า
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    /**
     * สร้าง input hidden สำหรับใส่ในฟอร์ม
     */
    public static function csrfField(): string
    {
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . self::csrfToken() . '">';
    }

    /**
     * ตรวจสอบ CSRF token ที่ส่งมา (เทียบแบบ timing-safe)
     */
    public static function verifyCsrf(?string $token): bool
    {
        if (empty($token) || empty($_SESSION[CSRF_TOKEN_NAME])) {
            return false;
        }
        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    /**
     * ทำความสะอาดข้อความกัน XSS (ใช้ตอนแสดงผล)
     */
    public static function clean($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * ทำความสะอาด array ทั้งชุด (recursive)
     */
    public static function cleanArray(array $data): array
    {
        return array_map(function ($v) {
            return is_array($v) ? self::cleanArray($v) : self::clean($v);
        }, $data);
    }

    /**
     * เข้ารหัสรหัสผ่านด้วย bcrypt/argon2
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * ตรวจสอบรหัสผ่าน
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * สร้าง token แบบสุ่ม (เช่น remember-me, reset password)
     */
    public static function randomToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * ตรวจสอบอีเมล
     */
    public static function isEmail(string $email): bool
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * สร้าง slug ที่เป็นมิตรกับ URL (รองรับภาษาไทย)
     */
    public static function slug(string $text): string
    {
        $text = trim($text);
        // แทนที่ช่องว่างและอักขระพิเศษด้วยขีด
        $text = preg_replace('/[\s\-]+/u', '-', $text);
        $text = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $text);
        $text = trim($text, '-');
        return mb_strtolower($text, 'UTF-8') ?: substr(md5((string)microtime(true)), 0, 8);
    }
}
