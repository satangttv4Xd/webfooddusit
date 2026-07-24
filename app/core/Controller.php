<?php
/**
 * ============================================================
 *  Controller (Core / Base Controller)
 * ------------------------------------------------------------
 *  คลาสแม่ของทุก Controller ให้เมธอดช่วยเหลือที่ใช้บ่อย เช่น
 *  โหลด model, render view, redirect, ตอบ JSON, ตรวจ CSRF ฯลฯ
 * ============================================================
 */

abstract class Controller
{
    /**
     * โหลด Model แล้วคืนอินสแตนซ์
     * @param string $name ชื่อคลาส model เช่น 'News'
     */
    protected function model(string $name)
    {
        $file = APP_PATH . '/models/' . $name . '.php';
        if (!file_exists($file)) {
            die("ไม่พบ Model: {$name}");
        }
        require_once $file;
        return new $name();
    }

    /**
     * แสดงผล view (ครอบด้วย layout)
     */
    protected function view(string $view, array $data = [], string $layout = 'public'): void
    {
        View::render($view, $data, $layout);
    }

    /**
     * แสดงผลหน้า admin (ใช้ layout admin โดยอัตโนมัติ)
     */
    protected function adminView(string $view, array $data = []): void
    {
        // ไฟล์ view ฝั่งหลังบ้านอยู่ใต้ views/admin/ เสมอ จึงเติม prefix ให้อัตโนมัติ
        if (!str_starts_with($view, 'admin/')) {
            $view = 'admin/' . $view;
        }
        View::render($view, $data, 'admin');
    }

    /**
     * ตอบกลับเป็น JSON
     */
    protected function json($data, int $status = 200): void
    {
        View::json($data, $status);
    }

    /**
     * เปลี่ยนหน้า (redirect) ไปยัง path ภายในเว็บ
     * @param string $path เช่น 'admin/news' หรือ 'news/detail/5'
     */
    protected function redirect(string $path = ''): void
    {
        // ถ้าเป็น URL เต็มอยู่แล้วให้ใช้เลย
        $url = str_starts_with($path, 'http') ? $path : BASE_URL . ltrim($path, '/');
        header('Location: ' . $url);
        exit;
    }

    /**
     * ดึงค่าจาก $_POST อย่างปลอดภัย (trim ให้ด้วย)
     */
    protected function input(string $key, $default = null)
    {
        if (!isset($_POST[$key])) {
            return $default;
        }
        return is_string($_POST[$key]) ? trim($_POST[$key]) : $_POST[$key];
    }

    /**
     * ดึงค่าจาก $_GET อย่างปลอดภัย
     */
    protected function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * ตรวจสอบว่าเป็น request แบบ POST หรือไม่
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * ตรวจสอบ CSRF token — ถ้าไม่ผ่านให้หยุดทำงาน
     */
    protected function verifyCsrf(): void
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!Security::verifyCsrf($token)) {
            if ($this->wantsJson()) {
                $this->json(['success' => false, 'message' => 'CSRF token ไม่ถูกต้อง'], 419);
            }
            http_response_code(419);
            die('การยืนยันความปลอดภัยล้มเหลว (CSRF token ไม่ถูกต้อง) กรุณาลองใหม่');
        }
    }

    /**
     * ตรวจว่า client ต้องการผลลัพธ์เป็น JSON หรือไม่ (AJAX)
     */
    protected function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $xrw = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return str_contains($accept, 'application/json') || strtolower($xrw) === 'xmlhttprequest';
    }

    /**
     * เก็บข้อความแจ้งเตือนแบบครั้งเดียว (Flash Message)
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }
}
