<?php
/**
 * Model: Download — ไฟล์ดาวน์โหลด
 */
class Download extends Model
{
    protected string $table = 'downloads';

    /** เพิ่มจำนวนการดาวน์โหลด */
    public function incrementCount(int $id): void
    {
        $this->db->query("UPDATE downloads SET download_count = download_count + 1 WHERE id = :id")
                 ->bind(['id' => $id])->execute();
    }

    /** ค้นหา + แบ่งหน้า สำหรับหน้าสาธารณะ */
    public function search(int $page, string $keyword = '', string $category = ''): array
    {
        $where = ["status = 'active'", "deleted_at IS NULL"];
        $params = [];
        if ($keyword !== '') {
            $where[] = "(title LIKE :kw OR description LIKE :kw2)";
            $params['kw'] = "%{$keyword}%";
            $params['kw2'] = "%{$keyword}%";
        }
        if ($category !== '') {
            $where[] = "category = :cat";
            $params['cat'] = $category;
        }
        $whereSql = 'WHERE ' . implode(' AND ', $where);
        $total = (int)$this->db->query("SELECT COUNT(*) FROM downloads {$whereSql}")->bind($params)->column();
        $perPage = PER_PAGE;
        $offset = (max(1, $page) - 1) * $perPage;
        $data = $this->db->query("SELECT * FROM downloads {$whereSql} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}")
                         ->bind($params)->all();
        return ['data' => $data, 'total' => $total, 'page' => max(1, $page),
                'per_page' => $perPage, 'total_pages' => (int)ceil($total / $perPage)];
    }

    /** รายการหมวดหมู่ที่มี */
    public function categories(): array
    {
        $rows = $this->db->query("SELECT DISTINCT category FROM downloads
                                  WHERE category IS NOT NULL AND category <> '' AND deleted_at IS NULL")->all();
        return array_column($rows, 'category');
    }
}
