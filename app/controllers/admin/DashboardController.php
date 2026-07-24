<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

/**
 * Admin\DashboardController — หน้าแดชบอร์ดหลังบ้าน
 */
class DashboardController extends AdminController
{
    public function index(): void
    {
        $stats = [
            'news'     => $this->model('News')->count(['status' => 'published']),
            'programs' => $this->model('Program')->count(['status' => 'active']),
            'events'   => $this->model('Event')->count(['status' => 'active']),
            'users'    => $this->model('User')->count(['status' => 'active']),
            'downloads'=> $this->model('Download')->count(['status' => 'active']),
            'contact'  => $this->model('Contact')->unreadCount(),
        ];

        $visitor = $this->model('VisitorLog');
        $visitStats = [
            'total' => $visitor->totalVisits(),
            'today' => $visitor->todayVisits(),
            'chart' => $visitor->last7Days(),
        ];

        $recentNews    = $this->model('News')->all('id DESC');
        $recentContact = $this->model('Contact')->all('id DESC');
        $recentActivity= (new \Database())
            ->query("SELECT a.*, u.full_name FROM audit_logs a
                     LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 10")->all();

        $this->adminView('dashboard', [
            'title'       => 'แดชบอร์ด',
            'stats'       => $stats,
            'visitStats'  => $visitStats,
            'recentNews'  => array_slice($recentNews, 0, 5),
            'recentContact'=> array_slice($recentContact, 0, 5),
            'recentActivity' => $recentActivity,
        ]);
    }
}
