<?php
/** Model: Alumni — ศิษย์เก่า */
class Alumni extends Model
{
    protected string $table = 'alumni';

    /** ศิษย์เก่าดีเด่น (แสดงหน้าแรก) */
    public function featured(int $limit = 6): array
    {
        return $this->where(['status' => 'active', 'is_featured' => 1], 'sort_order ASC', $limit);
    }

    /** ศิษย์เก่าที่เผยแพร่ทั้งหมด */
    public function activeAll(): array
    {
        return $this->where(['status' => 'active'], 'sort_order ASC, id ASC');
    }

    /** ทั้งหมดสำหรับหลังบ้าน */
    public function listAll(): array
    {
        return $this->all('sort_order ASC, id ASC');
    }
}
