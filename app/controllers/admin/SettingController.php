<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

/**
 * Admin\SettingController — ตั้งค่าเว็บไซต์
 */
class SettingController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('settings.manage');
    }

    public function index(): void
    {
        $settings = $this->model('Setting')->all();
        // จัดกลุ่มตาม group
        $grouped = [];
        foreach ($settings as $s) { $grouped[$s['group']][] = $s; }
        $this->adminView('settings/index', ['title' => 'ตั้งค่าเว็บไซต์', 'grouped' => $grouped]);
    }

    public function update(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/settings'); }
        $this->verifyCsrf();

        $data = $_POST['settings'] ?? [];
        // ล้างค่าที่ไม่ปลอดภัย ยกเว้น google_map ที่ยอมให้เป็น iframe
        foreach ($data as $key => $val) {
            if ($key !== 'google_map') {
                $data[$key] = trim($val);
            }
        }
        $this->model('Setting')->saveMany($data);
        audit_log('update', 'settings', 'แก้ไขการตั้งค่าเว็บไซต์');
        $this->flash('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
        $this->redirect('admin/settings');
    }
}
