<?php
/**
 * ============================================================
 *  HomeController — หน้าแรกของเว็บไซต์
 * ============================================================
 */
class HomeController extends Controller
{
    public function index(): void
    {
        // บันทึกสถิติผู้เข้าชม (เฉพาะหน้าแรกเพื่อความเบา)
        try { $this->model('VisitorLog')->track('/'); } catch (Throwable $e) {}

        $banner    = $this->model('Banner')->activeAll();
        $news      = $this->model('News')->latest(6);
        $featured  = $this->model('Program')->featured(3);
        $events    = $this->model('Event')->upcoming(3);
        $albums    = $this->model('GalleryAlbum')->withCount();
        $announce  = $this->model('Announcement')->latest(5);
        $teachers  = $this->model('Staff')->byType('teacher');
        $alumni    = $this->model('Alumni')->featured(4);
        $partners  = $this->model('Partner')->activeAll();

        // สถิติสำหรับ counter section
        $stats = [
            'programs' => $this->model('Program')->count(['status' => 'active']),
            'news'     => $this->model('News')->count(['status' => 'published']),
            'events'   => $this->model('Event')->count(['status' => 'active']),
            'alumni'   => $this->model('Alumni')->count(['status' => 'active']),
        ];

        $this->view('public/home', [
            'title'    => APP_NAME,
            'banner'   => $banner,
            'news'     => $news,
            'featured' => $featured,
            'events'   => $events,
            'albums'   => array_slice($albums, 0, 3),
            'announce' => $announce,
            'teachers' => $teachers,
            'alumni'   => $alumni,
            'partners' => $partners,
            'stats'    => $stats,
        ]);
    }
}
