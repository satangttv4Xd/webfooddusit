<?php
/** Model: Setting — ตั้งค่าเว็บไซต์ (key-value) */
class Setting extends Model
{
    protected string $table = 'settings';
    protected bool $softDelete = false;

    /** บันทึกค่า setting แบบ key=>value หลายค่าในครั้งเดียว */
    public function saveMany(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->db->query("UPDATE settings SET value=:v, updated_at=:t WHERE `key`=:k")
                     ->bind(['v' => $value, 't' => date('Y-m-d H:i:s'), 'k' => $key])->execute();
        }
    }

    /** ดึงทั้งหมด (override เพราะตารางไม่มี soft delete) */
    public function all(string $orderBy = '`group` ASC, id ASC'): array
    {
        return $this->db->query("SELECT * FROM settings ORDER BY {$orderBy}")->all();
    }
}
