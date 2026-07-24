<?php
/**
 * ============================================================
 *  View (Core)
 * ------------------------------------------------------------
 *  จัดการการ render ไฟล์ view + layout แยก HTML ออกจาก PHP
 *  รองรับการส่งข้อมูล (data) เข้าไปเป็นตัวแปรใน view
 * ============================================================
 */

class View
{
    /**
     * แสดงผล view โดยครอบด้วย layout
     * @param string $view  ชื่อไฟล์ view เช่น 'public/home' หรือ 'admin/news/index'
     * @param array  $data  ข้อมูลที่ส่งเข้า view
     * @param string $layout ชื่อ layout เช่น 'public' หรือ 'admin' (null = ไม่ใช้ layout)
     */
    public static function render(string $view, array $data = [], ?string $layout = 'public'): void
    {
        $viewFile = VIEW_PATH . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("ไม่พบไฟล์ View: {$view}");
        }

        // แปลง key ของ array ให้เป็นตัวแปร เช่น $data['title'] -> $title
        extract($data, EXTR_SKIP);

        // จับ output ของ view ไว้ในตัวแปร $content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            echo $content;
            return;
        }
        require $layoutFile;
    }

    /**
     * แสดงเฉพาะบางส่วน (partial) โดยไม่ใช้ layout — เหมาะกับ AJAX
     */
    public static function partial(string $view, array $data = []): void
    {
        self::render($view, $data, null);
    }

    /**
     * ส่งข้อมูลกลับเป็น JSON (สำหรับ REST API / AJAX)
     */
    public static function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
