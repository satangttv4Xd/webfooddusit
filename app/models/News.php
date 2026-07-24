<?php
/**
 * Model: News — ข่าวประชาสัมพันธ์
 */
class News extends Model
{
    protected string $table = 'news';

    /**
     * ข่าวล่าสุดที่เผยแพร่แล้ว (สำหรับหน้าแรก)
     */
    public function latest(int $limit = 6): array
    {
        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug
                FROM news n JOIN news_category c ON c.id = n.category_id
                WHERE n.status = 'published' AND n.deleted_at IS NULL
                ORDER BY n.published_at DESC LIMIT :lim";
        return $this->db->query($sql)->bind(['lim' => $limit])->all();
    }

    /**
     * ข่าวแนะนำ (featured)
     */
    public function featured(int $limit = 3): array
    {
        $sql = "SELECT n.*, c.name AS category_name FROM news n
                JOIN news_category c ON c.id = n.category_id
                WHERE n.is_featured = 1 AND n.status = 'published' AND n.deleted_at IS NULL
                ORDER BY n.published_at DESC LIMIT :lim";
        return $this->db->query($sql)->bind(['lim' => $limit])->all();
    }

    /**
     * แบ่งหน้าข่าวสำหรับหน้าสาธารณะ พร้อมกรองหมวดหมู่/ค้นหา
     */
    public function paginatePublic(int $page, ?int $categoryId = null, string $keyword = ''): array
    {
        $where = ["n.status = 'published'", "n.deleted_at IS NULL"];
        $params = [];
        if ($categoryId) {
            $where[] = "n.category_id = :cat";
            $params['cat'] = $categoryId;
        }
        if ($keyword !== '') {
            $where[] = "(n.title LIKE :kw OR n.excerpt LIKE :kw2)";
            $params['kw'] = "%{$keyword}%";
            $params['kw2'] = "%{$keyword}%";
        }
        $whereSql = 'WHERE ' . implode(' AND ', $where);

        $total = (int)$this->db->query("SELECT COUNT(*) FROM news n {$whereSql}")->bind($params)->column();
        $perPage = PER_PAGE;
        $offset = (max(1, $page) - 1) * $perPage;

        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug
                FROM news n JOIN news_category c ON c.id = n.category_id
                {$whereSql} ORDER BY n.published_at DESC LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->query($sql)->bind($params)->all();

        return [
            'data' => $data, 'total' => $total, 'page' => max(1, $page),
            'per_page' => $perPage, 'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    /**
     * ดึงข่าวตาม slug พร้อมข้อมูลหมวดหมู่
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug
                FROM news n JOIN news_category c ON c.id = n.category_id
                WHERE n.slug = :slug AND n.status = 'published' AND n.deleted_at IS NULL LIMIT 1";
        $row = $this->db->query($sql)->bind(['slug' => $slug])->one();
        return $row ?: null;
    }

    /**
     * ข่าวที่เกี่ยวข้อง (หมวดเดียวกัน ไม่รวมตัวเอง)
     */
    public function related(int $categoryId, int $excludeId, int $limit = 4): array
    {
        $sql = "SELECT id, title, slug, cover_image, published_at FROM news
                WHERE category_id = :cat AND id <> :ex AND status = 'published' AND deleted_at IS NULL
                ORDER BY published_at DESC LIMIT :lim";
        return $this->db->query($sql)->bind(['cat' => $categoryId, 'ex' => $excludeId, 'lim' => $limit])->all();
    }

    /**
     * เพิ่มยอดวิว
     */
    public function incrementViews(int $id): void
    {
        $this->db->query("UPDATE news SET views = views + 1 WHERE id = :id")->bind(['id' => $id])->execute();
    }

    /**
     * ข่าวยอดนิยม
     */
    public function popular(int $limit = 5): array
    {
        $sql = "SELECT id, title, slug, cover_image, views FROM news
                WHERE status = 'published' AND deleted_at IS NULL
                ORDER BY views DESC LIMIT :lim";
        return $this->db->query($sql)->bind(['lim' => $limit])->all();
    }
}
