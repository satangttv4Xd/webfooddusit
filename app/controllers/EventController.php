<?php
/**
 * EventController — กิจกรรม (รายการ/รายละเอียด/ลงทะเบียน)
 */
class EventController extends Controller
{
    public function index(): void
    {
        $events = $this->model('Event')->allActive();
        $this->view('public/events/index', [
            'title'  => 'ปฏิทินกิจกรรม',
            'events' => $events,
        ]);
    }

    public function detail(string $slug = ''): void
    {
        $event = $this->model('Event')->findBySlug($slug);
        if (!$event) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'ไม่พบกิจกรรม']);
            return;
        }
        $registered = $this->model('Event')->countRegistrations((int)$event['id']);
        $this->view('public/events/detail', [
            'title'      => $event['title'],
            'event'      => $event,
            'registered' => $registered,
        ]);
    }

    /** ลงทะเบียนเข้าร่วมกิจกรรม (POST) */
    public function register(): void
    {
        if (!$this->isPost()) { $this->redirect('events'); }
        $this->verifyCsrf();

        $eventId = (int)$this->input('event_id');
        $slug    = $this->input('slug', '');

        $v = new Validator($_POST);
        $ok = $v->validate(
            ['name' => 'required|max:150', 'email' => 'required|email', 'phone' => 'required'],
            ['name' => 'ชื่อ-นามสกุล', 'email' => 'อีเมล', 'phone' => 'เบอร์โทร']
        );
        if (!$ok) {
            $this->flash('error', $v->firstError());
            $this->redirect('events/detail/' . $slug);
        }

        $this->model('EventRegistration')->create([
            'event_id'     => $eventId,
            'name'         => $this->input('name'),
            'email'        => $this->input('email'),
            'phone'        => $this->input('phone'),
            'organization' => $this->input('organization', ''),
            'note'         => $this->input('note', ''),
            'status'       => 'pending',
        ]);
        $this->flash('success', 'ลงทะเบียนเข้าร่วมกิจกรรมเรียบร้อยแล้ว เจ้าหน้าที่จะติดต่อกลับ');
        $this->redirect('events/detail/' . $slug);
    }
}
