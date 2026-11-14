<?php
/**
 * CalendarController — หน้าแสดงปฏิทินงานสำหรับผู้ใช้งาน/บุคลากร
 */
class CalendarController extends Controller
{
    public function index(): void
    {
        $this->view('public/calendar/index', [
            'title' => 'ปฏิทินงานและภารกิจ',
        ]);
    }

    /**
     * ส่งข้อมูล Event Feed สำหรับ FullCalendar แบบ AJAX / JSON
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
            ];
        }

        $this->json($events);
    }
}
