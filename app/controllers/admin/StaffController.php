<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;
use Staff;

/**
 * Admin\StaffController — จัดการบุคลากร/อาจารย์ (CRUD)
 */
class StaffController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('staff.manage');
    }

    public function index(): void
    {
        $staff = $this->model('Staff')->listAll();
        $this->adminView('staff/index', ['title' => 'จัดการบุคลากร/อาจารย์', 'staff' => $staff]);
    }

    public function create(): void
    {
        $this->adminView('staff/form', [
            'title'       => 'เพิ่มบุคลากร',
            'item'        => null,
            'departments' => $this->model('Department')->all('name ASC'),
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/staff'); }
        $this->verifyCsrf();

        if ($this->input('full_name') === '') {
            $this->flash('error', 'กรุณากรอกชื่อ-นามสกุล');
            $this->redirect('admin/staff/create');
        }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'staff');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/staff/create'); }
            $data['photo'] = $path;
        }

        $this->model('Staff')->create($data);
        audit_log('create', 'staff', 'เพิ่มบุคลากร');
        $this->flash('success', 'เพิ่มบุคลากรเรียบร้อยแล้ว');
        $this->redirect('admin/staff');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Staff')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/staff'); }
        $this->adminView('staff/form', [
            'title'       => 'แก้ไขบุคลากร',
            'item'        => $item,
            'departments' => $this->model('Department')->all('name ASC'),
        ]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/staff'); }
        $this->verifyCsrf();
        $model = $this->model('Staff');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/staff'); }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'staff');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/staff/edit/' . $id); }
            Upload::remove($item['photo']);
            $data['photo'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'staff', "แก้ไขบุคลากร #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/staff');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('Staff')->find($id);
        if ($item) { Upload::remove($item['photo']); }
        $this->model('Staff')->forceDelete($id);
        audit_log('delete', 'staff', "ลบบุคลากร #{$id}");
        $this->flash('success', 'ลบบุคลากรแล้ว');
        $this->redirect('admin/staff');
    }

    private function collect(): array
    {
        $type = $this->input('type', 'teacher');
        if (!array_key_exists($type, Staff::TYPES)) { $type = 'teacher'; }

        $deptId = (int)$this->input('department_id', 0);

        return [
            'full_name'     => $this->input('full_name'),
            'position'      => $this->input('position', ''),
            'group_name'    => $this->input('group_name', '') ?: null,
            'type'          => $type,
            'department_id' => $deptId ?: null,
            'email'         => $this->input('email', ''),
            'phone'         => $this->input('phone', ''),
            'bio'           => $this->input('bio', ''),
            'expertise'     => $this->input('expertise', ''),
            'sort_order'    => (int)$this->input('sort_order', 0),
            'status'        => $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
