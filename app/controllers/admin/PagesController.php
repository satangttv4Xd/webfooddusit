<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;
use Upload;
use Validator;

/**
 * Admin\PageController — จัดการหน้าเนื้อหา (CRUD)
 */
class PagesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('pages.manage');
    }

    /** รายการหน้าเนื้อหา + ค้นหา + แบ่งหน้า */
    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $q = trim((string)$this->query('q', ''));

        $searchSql = ''; $searchParams = [];
        if ($q !== '') {
            $searchSql = 'title LIKE :q';
            $searchParams = ['q' => "%{$q}%"];
        }

        $pager = $this->model('Page')->paginate($page, ADMIN_PER_PAGE, [], 'id DESC', $searchSql, $searchParams);
        $this->adminView('pages/index', ['title' => 'จัดการหน้าเนื้อหา', 'pager' => $pager, 'q' => $q]);
    }

    public function create(): void
    {
        $this->adminView('pages/form', ['title' => 'เพิ่มหน้าเนื้อหา', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/pages'); }
        $this->verifyCsrf();

        $pageModel = $this->model('Page');
        $data = $this->collect();
        if (($err = $this->validate($data)) !== '') {
            $_SESSION['old'] = $_POST;
            $this->flash('error', $err);
            $this->redirect('admin/pages/create');
        }
        $data['slug'] = $pageModel->uniqueSlug($data['slug']);

        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'pages');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/pages/create'); }
            $data['cover_image'] = $path;
        }

        $id = $pageModel->create($data);
        audit_log('create', 'pages', "เพิ่มหน้า #{$id}: {$data['title']}");
        $this->flash('success', 'เพิ่มหน้าเนื้อหาเรียบร้อยแล้ว');
        $this->redirect('admin/pages');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Page')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบหน้าเนื้อหา'); $this->redirect('admin/pages'); }
        $this->adminView('pages/form', ['title' => 'แก้ไขหน้าเนื้อหา', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/pages'); }
        $this->verifyCsrf();

        $model = $this->model('Page');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบหน้าเนื้อหา'); $this->redirect('admin/pages'); }

        $data = $this->collect();
        if (($err = $this->validate($data)) !== '') {
            $this->flash('error', $err);
            $this->redirect('admin/pages/edit/' . $id);
        }
        $data['slug'] = $model->uniqueSlug($data['slug'], $id);

        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'pages');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/pages/edit/' . $id); }
            Upload::remove($item['cover_image']);
            $data['cover_image'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'pages', "แก้ไขหน้า #{$id}");
        $this->flash('success', 'บันทึกการแก้ไขเรียบร้อยแล้ว');
        $this->redirect('admin/pages');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('Page')->find($id);
        if ($item) { Upload::remove($item['cover_image']); }
        $this->model('Page')->delete($id);
        audit_log('delete', 'pages', "ลบหน้า #{$id}");
        $this->flash('success', 'ลบหน้าเนื้อหาแล้ว');
        $this->redirect('admin/pages');
    }

    private function collect(): array
    {
        $title = $this->input('title');
        return [
            'title'            => $title,
            'slug'             => $this->input('slug') ?: Security::slug($title),
            'content'          => $_POST['content'] ?? '',
            'template'         => $this->input('template', 'default'),
            'meta_title'       => $this->input('meta_title', ''),
            'meta_description' => $this->input('meta_description', ''),
            'status'           => $this->input('status', 'published'),
        ];
    }

    private function validate(array $data): string
    {
        $v = new Validator($data);
        $v->validate(
            ['title' => 'required|max:255', 'content' => 'required'],
            ['title' => 'ชื่อหน้า', 'content' => 'เนื้อหา']
        );
        return $v->firstError();
    }
}
