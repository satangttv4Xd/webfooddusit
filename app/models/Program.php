<?php
/**
 * Model: Program — หลักสูตร
 */
class Program extends Model
{
    protected string $table = 'programs';

    /** หลักสูตรทั้งหมดที่ใช้งาน จัดกลุ่มตามระดับ */
    public function activeAll(): array
    {
        return $this->where(['status' => 'active'], 'sort_order ASC, id ASC');
    }

    /** หลักสูตรตามระดับ */
    public function byLevel(string $level): array
    {
        return $this->where(['status' => 'active', 'level' => $level], 'sort_order ASC');
    }

    /** หลักสูตรแนะนำ */
    public function featured(int $limit = 3): array
    {
        return $this->where(['status' => 'active', 'is_featured' => 1], 'sort_order ASC', $limit);
    }

    /** หาตาม slug */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT p.*, d.name AS department_name FROM programs p
                LEFT JOIN departments d ON d.id = p.department_id
                WHERE p.slug = :slug AND p.status = 'active' AND p.deleted_at IS NULL LIMIT 1";
        $row = $this->db->query($sql)->bind(['slug' => $slug])->one();
        return $row ?: null;
    }

    /** รายวิชาในหลักสูตร */
    public function curriculum(int $programId): array
    {
        $sql = "SELECT * FROM curriculum WHERE program_id = :pid AND deleted_at IS NULL
                ORDER BY year ASC, semester ASC, sort_order ASC";
        return $this->db->query($sql)->bind(['pid' => $programId])->all();
    }

    /** ชื่อระดับภาษาไทย */
    public static function levelLabel(string $level): string
    {
        return [
            'bachelor' => 'ปริญญาตรี',
            'master'   => 'ปริญญาโท',
            'doctoral' => 'ปริญญาเอก',
            'training' => 'หลักสูตรอบรม',
        ][$level] ?? $level;
    }
}
