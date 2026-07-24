<?php
/**
 * DownloadController — ดาวน์โหลดไฟล์ (ค้นหา/นับยอด/ส่งไฟล์)
 */
class DownloadController extends Controller
{
    public function index(): void
    {
        $page     = (int)$this->query('page', 1);
        $keyword  = trim((string)$this->query('q', ''));
        $category = trim((string)$this->query('cat', ''));

        $model = $this->model('Download');
        $this->view('public/downloads/index', [
            'title'      => 'ดาวน์โหลดเอกสาร',
            'pager'      => $model->search($page, $keyword, $category),
            'categories' => $model->categories(),
            'keyword'    => $keyword,
            'category'   => $category,
        ]);
    }

    /** ส่งไฟล์ให้ดาวน์โหลด พร้อมนับยอด */
    public function file(int $id = 0): void
    {
        $model = $this->model('Download');
        $item = $model->find($id);
        if (!$item) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบไฟล์']);
            return;
        }

        $path = UPLOAD_PATH . '/' . ltrim($item['file_path'], '/');
        if (!is_file($path)) {
            $this->flash('error', 'ไม่พบไฟล์บนเซิร์ฟเวอร์');
            $this->redirect('downloads');
        }

        // นับยอดดาวน์โหลด
        $model->incrementCount($id);

        // ส่งไฟล์
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: must-revalidate');
        readfile($path);
        exit;
    }
}
