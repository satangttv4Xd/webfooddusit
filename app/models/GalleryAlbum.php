<?php
/** Model: GalleryAlbum — อัลบั้มภาพ */
class GalleryAlbum extends Model
{
    protected string $table = 'gallery_albums';

    /** อัลบั้มพร้อมจำนวนรูป */
    public function withCount(): array
    {
        $sql = "SELECT a.*, (SELECT COUNT(*) FROM gallery g
                    WHERE g.album_id = a.id AND g.deleted_at IS NULL) AS item_count
                FROM gallery_albums a
                WHERE a.status='active' AND a.deleted_at IS NULL
                ORDER BY a.event_date DESC, a.id DESC";
        return $this->db->query($sql)->all();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findWhere(['slug' => $slug, 'status' => 'active']);
    }

    /** รูป/วิดีโอในอัลบั้ม */
    public function items(int $albumId): array
    {
        $sql = "SELECT * FROM gallery WHERE album_id=:id AND status='active' AND deleted_at IS NULL
                ORDER BY sort_order ASC, id ASC";
        return $this->db->query($sql)->bind(['id' => $albumId])->all();
    }
}
