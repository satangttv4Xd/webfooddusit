<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

/**
 * Admin\RoleController — จัดการบทบาท (Roles) และสิทธิ์ที่ผูกกับบทบาท
 * ต้องมีสิทธิ์ 'roles.manage' (โดยปกติมีแต่ super_admin)
 */
class RoleController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('roles.manage');
    }

    public function index(): void
    {
        $roles = $this->model('Role')->allWithCounts();
        $this->adminView('roles/index', ['title' => 'จัดการบทบาท (Roles)', 'roles' => $roles]);
    }

    public function create(): void
    {
        $this->adminView('roles/form', [
            'title'       => 'เพิ่มบทบาท',
            'item'        => null,
            'permissions' => $this->model('Permission')->groupedByModule(),
            'checked'     => [],
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/roles'); }
        $this->verifyCsrf();

        [$name, $slug, $description, $status] = $this->collect();
        if ($name === '' || $slug === '') {
            $this->flash('error', 'กรุณากรอกชื่อบทบาทและรหัส (slug)');
            $this->redirect('admin/roles/create');
        }

        $model = $this->model('Role');
        if ($model->findWhere(['slug' => $slug])) {
            $this->flash('error', 'รหัสบทบาท (slug) นี้มีอยู่แล้ว');
            $this->redirect('admin/roles/create');
        }

        $id = $model->create([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'status'      => $status,
        ]);
        $model->syncPermissions($id, (array)$this->input('permissions', []));

        audit_log('create', 'roles', "เพิ่มบทบาท {$name} ({$slug})");
        $this->flash('success', 'เพิ่มบทบาทเรียบร้อยแล้ว');
        $this->redirect('admin/roles');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Role')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบบทบาท'); $this->redirect('admin/roles'); }

        $this->adminView('roles/form', [
            'title'       => 'แก้ไขบทบาท',
            'item'        => $item,
            'permissions' => $this->model('Permission')->groupedByModule(),
            'checked'     => $this->model('Role')->permissionIds($id),
        ]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/roles'); }
        $this->verifyCsrf();

        $model = $this->model('Role');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบบทบาท'); $this->redirect('admin/roles'); }

        [$name, $slug, $description, $status] = $this->collect();
        if ($name === '' || $slug === '') {
            $this->flash('error', 'กรุณากรอกชื่อบทบาทและรหัส (slug)');
            $this->redirect('admin/roles/edit/' . $id);
        }

        // slug ซ้ำกับบทบาทอื่นหรือไม่
        $dup = $model->findWhere(['slug' => $slug]);
        if ($dup && (int)$dup['id'] !== $id) {
            $this->flash('error', 'รหัสบทบาท (slug) นี้มีอยู่แล้ว');
            $this->redirect('admin/roles/edit/' . $id);
        }

        $data = ['name' => $name, 'description' => $description, 'status' => $status];
        // ห้ามเปลี่ยน slug ของ super_admin เพราะโค้ดอ้างอิงอยู่
        if ($item['slug'] !== 'super_admin') {
            $data['slug'] = $slug;
        }
        $model->update($id, $data);

        // super_admin ผ่านทุกสิทธิ์อยู่แล้ว ไม่ต้องแก้ตารางสิทธิ์
        if ($item['slug'] !== 'super_admin') {
            $model->syncPermissions($id, (array)$this->input('permissions', []));
        }

        audit_log('update', 'roles', "แก้ไขบทบาท #{$id} ({$item['slug']})");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/roles');
    }

    public function delete(int $id = 0): void
    {
        $model = $this->model('Role');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบบทบาท'); $this->redirect('admin/roles'); }

        // ห้ามลบบทบาทระบบหลัก
        if (in_array($item['slug'], ['super_admin', 'admin'], true)) {
            $this->flash('error', 'ไม่สามารถลบบทบาทหลักของระบบได้');
            $this->redirect('admin/roles');
        }
        // ห้ามลบบทบาทที่ยังมีผู้ใช้อยู่
        if ($model->userCount($id) > 0) {
            $this->flash('error', 'ไม่สามารถลบได้ เพราะยังมีผู้ใช้อยู่ในบทบาทนี้ (ย้ายผู้ใช้ไปบทบาทอื่นก่อน)');
            $this->redirect('admin/roles');
        }

        $model->delete($id);
        audit_log('delete', 'roles', "ลบบทบาท #{$id} ({$item['slug']})");
        $this->flash('success', 'ลบบทบาทแล้ว');
        $this->redirect('admin/roles');
    }

    /** รวบรวมและทำความสะอาด input จากฟอร์ม → [name, slug, description, status] */
    private function collect(): array
    {
        $name = trim((string)$this->input('name'));
        $slug = trim((string)$this->input('slug'));
        // slug บทบาทใช้ตัวพิมพ์เล็ก a-z, 0-9 และ _ เท่านั้น (สอดคล้องกับ super_admin ในโค้ด)
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9_]+/', '_', $slug);
        $slug = trim($slug, '_');
        $description = trim((string)$this->input('description', ''));
        $status = $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active';
        return [$name, $slug, $description, $status];
    }
}
