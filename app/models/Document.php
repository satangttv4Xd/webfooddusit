<?php
/** Model: Document — เอกสาร/รายงานที่เผยแพร่ (เช่น รายงานผลการดำเนินงาน) */
class Document extends Model
{
    protected string $table = 'documents';

    /** เอกสารตามหมวดหมู่ เรียงจากวันที่ล่าสุดก่อน */
    public function byCategory(string $category): array
    {
        return $this->where(['status' => 'active', 'category' => $category], 'doc_date DESC, id DESC');
    }

    /** รายการหมวดหมู่ที่มีอยู่จริง (ใช้ทำ dropdown ในฟอร์มแอดมิน) */
    public function categories(): array
    {
        $rows = $this->db->query(
            "SELECT DISTINCT category FROM documents WHERE category IS NOT NULL AND category <> '' AND deleted_at IS NULL"
        )->all();
        return array_column($rows, 'category');
    }

    /** ทั้งหมดสำหรับหลังบ้าน */
    public function listAll(): array
    {
        return $this->all('doc_date DESC, id DESC');
    }
}
