<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;

/**
 * Admin\DeansController — จัดการทำเนียบคณบดี (CRUD)
 */
class DeansController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('staff.manage');
    }

    public function index(): void
    {
        $deans = $this->model('Dean')->listAll();
        $this->adminView('deans/index', ['title' => 'จัดการทำเนียบคณบดี', 'deans' => $deans]);
    }

    public function create(): void
    {
        $this->adminView('deans/form', ['title' => 'เพิ่มคณบดี', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/deans'); }
        $this->verifyCsrf();

        if ($this->input('full_name') === '') {
            $this->flash('error', 'กรุณากรอกชื่อ-นามสกุล');
            $this->redirect('admin/deans/create');
        }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'deans');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/deans/create'); }
            $data['photo'] = $path;
        }

        $this->model('Dean')->create($data);
        audit_log('create', 'deans', 'เพิ่มคณบดี');
        $this->flash('success', 'เพิ่มคณบดีเรียบร้อยแล้ว');
        $this->redirect('admin/deans');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Dean')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/deans'); }
        $this->adminView('deans/form', ['title' => 'แก้ไขคณบดี', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/deans'); }
        $this->verifyCsrf();
        $model = $this->model('Dean');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/deans'); }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'deans');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/deans/edit/' . $id); }
            Upload::remove($item['photo']);
            $data['photo'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'deans', "แก้ไขคณบดี #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/deans');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('Dean')->find($id);
        if ($item) { Upload::remove($item['photo']); }
        $this->model('Dean')->forceDelete($id);
        audit_log('delete', 'deans', "ลบคณบดี #{$id}");
        $this->flash('success', 'ลบคณบดีแล้ว');
        $this->redirect('admin/deans');
    }

    private function collect(): array
    {
        return [
            'full_name'  => $this->input('full_name'),
            'position'   => $this->input('position', 'คณบดี') ?: 'คณบดี',
            'term'       => $this->input('term', ''),
            'bio'        => $this->input('bio', ''),
            'sort_order' => (int)$this->input('sort_order', 0),
            'status'     => $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
