<?php
/**
 * ============================================================
 *  Auth (Helper)
 * ------------------------------------------------------------
 *  จัดการการยืนยันตัวตน (Authentication) และสิทธิ์ (RBAC)
 *  ใช้ร่วมกับตาราง users / roles / permissions
 * ============================================================
 */

class Auth
{
    /**
     * ตรวจว่ามีผู้ใช้ล็อกอินอยู่หรือไม่
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * คืนข้อมูลผู้ใช้ที่ล็อกอินอยู่ (หรือ null)
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * คืน id ผู้ใช้ปัจจุบัน
     */
    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * บันทึกข้อมูลผู้ใช้เข้า session หลังล็อกอินสำเร็จ
     */
    public static function login(array $user, array $permissions = []): void
    {
        // ป้องกัน session fixation
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'        => (int)$user['id'],
            'name'      => $user['full_name'] ?? $user['username'],
            'username'  => $user['username'],
            'email'     => $user['email'] ?? '',
            'avatar'    => $user['avatar'] ?? '',
            'role_id'   => (int)$user['role_id'],
            'role'      => $user['role_slug'] ?? '',
            'role_name' => $user['role_name'] ?? '',
        ];
        $_SESSION['permissions'] = $permissions;
        $_SESSION['last_activity'] = time();
    }

    /**
     * ออกจากระบบ
     */
    public static function logout(): void
    {
        unset($_SESSION['user'], $_SESSION['permissions']);
        session_regenerate_id(true);
    }

    /**
     * ตรวจว่าผู้ใช้มี role ที่ระบุหรือไม่ (รับได้หลาย role)
     */
    public static function hasRole(string ...$roles): bool
    {
        $current = $_SESSION['user']['role'] ?? '';
        return in_array($current, $roles, true);
    }

    /**
     * Super Admin เข้าถึงได้ทุกอย่างเสมอ
     */
    public static function isSuperAdmin(): bool
    {
        return ($_SESSION['user']['role'] ?? '') === 'super_admin';
    }

    /**
     * ตรวจสิทธิ์ตาม permission slug (RBAC)
     * @param string $permission เช่น 'news.create'
     */
    public static function can(string $permission): bool
    {
        if (self::isSuperAdmin()) {
            return true;
        }
        $permissions = $_SESSION['permissions'] ?? [];
        return in_array($permission, $permissions, true);
    }

    /**
     * ตรวจว่า session หมดอายุหรือยัง (idle timeout)
     */
    public static function isSessionExpired(): bool
    {
        if (!isset($_SESSION['last_activity'])) {
            return false;
        }
        return (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT;
    }

    /**
     * รีเฟรชเวลาใช้งานล่าสุด
     */
    public static function touch(): void
    {
        $_SESSION['last_activity'] = time();
    }
}
