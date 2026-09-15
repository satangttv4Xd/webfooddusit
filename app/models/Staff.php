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

    /** ลำดับกลุ่ม/หลักสูตร ที่ใช้แสดงบนหน้า "บุคลากรสายวิชาการ" */
    public const ACADEMIC_GROUPS = [
        'วท.ม. เทคโนโลยีการประกอบอาหารและการบริการ',
        'ศศ.บ. คหกรรมศาสตร์',
        'วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ',
        'วท.บ. นวัตกรรมเทคโนโลยีเพื่อผู้ประกอบการอาหาร',
        'วท.บ. โภชนาการและการประกอบอาหารเพื่อสุขภาพและการชะลอวัย',
        'ศศ.บ. การจัดการธุรกิจบริการอาหารและศิลปะการประกอบอาหาร',
        'ศศ.บ. คหกรรมศาสตร์ วิทยาเขตสุพรรณบุรี',
        'วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ วิทยาเขตสุพรรณบุรี',
        'วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ลำปาง',
        'วท.บ. เทคโนโลยีการประกอบอาหารและการบริการ ศูนย์การศึกษา ตรัง',
        'อาจารย์ประจำโรงเรียนการเรือน (กรุงเทพมหานคร)',
        'อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ตรัง',
        'อาจารย์ประจำโรงเรียนการเรือน ศูนย์การศึกษา ลำปาง',
    ];

    public function byType(string $type): array
    {
        return $this->where(['status' => 'active', 'type' => $type], 'sort_order ASC');
    }

    /** อาจารย์ที่เผยแพร่ จัดกลุ่มตามหลักสูตร/วิทยาเขต สำหรับหน้าบุคลากรสายวิชาการ */
    public function academicGrouped(): array
    {
        $rows = $this->where(['status' => 'active', 'type' => 'teacher'], 'sort_order ASC, id ASC');

        $grouped = [];
        foreach (self::ACADEMIC_GROUPS as $g) { $grouped[$g] = []; } // การันตีลำดับกลุ่มตามที่กำหนด
        foreach ($rows as $r) {
            $key = $r['group_name'] ?: 'อื่น ๆ';
            $grouped[$key][] = $r;
        }
        return array_filter($grouped); // ตัดกลุ่มที่ไม่มีสมาชิกออก
    }

    /** บุคลากรตามประเภทที่ระบุ จัดกลุ่มตาม group_name (เรียงตามลำดับที่ปรากฏใน DB) — ใช้กับหน้าบุคลากรสายสนับสนุน ฯลฯ */
    public function groupedByType(string $type): array
    {
        $rows = $this->where(['status' => 'active', 'type' => $type], 'sort_order ASC, id ASC');

        $grouped = [];
        foreach ($rows as $r) {
            $key = $r['group_name'] ?: 'อื่น ๆ';
            $grouped[$key][] = $r;
        }
        return $grouped;
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
