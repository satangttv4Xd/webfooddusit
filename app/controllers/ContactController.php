<?php
/**
 * ContactController — ติดต่อเรา (ฟอร์ม + Google Map)
 */
class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('public/contact', ['title' => 'ติดต่อเรา']);
    }

    /** รับข้อมูลจากฟอร์มติดต่อ (POST) */
    public function send(): void
    {
        if (!$this->isPost()) { $this->redirect('contact'); }
        $this->verifyCsrf();

        // ตรวจ honeypot กันบอท (ฟิลด์ที่มนุษย์ไม่กรอก)
        if (!empty($this->input('website'))) {
            $this->redirect('contact');
        }

        $v = new Validator($_POST);
        $ok = $v->validate(
            ['name' => 'required|max:150', 'email' => 'required|email', 'message' => 'required|min:10'],
            ['name' => 'ชื่อ', 'email' => 'อีเมล', 'message' => 'ข้อความ']
        );

        if (!$ok) {
            $_SESSION['old'] = $_POST;
            $this->flash('error', $v->firstError());
            $this->redirect('contact');
        }

        $this->model('Contact')->create([
            'name'       => $this->input('name'),
            'email'      => $this->input('email'),
            'phone'      => $this->input('phone', ''),
            'subject'    => $this->input('subject', ''),
            'message'    => $this->input('message'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'is_read'    => 0,
            'status'     => 'active',
        ]);

        unset($_SESSION['old']);
        $this->flash('success', 'ส่งข้อความเรียบร้อยแล้ว ขอบคุณที่ติดต่อเรา');
        $this->redirect('contact');
    }
}
