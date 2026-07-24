<?php
namespace Admin;

use Controller;
use Auth;

/**
 * ============================================================
 *  AdminController — คลาสแม่ของทุก Controller ฝั่งหลังบ้าน
 * ------------------------------------------------------------
 *  ตรวจสอบการล็อกอินและสิทธิ์ก่อนเข้าถึงทุกหน้า admin
 * ============================================================
 */
abstract class AdminController extends Controller
{
    public function __construct()
    {
        // ต้องล็อกอินก่อนเสมอ (ยกเว้นหน้า login จะ override)
        if (!Auth::check()) {
            $this->flash('warning', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
            $this->redirect('admin/auth/login');
        }
    }

    /**
     * บังคับให้ต้องมีสิทธิ์ที่ระบุ ไม่งั้นเด้งออก
     */
    protected function requirePermission(string $permission): void
    {
        if (!Auth::can($permission)) {
            http_response_code(403);
            $this->adminView('errors/403', ['title' => 'ไม่มีสิทธิ์เข้าถึง']);
            exit;
        }
    }

    /**
     * บังคับ role ที่กำหนด
     */
    protected function requireRole(string ...$roles): void
    {
        if (!Auth::hasRole(...$roles) && !Auth::isSuperAdmin()) {
            http_response_code(403);
            $this->adminView('errors/403', ['title' => 'ไม่มีสิทธิ์เข้าถึง']);
            exit;
        }
    }
}
