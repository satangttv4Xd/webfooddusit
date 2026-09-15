<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;
use Auth;
use Upload;

/**
 * Admin\UserController — จัดการผู้ใช้งาน (CRUD) + โปรไฟล์ตนเอง
 */
class UsersController extends AdminController
{
    public function index(): void
    {
        $this->requirePermission('users.manage');
        $users = $this->model('User')->allWithRole();
        $this->adminView('users/index', ['title' => 'จัดการผู้ใช้งาน', 'users' => $users]);
    }

    public function create(): void
    {
        $this->requirePermission('users.manage');
        $this->adminView('users/form', [
            'title' => 'เพิ่มผู้ใช้', 'item' => null,
            'roles' => $this->model('Role')->activeAll(),
        ]);
    }

    public function store(): void
    {
        $this->requirePermission('users.manage');
        if (!$this->isPost()) { $this->redirect('admin/users'); }
        $this->verifyCsrf();

        $username = $this->input('username');
        $email = $this->input('email');
        $password = $this->input('password');

        if ($username === '' || $email === '' || $password === '') {
            $this->flash('error', 'กรุณากรอกข้อมูลให้ครบ (username, email, password)');
            $this->redirect('admin/users/create');
        }
        if (!Security::isEmail($email)) { $this->flash('error', 'อีเมลไม่ถูกต้อง'); $this->redirect('admin/users/create'); }
        if (mb_strlen($password) < 6) { $this->flash('error', 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร'); $this->redirect('admin/users/create'); }

        // กันซ้ำ
        $model = $this->model('User');
        if ($model->findWhere(['username' => $username])) { $this->flash('error', 'ชื่อผู้ใช้นี้มีอยู่แล้ว'); $this->redirect('admin/users/create'); }
        if ($model->findWhere(['email' => $email])) { $this->flash('error', 'อีเมลนี้มีอยู่แล้ว'); $this->redirect('admin/users/create'); }

        $model->create([
            'role_id'   => (int)$this->input('role_id'),
            'username'  => $username,
            'email'     => $email,
            'password'  => Security::hashPassword($password),
            'full_name' => $this->input('full_name', $username),
            'phone'     => $this->input('phone', ''),
            'position'  => $this->input('position', ''),
            'status'    => $this->input('status', 'active'),
        ]);
        audit_log('create', 'users', "เพิ่มผู้ใช้ {$username}");
        $this->flash('success', 'เพิ่มผู้ใช้เรียบร้อยแล้ว');
        $this->redirect('admin/users');
    }

    public function edit(int $id = 0): void
    {
        $this->requirePermission('users.manage');
        $item = $this->model('User')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบผู้ใช้'); $this->redirect('admin/users'); }
        $this->adminView('users/form', [
            'title' => 'แก้ไขผู้ใช้', 'item' => $item,
            'roles' => $this->model('Role')->activeAll(),
        ]);
    }

    public function update(int $id = 0): void
    {
        $this->requirePermission('users.manage');
        if (!$this->isPost()) { $this->redirect('admin/users'); }
        $this->verifyCsrf();

        $model = $this->model('User');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบผู้ใช้'); $this->redirect('admin/users'); }

        $data = [
            'role_id'   => (int)$this->input('role_id'),
            'email'     => $this->input('email'),
            'full_name' => $this->input('full_name'),
            'phone'     => $this->input('phone', ''),
            'position'  => $this->input('position', ''),
            'status'    => $this->input('status', 'active'),
        ];
        // เปลี่ยนรหัสผ่านเฉพาะเมื่อกรอกใหม่
        $password = $this->input('password');
        if ($password !== '') {
            if (mb_strlen($password) < 6) { $this->flash('error', 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร'); $this->redirect('admin/users/edit/' . $id); }
            $data['password'] = Security::hashPassword($password);
        }
        $model->update($id, $data);
        audit_log('update', 'users', "แก้ไขผู้ใช้ #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/users');
    }

    public function delete(int $id = 0): void
    {
        $this->requirePermission('users.manage');
        // ห้ามลบตัวเอง
        if ($id === Auth::id()) { $this->flash('error', 'ไม่สามารถลบบัญชีตนเองได้'); $this->redirect('admin/users'); }
        $this->model('User')->delete($id);
        audit_log('delete', 'users', "ลบผู้ใช้ #{$id}");
        $this->flash('success', 'ลบผู้ใช้แล้ว');
        $this->redirect('admin/users');
    }

    /** โปรไฟล์ของตนเอง (ทุก role เข้าได้) */
    public function profile(): void
    {
        $item = $this->model('User')->find(Auth::id());
        $this->adminView('users/profile', ['title' => 'โปรไฟล์ของฉัน', 'item' => $item]);
    }

    public function saveProfile(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/users/profile'); }
        $this->verifyCsrf();
        $model = $this->model('User');
        $id = Auth::id();

        $data = [
            'full_name' => $this->input('full_name'),
            'email'     => $this->input('email'),
            'phone'     => $this->input('phone', ''),
        ];
        $password = $this->input('password');
        if ($password !== '') {
            if (mb_strlen($password) < 6) { $this->flash('error', 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร'); $this->redirect('admin/users/profile'); }
            $data['password'] = Security::hashPassword($password);
        }
        $model->update($id, $data);
        // อัปเดตชื่อใน session
        $_SESSION['user']['name'] = $data['full_name'];
        $this->flash('success', 'บันทึกโปรไฟล์เรียบร้อยแล้ว');
        $this->redirect('admin/users/profile');
    }
}
