<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;
use Upload;

/**
 * Admin\EventController — จัดการกิจกรรม (CRUD) + ดูผู้ลงทะเบียน
 */
class EventsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('events.manage');
    }

    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $pager = $this->model('Event')->paginate($page, ADMIN_PER_PAGE, [], 'start_date DESC');
        $this->adminView('events/index', ['title' => 'จัดการกิจกรรม', 'pager' => $pager]);
    }

    public function create(): void
    {
        $this->adminView('events/form', ['title' => 'เพิ่มกิจกรรม', 'item' => null]);
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/events'); }
        $this->verifyCsrf();
        if ($this->input('title') === '' || $this->input('start_date') === '') {
            $this->flash('error', 'กรุณากรอกชื่อและวันที่เริ่ม'); $this->redirect('admin/events/create');
        }
        $eventModel = $this->model('Event');
        $data = $this->collect();
        $data['slug'] = $eventModel->uniqueSlug($data['slug']);
        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'gallery');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/events/create'); }
            $data['cover_image'] = $path;
        }
        $eventModel->create($data);
        audit_log('create', 'events', 'เพิ่มกิจกรรม');
        $this->flash('success', 'เพิ่มกิจกรรมเรียบร้อยแล้ว');
        $this->redirect('admin/events');
    }

    public function edit(int $id = 0): void
    {
        $item = $this->model('Event')->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบกิจกรรม'); $this->redirect('admin/events'); }
        $this->adminView('events/form', ['title' => 'แก้ไขกิจกรรม', 'item' => $item]);
    }

    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/events'); }
        $this->verifyCsrf();
        $model = $this->model('Event');
        $item = $model->find($id);
        if (!$item) { $this->flash('error', 'ไม่พบกิจกรรม'); $this->redirect('admin/events'); }
        $data = $this->collect();
        $data['slug'] = $model->uniqueSlug($data['slug'], $id);
        if (!empty($_FILES['cover_image']['name'])) {
            $path = Upload::image($_FILES['cover_image'], 'gallery');
            if ($path === null) { $this->flash('error', Upload::$error); $this->redirect('admin/events/edit/' . $id); }
            Upload::remove($item['cover_image']);
            $data['cover_image'] = $path;
        }
        $model->update($id, $data);
        audit_log('update', 'events', "แก้ไขกิจกรรม #{$id}");
        $this->flash('success', 'บันทึกเรียบร้อยแล้ว');
        $this->redirect('admin/events');
    }

    public function delete(int $id = 0): void
    {
        $this->model('Event')->delete($id);
        $this->flash('success', 'ลบกิจกรรมแล้ว');
        $this->redirect('admin/events');
    }

    /** ดูรายชื่อผู้ลงทะเบียน */
    public function registrations(int $id = 0): void
    {
        $event = $this->model('Event')->find($id);
        if (!$event) { $this->flash('error', 'ไม่พบกิจกรรม'); $this->redirect('admin/events'); }
        $regs = $this->model('EventRegistration')->where(['event_id' => $id], 'id DESC');
        $this->adminView('events/registrations', ['title' => 'ผู้ลงทะเบียน: ' . $event['title'], 'event' => $event, 'regs' => $regs]);
    }

    private function collect(): array
    {
        $title = $this->input('title');
        return [
            'title'         => $title,
            'slug'          => $this->input('slug') ?: Security::slug($title),
            'description'   => $_POST['description'] ?? '',
            'location'      => $this->input('location', ''),
            'start_date'    => $this->input('start_date'),
            'end_date'      => $this->input('end_date') ?: null,
            'max_attendees' => (int)$this->input('max_attendees') ?: null,
            'allow_register'=> $this->input('allow_register') ? 1 : 0,
            'is_featured'   => $this->input('is_featured') ? 1 : 0,
            'status'        => $this->input('status', 'active'),
        ];
    }
}
