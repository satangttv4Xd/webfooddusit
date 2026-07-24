<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;

/**
 * Admin\DownloadController — จัดการไฟล์ดาวน์โหลด (CRUD)
 */
class DownloadController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('downloads.manage');
    }

    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $pager = $this->model('Download')->paginate($page, ADMIN_PER_PAGE, [], 'id DESC');
        $this->adminView('downloads/index', ['title' => 'จัดการดาวน์โหลด', 'pager' => $pager]);
    }

    public function create(): void
    {
        $this->adminView('downloads/form', ['title' => 'เพิ่มไฟล์', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/downloads'); }
        $this->verifyCsrf();
        if ($this->input('title') === '') { $this->flash('error', 'กรุณากรอกชื่อไฟล์'); $this->redirect('admin/downloads/create'); }
        if (empty($_FILES['file']['name'])) { $this->flash('error', 'กรุณาเลือกไฟล์'); $this->redirect('admin/downloads/create'); }

        $path = Upload::document($_FILES['file'], 'downloads');
        if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/downloads/create'); }

        $data = $this->collect();
        $data['file_path'] = $path;
        $data['file_type'] = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $data['file_size'] = (int)$_FILES['file']['size'];
        $this->model('Download')->create($data);
        audit_log('create', 'downloads', 'เพิ่มไฟล์ดาวน์โหลด');
        $this->flash('success', 'เพิ่มไฟล์เรียบร้อยแล้ว');
        $this->redirect('admin/downloads');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Download')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบไฟล์'); $this->redirect('admin/downloads'); }
        $this->adminView('downloads/form', ['title' => 'แก้ไขไฟล์', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/downloads'); }
        $this->verifyCsrf();
        $model = $this->model('Download');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบไฟล์'); $this->redirect('admin/downloads'); }

        $data = $this->collect();
        if (!empty($_FILES['file']['name'])) {
            $path = Upload::document($_FILES['file'], 'downloads');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/downloads/edit/' . $id); }
            Upload::remove($item['file_path']);
            $data['file_path'] = $path;
            $data['file_type'] = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $data['file_size'] = (int)$_FILES['file']['size'];
        }
        $model->update($id, $data);
        audit_log('update', 'downloads', "แก้ไขไฟล์ #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/downloads');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('Download')->find($id);
        if ($item) { Upload::remove($item['file_path']); }
        $this->model('Download')->delete($id);
        $this->flash('success', 'ลบไฟล์แล้ว');
        $this->redirect('admin/downloads');
    }

    private function collect(): array
    {
        return [
            'title'       => $this->input('title'),
            'description' => $this->input('description', ''),
            'category'    => $this->input('category', ''),
            'status'      => $this->input('status', 'active'),
        ];
    }
}
