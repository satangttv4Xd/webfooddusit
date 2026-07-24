<?php
/** Model: Contact — ข้อความติดต่อ */
class Contact extends Model
{
    protected string $table = 'contact';
    public function unreadCount(): int { return $this->count(['is_read' => 0]); }
    public function markRead(int $id): void
    {
        $this->db->query("UPDATE contact SET is_read=1 WHERE id=:id")->bind(['id' => $id])->execute();
    }
}
