<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;

/**
 * Admin\AlumniController — จัดการศิษย์เก่า (CRUD)
 */
class AlumniController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('staff.manage');
    }

    public function index(): void
    {
        $alumni = $this->model('Alumni')->listAll();
        $this->adminView('alumni/index', ['title' => 'จัดการศิษย์เก่า', 'alumni' => $alumni]);
    }

    public function create(): void
    {
        $this->adminView('alumni/form', ['title' => 'เพิ่มศิษย์เก่า', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/alumni'); }
        $this->verifyCsrf();

        if ($this->input('full_name') === '') {
            $this->flash('error', 'กรุณากรอกชื่อ-นามสกุล');
            $this->redirect('admin/alumni/create');
        }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'alumni');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/alumni/create'); }
            $data['photo'] = $path;
        }

        $this->model('Alumni')->create($data);
        audit_log('create', 'alumni', 'เพิ่มศิษย์เก่า');
        $this->flash('success', 'เพิ่มศิษย์เก่าเรียบร้อยแล้ว');
        $this->redirect('admin/alumni');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Alumni')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/alumni'); }
        $this->adminView('alumni/form', ['title' => 'แก้ไขศิษย์เก่า', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/alumni'); }
        $this->verifyCsrf();
        $model = $this->model('Alumni');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/alumni'); }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'alumni');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/alumni/edit/' . $id); }
            Upload::remove($item['photo']);
            $data['photo'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'alumni', "แก้ไขศิษย์เก่า #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/alumni');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('Alumni')->find($id);
        if ($item) { Upload::remove($item['photo']); }
        $this->model('Alumni')->forceDelete($id);
        audit_log('delete', 'alumni', "ลบศิษย์เก่า #{$id}");
        $this->flash('success', 'ลบศิษย์เก่าแล้ว');
        $this->redirect('admin/alumni');
    }

    private function collect(): array
    {
        return [
            'full_name'     => $this->input('full_name'),
            'graduate_year' => $this->input('graduate_year', ''),
            'program'       => $this->input('program', ''),
            'current_job'   => $this->input('current_job', ''),
            'organization'  => $this->input('organization', ''),
            'quote'         => $this->input('quote', ''),
            'is_featured'   => !empty($this->input('is_featured')) ? 1 : 0,
            'sort_order'    => (int)$this->input('sort_order', 0),
            'status'        => $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
