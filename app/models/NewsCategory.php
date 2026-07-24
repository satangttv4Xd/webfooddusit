<?php
/** Model: NewsCategory — หมวดหมู่ข่าว */
class NewsCategory extends Model
{
    protected string $table = 'news_category';

    /** หมวดหมู่ที่ใช้งานพร้อมจำนวนข่าว */
    public function withCount(): array
    {
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM news n
                    WHERE n.category_id = c.id AND n.status='published' AND n.deleted_at IS NULL) AS news_count
                FROM news_category c
                WHERE c.status='active' AND c.deleted_at IS NULL
                ORDER BY c.sort_order ASC";
        return $this->db->query($sql)->all();
    }
}
