<?php
/**
 * PageController — หน้าเนื้อหาทั่วไปตาม slug (เช่น /page/history)
 */
class PageController extends Controller
{
    public function view_(): void {}

    public function index(string $slug = ''): void
    {
        $page = $this->model('Page')->findBySlug($slug);
        if (!$page) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบหน้า']);
            return;
        }
        $this->view('public/page', [
            'title' => $page['title'],
            'page'  => $page,
        ]);
    }
}
