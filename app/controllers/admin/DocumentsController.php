<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;

/**
 * Admin\DocumentController — จัดการเอกสาร/รายงาน (CRUD)
 * ใช้กับหน้าสาธารณะ เช่น "รายงานผลการดำเนินงาน" (/info/annual-report)
 */
class DocumentsController extends AdminController
{
    /** หมวดหมู่แนะนำ (ผู้ใช้พิมพ์หมวดอื่นเพิ่มเองได้ผ่าน datalist) */
    public const SUGGESTED_CATEGORIES = [
        'รายงานผลการดำเนินงาน',
        'จดหมายข่าว',
        'การจัดการความรู้ (KM)',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('documents.manage');
    }

    public function index(): void
    {
        $items = $this->model('Document')->listAll();
        $this->adminView('documents/index', ['title' => 'จัดการเอกสาร/รายงาน', 'items' => $items]);
    }

    public function create(): void
    {
        $this->adminView('documents/form', [
            'title' => 'เพิ่มเอกสาร', 'item' => null,
            'categories' => $this->suggestedCategories(),
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/documents'); }
        $this->verifyCsrf();

        if ($this->input('title') === '') {
            $this->flash('error', 'กรุณากรอกชื่อเอกสาร');
            $this->redirect('admin/documents/create');
        }
        if ($this->input('category') === '') {
            $this->flash('error', 'กรุณาเลือกหมวดหมู่');
            $this->redirect('admin/documents/create');
        }
        if (empty($_FILES['file']['name'])) {
            $this->flash('error', 'กรุณาเลือกไฟล์');
            $this->redirect('admin/documents/create');
        }

        $path = Upload::document($_FILES['file'], 'documents');
        if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/documents/create'); }

        $data = $this->collect();
        $data['file_path'] = $path;

        if (!empty($_FILES['cover_image']['name'])) {
            $coverPath = Upload::image($_FILES['cover_image'], 'documents');
            if ($coverPath === null) { $this->flash('error', Upload::$error); $this->redirect('admin/documents/create'); }
            $data['cover_image'] = $coverPath;
        }

        $this->model('Document')->create($data);
        audit_log('create', 'documents', 'เพิ่มเอกสาร ' . $data['title']);
        $this->flash('success', 'เพิ่มเอกสารเรียบร้อยแล้ว');
        $this->redirect('admin/documents');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Document')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบเอกสาร'); $this->redirect('admin/documents'); }
        $this->adminView('documents/form', [
            'title' => 'แก้ไขเอกสาร', 'item' => $item,
            'categories' => $this->suggestedCategories(),
        ]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/documents'); }
        $this->verifyCsrf();
        $model = $this->model('Document');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบเอกสาร'); $this->redirect('admin/documents'); }
        if ($this->input('category') === '') {
            $this->flash('error', 'กรุณาเลือกหมวดหมู่');
            $this->redirect('admin/documents/edit/' . $id);
        }

        $data = $this->collect();
        if (!empty($_FILES['file']['name'])) {
            $path = Upload::document($_FILES['file'], 'documents');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/documents/edit/' . $id); }
            Upload::remove($item['file_path']);
            $data['file_path'] = $path;
        }
        if (!empty($_FILES['cover_image']['name'])) {
            $coverPath = Upload::image($_FILES['cover_image'], 'documents');
            if ($coverPath === null) { $this->flash('error', Upload::$error); $this->redirect('admin/documents/edit/' . $id); }
            Upload::remove($item['cover_image']);
            $data['cover_image'] = $coverPath;
        }

        $model->update($id, $data);
        audit_log('update', 'documents', "แก้ไขเอกสาร #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/documents');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('Document')->find($id);
        if ($item) { Upload::remove($item['file_path']); Upload::remove($item['cover_image']); }
        $this->model('Document')->delete($id);
        audit_log('delete', 'documents', "ลบเอกสาร #{$id}");
        $this->flash('success', 'ลบเอกสารแล้ว');
        $this->redirect('admin/documents');
    }

    /** รวมหมวดหมู่แนะนำ + หมวดที่เคยใช้จริงในฐานข้อมูล (ไม่ซ้ำ) */
    private function suggestedCategories(): array
    {
        return array_values(array_unique(array_merge(
            self::SUGGESTED_CATEGORIES,
            $this->model('Document')->categories()
        )));
    }

    private function collect(): array
    {
        return [
            'title'      => $this->input('title'),
            'doc_number' => $this->input('doc_number', ''),
            'category'   => $this->input('category', ''),
            'doc_date'   => $this->input('doc_date') ?: null,
            'status'     => $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
