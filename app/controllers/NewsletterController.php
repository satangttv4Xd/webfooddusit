<?php
/**
 * NewsletterController — สมัครรับข่าวสาร
 */
class NewsletterController extends Controller
{
    public function subscribe(): void
    {
        if (!$this->isPost()) { $this->redirect(''); }
        $this->verifyCsrf();

        $email = $this->input('email', '');
        if (!Security::isEmail($email)) {
            $this->flash('error', 'อีเมลไม่ถูกต้อง');
            $this->redirect('');
        }
        // กันซ้ำ
        $exists = $this->model('Setting'); // dummy to reuse db
        try {
            (new Database())->query("INSERT IGNORE INTO newsletter_subscribers (email,status,created_at,updated_at)
                VALUES (:e,'active',:n,:n)")->bind(['e' => $email, 'n' => date('Y-m-d H:i:s')])->execute();
            $this->flash('success', 'สมัครรับข่าวสารเรียบร้อยแล้ว ขอบคุณค่ะ');
        } catch (Throwable $e) {
            $this->flash('info', 'อีเมลนี้เคยสมัครไว้แล้ว');
        }
        $this->redirect('');
    }
}
