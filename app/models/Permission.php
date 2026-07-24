<?php
/** Model: Permission — สิทธิ์การใช้งาน */
class Permission extends Model
{
    protected string $table = 'permissions';

    /** ดึงสิทธิ์ที่เปิดใช้งานทั้งหมด จัดกลุ่มตามโมดูล (สำหรับหน้าเลือกสิทธิ์) */
    public function groupedByModule(): array
    {
        $rows = $this->db->query(
            "SELECT * FROM permissions
             WHERE deleted_at IS NULL AND status = 'active'
             ORDER BY module ASC, id ASC"
        )->all();

        $grouped = [];
        foreach ($rows as $p) {
            $grouped[$p['module']][] = $p;
        }
        return $grouped;
    }
}
