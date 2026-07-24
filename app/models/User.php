<?php
/**
 * Model: User — ผู้ใช้งานระบบหลังบ้าน + จัดการล็อกอิน/RBAC
 */
class User extends Model
{
    protected string $table = 'users';

    /**
     * ค้นหาผู้ใช้จาก username หรือ email (สำหรับล็อกอิน)
     * ดึงข้อมูล role มาด้วย
     */
    public function findForLogin(string $login): ?array
    {
        $sql = "SELECT u.*, r.slug AS role_slug, r.name AS role_name
                FROM users u
                JOIN roles r ON r.id = u.role_id
                WHERE (u.username = :login OR u.email = :login2)
                  AND u.deleted_at IS NULL
                LIMIT 1";
        $row = $this->db->query($sql)->bind(['login' => $login, 'login2' => $login])->one();
        return $row ?: null;
    }

    /**
     * ดึงรายการ permission slug ของผู้ใช้ตาม role
     */
    public function permissions(int $roleId): array
    {
        $sql = "SELECT p.slug
                FROM role_permissions rp
                JOIN permissions p ON p.id = rp.permission_id
                WHERE rp.role_id = :rid AND p.deleted_at IS NULL";
        $rows = $this->db->query($sql)->bind(['rid' => $roleId])->all();
        return array_column($rows, 'slug');
    }

    /**
     * ดึงผู้ใช้ทั้งหมดพร้อมชื่อ role (สำหรับหน้า list)
     */
    public function allWithRole(): array
    {
        $sql = "SELECT u.*, r.name AS role_name
                FROM users u JOIN roles r ON r.id = u.role_id
                WHERE u.deleted_at IS NULL
                ORDER BY u.id DESC";
        return $this->db->query($sql)->all();
    }

    /**
     * บันทึกความสำเร็จของการล็อกอิน (รีเซ็ตตัวนับ + เวลาเข้าล่าสุด)
     */
    public function recordLoginSuccess(int $id): void
    {
        $this->db->query("UPDATE users SET login_attempts = 0, locked_until = NULL,
                          last_login_at = :now, last_login_ip = :ip WHERE id = :id")
                 ->bind(['now' => date('Y-m-d H:i:s'), 'ip' => $_SERVER['REMOTE_ADDR'] ?? '', 'id' => $id])
                 ->execute();
    }

    /**
     * รีเซ็ตตัวนับล็อกอินผิด (ใช้เมื่อการล็อกหมดอายุแล้ว เพื่อเริ่มนับใหม่)
     */
    public function resetAttempts(int $id): void
    {
        $this->db->query("UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = :id")
                 ->bind(['id' => $id])->execute();
    }

    /**
     * เพิ่มตัวนับล็อกอินผิด และล็อกบัญชีเมื่อครบจำนวน
     */
    public function recordLoginFail(int $id, int $attempts): void
    {
        $lockUntil = null;
        if ($attempts + 1 >= LOGIN_MAX_ATTEMPTS) {
            $lockUntil = date('Y-m-d H:i:s', time() + LOGIN_LOCK_TIME);
        }
        $this->db->query("UPDATE users SET login_attempts = login_attempts + 1, locked_until = :lock WHERE id = :id")
                 ->bind(['lock' => $lockUntil, 'id' => $id])->execute();
    }
}
