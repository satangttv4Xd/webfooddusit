<?php
/**
 * Model: Event — กิจกรรม
 */
class Event extends Model
{
    protected string $table = 'events';

    /** กิจกรรมที่กำลังจะมาถึง */
    public function upcoming(int $limit = 4): array
    {
        $sql = "SELECT * FROM events
                WHERE status = 'active' AND deleted_at IS NULL AND start_date >= :now
                ORDER BY start_date ASC LIMIT :lim";
        return $this->db->query($sql)->bind(['now' => date('Y-m-d'), 'lim' => $limit])->all();
    }

    /** ทั้งหมด (เรียงตามวันเริ่ม) */
    public function allActive(): array
    {
        return $this->where(['status' => 'active'], 'start_date DESC');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findWhere(['slug' => $slug, 'status' => 'active']);
    }

    /** นับจำนวนผู้ลงทะเบียนของกิจกรรม */
    public function countRegistrations(int $eventId): int
    {
        $sql = "SELECT COUNT(*) FROM event_registrations
                WHERE event_id = :id AND status IN ('confirmed','pending') AND deleted_at IS NULL";
        return (int)$this->db->query($sql)->bind(['id' => $eventId])->column();
    }
}
