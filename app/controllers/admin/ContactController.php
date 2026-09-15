<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

/**
 * Admin\ContactController — กล่องข้อความติดต่อ
 */
class ContactController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('contact.view');
    }

    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $pager = $this->model('Contact')->paginate($page, ADMIN_PER_PAGE, [], 'is_read ASC, id DESC');
        $this->adminView('contact/index', ['title' => 'ข้อความติดต่อ', 'pager' => $pager]);
    }

    public function show(int $id = 0): void
    {
        $model = $this->model('Contact');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบข้อความ'); $this->redirect('admin/contact'); }
        if (!$item['is_read']) { $model->markRead($id); }
        $this->adminView('contact/view', ['title' => 'รายละเอียดข้อความ', 'item' => $item]);
    }

    public function delete(int $id = 0): void
    {
        $this->model('Contact')->delete($id);
        audit_log('delete', 'contact', "ลบข้อความ #{$id}");
        $this->flash('success', 'ลบข้อความแล้ว');
        $this->redirect('admin/contact');
    }
}
