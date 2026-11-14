<?php
namespace Admin;

require_once __DIR__ . '/AdminController.php';

use Security;

/**
 * Admin\SchedulesController — จัดการปฏิทินงาน / ภารกิจ (เพิ่ม ลบ แก้ไข ดูปฏิทิน)
 */
class SchedulesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('schedules.manage');
    }

    /**
     * หน้าแรก: มุมมองปฏิทินงาน (Calendar View)
     */
    public function index(): void
    {
        // เรียก Model เพื่อกระตุ้น Auto-migration สร้างตารางอัตโนมัติหากยังไม่มี
        $this->model('Schedule');
        $this->adminView('schedules/calendar', [
            'title' => 'ปฏิทินการปฏิบัติงาน',
        ]);
    }

    /**
     * ดึงข้อมูล Feed สำหรับ FullCalendar ในหน้าแอดมิน
     */
    public function feed(): void
    {
        $start = trim($this->query('start', ''));
        $end   = trim($this->query('end', ''));

        if ($start === '') {
            $start = date('Y-m-01', strtotime('-2 months'));
        } else {
            $start = date('Y-m-d', strtotime($start));
        }

        if ($end === '') {
            $end = date('Y-m-t', strtotime('+3 months'));
        } else {
            $end = date('Y-m-d', strtotime($end));
        }

        $items = $this->model('Schedule')->getBetweenDates($start, $end, false);

        $events = [];
        foreach ($items as $item) {
            $color = $item['color'] ?: '#0d6efd';
            if ($item['status'] === 'cancelled') {
                $color = '#6c757d';
            } elseif ($item['status'] === 'completed') {
                $color = '#198754';
            }

            $events[] = [
                'id'              => $item['id'],
                'title'           => $item['title'],
                'start'           => date('c', strtotime($item['start_date'])),
                'end'             => !empty($item['end_date']) ? date('c', strtotime($item['end_date'])) : null,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'textColor'       => '#ffffff',
                'location'        => $item['location'] ?? '',
                'image'           => !empty($item['image']) ? upload_url($item['image']) : null,
                'assigned_to'     => $item['assigned_to'] ?? '',
                'status'          => $item['status'],
                'description'     => $item['description'] ?? '',
                'startDateFormatted' => thai_date($item['start_date'], true),
                'endDateFormatted'   => !empty($item['end_date']) ? thai_date($item['end_date'], true) : null,
                'editUrl'         => url('admin/schedules/edit/' . $item['id']),
                'deleteUrl'       => url('admin/schedules/delete/' . $item['id']),
            ];
        }

        $this->json($events);
    }

    /**
     * ฟอร์มเพิ่มงานใหม่
     */
    public function create(): void
    {
        $defaultDate = trim($this->query('date', ''));
        $this->adminView('schedules/form', [
            'title'       => 'ลงงานใหม่ในปฏิทิน',
            'item'        => null,
            'defaultDate' => $defaultDate,
        ]);
    }

    /**
     * บันทึกข้อมูลงานใหม่
     */
    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('admin/schedules'); }
        $this->verifyCsrf();

        $title     = trim($this->input('title'));
        $startDate = trim($this->input('start_date'));

        if ($title === '' || $startDate === '') {
            $this->flash('error', 'กรุณากรอกชื่องานและวันเวลาเริ่มต้น');
            $this->redirect('admin/schedules/create');
        }

        $data = $this->collect();
        if (!empty($_FILES['image']['name'])) {
            $path = \Upload::image($_FILES['image'], 'schedules');
            if ($path === null) {
                $this->flash('error', \Upload::$error);
                $this->redirect('admin/schedules/create');
            }
            $data['image'] = $path;
        }

        $this->model('Schedule')->create($data);
        audit_log('create', 'schedules', 'ลงงานใหม่: ' . $title);

        $this->flash('success', 'บันทึกลงปฏิทินงานเรียบร้อยแล้ว');
        $this->redirect('admin/schedules');
    }

    /**
     * ฟอร์มแก้ไขงาน
     */
    public function edit(int $id = 0): void
    {
        $item = $this->model('Schedule')->find($id);
        if (!$item) {
            $this->flash('error', 'ไม่พบข้อมูลงาน');
            $this->redirect('admin/schedules');
        }

        $this->adminView('schedules/form', [
            'title' => 'แก้ไขงานในปฏิทิน',
            'item'  => $item,
        ]);
    }

    /**
     * บันทึกการแก้ไขงาน
     */
    public function update(int $id = 0): void
    {
        if (!$this->isPost()) { $this->redirect('admin/schedules'); }
        $this->verifyCsrf();

        $model = $this->model('Schedule');
        $item = $model->find($id);
        if (!$item) {
            $this->flash('error', 'ไม่พบข้อมูลงาน');
            $this->redirect('admin/schedules');
        }

        $title     = trim($this->input('title'));
        $startDate = trim($this->input('start_date'));
        if ($title === '' || $startDate === '') {
            $this->flash('error', 'กรุณากรอกชื่องานและวันเวลาเริ่มต้น');
            $this->redirect('admin/schedules/edit/' . $id);
        }

        $data = $this->collect();
        $removeImage = (int)$this->input('remove_image', 0);

        if (!empty($_FILES['image']['name'])) {
            $path = \Upload::image($_FILES['image'], 'schedules');
            if ($path === null) {
                $this->flash('error', \Upload::$error);
                $this->redirect('admin/schedules/edit/' . $id);
            }
            if (!empty($item['image'])) {
                \Upload::remove($item['image']);
            }
            $data['image'] = $path;
        } elseif ($removeImage === 1) {
            if (!empty($item['image'])) {
                \Upload::remove($item['image']);
            }
            $data['image'] = null;
        }

        $model->update($id, $data);
        audit_log('update', 'schedules', "แก้ไขงาน #{$id}: {$title}");

        $this->flash('success', 'บันทึกการแก้ไขเรียบร้อยแล้ว');
        $this->redirect('admin/schedules');
    }

    /**
     * ลบงานออกจากปฏิทิน
     */
    public function delete(int $id = 0): void
    {
        $model = $this->model('Schedule');
        $item = $model->find($id);
        if ($item) {
            if (!empty($item['image'])) {
                \Upload::remove($item['image']);
            }
            $model->delete($id);
            audit_log('delete', 'schedules', "ลบงาน #{$id}: " . $item['title']);
            $this->flash('success', 'ลบงานออกจากปฏิทินแล้ว');
        }
        $this->redirect('admin/schedules');
    }

    private function collect(): array
    {
        return [
            'title'       => $this->input('title'),
            'description' => $_POST['description'] ?? '',
            'start_date'  => $this->input('start_date'),
            'end_date'    => $this->input('end_date') ?: null,
            'location'    => $this->input('location', ''),
            'color'       => $this->input('color', '#0d6efd'),
            'assigned_to' => $this->input('assigned_to', ''),
            'status'      => $this->input('status', 'active'),
        ];
    }
}
