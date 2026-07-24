<?php
/** Model: Announcement — ประกาศ */
class Announcement extends Model
{
    protected string $table = 'announcements';
    public function latest(int $limit = 5): array
    {
        $sql = "SELECT * FROM announcements WHERE status='active' AND deleted_at IS NULL
                ORDER BY is_pinned DESC, published_at DESC, id DESC LIMIT :lim";
        return $this->db->query($sql)->bind(['lim' => $limit])->all();
    }
}
