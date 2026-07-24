<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

/**
 * Admin\FaqController — จัดการคำถามที่พบบ่อย (CRUD)
 */
class FaqController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('pages.manage');
    }

    public function index(): void
    {
        $faqs = $this->model('Faq')->all('sort_order ASC, id ASC');
        $this->adminView('faq/index', ['title' => 'จัดการคำถามที่พบบ่อย', 'faqs' => $faqs]);
    }

    public function create(): void
    {
        $this->adminView('faq/form', ['title' => 'เพิ่มคำถาม', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/faq'); }
        $this->verifyCsrf();
        if ($this->input('question') === '') { $this->flash('error', 'กรุณากรอกคำถาม'); $this->redirect('admin/faq/create'); }
        $this->model('Faq')->create($this->collect());
        audit_log('create', 'faq', 'เพิ่มคำถาม');
        $this->flash('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
        $this->redirect('admin/faq');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Faq')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/faq'); }
        $this->adminView('faq/form', ['title' => 'แก้ไขคำถาม', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/faq'); }
        $this->verifyCsrf();
        $this->model('Faq')->update($id, $this->collect());
        audit_log('update', 'faq', "แก้ไขคำถาม #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/faq');
    }

    public function delete(int $id = 0): void
    {
        $this->model('Faq')->delete($id);
        $this->flash('success', 'ลบคำถามแล้ว');
        $this->redirect('admin/faq');
    }

    private function collect(): array
    {
        return [
            'question'   => $this->input('question'),
            'answer'     => $this->input('answer', ''),
            'category'   => $this->input('category', ''),
            'sort_order' => (int)$this->input('sort_order', 0),
            'status'     => $this->input('status', 'active'),
        ];
    }
}
