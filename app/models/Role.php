<?php
/** Model: Role — บทบาทผู้ใช้ (RBAC) */
class Role extends Model
{
    protected string $table = 'roles';

    /** บทบาทที่เปิดใช้งานทั้งหมด (สำหรับ dropdown เลือก role ของผู้ใช้) */
    public function activeAll(): array { return $this->where(['status' => 'active'], 'id ASC'); }

    /** ดึงบทบาททั้งหมด พร้อมจำนวนผู้ใช้และจำนวนสิทธิ์ (สำหรับหน้า list) */
    public function allWithCounts(): array
    {
        $sql = "SELECT r.*,
                       (SELECT COUNT(*) FROM users u
                          WHERE u.role_id = r.id AND u.deleted_at IS NULL) AS user_count,
                       (SELECT COUNT(*) FROM role_permissions rp
                          WHERE rp.role_id = r.id) AS perm_count
                FROM roles r
                WHERE r.deleted_at IS NULL
                ORDER BY r.id ASC";
        return $this->db->query($sql)->all();
    }

    /** จำนวนผู้ใช้ที่อยู่ในบทบาทนี้ */
    public function userCount(int $roleId): int
    {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM users WHERE role_id = :rid AND deleted_at IS NULL"
        )->bind(['rid' => $roleId])->column();
    }

    /** รายการ permission id ที่บทบาทนี้มี */
    public function permissionIds(int $roleId): array
    {
        $rows = $this->db->query(
            "SELECT permission_id FROM role_permissions WHERE role_id = :rid"
        )->bind(['rid' => $roleId])->all();
        return array_map('intval', array_column($rows, 'permission_id'));
    }

    /** ตั้งค่าสิทธิ์ของบทบาทใหม่ทั้งหมด (ลบของเดิม แล้วใส่ตามรายการที่เลือก) */
    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        $this->db->query("DELETE FROM role_permissions WHERE role_id = :rid")
                 ->bind(['rid' => $roleId])->execute();

        $now = date('Y-m-d H:i:s');
        foreach (array_unique(array_map('intval', $permissionIds)) as $pid) {
            if ($pid <= 0) { continue; }
            $this->db->query(
                "INSERT INTO role_permissions (role_id, permission_id, created_at)
                 VALUES (:rid, :pid, :now)"
            )->bind(['rid' => $roleId, 'pid' => $pid, 'now' => $now])->execute();
        }
    }
}
