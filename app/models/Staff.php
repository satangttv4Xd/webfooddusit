<?php
/** Model: Staff — บุคลากร/ผู้บริหาร/อาจารย์ */
class Staff extends Model
{
    protected string $table = 'staff';

    /** ประเภทบุคลากร (ใช้ทั้งฟอร์มและการแสดงผล) */
    public const TYPES = [
        'executive' => 'ผู้บริหาร',
        'teacher'   => 'อาจารย์',
        'staff'     => 'เจ้าหน้าที่',
    ];

    public function byType(string $type): array
    {
        return $this->where(['status' => 'active', 'type' => $type], 'sort_order ASC');
    }

    /** รายการทั้งหมดสำหรับหลังบ้าน (จัดกลุ่มตามประเภท) */
    public function listAll(): array
    {
        return $this->all('type ASC, sort_order ASC, id ASC');
    }

    /** แปลงรหัสประเภทเป็นชื่อภาษาไทย */
    public static function typeLabel(string $type): string
    {
        return self::TYPES[$type] ?? $type;
    }
}
