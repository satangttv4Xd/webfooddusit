<?php
namespace Admin;

use Controller;
use Auth;
use Security;
use User;

/**
 * ============================================================
 *  Admin\AuthController — เข้าสู่ระบบ / ออกจากระบบ (หลังบ้าน)
 * ------------------------------------------------------------
 *  ไม่ extends AdminController เพราะหน้านี้ยังไม่ต้องล็อกอิน
 * ============================================================
 */
class AuthController extends Controller
{
    /** แสดงหน้าฟอร์มล็อกอิน */
    public function login(): void
    {
        // ถ้าล็อกอินอยู่แล้วให้ไป dashboard
        if (Auth::check()) {
            $this->redirect('admin');
        }
        $this->view('admin/auth/login', ['title' => 'เข้าสู่ระบบ'], 'blank');
    }

    /** ประมวลผลการล็อกอิน (POST) */
    public function authenticate(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/auth/login'); }
        $this->verifyCsrf();

        $login    = $this->input('login');
        $password = $this->input('password');
        $remember = !empty($this->input('remember'));

        if ($login === '' || $password === '') {
            $this->flash('error', 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน');
            $this->redirect('admin/auth/login');
        }

        /** @var User $userModel */
        $userModel = $this->model('User');
        $user = $userModel->findForLogin($login);

        // ไม่พบผู้ใช้
        if (!$user) {
            $this->flash('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
            $this->redirect('admin/auth/login');
        }

        // ตรวจบัญชีถูกล็อกจากการล็อกอินผิดหลายครั้ง
        if (!empty($user['locked_until'])) {
            if (strtotime($user['locked_until']) > time()) {
                $mins = ceil((strtotime($user['locked_until']) - time()) / 60);
                $this->flash('error', "บัญชีถูกล็อกชั่วคราว กรุณาลองใหม่ใน {$mins} นาที");
                $this->redirect('admin/auth/login');
            }
            // ล็อกหมดอายุแล้ว → รีเซ็ตตัวนับให้เริ่มลองใหม่ได้ครบจำนวน
            $userModel->resetAttempts((int)$user['id']);
            $user['login_attempts'] = 0;
        }

        // ตรวจสถานะบัญชี
        if ($user['status'] !== 'active') {
            $this->flash('error', 'บัญชีนี้ถูกปิดใช้งาน');
            $this->redirect('admin/auth/login');
        }

        // ตรวจรหัสผ่าน
        if (!Security::verifyPassword($password, $user['password'])) {
            $userModel->recordLoginFail((int)$user['id'], (int)$user['login_attempts']);
            $this->flash('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
            $this->redirect('admin/auth/login');
        }

        // ล็อกอินสำเร็จ
        $permissions = $userModel->permissions((int)$user['role_id']);
        Auth::login($user, $permissions);
        $userModel->recordLoginSuccess((int)$user['id']);

        // Remember me (ตั้งคุกกี้อายุยาว)
        if ($remember) {
            $token = Security::randomToken(32);
            $userModel->update((int)$user['id'], ['remember_token' => $token]);
            setcookie('remember_token', $token, [
                'expires'  => time() + REMEMBER_ME_DAYS * 86400,
                'path'     => '/', 'httponly' => true, 'samesite' => 'Lax',
            ]);
        }

        audit_log('login', 'auth', 'เข้าสู่ระบบ');
        $this->flash('success', 'ยินดีต้อนรับ ' . ($user['full_name'] ?? $user['username']));
        $this->redirect('admin');
    }

    /** ออกจากระบบ */
    public function logout(): void
    {
        audit_log('logout', 'auth', 'ออกจากระบบ');
        Auth::logout();
        setcookie('remember_token', '', time() - 3600, '/');
        $this->flash('success', 'ออกจากระบบเรียบร้อยแล้ว');
        $this->redirect('admin/auth/login');
    }
}
