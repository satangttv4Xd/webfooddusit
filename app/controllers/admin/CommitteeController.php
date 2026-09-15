<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Upload;
use SchoolCommittee;

/**
 * Admin\CommitteeController — จัดการคณะกรรมการประจำโรงเรียนการเรือน (CRUD)
 */
class CommitteeController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('committee.manage');
    }

    public function index(): void
    {
        $items = $this->model('SchoolCommittee')->listAll();
        $this->adminView('committee/index', ['title' => 'จัดการคณะกรรมการประจำโรงเรียนการเรือน', 'items' => $items]);
    }

    public function create(): void
    {
        $this->adminView('committee/form', [
            'title' => 'เพิ่มกรรมการ', 'item' => null,
            'groups' => SchoolCommittee::GROUPS,
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/committee'); }
        $this->verifyCsrf();

        if ($this->input('full_name') === '') {
            $this->flash('error', 'กรุณากรอกชื่อ-นามสกุล');
            $this->redirect('admin/committee/create');
        }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'committee');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/committee/create'); }
            $data['photo'] = $path;
        }

        $this->model('SchoolCommittee')->create($data);
        audit_log('create', 'school_committee', 'เพิ่มกรรมการ ' . $data['full_name']);
        $this->flash('success', 'เพิ่มกรรมการเรียบร้อยแล้ว');
        $this->redirect('admin/committee');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('SchoolCommittee')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/committee'); }
        $this->adminView('committee/form', [
            'title' => 'แก้ไขกรรมการ', 'item' => $item,
            'groups' => SchoolCommittee::GROUPS,
        ]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/committee'); }
        $this->verifyCsrf();
        $model = $this->model('SchoolCommittee');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อมูล'); $this->redirect('admin/committee'); }

        $data = $this->collect();
        if (!empty($_FILES['photo']['name'])) {
            $path = Upload::image($_FILES['photo'], 'committee');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/committee/edit/' . $id); }
            Upload::remove($item['photo']);
            $data['photo'] = $path;
        }

        $model->update($id, $data);
        audit_log('update', 'school_committee', "แก้ไขกรรมการ #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/committee');
    }

    public function delete(int $id = 0): void
    {
        $item = $this->model('SchoolCommittee')->find($id);
        if ($item) { Upload::remove($item['photo']); }
        $this->model('SchoolCommittee')->forceDelete($id);
        audit_log('delete', 'school_committee', "ลบกรรมการ #{$id}");
        $this->flash('success', 'ลบข้อมูลแล้ว');
        $this->redirect('admin/committee');
    }

    private function collect(): array
    {
        $group = $this->input('group_name', '');
        if (!in_array($group, SchoolCommittee::GROUPS, true)) {
            $group = SchoolCommittee::GROUPS[0];
        }
        return [
            'full_name'  => $this->input('full_name'),
            'position'   => $this->input('position', ''),
            'bio'        => $this->input('bio', ''),
            'group_name' => $group,
            'sort_order' => (int)$this->input('sort_order', 0),
            'status'     => $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
        ];
    }
}
