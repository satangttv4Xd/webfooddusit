<?php
/** Model: VisitorLog — สถิติผู้เข้าชม */
class VisitorLog extends Model
{
    protected string $table = 'visitor_logs';
    protected bool $softDelete = false;

    /** บันทึกการเข้าชมหนึ่งครั้ง */
    public function track(string $url): void
    {
        $this->db->query("INSERT INTO visitor_logs (ip_address,url,referer,user_agent,visit_date,created_at)
                          VALUES (:ip,:url,:ref,:ua,:vd,:now)")
                 ->bind([
                     'ip'  => $_SERVER['REMOTE_ADDR'] ?? '',
                     'url' => substr($url, 0, 255),
                     'ref' => substr($_SERVER['HTTP_REFERER'] ?? '', 0, 255),
                     'ua'  => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                     'vd'  => date('Y-m-d'),
                     'now' => date('Y-m-d H:i:s'),
                 ])->execute();
    }

    public function totalVisits(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM visitor_logs")->column();
    }

    /** ผู้เข้าชมไม่ซ้ำวันนี้ */
    public function todayVisits(): int
    {
        return (int)$this->db->query("SELECT COUNT(DISTINCT ip_address) FROM visitor_logs WHERE visit_date=:d")
                             ->bind(['d' => date('Y-m-d')])->column();
    }

    /** สถิติ 7 วันล่าสุด (สำหรับกราฟ dashboard) */
    public function last7Days(): array
    {
        $sql = "SELECT visit_date, COUNT(*) AS total FROM visitor_logs
                WHERE visit_date >= :d GROUP BY visit_date ORDER BY visit_date ASC";
        return $this->db->query($sql)->bind(['d' => date('Y-m-d', strtotime('-6 days'))])->all();
    }
}
