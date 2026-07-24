<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;
use Upload;

/**
 * Admin\ProgramController — จัดการหลักสูตร (CRUD)
 */
class ProgramController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('programs.manage');
    }

    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $q = trim((string)$this->query('q', ''));
        $searchSql = $q !== '' ? 'name LIKE :q' : '';
        $searchParams = $q !== '' ? ['q' => "%{$q}%"] : [];
        $pager = $this->model('Program')->paginate($page, ADMIN_PER_PAGE, [], 'sort_order ASC, id DESC', $searchSql, $searchParams);
        $this->adminView('programs/index', ['title' => 'จัดการหลักสูตร', 'pager' => $pager, 'q' => $q]);
    }

    public function create(): void
    {
        $this->adminView('programs/form', [
            'title' => 'เพิ่มหลักสูตร', 'program' => null,
            'departments' => $this->model('Department')->activeAll(),
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/programs'); }
        $this->verifyCsrf();
        $data = $this->collect();
        if ($data['name'] === '') { $this->flash('error', 'กรุณากรอกชื่อหลักสูตร'); $this->redirect('admin/programs/create'); }

        $data = $this->handleFiles($data, null, 'admin/programs/create');
        $id = $this->model('Program')->create($data);
        audit_log('create', 'programs', "เพิ่มหลักสูตร #{$id}");
        $this->flash('success', 'เพิ่มหลักสูตรเรียบร้อยแล้ว');
        $this->redirect('admin/programs');
    }

    public function edit(int $id = 0): void
    {
        $program = $this->model('Program')->find($id);
        if (!$program) { $this->flash('error', 'ไม่พบหลักสูตร'); $this->redirect('admin/programs'); }
        $this->adminView('programs/form', [
            'title' => 'แก้ไขหลักสูตร', 'program' => $program,
            'departments' => $this->model('Department')->activeAll(),
        ]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/programs'); }
        $this->verifyCsrf();
        $model = $this->model('Program');
        $program = $model->find($id);
        if (!$program) { $this->flash('error', 'ไม่พบหลักสูตร'); $this->redirect('admin/programs'); }

        $data = $this->collect();
        $data = $this->handleFiles($data, $program, 'admin/programs/edit/' . $id);
        $model->update($id, $data);
        audit_log('update', 'programs', "แก้ไขหลักสูตร #{$id}");
        $this->flash('success', 'บันทึกการแก้ไขเรียบร้อยแล้ว');
        $this->redirect('admin/programs');
    }

    public function delete(int $id = 0): void
    {
        $this->model('Program')->delete($id);
        audit_log('delete', 'programs', "ลบหลักสูตร #{$id}");
        $this->flash('success', 'ลบหลักสูตรแล้ว');
        $this->redirect('admin/programs');
    }

    public function restore(int $id = 0): void
    {
        $this->model('Program')->restore($id);
        $this->flash('success', 'กู้คืนแล้ว');
        $this->redirect('admin/programs');
    }

    private function collect(): array
    {
        $name = $this->input('name');
        return [
            'name'        => $name,
            'name_en'     => $this->input('name_en', ''),
            'slug'        => $this->input('slug') ?: Security::slug($name),
            'department_id' => (int)$this->input('department_id') ?: null,
            'level'       => $this->input('level', 'bachelor'),
            'degree'      => $this->input('degree', ''),
            'duration'    => $this->input('duration', ''),
            'credits'     => $this->input('credits', ''),
            'tuition_fee' => $this->input('tuition_fee', ''),
            'overview'    => $this->input('overview', ''),
            'qualification' => $this->input('qualification', ''),
            'careers'     => $this->input('careers', ''),
            'apply_url'   => $this->input('apply_url', ''),
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'sort_order'  => (int)$this->input('sort_order', 0),
            'status'      => $this->input('status', 'active'),
        ];
    }

    private function handleFiles(array $data, ?array $old, string $backUrl): array
    {
        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'programs');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect($backUrl); }
            if ($old) Upload::remove($old['cover_image']);
            $data['cover_image'] = $path;
        }
        if (!empty($_FILES['pdf_file']['name'])) {
            $pdf = Upload::document($_FILES['pdf_file'], 'programs');
            if ($pdf === null) { $this->flash('error', Upload::$error); $this->redirect($backUrl); }
            if ($old) Upload::remove($old['pdf_file']);
            $data['pdf_file'] = $pdf;
        }
        return $data;
    }
}
