<?php
/** Model: SchoolCommittee — คณะกรรมการประจำโรงเรียนการเรือน */
class SchoolCommittee extends Model
{
    protected string $table = 'school_committee';

    /** รายชื่อบทบาท/กลุ่มที่ใช้ได้ เรียงลำดับการแสดงผลบนเว็บ */
    public const GROUPS = [
        'ผู้บริหาร',
        'ผู้ทรงคุณวุฒิ',
        'ประธานหลักสูตร',
        'ผู้แทนจากคณาจารย์ประจำ',
        'เลขานุการ',
    ];

    /** ทั้งหมดสำหรับหลังบ้าน เรียงตามกลุ่มแล้วตามลำดับ */
    public function listAll(): array
    {
        return $this->all('group_name ASC, sort_order ASC, id ASC');
    }

    /** คณะกรรมการที่เผยแพร่ จัดกลุ่มตามบทบาท สำหรับหน้าเว็บสาธารณะ */
    public function activeGrouped(): array
    {
        $rows = $this->where(['status' => 'active'], 'sort_order ASC, id ASC');

        $grouped = [];
        foreach (self::GROUPS as $g) { $grouped[$g] = []; } // การันตีลำดับกลุ่มตามที่กำหนด
        foreach ($rows as $r) {
            $grouped[$r['group_name']][] = $r;
        }
        return array_filter($grouped); // ตัดกลุ่มที่ไม่มีสมาชิกออก
    }
}
